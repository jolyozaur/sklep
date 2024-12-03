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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $cost = $_POST['cost'];

    $stmt = $pdo->prepare("INSERT INTO Shipping_methods (name, cost) VALUES (?, ?)");
    $stmt->execute([$name, $cost]);

    header("Location: dostawy.php");
    exit();
}

$sql = "SELECT * FROM Shipping_methods";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Sposobami Dostawy</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <style>
.table{
    color:white;
}

   </style> 
</head>
<body>
    <header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="display-4">Panel Administracyjny</h1>
            <p class="lead">Zarządzanie metodami dostawy</p>
        </div>
    </header>

    <div class="container mt-5">
        <h2>Lista metod dostawy</h2>
        <a href="index.php" class="btn btn-danger mb-3">Strona główna</a>
        <a href="uzytkownicy.php" class="btn btn-danger mb-3">Zarządzaj <br> użytkownikami</a>
        <a href="kategorie.php" class="btn btn-danger mb-3">Zarządzaj<br> kategoriami</a>
        <a href="zamowienia.php" class="btn btn-danger mb-3">Zarządzaj<br> zamówieniami</a>
        <a href="podstrony.php" class="btn btn-danger mb-3">Zarządzaj<br> podstronami</a>
        <a href="admin.php" class="btn btn-danger mb-3">Zarządzaj<br> produktami</a>
        <a href="dostawy.php" class="btn btn-danger mb-3">Zarządzaj<br> dostawami</a>
    </div>

    <div class="container mt-5">
        <button class="btn btn-success mb-3" data-toggle="collapse" data-target="#addMethodForm">Dodaj nową metodę dostawy</button>

        <div id="addMethodForm" class="collapse mt-3">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Nazwa sposobu dostawy</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="cost">Koszt dostawy</label>
                    <input type="number" class="form-control" id="cost" name="cost" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-success">Dodaj</button>
            </form>
        </div>

        <table class="table mt-5">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Koszt</th>
                    <th>Opcje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['cost'] . " zł</td>";
                        echo "<td>
                            <a href='edit_shipping_method.php?id=" . $row['id'] . "' class='btn btn-warning'>Edytuj</a> |
                            <a href='delete_shipping_method.php?id=" . $row['id'] . "' class='btn btn-danger'>Usuń</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Brak metod dostawy</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
