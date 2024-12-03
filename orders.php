<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; 
$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje zamówienia</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
<header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="display-4">Panel Administracyjny</h1>
            <p class="lead">Zarządzanie kategoriami sklepu motocyklowego</p>
        </div>
        <div class="container mt-5">


<a href="index.php" class="btn btn-danger">Strona główna</a>
<a href="uzytkownicy.php" class="btn btn-danger">Zarządzaj użytkownikami</a>
<a href="kategorie.php" class="btn btn-danger">Zarządzaj kategoriami</a>
<a href="zamowienia.php" class="btn btn-danger">Zarządzaj zamówieniami</a>
<a href="podstrony.php" class="btn btn-danger">Zarządzaj podstronami</a>
<a href="admin.php" class="btn btn-danger">Zarządzaj produktami</a>
    </header>

    <div class="container">
        <h1>Moje zamówienia</h1>
        <?php echo $user_id;?>
        <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Zamówienia</th>
                        <th>Status</th>
                        <th>Data Zamówienia</th>
                        <th>Kwota</th>
                        <th>Szczóły</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($order['order_date'])); ?></td>
                            <td><?php echo number_format($order['total'], 2, ',', ' ') . ' zł'; ?></td>
                            <td><a href="order_details.php?order_id="<?php echo $order['id']; ?>>Szczegóły</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nie masz żadnych zamówień.</p>
        <?php endif; ?>
    </div>
</body>
</html>
