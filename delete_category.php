<?php
require 'db.php'; 


session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Zaloguj się, aby usuwać kategorie.";
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Połączenie z bazą danych nieudane: ' . $e->getMessage()]);
    exit;
}

// Usuwanie kategorii
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: kategorie.php");
    exit;
}
?>
