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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Shipping_methods WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: dostawy.php");
    exit();
} else {
    header("Location: dostawy.php");
    exit();
}
?>
