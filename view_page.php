<?php
session_start();
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM podstrony WHERE id = ?");
    $stmt->execute([$id]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$page) {
        die("Podstrona nie została znaleziona.");
    }
} else {
    die("Brak ID podstrony w URL.");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['tytul']) ?></title>
    <link rel="stylesheet" href="style.css"> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap -->
</head>
<body>

    <header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="display-4"><?= htmlspecialchars($page['tytul']) ?></h1>
        </div>
    </header>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="content-box p-4">
                    <h2 class="h4">Treść podstrony</h2>
                    <p><?= nl2br(htmlspecialchars($page['tresc'])) ?></p>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
