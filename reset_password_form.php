<?php
session_start();
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Sprawdzamy, czy token jest ważny
    $sql = "SELECT id, reset_token_expiry FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $reset_token_expiry);
        $stmt->fetch();

        if ($reset_token_expiry > time()) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $new_password = $_POST['new_password'];

                // Hashowanie nowego hasła
                $passwordHash = password_hash($new_password, PASSWORD_DEFAULT);

                // Zaktualizowanie hasła w bazie
                $sql = "UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $passwordHash, $id);
                $stmt->execute();

                echo "Twoje hasło zostało zaktualizowane.";
                exit;
            }
        } else {
            echo "Token wygasł.";
        }
    } else {
        echo "Nie znaleziono użytkownika.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zmiana hasła</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="login-container">
    <h2>Zmiana hasła</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="new_password">Nowe hasło</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Zmień hasło</button>
    </form>
</div>
</body>
</html>
