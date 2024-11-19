<?php
session_start();
require 'db.php';
$host = 'localhost';
$db = 'm10280_motocykle_skep';
$user = 'root'; 
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = ""; // Zmienna do przechowywania błędów logowania

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Wyszukanie użytkownika w bazie danych
    $sql = "SELECT id, username, password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username, $passwordHash);
        $stmt->fetch();

        // Weryfikacja hasła
        if (password_verify($password, $passwordHash)) {
            // Zapisanie ID i nazwy użytkownika w sesji
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Sprawdzenie, czy to administrator
            if ($username === 'admin1234') {
                header("Location: admin.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            $login_error = "Nieprawidłowe hasło.";
        }
    } else {
        $login_error = "Nie znaleziono użytkownika.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style_login_rej.css"> <!-- Używamy tego samego stylu -->
</head>
<body>
<div class="login-container">
    <h2>Logowanie</h2>

    <!-- Wyświetlanie błędów logowania -->
    <?php if (!empty($login_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($login_error) ?>
        </div>
    <?php endif; ?>

    <!-- Formularz logowania -->
    <form method="POST" action="login.php" class="form-container">
        <div class="form-group">
            <label for="username">Nazwa użytkownika</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Wprowadź nazwę użytkownika" required>
        </div>
        <div class="form-group">
            <label for="password">Hasło</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Wprowadź hasło" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary btn-block">Zaloguj się</button>
    </form>

    <!-- Przycisk do rejestracji i strony głównej -->
    <a href="register.php" class="btn btn-secondary btn-block mt-3">Rejestracja</a>
    <a href="index.php" class="btn btn-secondary btn-block mt-3">Strona Główna</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
