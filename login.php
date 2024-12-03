<?php
session_start();
require 'db.php';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = ""; 
$forgot_password_message = ""; // Zmienna do wyświetlania komunikatu o wysłaniu e-maila

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['forgot_password'])) {
        // Przesyłanie linku do resetu hasła
        $email = $_POST['email'];

        // Sprawdź, czy e-mail istnieje w bazie
        $sql = "SELECT id, email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Przykładowy kod do wysłania linku do resetu hasła (musisz skonfigurować wysyłanie maili)
            // Załóżmy, że masz funkcję do wysyłania maili z linkiem do resetu hasła
            $forgot_password_message = "Link do resetu hasła został wysłany na Twój e-mail.";
        } else {
            $forgot_password_message = "Nie znaleziono użytkownika z tym adresem e-mail.";
        }

        $stmt->close();
    } else {
        // Logowanie
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT id, username, password_hash FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $passwordHash);
            $stmt->fetch();

            if (password_verify($password, $passwordHash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;

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
    <link rel="stylesheet" href="style_login_rej.css"> 
    <style>
        #forgot-password-form {
            display: none;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Logowanie</h2>

    <?php if (!empty($login_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($login_error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($forgot_password_message)): ?>
        <div class="alert alert-info" role="alert">
            <?= htmlspecialchars($forgot_password_message) ?>
        </div>
    <?php endif; ?>

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

    <a href="register.php" class="btn btn-secondary btn-block mt-3">Rejestracja</a>
    <a href="index.php" class="btn btn-secondary btn-block mt-3">Strona Główna</a>

    <a href="javascript:void(0);" id="forgot-password-link" class="btn btn-link mt-3">Nie pamiętam hasła</a>

    <div id="forgot-password-form" class="mt-3">
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Wprowadź swój adres e-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Twój adres e-mail" required>
            </div>
            <button type="submit" name="forgot_password" class="btn btn-warning btn-block">Wyślij link resetujący hasło</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('forgot-password-link').addEventListener('click', function() {
        document.getElementById('forgot-password-form').style.display = 'block';
    });
</script>
</body>
</html>
