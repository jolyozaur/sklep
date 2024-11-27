<?php
// process_platnosci.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $paymentMethod = htmlspecialchars($_POST['payment_method']);
    $amount = htmlspecialchars($_POST['amount']);

    // Tutaj możesz podłączyć odpowiednie API do przetwarzania płatności (np. TPAY, PayPal)
    
    // Po zakończeniu płatności wyświetlamy potwierdzenie z animacją
    echo '
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Potwierdzenie Płatności</title>
        <style>
            /* Styl dla modala */
            .modal {
                display: none; /* Ukryty domyślnie */
                position: fixed;
                z-index: 1000;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5); /* Ciemne tło */
                display: flex;
                justify-content: center;
                align-items: center;
                animation: fadeIn 0.5s ease-out;
            }

            .modal-content {
                background-color: #fff;
                padding: 40px;
                text-align: center;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
                position: relative;
                width: 300px;
            }

            .checkmark-container {
                margin-bottom: 20px;
                animation: checkmarkAnimation 1s ease-out;
            }

            .checkmark-svg {
                width: 80px;
                height: 80px;
                margin: 0 auto;
            }

            .checkmark-circle {
                stroke-dasharray: 157; /* Długość okręgu */
                stroke-dashoffset: 157;
                animation: circleAnimation 1.5s ease-out forwards;
            }

            .checkmark {
                stroke-dasharray: 48; /* Długość ścieżki ptaszka */
                stroke-dashoffset: 48;
                animation: checkmarkPathAnimation 1s 0.5s ease-out forwards;
            }

            .payment-message {
                font-size: 18px;
                color: #4CAF50;
                font-weight: bold;
                margin-top: 20px;
            }

            /* Styl przycisku powrotu */
            .back-button {
                display: inline-block;
                padding: 10px 20px;
                margin-top: 20px;
                background-color: #4CAF50;
                color: white;
                font-weight: bold;
                text-decoration: none;
                border-radius: 5px;
                cursor: pointer;
                border: none;
            }

            .back-button:hover {
                background-color: #45a049;
            }

            /* Animacje */
            @keyframes fadeIn {
                0% {
                    opacity: 0;
                }
                100% {
                    opacity: 1;
                }
            }

            @keyframes circleAnimation {
                0% {
                    stroke-dashoffset: 157;
                }
                100% {
                    stroke-dashoffset: 0;
                }
            }

            @keyframes checkmarkPathAnimation {
                0% {
                    stroke-dashoffset: 48;
                }
                100% {
                    stroke-dashoffset: 0;
                }
            }
        </style>
    </head>
    <body>
        <!-- Modal potwierdzenia płatności -->
        <div id="payment-success-modal" class="modal">
            <div class="modal-content">
                <div class="checkmark-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" class="checkmark-svg">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" stroke="#4CAF50" stroke-width="2"></circle>
                        <path class="checkmark" fill="none" stroke="#4CAF50" stroke-width="4" d="M14 26l7 7 15-15"></path>
                    </svg>
                </div>
                <p class="payment-message">Płatność zakończona pomyślnie!</p>
                <p>Wybrano metodę płatności: ' . $paymentMethod . '</p>
                <p>Kwota do zapłaty: ' . $amount . ' PLN</p>
                
                <!-- Przycisk powrotu do index.php -->
                <a href="index.php" class="back-button">Powrót do strony głównej</a>
            </div>
        </div>

        <script>
            // Funkcja do pokazania modala po zakończeniu płatności
            window.onload = function() {
                // Pokaż modal
                const modal = document.getElementById("payment-success-modal");
                modal.style.display = "flex";
                
            };
        </script>
    </body>
    </html>
    ';
}
?>
