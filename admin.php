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

    if ($userData && ($userData['rodzaj'] === 'admin' || $userData['rodzaj'] === 'pracownik')) {
        $isAllowed = true;
    }
}

$stmt = $pdo->query("SELECT p.*, c.type 
FROM products p
JOIN categories c ON p.category_id = c.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $Opis = $_POST['Opis'];


    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = 'images/' . $imageName; 
        
        if (move_uploaded_file($imageTmp, $imagePath)) {
            $image = $imagePath;
        }
    }


    if ($name && $category_id && $price && $Opis && $image) {
        $stmt = $pdo->prepare("INSERT INTO products (name,  price, Opis, image, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name,  $price, $Opis, $image, $category_id]);
        header("Location: admin.php"); 
        exit;
    } else {
        echo "Proszę uzupełnić wszystkie pola.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie produktami</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        #addProductForm {
            display: none;
        }
    </style>
</head>
<body>
<header class="bg-dark text-white py-3">
    <div class="container">
        <h1 class="display-4">Panel Administracyjny</h1>
        <p class="lead">Zarządzanie kategoriami sklepu motocyklowego</p>
    </div>
    <div class="container mt-5">
    <a href="index.php" class="btn btn-danger mb-3">Strona główna</a>
        <a href="uzytkownicy.php" class="btn btn-danger mb-3">Zarządzaj <br> użytkownikami</a>
        <a href="kategorie.php" class="btn btn-danger mb-3">Zarządzaj<br> kategoriami</a>
        <a href="zamowienia.php" class="btn btn-danger mb-3">Zarządzaj<br> zamówieniami</a>
        <a href="podstrony.php" class="btn btn-danger mb-3">Zarządzaj<br> podstronami</a>
        <a href="admin.php" class="btn btn-danger mb-3">Zarządzaj<br> produktami</a>
        <a href="dostawy.php" class="btn btn-danger mb-3">Zarządzaj<br> dostawami</a>
    </div>
</header>


<div class="container mt-5">
    <button class="btn btn-success" onclick="toggleForm()">Dodaj nowy produkt</button>
</div>

<div class="container mt-5" id="addProductForm">
    <h3>Dodaj nowy produkt</h3>
    <form action="admin.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nazwa produktu</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="category_id">Kategoria</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <?php
                $categoriesStmt = $pdo->query("SELECT * FROM categories");
                $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categories as $category) {
                    echo "<option value='" . $category['id'] . "'>" . $category['type'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="price">Cena</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="Opis">Opis produktu</label>
            <textarea class="form-control" id="Opis" name="Opis" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Zdjęcie produktu</label>
            <input type="file" class="form-control" id="image" name="image" required>
        </div>
        <button type="submit" class="btn btn-success">Zatwierdź</button>
    </form>
</div>

<div class="container mt-5">
    <h3 class="dane">Lista produktów</h3>
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

<script>
    function toggleForm() {
        const form = document.getElementById('addProductForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>

</body>
</html>

