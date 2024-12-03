<?php
session_start();
require 'db.php';

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



if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    header("Location: admin.php"); 
    exit;
} else {
    die("Brak ID produktu.");
}
?>
