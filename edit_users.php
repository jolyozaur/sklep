<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin1234') {
    header("Location: login.php"); 
    exit;
}

require 'db.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userdane = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userdane) {
        die("Produkt nie znaleziony.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $czy_admin = $_POST['czy_admin'];
   
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, czy_admin = ?  WHERE id = ?");
    $stmt->execute([$username, $email, $czy_admin, $userId]);

    header("Location: uzytkownicy.php"); 
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
                <input type="text" class="form-control" id="username" name="username" value="<?= ($userdane['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Typ</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userdane['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Czy admin</label>

                <p>tak</p>
                <input type="checkbox" class="form-control" id="czy_admin" name="czy_admin" value="1">
                <p>Nie</p>
                <input type="checkbox" class="form-control" id="czy_admin" name="czy_admin" value="0">
                
            </div>
           
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button><br><br>
            <a href="uzytkownicy.php" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
