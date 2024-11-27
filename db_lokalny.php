<?php
$host = 'localhost'; // Adres serwera bazy danych
$db = 'motocykle_skep'; // Nazwa bazy danych
$user = 'root'; // Zmień na swoją nazwę użytkownika
$pass = ''; // Zmień na swoje hasło

// Tworzenie połączenia
$conn = new mysqli($host, $user, $pass, $db);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie udało się połączyć z bazą danych $db :" . $e->getMessage());
}
?>
