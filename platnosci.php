<?php
session_start();
include 'db.php';


function clearCart() {
    global $pdo;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } else {
        $_SESSION['cart'] = [];
    }
}

function calculateTotalAmount($cartItems) {
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    return $totalAmount;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_form'])) {
    $shippingMethodId = intval($_POST['shipping_method']);
    $paymentMethodId = intval($_POST['payment_method']);
    $addressId = isset($_POST['address_id']) ? intval($_POST['address_id']) : null;

    
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        $userId = null;
    }

  
    if ($userId) {
  
        $stmt = $pdo->prepare("
            SELECT c.product_id, p.name, p.price, c.quantity
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        $cartItems = $stmt->fetchAll();
    } else {
      
        $cartItems = $_SESSION['cart'] ?? [];
        foreach ($cartItems as &$item) {
           
            if (!isset($item['name']) || !isset($item['price'])) {
        
                $item['name'] = 'Nieznany produkt';
                $item['price'] = 0;
            }
        }
    }

    $totalAmount = calculateTotalAmount($cartItems);

 
    if (!$userId) {
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $ulica = $_POST['ulica'];
        $miasto = $_POST['miasto'];
        $kod = $_POST['kod'];
        $tel = $_POST['tel'];

        $stmt = $pdo->prepare("
            INSERT INTO addresses (user_id, Imie, Nazwisko, ulica, miasto, kod, tel)
            VALUES (NULL, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$imie, $nazwisko, $ulica, $miasto, $kod, $tel]);

        $addressId = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, total_amount, shipping_method_id, payment_method_id, address_id, status, order_date)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $userId,
        $totalAmount,
        $shippingMethodId,
        $paymentMethodId,
        $addressId,
        'Oczekujące',
        date('Y-m-d H:i:s')
    ]);

    $orderId = $pdo->lastInsertId();

    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $orderId,
            $item['product_id'] ?? null,  
            $item['name'],
            $item['quantity'],
            $item['price']
        ]);
    }


    clearCart();


    header('Location: thank_you.php');
    exit;
}

$cartItems = [];
$totalAmount = 0;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
    $stmt->execute([$userId]);
    $addresses = $stmt->fetchAll();

    
    $stmt = $pdo->prepare("
        SELECT c.product_id, p.name, p.price, c.quantity 
        FROM carts c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();

    $totalAmount = calculateTotalAmount($cartItems);
} else {

    $cartItems = $_SESSION['cart'] ?? [];
    $addresses = [];  
    foreach ($cartItems as &$item) {
      
        if (!isset($item['name']) || !isset($item['price'])) {
            $item['name'] = 'Nieznany produkt';
            $item['price'] = 0;
        }
    }

    $totalAmount = calculateTotalAmount($cartItems);
}

$stmt = $pdo->query("SELECT id, name FROM shipping_methods");
$shippingMethods = $stmt->fetchAll();

$stmt = $pdo->query("SELECT id, name FROM payment_methods");
$paymentMethods = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularz Płatności</title>
    <link rel="stylesheet" href="platnosci.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Formularz Płatności</h2>
        <form method="POST" id="paymentForm">
            <div class="mb-3">
                <label for="shipping" class="form-label">Metoda dostawy</label>
                <select name="shipping_method" id="shipping" class="form-control" required>
                    <?php foreach ($shippingMethods as $method): ?>
                        <option value="<?= $method['id'] ?>"><?= htmlspecialchars($method['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Wybierz metodę płatności</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="" disabled selected>Wybierz metodę płatności</option>
                    <?php foreach ($paymentMethods as $paymentMethod): ?>
                        <option value="<?= htmlspecialchars($paymentMethod['id']) ?>"><?= htmlspecialchars($paymentMethod['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
    <label for="address_id" class="form-label">Adres do wysyłki</label>
    <?php if (count($addresses) > 0): ?>
        <select name="address_id" id="address_id" class="form-control" required>
            <option value="" disabled selected>Wybierz adres</option>
            <?php foreach ($addresses as $addr): ?>
                <option value="<?= $addr['id_adresu'] ?>"><?= htmlspecialchars($addr['Imie']) . ' ' . htmlspecialchars($addr['Nazwisko']) . ', ' . htmlspecialchars($addr['ulica']) . ', ' . htmlspecialchars($addr['miasto']) . ', ' . htmlspecialchars($addr['kod']) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="mt-3">
           
            <a href="edit_address.php?address_id=<?= $addr['id_adresu'] ?>" class="btn btn-warning mt-2">Edytuj adres</a> 
        </div>
    <?php else: ?>
        <p>Nie masz zapisanych adresów. Wypełnij poniższy formularz, aby dodać nowy adres.</p>
    <?php endif; ?>
</div>
            <div class="mb-3">
                <strong>Całkowita kwota: <?= number_format($totalAmount, 2) ?> PLN</strong>
            </div>
            <button type="submit" name="payment_form" class="btn btn-success">Zapłać</button>
            <br></br>
            <button class="btn btn-danger" onclick="window.location.href='index.php';">Powrót do strony głównej</button>


        </form>
       

    </div>
    <div class="cart-box">
        <h4 class="text-center">Koszyk</h4>
        <ul class="list-group">
            <?php foreach ($cartItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($item['name']) ?></span>
                    <span><?= number_format($item['price'], 2) ?> PLN x <?= htmlspecialchars($item['quantity']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="total mt-3">
            <strong>Całkowita kwota: <?= number_format($totalAmount, 2) ?> PLN</strong>
        </div>
    </div>
</div>
</body>
</html>
