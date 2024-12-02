<?php
session_start();
include 'db.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Pobranie ostatniego zamówienia użytkownika
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1");
$stmt->execute([$userId]);
$order = $stmt->fetch();

// Debug: Sprawdzamy, czy zamówienie zostało znalezione
if (!$order) {
    echo "Nie znaleziono zamówienia.";
    exit;
}

// Pobranie produktów zamówionych przez użytkownika
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order['id']]);
$orderedItems = $stmt->fetchAll();

// Debug: Sprawdzamy, czy produkty zostały poprawnie pobrane
if (empty($orderedItems)) {
    echo "Brak produktów w zamówieniu.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potwierdzenie zamówienia</title>
    <link rel="stylesheet" href="platnosci.css"> <!-- Korzystamy ze wspólnego pliku CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="form-container p-4">
        <h2 class="text-center">Potwierdzenie zamówienia</h2>
        <p class="text-center">Dziękujemy za złożenie zamówienia! Twoje zamówienie zostało przyjęte.</p>

        <h3>Numer zamówienia: <?= htmlspecialchars($order['id']) ?></h3>
        <h4>Status: <?= htmlspecialchars($order['status']) ?></h4>
        <p>Data zamówienia: <?= htmlspecialchars($order['order_date']) ?></p>

        <h4>Produkty w zamówieniu:</h4>
        <ul class="list-group">
            <?php foreach ($orderedItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($item['name']) ?> - <?= htmlspecialchars($item['quantity']) ?> x <?= number_format($item['price'], 2) ?> PLN
                    <strong>Łącznie: <?= number_format($item['quantity'] * $item['price'], 2) ?> PLN</strong>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3 class="mt-3">Całkowita kwota: <?= number_format($order['total_amount'], 2) ?> PLN</h3>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-danger">Wróć do strony głównej</a>
        </div>
    </div>
</div>
</body>
</html>
