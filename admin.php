<?php
session_start();
require 'db.php'; // Tutaj umieść dane do PDO.

$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$isAdmin = false;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email, czy_admin FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && $userData['czy_admin']) {
        $isAdmin = true;
    }
}

if (!$isAdmin) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style_login_rej.css">
</head>    
<style>
.table{

    color:white;
}
    </style>
<body>
    
<div class="container mt-5">
<br></br><br></br><br></br><br></br><br></br>
    <h2>Panel Administracyjny</h2>
    <p>Witaj w panelu administracyjnym, <?php echo $_SESSION['username']; ?>!</p>

     
    <a href="logout.php" class="btn btn-danger ">Wyloguj się</a>
    <a href="index.php" class="btn btn-danger ">główna strona</a>
    <a href="uzytkownicy.php" class="btn btn-danger ">Zarządzaj uzytkownikami</a>
                
    <h3 class="dane">Lista produktów</h3><br>
   <table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Typ</th>
            <th>Zdjęcie podglądowe</th>
            <th>Cena</th>
            <th>Opis</th>
            <th>Opcje</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($products)) {
            foreach ($products as $row) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['type'] . "</td>";
                echo "<td><img src='" . $row['image'] . "' alt='" . $row['name'] . "' style='width:100px;'></td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['Opis'] . "</td>";
                echo "<td>
                        <a href='edit_product.php?id=" . $row['id'] . "' class='btn btn-warning'>Edytuj</a> |
                        <a href='delete_product.php?id=" . $row['id'] . "' class='btn btn-danger'>Usuń</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Brak produktów</td></tr>";
        }
        ?>
    </tbody>
</table>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>


