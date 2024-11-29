<?php
session_start();



    if (isset($_POST['czysc']) && $_POST['czysc'] == 'true') {

        $_SESSION['cart'] = [];
     
        header('Location: index.php');
      
        exit;
    }


if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
    $stmt->execute([$userId]);
    $address = $stmt->fetch(PDO::FETCH_ASSOC);

   
    $stmt = $pdo->prepare("SELECT c.*, p.name, p.price FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {

    $cartItems = $_SESSION['cart'] ?? [];
}

$imie = $address['Imie'] ?? '';
$nazwisko = $address['Nazwisko'] ?? '';
$ulica = $address['ulica'] ?? '';
$miasto = $address['miasto'] ?? '';
$kod = $address['kod'] ?? '';
$tel = $address['tel'] ?? '';

$totalAmount = 0;

foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
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

<div class="container">
    <div class="form-container">
        <h2>Formularz Płatności</h2>

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

    <input type="hidden" name="czysc" value="true">

    <button type="submit" class="btn zaplace">Zapłać</button><br><br>
    <input type="button" class="btn wstecz" value="Wstecz" onClick="history.back();" />
</form>

</div>

    <div class="col-md-4">
        <div class="cart-box">
            <h4 class="text-center">Koszyk</h4>
            <ul class="list-group">
                <?php foreach ($cartItems as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?= htmlspecialchars($item['name']) ?></span>
                        <span><?= number_format($item['price'], 2) ?> PLN x <?= htmlspecialchars($item['quantity']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="total mt-3">
                <strong>Całkowita kwota: <?= number_format($totalAmount, 2) ?> PLN</strong>
            </div>
        </div>
    </div>

</div>

<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h4>Kwota do zapłaty: <?= number_format($totalAmount, 2) ?> PLN</h4>
        <div class="payment-methods">
            <div class="payment-method" onclick="selectPaymentMethod('Przelew')" id="paymentMethodPrzelew">
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e3/Przelewy24_logo.png" alt="Przelew" />
                <h5>Przelew</h5>
            </div>
            <div class="payment-method" onclick="selectPaymentMethod('Blik')" id="paymentMethodBlik">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Blik_logo.jpg" alt="Blik" />
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

<script src="platnosci.js"></script>
</body>
</html>
