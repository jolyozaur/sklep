<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}


$type = isset($_GET['type']) ? trim($_GET['type']) : '';

if ($type === '') {

    $stmt = $pdo->prepare("
        SELECT p.*, c.type 
        FROM products p
        JOIN categories c ON p.category_id = c.id
    ");
    $stmt->execute();
} else {
  
    $stmt = $pdo->prepare("
        SELECT p.*, c.type 
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE LOWER(c.type) = :type
    ");
    $stmt->execute(['type' => strtolower($type)]);
}


$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
?>
