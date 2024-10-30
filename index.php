
<?php

session_start();
require 'db.php';

// Sprawdzanie, czy użytkownik jest zalogowany
$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;

// Pobieranie danych użytkownika
if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obsługa wylogowania
if (isset($_POST['logout'])) {
    session_unset(); // Usuwa wszystkie zmienne sesji
    session_destroy(); // Kończy sesję
    header('Location: index.php'); // Przekierowanie na stronę główną
    exit;
}
  

  
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Motocyklowy</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="script.js" defer></script>
     
</head>
<body>
    <header>
      
        <div class="container header-container">
            <h1>Sklep Motocyklowy</h1>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Szukaj...">
                <button onclick="searchProduct()">Wyszukaj</button>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Strona Główna</a></li>
                    <li><a href="forgot_password.php">Motocykle</a></li>
                    <?php if ($loggedIn): ?>
                        <li>
                            <div class="account-info">
                                <span class="username"><?= htmlspecialchars($username) ?></span>
                                <i class="fas fa-user account-icon" onclick="toggleAccountMenu()"></i>
                                <div id="account-menu" class="account-menu">
                                    <a href="javascript:void(0)" onclick="openModal()">Informacje o koncie</a>
                                    <form method="POST" action="index.php">
                                        <button type="submit" name="logout" class="logout-button">Wyloguj się</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php">Zaloguj</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="filter-container">
                <h2>Filtruj według kategorii:</h2>
                <div class="filter-options">
                    <button class="filter-button active" onclick="filterProducts('')">Wszystkie</button>
                    <button class="filter-button active" onclick="filterProducts('cruiser')">Cruiser</button>
                    <button class="filter-button active" onclick="filterProducts('naked')">Naked</button>
                  <button class="filter-button active" onclick="filterProducts('sport')">Sport</button>
                    <button class="filter-button active" onclick="filterProducts('skuter')">Skuter</button>
                    <button class="filter-button active" onclick="filterProducts('adventure')">Adventure</button>
                  
                </div>
            </section>

            <section class="product-list">
                <h2>Dostępne Motocykle</h2>
                <div id="products">
                                    </div>
            </section>
        </div>
    </main>
  
    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

    <div id="accountModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Informacje o koncie</h2>
            <?php if ($userData): ?>
                <table>
                    <tr>
                        <td><strong>Login:</strong></td>
                        <td><?= htmlspecialchars($userData['username']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?= htmlspecialchars($userData['email']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Hasło:</strong></td>
                        <td>**zabezpieczone**</td> 
                    </tr>
                </table>
            <?php else: ?>
                <p>Brak danych użytkownika.</p>
            <?php endif; ?>
            <button class="close-modal-btn" id="close" onclick="closeModal()">Zamknij</button> 
        </div>
    </div>

    <script>
        function toggleAccountMenu() {
            const menu = document.getElementById('account-menu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        function openModal() {
            document.getElementById('accountModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('accountModal').style.display = "none";
        }
        

        window.onclick = function(event) {
            if (!event.target.matches('.account-icon')) {
                const menu = document.getElementById('account-menu');
                if (menu.style.display === 'block') {
                    menu.style.display = 'none';
                }
            }

       
            const modal = document.getElementById('accountModal');
            if (modal.style.display === "block" && !event.target.matches('.modal-content')) {
                closeModal();
            }
        }
    </script>
</body>
</html>
