<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin1234') {
    header("Location: login.php"); 
    exit;
}

require 'db.php';

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
