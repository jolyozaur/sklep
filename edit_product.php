<?php
require 'db.php';
session_start();
$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;
$isAdmin = false;
$status_admina = null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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

if (!$isAllowed) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT p.*, c.type 
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Produkt nie znaleziony.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $opis = $_POST['opis'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, category_id = ?, price = ?, Opis = ? WHERE id = ?");
    $stmt->execute([$name, $type, $price, $opis, $productId]);

    if (!empty($_FILES['new_images']['name'][0])) {
        $uploadDir = "zdjecia_opis/";
        foreach ($_FILES['new_images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['new_images']['name'][$key]);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $filePath)) {
                $stmt = $pdo->prepare("INSERT INTO zdjecia_opis (produkt_id, sciezka) VALUES (?, ?)");
                $stmt->execute([$productId, $filePath]);
            } else {
                echo "Błąd przesyłania pliku: " . $fileName . "<br>";
            }
        }
    }

    if (isset($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $imageId) {
            $stmt = $pdo->prepare("SELECT sciezka FROM zdjecia_opis WHERE id = ?");
            $stmt->execute([$imageId]);
            $imageData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($imageData) {
                $filePath = $imageData['sciezka'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $stmt = $pdo->prepare("DELETE FROM zdjecia_opis WHERE id = ?");
                $stmt->execute([$imageId]);
            }
        }
    }

    header("Location: admin.php");
    exit;
}

$categoriesStmt = $pdo->prepare("SELECT * FROM categories");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

$sql_zdjecia = "SELECT * FROM zdjecia_opis WHERE produkt_id = ?";
$stmt_zdjecia = $conn->prepare($sql_zdjecia);
$stmt_zdjecia->bind_param("i", $id);
$stmt_zdjecia->execute();
$result_zdjecia = $stmt_zdjecia->get_result();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Produkt</title>
    <link rel="stylesheet" href="style_edycja.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edytuj Produkt</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nazwa Produktu</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Typ</label>
                <select class="form-control" id="type" name="type" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['type']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Cena</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>
            <div class="form-group">
                <label for="opis">Opis</label>
                <input type="text" class="form-control" id="opis" name="opis" value="<?= htmlspecialchars($product['Opis']) ?>" >
            </div>

            <div class="product-gallery">
                <h3>Galeria</h3>
                <div class="gallery-grid">
                    <?php if ($result_zdjecia->num_rows > 0): ?>
                        <?php while ($zdjecie = $result_zdjecia->fetch_assoc()): ?>
                            <div class="image-item">
                                <img src="<?= htmlspecialchars($zdjecie['sciezka']) ?>" alt="Zdjęcie produktu">
                                <button class="btn btn-danger btn-sm btn-delete" disabled>
                                    <input type="checkbox" name="delete_images[]" value="<?= $zdjecie['id'] ?>" id="delete_<?= $zdjecie['id'] ?>" class="delete-checkbox">
                                    Usuń
                                </button>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Brak zdjęć dla tego produktu.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="new_images">Dodaj nowe zdjęcia</label>
                <input type="file" class="form-control btn-add-image" id="new_images" name="new_images[]" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Zapisz zmiany</button><br><br>
            <a href="admin.php" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
