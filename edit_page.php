<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = false;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT rodzaj FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && $userData['rodzaj'] === 'admin') {
        $isAdmin = true;
    }
}

if (!$isAdmin) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM podstrony WHERE id = ?");
    $stmt->execute([$id]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tytul = $_POST['tytul'];
    $tresc = $_POST['tresc'];

    $stmt = $pdo->prepare("UPDATE podstrony SET tytul = ?, tresc = ? WHERE id = ?");
    $stmt->execute([$tytul, $tresc, $id]);

    header("Location: podstrony.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Podstronę</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edytuj Podstronę: <?php echo htmlspecialchars($page['tytul']); ?></h2>
        <form method="POST">
            <div class="form-group">
                <label for="tytul">Tytuł Podstrony</label>
                <input type="text" class="form-control" id="tytul" name="tytul" value="<?php echo htmlspecialchars($page['tytul']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tresc">Treść Podstrony</label>
                <textarea class="form-control" id="tresc" name="tresc" rows="5" required><?php echo htmlspecialchars($page['tresc']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

</body>
</html>
