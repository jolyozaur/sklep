<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany jako administrator
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin1234') {
    header("Location: login.php"); // Jeśli nie, przekierowanie na stronę logowania
    exit;
}

require 'db.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Usuwanie produktu z bazy
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    header("Location: admin.php"); // Przekierowanie z powrotem do panelu administracyjnego
    exit;
} else {
    die("Brak ID produktu.");
}
?>
