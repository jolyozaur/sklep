<?php
session_start();
require 'db.php';

// Sprawdzenie, czy użytkownik jest zalogowany jako administrator
$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;
$isAdmin = false;

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email, czy_admin FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && $userData['czy_admin']) {
        $isAdmin = true;
    }
} else {
    $userId = null;
}

if (!$isAdmin) {
    header("Location: login.php");
    exit;
}

// Pobranie wszystkich zamówień
$stmt = $pdo->prepare("SELECT * FROM orders ORDER BY order_date DESC");
$stmt->execute();
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie zamówieniami</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style_login_rej.css">
</head>
<style>
.table {
    color: white;
}
</style>
<body>
    
<div class="container mt-5">

    <h2>Panel Administracyjny</h2>
    <p>Witaj w panelu administracyjnym, <?php echo $_SESSION['username']; ?>!</p>

    <a href="logout.php" class="btn btn-danger ">Wyloguj się</a>
    <a href="index.php" class="btn btn-danger ">główna strona</a>
    <a href="uzytkownicy.php" class="btn btn-danger ">Zarządzaj uzytkownikami</a>
    <a href="kategorie.php" class="btn btn-danger ">Zarządzaj kategoriami</a>
    <a href="zamowienia.php" class="btn btn-danger ">Zarządzaj zamówieniami</a>
    <br><br>

    <h3 class="dane">Lista zamówień</h3><br>
    <table class="table">
        <thead>
            <tr>
                <th>ID Zamówienia</th>
                <th>ID Użytkownika</th>
                <th>Data Zamówienia</th>
                <th>Status</th>
                <th>Całkowita Kwota</th>
                <th>Produkty</th>
                <th>Adres</th>
                <th>Opcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($orders)) {
                foreach ($orders as $order) {
                
                    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
                    $stmt->execute([$order['user_id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

          
                    $stmt = $pdo->prepare("
                        SELECT oi.*, p.name, p.price 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?
                    ");
                    $stmt->execute([$order['id']]);
                    $orderedItems = $stmt->fetchAll();

                
                    $productList = '';
                    foreach ($orderedItems as $item) {
                        $productList .= htmlspecialchars($item['name']) . " - " . htmlspecialchars($item['quantity']) . " x " . number_format($item['price'], 2) . " PLN<br>";
                    }

                  
                    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE id_adresu = ?");
                    $stmt->execute([$order['address_id']]);
                    $address = $stmt->fetch(PDO::FETCH_ASSOC);

               
                    $addressDetails = '';
                    if ($address) {
                        $addressDetails = htmlspecialchars($address['Imie']) . " " . htmlspecialchars($address['Nazwisko']) . "<br>" .
                                          htmlspecialchars($address['ulica']) . "<br>" .
                                          htmlspecialchars($address['miasto']) . ", " . htmlspecialchars($address['kod']) . "<br>" .
                                          "Telefon: " . htmlspecialchars($address['tel']);
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['status']) . "</td>";
                    echo "<td>" . number_format($order['total_amount'], 2) . " PLN</td>";
                    echo "<td>" . $productList . "</td>";
                    echo "<td>" . $addressDetails . "</td>";
                    echo "<td>
                            <a href='edit_order.php?id=" . $order['id'] . "' class='btn btn-warning'>Edytuj</a> |
                            <a href='delete_order.php?id=" . $order['id'] . "' class='btn btn-danger'>Usuń</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Brak zamówień.</td></tr>";
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

$pdo = null;
?>
