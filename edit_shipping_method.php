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
    $stmt = $pdo->prepare("SELECT * FROM Shipping_methods WHERE id = ?");
    $stmt->execute([$id]);
    $method = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $cost = $_POST['cost'];

    $stmt = $pdo->prepare("UPDATE Shipping_methods SET name = ?, cost = ? WHERE id = ?");
    $stmt->execute([$name, $cost, $id]);

    header("Location: shipping_methods.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Sposób Dostawy</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="display-4">Panel Administracyjny</h1>
            <p class="lead">Edytuj sposób dostawy</p>
        </div>
    </header>

    <div class="container mt-5">
        <h2>Edytuj sposób dostawy: <?php echo htmlspecialchars($method['name']); ?></h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Nazwa sposobu dostawy</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($method['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="cost">Koszt dostawy</label>
                <input type="number" class="form-control" id="cost" name="cost" value="<?php echo htmlspecialchars($method['cost']); ?>" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-success">Zapisz zmiany</button>
        </form>
        <a href="dostawy.php" class="btn btn-danger mt-3">Powrót</a>
    </div>

    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>
</body>
</html>
