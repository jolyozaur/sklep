<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}

$userId = $_SESSION['user_id'];

if (isset($_GET['address_id'])) {
    $addressId = intval($_GET['address_id']);


    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? AND id_adresu = ?");
    $stmt->execute([$userId, $addressId]);
    $address = $stmt->fetch();

    if (!$address) {
     
        header('Location: platnosci.php');
        exit;
    }
} else {
    header('Location: platnosci.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $ulica = $_POST['ulica'];
    $miasto = $_POST['miasto'];
    $kod = $_POST['kod'];
    $tel = $_POST['tel'];

  
    if (!empty($imie) && !empty($nazwisko) && !empty($ulica) && !empty($miasto) && !empty($kod) && !empty($tel)) {
        $stmt = $pdo->prepare("UPDATE addresses SET Imie = ?, Nazwisko = ?, ulica = ?, miasto = ?, kod = ?, tel = ? WHERE id_adresu = ?");
        $stmt->execute([$imie, $nazwisko, $ulica, $miasto, $kod, $tel, $addressId]);

    
        header('Location: platnosci.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj adres</title>
    <link rel="stylesheet" href="platnosci.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Edytuj adres</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="imie" class="form-label">Imię</label>
                <input type="text" class="form-control" id="imie" name="imie" value="<?= htmlspecialchars($address['Imie']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nazwisko" class="form-label">Nazwisko</label>
                <input type="text" class="form-control" id="nazwisko" name="nazwisko" value="<?= htmlspecialchars($address['Nazwisko']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ulica" class="form-label">Ulica</label>
                <input type="text" class="form-control" id="ulica" name="ulica" value="<?= htmlspecialchars($address['ulica']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="miasto" class="form-label">Miasto</label>
                <input type="text" class="form-control" id="miasto" name="miasto" value="<?= htmlspecialchars($address['miasto']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="kod" class="form-label">Kod pocztowy</label>
                <input type="text" class="form-control" id="kod" name="kod" value="<?= htmlspecialchars($address['kod']) ?>" required pattern="\d{2}-\d{3}">
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">Telefon</label>
                <input type="tel" class="form-control" id="tel" name="tel" value="<?= htmlspecialchars($address['tel']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Aktualizuj adres</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
