<?php
session_start();
require 'db.php';

// Upewnij się, że użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Przekierowanie do logowania, jeśli użytkownik nie jest zalogowany
    exit();
}

$userId = $_SESSION['user_id'];

// Pobierz dane użytkownika z bazy
$stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
$stmt->execute([$userId]);
$address = $stmt->fetch(PDO::FETCH_ASSOC);

// Jeśli brak danych adresowych, ustaw pustną wartość
$imie = $address['Imie'] ?? '';
$nazwisko = $address['Nazwisko'] ?? '';
$ulica = $address['ulica'] ?? '';
$miasto = $address['miasto'] ?? '';
$kod = $address['kod'] ?? '';
$tel = $address['tel'] ?? '';

// Obliczanie całkowitej kwoty zamówienia z koszyka
$totalAmount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalAmount += $item['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularz Płatności</title>
    <link rel="stylesheet" href="platnosci.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Formularz Płatności</h2>

    <form method="POST" id="paymentForm">
        <div class="mb-3">
            <label for="imie" class="form-label">Imię</label>
            <input type="text" class="form-control" id="imie" name="imie" value="<?= htmlspecialchars($imie) ?>" required>
        </div>

        <div class="mb-3">
            <label for="nazwisko" class="form-label">Nazwisko</label>
            <input type="text" class="form-control" id="nazwisko" name="nazwisko" value="<?= htmlspecialchars($nazwisko) ?>" required>
        </div>

        <div class="mb-3">
            <label for="ulica" class="form-label">Ulica</label>
            <input type="text" class="form-control" id="ulica" name="ulica" value="<?= htmlspecialchars($ulica) ?>" required>
        </div>

        <div class="mb-3">
            <label for="miasto" class="form-label">Miasto</label>
            <input type="text" class="form-control" id="miasto" name="miasto" value="<?= htmlspecialchars($miasto) ?>" required>
        </div>

        <div class="mb-3">
            <label for="kod" class="form-label">Kod pocztowy</label>
            <input type="text" class="form-control" id="kod" name="kod" value="<?= htmlspecialchars($kod) ?>" required pattern="\d{2}-\d{3}">
        </div>

        <div class="mb-3">
            <label for="tel" class="form-label">Telefon</label>
            <input type="tel" class="form-control" id="tel" name="tel" value="<?= htmlspecialchars($tel) ?>" required pattern="^(\+?\d{1,3})? ?\d{3} ?\d{3} ?\d{3}$">
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-primary" id="paymentButton">Zapłać</button>
        </div>
    </form>

    <!-- Modal dla wyboru metod płatności -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h4>Kwota do zapłaty: <?= number_format($totalAmount, 2) ?> PLN</h4>

            <!-- Kafelki wyboru metod płatności -->
            <div class="payment-methods">
                <div class="payment-method" onclick="selectPaymentMethod('Przelew')" id="paymentMethodPrzelew">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e3/Przelewy24_logo.png" alt="Przelew" />
                    <h5>Przelew</h5>
                </div>
                <div class="payment-method" onclick="selectPaymentMethod('Blik')" id="paymentMethodBlik">
                    <img src="data:image/png;base64,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" alt="Blik" />
                    <h5>Blik</h5>
                </div>
                <div class="payment-method" onclick="selectPaymentMethod('Zapłata przy odbiorze')" id="paymentMethodOdbior">
                    <img src="https://www.sklep.bungalow.com.pl/wp-content/uploads/2020/04/odbior-osobisty.png" alt="PrzyOdbiorze" />
                    <h5>Przy odbiorze</h5>
                </div>
                <div class="payment-method" onclick="selectPaymentMethod('ApplePay')" id="paymentMethodApplePay">
                    <img src="https://www.bskowal.pl/upload/image/specjalne/ikona_applepay_s.jpg" alt="Apple Pay" />
                    <h5>Apple Pay</h5>
                </div>
            </div>
            <form action="process_platnosci.php" method="POST" id="paymentConfirmationForm">
                <input type="hidden" id="selectedPaymentMethod" name="payment_method" value="">
                <input type="hidden" id="paymentAmount" name="amount" value="<?= $totalAmount ?>">
                <button type="submit" class="btn btn-success mt-3">Potwierdź płatność</button>
            </form>
        </div>
    </div>
</div>

<script src="platnosci.js"></script>
</body>
</html>
