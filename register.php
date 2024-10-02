<?php
session_start();
include 'config.php';

// Sprawdzanie, czy użytkownik jest już zalogowany
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$register_error = '';
$register_success = '';

// Obsługa rejestracji
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Sprawdzenie, czy dane są poprawne
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $register_error = "Wszystkie pola są wymagane.";
    } elseif ($password !== $confirm_password) {
        $register_error = "Hasła nie są zgodne.";
    } elseif (strlen($password) < 8) {
        $register_error = "Hasło musi mieć co najmniej 8 znaków.";
    } else {
        // Sprawdzenie, czy użytkownik o tej nazwie już istnieje
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $register_error = "Użytkownik o tej nazwie już istnieje.";
        } else {
            // Haszowanie hasła
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Wstawianie użytkownika do bazy danych
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password
            ]);

            // Sukces rejestracji
            $register_success = "Rejestracja przebiegła pomyślnie! Zostaniesz przekierowany na stronę logowania.";
            header("refresh:2;url=login.php"); // Automatyczne przekierowanie po 2 sekundach
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .register-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .password-check, .confirm-password-check {
            font-size: 0.9em;
            color: red;
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        .password-check.valid {
            color: green;
            opacity: 1;
        }
        .confirm-password-check.valid {
            color: green;
            opacity: 1;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2>Rejestracja</h2>

    <!-- Wyświetlanie błędów rejestracji -->
    <?php if (!empty($register_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($register_error) ?>
        </div>
    <?php endif; ?>

    <!-- Wyświetlanie sukcesu rejestracji -->
    <?php if (!empty($register_success)): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($register_success) ?>
        </div>
    <?php endif; ?>

    <!-- Formularz rejestracji -->
    <form method="POST" action="register.php" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="username">Nazwa użytkownika</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Wprowadź nazwę użytkownika" required>
        </div>
        <div class="form-group">
            <label for="password">Hasło</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Wprowadź hasło" required oninput="checkPassword()">
            <small id="passwordHelp" class="form-text password-check">Hasło musi mieć co najmniej 8 znaków.</small>
        </div>
        <div class="form-group">
            <label for="confirm_password">Potwierdź hasło</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Powtórz hasło" required oninput="checkPasswordMatch()">
            <small id="confirmPasswordHelp" class="form-text confirm-password-check">Hasła się nie zgadzają.</small>
        </div>
        <button type="submit" name="register" class="btn btn-primary btn-block">Zarejestruj się</button>
    </form>

    <!-- Przycisk do logowania -->
    <a href="login.php" class="btn btn-secondary btn-block mt-3">Powrót do logowania</a>
</div>

<!-- Walidacja po stronie klienta (frontend) -->
<script>
    function checkPassword() {
        var password = document.getElementById('password').value;
        var passwordHelp = document.getElementById('passwordHelp');

        if (password.length >= 8) {
            passwordHelp.classList.add('valid');
            passwordHelp.textContent = 'Hasło spełnia wymagania.';
        } else {
            passwordHelp.classList.remove('valid');
            passwordHelp.textContent = 'Hasło musi mieć co najmniej 8 znaków.';
        }
    }

    function checkPasswordMatch() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;
        var confirmPasswordHelp = document.getElementById('confirmPasswordHelp');

        if (password === confirmPassword && password.length >= 8) {
            confirmPasswordHelp.classList.add('valid');
            confirmPasswordHelp.textContent = 'Hasła się zgadzają.';
        } else {
            confirmPasswordHelp.classList.remove('valid');
            confirmPasswordHelp.textContent = 'Hasła się nie zgadzają.';
        }
    }

    function validateForm() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        if (password.length < 8) {
            alert('Hasło musi mieć co najmniej 8 znaków.');
            return false;
        }

        if (password !== confirmPassword) {
            alert('Hasła się nie zgadzają.');
            return false;
        }

        return true;
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
