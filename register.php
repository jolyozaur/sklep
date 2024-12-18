<?php
session_start();
include 'db.php';
$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $register_error = "Wszystkie pola są wymagane.";
    } elseif ($password !== $confirm_password) {
        $register_error = "Hasła nie są zgodne.";
    } elseif (strlen($password) < 8) {
        $register_error = "Hasło musi mieć co najmniej 8 znaków.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $register_error = "Użytkownik o tej nazwie już istnieje.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email) VALUES (:username, :password, :email)");
            $stmt->execute(['username' => $username, 'password' => $hashed_password, 'email' => $email]);
            
            

            $register_success = "Rejestracja przebiegła pomyślnie! Zostaniesz przekierowany na stronę logowania.";
            header("refresh:2;url=login.php"); 
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
    <link rel="stylesheet" href="style_login_rej.css">

       
</head>
<body>
<div class="register-container">
    <h2>Rejestracja</h2>

    <?php if (!empty($register_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($register_error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($register_success)): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($register_success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php" class="form-container" onsubmit="return validateForm()">
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
        <div class="form-group">
    <label for="email">Adres e-mail</label>
    <input type="email" class="form-control" id="email" name="email" required placeholder="Wprowadź adres e-mail" required>
</div>
        <button type="submit" name="register" class="btn btn-primary btn-block">Zarejestruj się</button>
    </form>

    <a href="login.php" class="btn btn-secondary btn-block mt-3">Powrót do logowania</a>
</div>

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
