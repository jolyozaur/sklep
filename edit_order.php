<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = false;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT czy_admin FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && $userData['czy_admin']) {
        $isAdmin = true;
    }
} else {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Brak ID zamówienia.";
    exit;
}

$orderId = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Zamówienie nie istnieje.";
    exit;
}

$statuses = ['Oczekujące', 'W realizacji', 'Zrealizowane', 'Anulowane'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newStatus = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja zamówienia</title>
    <link rel="stylesheet" href="style_edycja.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edycja zamówienia</h2>
    <p><strong>ID Zamówienia:</strong> <?= htmlspecialchars($order['id']) ?></p>
    <p><strong>Data Zamówienia:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

    <form method="POST">
        <div class="form-group">
            <label for="status">Nowy status</label>
            <select class="form-control" id="status" name="status" required>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= $status ?>" <?= $order['status'] == $status ? 'selected' : '' ?>><?= $status ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Zapisz zmiany</button>
    </form>

    <a href="admin.php" class="btn btn-secondary mt-3">Powrót do listy zamówień</a>
</div>
</body>
</html>
