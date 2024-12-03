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

if (!$isAllowed) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Brak ID zamówienia.";
    exit;
}

$orderId = $_GET['id'];


$stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);

$stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
$stmt->execute([$orderId]);


header("Location: admin.php");
exit;
?>
