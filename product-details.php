<?php
require 'db.php'; 
session_start(); 

$loggedIn = isset($_SESSION['user_id']);
$userData = null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tresc_opinii'])) {
    $tresciopini = trim($_POST['tresc_opinii']);
    $autor = "anonim"; 

    if ($loggedIn) {
        $userId = $_SESSION['user_id'];

 
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $autor = $userData['username'];
        }
    }

    $data_utworzenia = date("Y-m-d H:i:s");

    if (!empty($tresciopini)) {
        $sql_opinie_dodaj = "INSERT INTO opinie (produkt_id, autor, tresc, data_utworzenia) VALUES (?, ?, ?, ?)";
        $stmt_opinie_dodaj = $conn->prepare($sql_opinie_dodaj);
        $stmt_opinie_dodaj->bind_param("isss", $id, $autor, $tresciopini, $data_utworzenia);

        if ($stmt_opinie_dodaj->execute()) {
            echo "Opinia została dodana pomyślnie.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit; 
        } else {
            echo "Błąd podczas dodawania opinii.";
        }
    } else {
        echo "Treść opinii nie może być pusta.";
    }
}

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


$sql_zdjecia = "SELECT * FROM zdjecia_opis WHERE produkt_id = ?";
$stmt_zdjecia = $conn->prepare($sql_zdjecia);
$stmt_zdjecia->bind_param("i", $id);
$stmt_zdjecia->execute();
$result_zdjecia = $stmt_zdjecia->get_result();


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
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
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

    <h4>Dodaj Opinię</h4>
    <form method="POST" action="">
        <div class="form-group">
            <label for="tresc_opinii">Twoja opinia</label><br>
            <textarea name="tresc_opinii" placeholder="Wpisz opinię" required></textarea><br><br>
        </div>
        <button type="submit" class="btn btn-danger">Wyślij opinię</button>
    </form>

    <h4>Dotychczasowe Opinie</h4>
    <div class="reviews">
        <?php
        $sql = "SELECT * FROM opinie WHERE produkt_id = 1 ORDER BY data_utworzenia DESC";
        $result_opinie = $conn->query($sql);

        if ($result_opinie->num_rows > 0) {
            while ($opinia = $result_opinie->fetch_assoc()) {
                echo "<div class='review'>";
                echo "<strong>" . htmlspecialchars($opinia['autor']) . "</strong> - ";
                echo "<em>" . $opinia['data_utworzenia'] . "</em>";
                echo "<p>" . htmlspecialchars($opinia['tresc']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Brak opinii dla tego produktu.</p>";
        }
        ?>
    </div>
</div>



    </main>

    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

    <script src="script.js"></script>

</body>
</html>
