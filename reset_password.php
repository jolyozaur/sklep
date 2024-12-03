<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Sprawdzenie, czy e-mail istnieje w bazie
    $sql = "SELECT id, username FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username);
        $stmt->fetch();

        // Generowanie tokenu resetującego
        $token = bin2hex(random_bytes(50)); // Generowanie losowego tokenu
        $expiry = time() + 3600; // Token wygasa za godzinę

        // Zapisanie tokenu i daty wygaśnięcia w bazie
        $sql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $token, $expiry, $id);
        $stmt->execute();

        // Tworzenie linku resetującego
        $reset_link = "http://yourdomain.com/reset_password_form.php?token=" . $token;

        // Wysłanie e-maila
        $subject = "Link do resetowania hasła";
        $message = "Kliknij w poniższy link, aby zresetować swoje hasło:\n" . $reset_link;
        $headers = "From: your-email@example.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Link do resetowania hasła został wysłany na Twój adres e-mail.";
        } else {
            echo "Wystąpił problem podczas wysyłania e-maila.";
        }
    } else {
        echo "Nie znaleziono użytkownika z takim adresem e-mail.";
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
    <title>Resetowanie hasła</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="login-container">
    <h2>Resetowanie hasła</h2>
    <form method="POST" action="reset_password.php">
        <div class="form-group">
            <label for="email">Wprowadź swój adres e-mail</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Wprowadź e-mail" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Wyślij link do resetowania hasła</button>
    </form>
    <a href="login.php" class="btn btn-secondary btn-block mt-3">Powrót do logowania</a>
</div>
</body>
</html>
