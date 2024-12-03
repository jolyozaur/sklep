<?php
session_start();
require 'db.php'; 

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = false;

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

$stmt = $pdo->query("SELECT p.*, c.type 
FROM products p
JOIN categories c ON p.category_id = c.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie produktami</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="display-4">Panel Administracyjny</h1>
            <p class="lead">Zarządzanie produktami w sklepie motocyklowym</p>
        </div>
    </header>

  
    <div class="container mt-5">
        <h2>Panel Administracyjny</h2>
        <p>Witaj w panelu administracyjnym, <?php echo $_SESSION['username']; ?>!</p>
        
        <a href="logout.php" class="btn btn-danger">Wyloguj się</a>
        <a href="index.php" class="btn btn-danger">Strona główna</a>
        <a href="uzytkownicy.php" class="btn btn-danger">Zarządzaj użytkownikami</a>
        <a href="kategorie.php" class="btn btn-danger">Zarządzaj kategoriami</a>
        <a href="zamowienia.php" class="btn btn-danger">Zarządzaj zamówieniami</a>
        <br><br>

        <h3 class="dane">Lista produktów</h3>
        <br>

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
