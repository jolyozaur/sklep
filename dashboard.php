<?php
session_start();

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obsługa wylogowania
if (isset($_POST['logout'])) {
    session_unset(); // Usuwa wszystkie zmienne sesji
    session_destroy(); // Kończy sesję
    header('Location: login.php'); // Przekierowanie na stronę logowania
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Witaj w panelu użytkownika!</h1>

    <!-- Formularz wylogowania -->
    <form method="POST" action="dashboard.php">
        <button type="submit" name="logout" class="btn btn-danger">Wyloguj się</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
