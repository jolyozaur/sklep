<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = false;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT rodzaj FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && $userData['rodzaj'] === 'admin') {
        $isAdmin = true;
    }
}

if (!$isAdmin) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tytul = $_POST['tytul'];
    $tresc = $_POST['tresc'];

    $stmt = $pdo->prepare("INSERT INTO podstrony (tytul, tresc) VALUES (?, ?)");
    $stmt->execute([$tytul, $tresc]);

    header("Location: podstrony.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Podstronę</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Dodaj nową podstronę</h2>
        <form method="POST">
            <div class="form-group">
                <label for="tytul">Tytuł Podstrony</label>
                <input type="text" class="form-control" id="tytul" name="tytul" required>
            </div>
            <div class="form-group">
                <label for="tresc">Treść Podstrony</label>
                <textarea class="form-control" id="tresc" name="tresc" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj Podstronę</button>
        </form>
    </div>
</body>
</html>
