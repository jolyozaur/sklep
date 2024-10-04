<?php
global $pdo;
session_start();
include 'config.php';


if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}


$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];


    if (!empty($username) && !empty($password)) {
      
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

      
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); 
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
    <link rel="stylesheet" href="style.css">
  
        
</head>
<body>
<div class="login-container">
    <h2>Logowanie</h2>


    <?php if (!empty($login_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($login_error) ?>
        </div>
    <?php endif; ?>


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

   
    <a href="register.php" class="btn btn-secondary btn-block mt-3">Rejestracja</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
