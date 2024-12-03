<?php
require 'db.php'; 


session_start();
$loggedIn = isset($_SESSION['user_id']);
$isAdmin = false;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email, rodzaj FROM users WHERE id = ?");
    $stmt->execute([$userId]); 
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($userData && ($userData['rodzaj'] === 'admin' || $userData['rodzaj'] === 'pracownik')) {
        $isAllowed = true;
    }
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Połączenie z bazą danych nieudane: ' . $e->getMessage()]);
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: kategorie.php");
    exit;
}
?>
