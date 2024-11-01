<?php
// Ustawienie nagłówka JSON
header('Content-Type: application/json');

// Konfiguracja połączenia z bazą danych
$host = 'mysql12.serv00.com';
$db = 'm10280_motocykle_skep';
$user = 'm10280_jolyozaur';
$pass = 'Haslo123';

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

// Pobranie typu z parametru GET
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Przygotowanie zapytania SQL
$query = "SELECT * FROM products";
if ($type) {
    $query .= " WHERE type = :type";
}

$stmt = $pdo->prepare($query);

// Powiązanie parametru, jeśli podano typ
if ($type) {
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
}

$stmt->execute();
$results = $stmt->fetchAll();

// Zwrot danych jako JSON
echo json_encode($results);
?>
