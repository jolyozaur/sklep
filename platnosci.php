<?php
session_start();
include 'db.php';

// Funkcja czyszcząca koszyk
function clearCart() {
    global $pdo;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } else {
        $_SESSION['cart'] = [];
    }
}

// Funkcja obliczająca całkowitą kwotę zamówienia
function calculateTotalAmount($cartItems) {
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    return $totalAmount;
}

// Sprawdzamy czy jest POST z formularza płatności
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_form'])) {
    $shippingMethodId = intval($_POST['shipping_method']);
    $paymentMethodId = intval($_POST['payment_method']);
    $addressId = intval($_POST['address_id']);  // Używamy address_id z formularza

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        
        // Pobranie danych o koszyku
        $stmt = $pdo->prepare("
            SELECT c.product_id, p.name, p.price, c.quantity
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        $cartItems = $stmt->fetchAll();

        // Obliczenie całkowitej kwoty zamówienia
        $totalAmount = calculateTotalAmount($cartItems);

        // Tworzenie zamówienia w tabeli orders
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

        // Pobranie ID ostatniego zamówienia
        $orderId = $pdo->lastInsertId();

        // Dodanie produktów do tabeli order_items
        foreach ($cartItems as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $item['name'],
                $item['quantity'],
                $item['price']
            ]);
        }

        // Usuwanie produktów z koszyka po złożeniu zamówienia
        clearCart();
    }

    header('Location: thank_you.php');
    exit;
}

// Jeśli użytkownik jest zalogowany, pobieramy dane
$cartItems = [];
$totalAmount = 0;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Pobranie zapisanych adresów
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
    $stmt->execute([$userId]);
    $addresses = $stmt->fetchAll();

    // Pobranie produktów w koszyku
    $stmt = $pdo->prepare("
        SELECT c.product_id, p.name, p.price, c.quantity 
        FROM carts c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();

    // Obliczenie całkowitej kwoty zamówienia
    $totalAmount = calculateTotalAmount($cartItems);
} else {
    // Koszyk dla niezalogowanych użytkowników
    $cartItems = $_SESSION['cart'] ?? [];
    foreach ($cartItems as &$item) {
        $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt->execute([$item['product_id']]);
        $product = $stmt->fetch();
        $item['name'] = $product['name'] ?? 'Nieznany produkt';
        $item['price'] = $product['price'] ?? 0;
    }
    // Obliczenie całkowitej kwoty zamówienia
    $totalAmount = calculateTotalAmount($cartItems);
}

// Pobranie metod płatności i dostawy
$stmt = $pdo->query("SELECT id, name FROM shipping_methods");
$shippingMethods = $stmt->fetchAll();

$stmt = $pdo->query("SELECT id, name FROM payment_methods");
$paymentMethods = $stmt->fetchAll();
?>
<!-- Struktura HTML formularza płatności -->

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
                <label for="address_id" class="form-label">Wybierz adres</label>
                <?php if (count($addresses) > 0): ?>
                    <select name="address_id" id="address_id" class="form-control" required>
                        <option value="" disabled selected>Wybierz adres</option>
                        <?php foreach ($addresses as $addr): ?>
                            <option value="<?= $addr['id_adresu'] ?>"><?= htmlspecialchars($addr['Imie']) . ' ' . htmlspecialchars($addr['Nazwisko']) . ', ' . htmlspecialchars($addr['ulica']) . ', ' . htmlspecialchars($addr['miasto']) . ', ' . htmlspecialchars($addr['kod']) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <p>Nie masz zapisanych adresów. Wypełnij poniższy formularz, aby dodać nowy adres.</p>
                    <div class="mb-3">
                        <label for="imie" class="form-label">Imię</label>
                        <input type="text" class="form-control" id="imie" name="imie" required>
                    </div>
                    <div class="mb-3">
                        <label for="nazwisko" class="form-label">Nazwisko</label>
                        <input type="text" class="form-control" id="nazwisko" name="nazwisko" required>
                    </div>
                    <div class="mb-3">
                        <label for="ulica" class="form-label">Ulica</label>
                        <input type="text" class="form-control" id="ulica" name="ulica" required>
                    </div>
                    <div class="mb-3">
                        <label for="miasto" class="form-label">Miasto</label>
                        <input type="text" class="form-control" id="miasto" name="miasto" required>
                    </div>
                    <div class="mb-3">
                        <label for="kod" class="form-label">Kod pocztowy</label>
                        <input type="text" class="form-control" id="kod" name="kod" required pattern="\d{2}-\d{3}">
                    </div>
                    <div class="mb-3">
                        <label for="tel" class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="tel" name="tel" required pattern="^(\+?\d{1,3})? ?\d{3} ?\d{3} ?\d{3}$">
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <strong>Całkowita kwota: <?= number_format($totalAmount, 2) ?> PLN</strong>
            </div>
            <button type="submit" name="payment_form" class="btn btn-success">Zapłać</button>
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
