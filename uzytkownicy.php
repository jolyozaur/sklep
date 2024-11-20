<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;
$isAdmin = false; // Domyślna wartość
$status_admina = null;
// Pobieranie danych użytkownika
if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email, czy_admin FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ustawienie zmiennej $isAdmin na podstawie danych z bazy
    if ($userData) {
        $isAdmin = (bool) $userData['czy_admin'];
    }
}

// Sprawdzenie, czy użytkownik jest zalogowany jako administrator
if ( $userData['czy_admin'] !== '1') {
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
$sql = "SELECT * FROM users";
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

    <h2>Panel Administracyjny</h2>
    <p>Witaj w panelu administracyjnym, <?php echo $_SESSION['username']; ?>!</p>

     
    <a href="logout.php" class="btn btn-danger ">Wyloguj się</a>
    <a href="index.php" class="btn btn-danger ">główna strona</a>
    <a href="admin.php" class="btn btn-danger ">Zarządzaj produktami</a>
                
    <h3 class="dane">Lista produktów</h3><br>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>username</th>
                <th>email</th>
                <th>data utworzenia</th>
                <th>Czy admin</th>
                <th>Opcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $status_admina = 'null';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                   if($row['czy_admin'] == 1){
                    $status_admina = 'tak';}
                    else{
                      $status_admina = 'nie';
                    }
                    
                   
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>" . $status_admina . "</td>";
                    echo "<td>
                            <a href='edit_users.php?id=" . $row['id'] . "' class='btn btn-warning'>Edytuj</a> |
                            <a href='delete_users.php?id=" . $row['id'] . "' class='btn btn-danger'>Usuń</a>
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
