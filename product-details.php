<?php
require 'db.php'; // Połączenie z bazą danych

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Zapytanie do pobrania produktu
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Produkt nie został znaleziony.";
    exit;
}
// Zapytanie do pobrania zdjęć
$sql_zdjecia = "SELECT * FROM zdjecia_opis WHERE produkt_id = ?";
$stmt_zdjecia = $conn->prepare($sql_zdjecia);
$stmt_zdjecia->bind_param("i", $id);
$stmt_zdjecia->execute();
$result_zdjecia = $stmt_zdjecia->get_result();

// Zapytanie do pobrania opinii
$sql_opinie = "SELECT * FROM opinie WHERE produkt_id = ? ORDER BY data_utworzenia DESC";
$stmt_opinie = $conn->prepare($sql_opinie);
$stmt_opinie->bind_param("i", $id);
$stmt_opinie->execute();
$result_opinie = $stmt_opinie->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_produkt.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />

</head>
<body>
    <header>
        <h1>Sklep Motocyklowy</h1>
        <nav>
            <ul>
                <li><a href="index.php">Strona Główna</a></li>
                <li><a href="products.php">Motocykle</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="product-container">
            <div class="product-image">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="product-details">
                <h2><?php echo $product['name']; ?></h2>
                <p class="price">Cena: <strong><?php echo $product['price']; ?> PLN</strong></p>
                <p>Typ: <?php echo $product['type']; ?></p><br>
                <button class="buy-button">Kup Teraz</button>
            </div>
        </div>

        <div class="product-description">
            <h3>Opis produktu</h3>
            <p><?php echo $product['Opis']; ?></p> 
        </div>

        <div class="product-gallery">
    <h3>Galeria</h3>
    <div class="gallery-images">
        <?php if ($result_zdjecia->num_rows > 0): ?>
            <?php while ($zdjecie = $result_zdjecia->fetch_assoc()): ?>
                <img src="<?php echo htmlspecialchars($zdjecie['sciezka']); ?>" alt="" style="width: 100px; cursor: pointer;">
            <?php endwhile; ?>
        <?php else: ?>
            <p>Brak zdjęć dla tego produktu.</p>
        <?php endif; ?>
    </div>
</div>




        <div class="product-reviews">
            <h3>Opinie</h3>
            <?php if ($result_opinie->num_rows > 0): ?>
                <?php while ($opinia = $result_opinie->fetch_assoc()): ?>
                    <div class="review">
                        <strong><?php echo htmlspecialchars($opinia['autor']); ?></strong> - <em><?php echo $opinia['data_utworzenia']; ?></em>
                        <p><?php echo htmlspecialchars($opinia['tresc']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Brak opinii dla tego produktu.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>
    <!-- Modal -->
<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>

    <script src="script.js"></script>

</body>
</html>
