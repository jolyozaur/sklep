<?php
  
require 'db.php';
session_start();
$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;
$isAdmin = false;
$status_admina = null;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email, czy_admin FROM users WHERE id = ?");
    $stmt->execute([$userId]);     $userData = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($userData && $userData['czy_admin']) {
        $isAdmin = true;
    }
} else {
  
    $userId = null;
}

if ( $isAdmin !== true) {
    header("Location: login.php");
    exit;
}


if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Produkt nie znaleziony.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $opis = $_POST['opis'];
    $stmt = $pdo->prepare("UPDATE products SET name = ?, type = ?, price = ?, Opis = ? WHERE id = ?");
    $stmt->execute([$name, $type, $price, $opis, $productId]);

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Produkt</title>
    <link rel="stylesheet" href="style_edycja.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edytuj Produkt</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Nazwa Produktu</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Typ</label>
                <input type="text" class="form-control" id="type" name="type" value="<?= htmlspecialchars($product['type']) ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Cena</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>
            <div class="form-group">
                <label for="opis">Opis</label>
                <input type="text" class="form-control" id="opis" name="opis" value="<?= htmlspecialchars($product['Opis']) ?>" required>
                
            </div>
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button><br><br>
            <a href="admin.php" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



