<?php

session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;
$isAdmin = false; // Domyślna wartość

// Pobieranie danych użytkownika
if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username, email, czy_admin FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ustawienie zmiennej $isAdmin na podstawie danych z bazy
    if ($userData) {
        $isAdmin = (bool) $userData['czy_admin'];
    }
}
// Obsługa wylogowania
if (isset($_POST['logout'])) {
    session_unset(); // Usuwa wszystkie zmienne sesji
    session_destroy(); // Kończy sesję
    header('Location: index.php'); // Przekierowanie na stronę główną
    exit;
}

//ZMIANA DANYCH
$zmiana_error = '';
$zmiana_success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zmianadanych'])) {
    $new_username = trim($_POST['new_username']);
    $new_password = $_POST['new_password'];
    $new_confirm_password = $_POST['new_confirm_password'];
    $new_email = trim($_POST['new_email']);
    $zmiana_error = '';
    $zmiana_success = '';

    // Walidacja
    if (empty($new_username) || empty($new_password) || empty($new_confirm_password) || empty($new_email)) {
        $zmiana_error = "Wszystkie pola są wymagane.";
    } elseif ($new_password !== $new_confirm_password) {
        $zmiana_error = "Hasła nie są zgodne.";
    } elseif (strlen($new_password) < 8) {
        $zmiana_error = "Hasło musi mieć co najmniej 8 znaków.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $zmiana_error = "Nieprawidłowy adres e-mail.";
    } else {
        // Sprawdzenie czy nazwa użytkownika jest zajęta
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username AND id != :user_id");
        $stmt->execute(['username' => $new_username, 'user_id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $zmiana_error = "Użytkownik o tej nazwie już istnieje.";
        } else {
            // Haszowanie hasła
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Aktualizacja danych
            $stmt = $pdo->prepare('
                UPDATE users 
                SET username = :username, password_hash = :password, email = :email 
                WHERE id = :user_id
            ');
            $stmt->execute([
                'username' => $new_username,
                'password' => $new_hashed_password,
                'email' => $new_email,
                'user_id' => $_SESSION['user_id']
            ]);

            if ($stmt) {
                $zmiana_success = "Dane zostały zaktualizowane pomyślnie!";
                header("refresh:1;url=index.php");
                exit;
            } else {
                $zmiana_error = "Wystąpił problem podczas aktualizacji danych.";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Motocyklowy</title>
    <link rel="newest stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
</head>
<body >
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
                    <button class="filter-button active" onclick="filterProducts('Cruiser')" >Cruiser</button>
                    <button class="filter-button active" onclick="filterProducts('naked')" >Naked</button>
                    <button class="filter-button active" onclick="filterProducts('sport')">Sport</button>
                    <button class="filter-button active" onclick="filterProducts('skuter')">Skuter</button>
                    <button class="filter-button active" onclick="filterProducts('adventure')">Adventure</button>
                </div>
            </section>

            <section class="product-list">
                <h2>Dostępne Motocykle</h2>
                <div id="products">
                    <!-- Produkty zostaną załadowane tutaj -->
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

    <div id="accountModal" class="modal">
    

    <div class="modal-content" style="background-color:transparent;">
   
        <h2>Informacje o koncie</h2>
        <?php if ($userData):  ?>
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
                    <td>**zabezpieczone**  
                    </td>

                </tr>
            </table>
            <form method="POST" action="index.php"><a class="btn btn-secondary " name="zmiana_hasla" onclick="openpasswordModal()"> Zmień Dane</a></form>
        <?php else: ?>
            <p>Brak danych użytkownika.</p>
        <?php endif; ?>
        <?php if ($isAdmin === true){
 
 
 echo '
        <section class="admin-panel">
       
           
            <h2>Panel Administracyjny</h2>
            <section class="admin-options">
          
                <a href="uzytkownicy.php" class="btn btn-secondary" role="button">Zarządzaj użytkownikami</a>
                <a href="admin.php" class="btn btn-secondary">Zarządzaj produktami</a>
                <a href="zamowienia.php" class="btn btn-secondary">Przeglądaj zamówienia</a>

            </section>';}
       ?>
    </section>
        <button class="close-modal-btn" id="close" onclick="closeModal()">Zamknij</button>
    </div>
  

        <div id="passwordModal" class="modal">
        <div class="modal-content" style="background-color:transparent;">
        <span class="close" onclick="closepasswordModal()">&times;</span>
        <h2>Informacje o koncie</h2>
        <?php if ($userData): ?>
            <form method="POST" action="index.php" class="form-container" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="new_username">Nazwa użytkownika</label>
            <input type="text" class="form-control" id="new_username" name="new_username" placeholder="Wprowadź nazwę użytkownika" value="<?php echo $userData['username'] ?>" required>
        </div>
        <div class="form-group">
            <label for="new_password">Hasło</label>
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Wprowadź hasło" required oninput="checkPassword()">
            <small id="new_passwordHelp" class="form-text password-check">Hasło musi mieć co najmniej 8 znaków.</small>
        </div>
        <div class="form-group">
            <label for="new_confirm_password">Potwierdź hasło</label>
            <input type="password" class="form-control" id="new_confirm_password" name="new_confirm_password" placeholder="Powtórz hasło"  value=""required oninput="checkPasswordMatch()">
            <small id="new_confirmPasswordHelp" class="form-text confirm-password-check">Hasła się nie zgadzają.</small>
        </div>
        <div class="form-group">
    <label for="email">Adres e-mail</label>
    <input type="email" class="form-control" id="new_email" name="new_email" required placeholder="Wprowadź adres e-mail" value="<?php echo $userData['email'] ?>" required>
</div>
        <button type="submit" name="zmianadanych" class="btn btn-primary btn-block">Zmień dane</button>
        <?php if (!empty($zmiana_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($zmiana_error) ?>
        </div>
    <?php endif; ?>
    </form>





            </table>
        <?php else: ?>
            <p>Brak danych użytkownika.</p>
        <?php endif; ?>


       
        <button class="close-modal-btn" id="close" onclick="closepasswordModal()">Zamknij</button>
    </div>
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

        function openpasswordModal() {
            document.getElementById('passwordModal').style.display = "block";
            
        }

        function closeModal() {
            document.getElementById('accountModal').style.display = "none";
        }
        function closepasswordModal() {
            document.getElementById('passwordModal').style.display = "none";
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
