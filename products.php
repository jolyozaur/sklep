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
$query = isset($_GET['query']) ? strtolower(trim($_GET['query'])) : '';
$stmt = $pdo->prepare("SELECT * FROM products WHERE LOWER(name) LIKE :query");
$stmt->execute(['query' => '%' . $query . '%']);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
?>
