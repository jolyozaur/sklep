<?php
$host = 'localhost';
$db = 'm10280_motocykle_skep';
$user = 'root'; 
$pass = '';

// Tworzenie połączenia
$conn = new mysqli($host, $user, $pass, $db);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie udało się połączyć z bazą danych $db :" . $e->getMessage());
}
?>
