<?php
session_start();
include 'db.php';

if (isset($_POST['czysc']) && $_POST['czysc'] === 'true') {
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } else {
        $_SESSION['cart'] = [];
    }
    header('Location: platnosci.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $productId = intval($_POST['product_id']);
    $newQuantity = max(1, intval($_POST['quantity']));

    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$newQuantity, $_SESSION['user_id'], $productId]);
    } else {
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['product_id'] === $productId) {
                    $item['quantity'] = $newQuantity;
                    break;
                }
            }
        }
    }
    header('Location: platnosci.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_form'])) {
    // Tworzenie nowego zamówienia
    $shippingMethodId = intval($_POST['shipping_method']);
    $paymentMethodId = intval($_POST['payment_method']);
    $addressId = intval($address['id']); // Pobieramy ID adresu użytkownika

    // Sprawdzenie zmiennych przed zapytaniem
    var_dump($_SESSION['user_id'], $totalAmount, $shippingMethodId, $paymentMethodId, $addressId);

    // Jeśli użytkownik jest zalogowany
    if (isset($_SESSION['user_id'])) {
        // Tworzenie rekordu w tabeli orders z ID adresu zamiast pełnych danych adresowych
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, shipping_method_id, payment_method_id, shipping_address, status, order_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt->execute([
            $_SESSION['user_id'],
            $totalAmount,
            $shippingMethodId,
            $paymentMethodId,
            $addressId, // Zapisujemy ID adresu zamiast pełnego adresu
            'Oczekujące',
            date('Y-m-d H:i:s')
        ])) {
            var_dump($stmt->errorInfo()); // Wyświetlanie błędów
            exit;
        }

        // Pobieramy ID ostatnio dodanego zamówienia
        $orderId = $pdo->lastInsertId();

        // Dodajemy produkty do tabeli order_items
        foreach ($cartItems as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                VALUES (?, ?, ?, ?, ?)
            ");
            if (!$stmt->execute([
                $orderId,
                $item['product_id'],
                $item['name'],
                $item['quantity'],
                $item['price']
            ])) {
                var_dump($stmt->errorInfo()); // Wyświetlanie błędów
                exit;
            }
        }

        // Usuwanie produktów z koszyka po złożeniu zamówienia
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        if (!$stmt->execute([$_SESSION['user_id']])) {
            var_dump($stmt->errorInfo()); // Sprawdzamy, dlaczego zapytanie się nie wykonuje
            exit;
        }
    } else {
        // Obsługa dla użytkowników niezalogowanych (jeśli dotyczy)
    }

    // Przekierowanie na stronę podziękowań lub płatności
    header('Location: thank_you.php');
    exit;
}


$address = [];
$cartItems = [];

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
    $stmt->execute([$userId]);
    $address = $stmt->fetch();

    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price 
        FROM carts c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();
} else {
    $cartItems = $_SESSION['cart'] ?? [];
    foreach ($cartItems as &$item) {
        $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt->execute([$item['product_id']]);
        $product = $stmt->fetch();
        $item['name'] = $product['name'] ?? 'Nieznany produkt';
        $item['price'] = $product['price'] ?? 0;
    }
}

$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * ($item['quantity'] ?? 1);
}


$stmt = $pdo->query("SELECT id, name FROM shipping_methods");
$shippingMethods = $stmt->fetchAll();

// Pobierz dostępne metody płatności z bazy danych
$stmt = $pdo->query("SELECT id, name FROM payment_methods");
$paymentMethods = $stmt->fetchAll();

$imie = $address['Imie'] ?? '';
$nazwisko = $address['Nazwisko'] ?? '';
$ulica = $address['ulica'] ?? '';
$miasto = $address['miasto'] ?? '';
$kod = $address['kod'] ?? '';
$tel = $address['tel'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product'])) {
    $productId = intval($_POST['product_id']); // Pobieramy ID produktu

    if (isset($_SESSION['user_id'])) {
        // Usuń produkt z koszyka w bazie danych
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $productId]);
    } else {
        // Usuń produkt z koszyka sesji
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['product_id'] === $productId) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Przebuduj indeksy tablicy
        }
    }

    // Przekierowanie po zakończeniu operacji
    header('Location: platnosci.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['czysc'])) {
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } else {
        // Przed czyszczeniem koszyka, sprawdźmy jego zawartość
        var_dump($_SESSION['cart']);
        $_SESSION['cart'] = [];
    }
    // Sprawdzamy, czy koszyk został wyczyszczony
    var_dump($_SESSION['cart']); 

    header('Location: platnosci.php');
    exit;
}
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
        <form method="POST" id="paymentForm" >
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div class="mb-3">
                <label for="imie" class="form-label">Imię</label>
                <input type="text" class="form-control" id="imie" name="imie" value="<?= htmlspecialchars($imie) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nazwisko" class="form-label">Nazwisko</label>
                <input type="text" class="form-control" id="nazwisko" name="nazwisko" value="<?= htmlspecialchars($nazwisko) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ulica" class="form-label">Ulica</label>
                <input type="text" class="form-control" id="ulica" name="ulica" value="<?= htmlspecialchars($ulica) ?>" required>
            </div>
            <div class="mb-3">
                <label for="miasto" class="form-label">Miasto</label>
                <input type="text" class="form-control" id="miasto" name="miasto" value="<?= htmlspecialchars($miasto) ?>" required>
            </div>
            <div class="mb-3">
                <label for="kod" class="form-label">Kod pocztowy</label>
                <input type="text" class="form-control" id="kod" name="kod" value="<?= htmlspecialchars($kod) ?>" required pattern="\d{2}-\d{3}">
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">Telefon</label>
                <input type="tel" class="form-control" id="tel" name="tel" value="<?= htmlspecialchars($tel) ?>" required pattern="^(\+?\d{1,3})? ?\d{3} ?\d{3} ?\d{3}$">
            </div>
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
                <strong>Całkowita kwota: <?= number_format($totalAmount, 2) ?> PLN</strong>
            </div>
            <button type="submit" class="btn btn-success">Zapłać</button>
        </form>
       
        <button class="btn-secondary btn  mt-3" onclick="window.location.href='index.php';">Strona główna</button>
    </div>
    <div class="cart-box">
        <h4 class="text-center">Koszyk</h4>
        <ul class="list-group">
            <?php foreach ($cartItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($item['name']) ?></span>
                    <form method="POST" class="d-flex align-items-center">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                        <input type="hidden" name="update_quantity" value="1">
                        <input type="number" name="quantity" class="form-control me-2" value="<?= htmlspecialchars($item['quantity'] ?? 1) ?>" min="1" style="width: 80px;">
                        <button type="submit" class="btn btn-primary btn-sm">Zmień</button>
                    </form>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                        <input type="hidden" name="remove_product" value="true">
                        <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                    </form>
                    <span><?= number_format($item['price'], 2) ?> PLN x <?= htmlspecialchars($item['quantity'] ?? 1) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="total mt-3">
            <strong>Całkowita kwota: <?= number_format($totalAmount, 2) ?> PLN</strong>
        
            <form method="POST" action="">
       
            <button type="submit" class="btn btn-danger mt-3">Wyczyść koszyk</button>
        </form>
        </div>

        
    </div>
</div>
</body>
</html>



