<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;
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

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie użytkownikami</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>    
<style>
.table{
    color:white;
}
</style>
<body>
<header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="display-4">Panel Administracyjny</h1>
            <p class="lead">Zarządzanie użytkownikami sklepu motocyklowego</p>
        </div>
    </header>
<div class="container mt-5">

    <a href="logout.php" class="btn btn-danger">Wyloguj się</a>
    <a href="index.php" class="btn btn-danger">Główna strona</a>
    <a href="uzytkownicy.php" class="btn btn-danger">Zarządzaj użytkownikami</a>
    <a href="kategorie.php" class="btn btn-danger">Zarządzaj kategoriami</a>
    <a href="zamowienia.php" class="btn btn-danger">Zarządzaj zamówieniami</a>
    <br><br>

    <h3 class="dane">Lista użytkowników</h3><br>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Data utworzenia</th>
                <th>Rodzaj konta</th>
                <th>Opcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  
              

                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>" . $row['rodzaj'] . "</td>";
                    echo "<td>
                            <a href='edit_users.php?id=" . $row['id'] . "' class='btn btn-warning'>Edytuj</a> |
                            <a href='delete_users.php?id=" . $row['id'] . "' class='btn btn-danger'>Usuń</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Brak użytkowników</td></tr>";
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

<?php
$conn->close();
?>
