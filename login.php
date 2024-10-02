<?php
global $pdo;
session_start();
include 'config.php';

// Sprawdzanie, czy użytkownik jest już zalogowany
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Obsługa logowania
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Sprawdzenie, czy dane zostały wprowadzone
    if (!empty($username) && !empty($password)) {
        // Przygotowanie zapytania SQL do pobrania użytkownika
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Weryfikacja hasła i ustawienie sesji
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Zabezpieczenie przed atakami sesyjnymi
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $login_error = "Nieprawidłowa nazwa użytkownika lub hasło.";
        }
    } else {
        $login_error = "Wprowadź nazwę użytkownika i hasło.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
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
    <form method="POST" action="login.php">
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

    <!-- Przycisk do rejestracji -->
    <a href="register.php" class="btn btn-secondary btn-block mt-3">Rejestracja</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
