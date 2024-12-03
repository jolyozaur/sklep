<?php
session_start();
require 'db.php';
$loggedIn = isset($_SESSION['user_id']);
$isAllowed = false;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email, rodzaj FROM users WHERE id = ?");
    $stmt->execute([$userId]); 
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Sprawdzamy, czy użytkownik jest administratorem lub pracownikiem
    if ($userData && ($userData['rodzaj'] === 'admin' || $userData['rodzaj'] === 'pracownik')) {
        $isAllowed = true;
    }
}

if (!$isAllowed) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userdane = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userdane) {
        die("Użytkownik nie znaleziony.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $rodzaj = $_POST['rodzaj'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, rodzaj = ? WHERE id = ?");
    $stmt->execute([$username, $email, $rodzaj, $userId]);

    header("Location: uzytkownicy.php"); 
    exit;
}

// Pobranie dostępnych rodzajów konta (np. 'admin', 'pracownik', 'klient')
$stmt = $pdo->prepare("SELECT DISTINCT rodzaj FROM users");
$stmt->execute();
$rodzaje = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Użytkownika</title>
    <link rel="stylesheet" href="style_edycja.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edytuj Użytkownika</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Nazwa użytkownika</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userdane['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userdane['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="rodzaj">Rodzaj konta</label>
                <select class="form-control" id="rodzaj" name="rodzaj" required>
                    <?php foreach ($rodzaje as $rodzajOption): ?>
                        <option value="<?= $rodzajOption['rodzaj'] ?>" <?= $userdane['rodzaj'] === $rodzajOption['rodzaj'] ? 'selected' : '' ?>>
                            <?= ucfirst($rodzajOption['rodzaj']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
           
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button><br><br>
            <a href="uzytkownicy.php" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
