<?php
header('Content-Type: application/json');

$host = 'localhost'; 
$db = 'm10280_motocykle_skep'; 
$user = 'root'; 
$pass = '';

$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$type = isset($_GET['type']) ? $_GET['type'] : '';

$query = "SELECT * FROM products";
if ($type) {
    $query .= " WHERE type = :type";
}

$stmt = $pdo->prepare($query);

if ($type) {
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
}

$stmt->execute();
$results = $stmt->fetchAll();

echo json_encode($results);
?>
