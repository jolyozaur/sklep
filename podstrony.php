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


$sql = "SELECT * FROM podstrony";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Podstronami</title>
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
            <p class="lead">Zarządzanie podstronami sklepu motocyklowego</p>
        </div>
    </header>


    <div class="container mt-5">
        <h2>Lista Podstron</h2>
        <a href="add_page.php" class="btn btn-success mb-3">Dodaj nową podstronę</a>
        <table class="table ">
            <thead class="">
                <tr>
                    <th>ID</th>
                    <th>Tytuł</th>
                    <th>Data utworzenia</th>
                    <th>Opcje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['tytul'] . "</td>";
                        echo "<td>" . $row['data_utworzenia'] . "</td>";
                        echo "<td>
                          <a href='view_page.php?id=" . $row['id'] . "' class='btn btn-info'>Zobacz</a> |
                          <a href='edit_page.php?id=" . $row['id'] . "' class='btn btn-warning'>Edytuj</a> |
                          <a href='delete_page.php?id=" . $row['id'] . "' class='btn btn-danger'>Usuń</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Brak podstron</td></tr>";
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
