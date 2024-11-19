<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany jako administrator
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin1234') {
    header("Location: login.php"); // Jeśli nie, przekierowanie na stronę logowania
    exit;
}

require 'db.php';
$host = 'localhost';
$db = 'm10280_motocykle_skep';
$user = 'root'; 
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobieranie danych o produktach
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style_login_rej.css"> <!-- Używamy tego samego stylu -->
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
  
                
    <h3 class="dane">Lista produktów</h3><br>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Typ</th>
                <th>Cena</th>
                <th>Opis</th>
                <th>Opcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['Opis'] . "</td>";
                    echo "<td>
                            <a href='edit_product.php?id=" . $row['id'] . "' class='btn btn-warning'>Edytuj</a> |
                            <a href='delete_product.php?id=" . $row['id'] . "' class='btn btn-danger'>Usuń</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Brak produktów</td></tr>";
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
