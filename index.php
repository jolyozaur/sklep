<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$userData = null;
$userRole = '';  

if ($loggedIn) {
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare(
        'SELECT username, email,  rodzaj FROM users WHERE id = ?' 
    );
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($userData) {
        $userRole = $userData['rodzaj']; 
    }

    
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
    $stmt->execute([$userId]);
    $useradres = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if ($loggedIn) {
    $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['cart'] = [];
    foreach ($cart_items as $item) {
        $_SESSION['cart'][] = [
            'id' => $item['product_id'],
            'name' => $item['product_name'],
            'price' => $item['product_price'],
            'quantity' => $item['quantity']
        ];
    }
} else {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    if ($loggedIn) {
      
        $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart_item) {
          
            $new_quantity = $cart_item['quantity'] + 1;
            $updateStmt = $pdo->prepare('UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?');
            $updateStmt->execute([$new_quantity, $_SESSION['user_id'], $product_id]);
        } else {
           
            $stmt = $pdo->prepare('INSERT INTO carts (user_id, product_id, product_name, product_price, quantity) 
                                   VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$_SESSION['user_id'], $product_id, $product_name, $product_price, 1]);
        }

        refresh_cart_session();
    } else {
      
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => 1
            ];
        }
    }

    header('Location: index.php');
    exit();
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    if ($loggedIn) {
        $stmt = $pdo->prepare('DELETE FROM carts WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        refresh_cart_session();
    } else {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
            return $item['id'] != $product_id;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    header('Location: index.php');
    exit();
}

function refresh_cart_session() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['cart'] = [];
    foreach ($cart_items as $item) {
        $_SESSION['cart'][] = [
            'id' => $item['product_id'],
            'name' => $item['product_name'],
            'price' => $item['product_price'],
            'quantity' => $item['quantity']
        ];
    }
}

$zmiana_error = '';
$zmiana_success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zmianadanych'])) {
    $new_username = trim($_POST['new_username']);
    $new_password = $_POST['new_password'];
    $new_confirm_password = $_POST['new_confirm_password'];
    $new_email = trim($_POST['new_email']);
    $zmiana_error = '';
    $zmiana_success = '';

    if (
        empty($new_username) ||
        empty($new_password) ||
        empty($new_confirm_password) ||
        empty($new_email)
    ) {
        $zmiana_error = 'Wszystkie pola są wymagane.';
    } elseif ($new_password !== $new_confirm_password) {
        $zmiana_error = 'Hasła nie są zgodne.';
    } elseif (strlen($new_password) < 8) {
        $zmiana_error = 'Hasło musi mieć co najmniej 8 znaków.';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $zmiana_error = 'Nieprawidłowy adres e-mail.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id FROM users WHERE username = :username AND id != :user_id'
        );
        $stmt->execute([
            'username' => $new_username,
            'user_id' => $_SESSION['user_id'],
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $zmiana_error = 'Użytkownik o tej nazwie już istnieje.';
        } else {
            $new_hashed_password = password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare('
                UPDATE users 
                SET username = :username, password_hash = :password, email = :email 
                WHERE id = :user_id
            ');
            $stmt->execute([
                'username' => $new_username,
                'password' => $new_hashed_password,
                'email' => $new_email,
                'user_id' => $_SESSION['user_id'],
            ]);

            if ($stmt) {
                $zmiana_success = 'Dane zostały zaktualizowane pomyślnie!';
                header('refresh:1;url=index.php');
                exit();
            } else {
                $zmiana_error = 'Wystąpił problem podczas aktualizacji danych.';
            }
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $imie = htmlspecialchars(trim($_POST['new_name']));
    $nazwisko = htmlspecialchars(trim($_POST['new_surname']));
    $ulica = htmlspecialchars(trim($_POST['new_street']));
    $miasto = htmlspecialchars(trim($_POST['new_city']));
    $kod = htmlspecialchars(trim($_POST['new_postcode']));
    $tel = htmlspecialchars(trim($_POST['new_phone']));
    $user_id = htmlspecialchars(trim($_POST['user_id']));

    if (!preg_match('/^\d{2}-\d{3}$/', $kod)) {
        $address_error = "Nieprawidłowy format kodu pocztowego.";
    } elseif (!preg_match('/^(\+?\d{1,3})? ?\d{3} ?\d{3} ?\d{3}$/', $tel)) {
        $address_error = "Nieprawidłowy format numeru telefonu.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO addresses (imie, nazwisko, ulica, miasto, kod, tel, user_id )
                VALUES (:imie, :nazwisko, :ulica, :miasto, :kod,  :tel, :user_id)
            ");

            $stmt->bindParam(':imie', $imie);
            $stmt->bindParam(':nazwisko', $nazwisko);
            $stmt->bindParam(':ulica', $ulica);
            $stmt->bindParam(':miasto', $miasto);
            $stmt->bindParam(':kod', $kod);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':tel', $tel);

            $stmt->execute();
            header("Location: index.php"); 
            exit();
        } catch (PDOException $e) {
            $address_error = "Błąd bazy danych: " . $e->getMessage();
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

        <?php
$sqlMenu = "SELECT * FROM podstrony";
$resultMenu = $pdo->query($sqlMenu);


?>




            <ul>
           
    <li class="nav-item">
        <a class="nav-link" href="index.php">Strona Główna</a>
    </li>
    <?php while ($row = $resultMenu->fetch(PDO::FETCH_ASSOC)): ?>
        <li class="nav-item">  
            <a class="nav-link" href="view_page.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['tytul']); ?></a>
        </li>
        
    <?php endwhile; ?>

                
              
                <?php if ($loggedIn): ?>
                    <li>
                        <div class="account-info">
                            <span class="username"><?= htmlspecialchars(
                                $username
                            ) ?></span>
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
                <li>
       
                <div class="cart-icon" onclick="toggleCart()">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count"><?= array_sum(array_column($_SESSION['cart'], 'quantity')) ?></span> 
</div>

<div id="cart-content" class="cart-content">
    <h2>Twój Koszyk</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td><?= $item['quantity']; ?> x <?= $item['price']; ?> zł</td> 
                    <td><?= $item['quantity'] * $item['price']; ?> zł</td> 
                    <td>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['id']) ?>">
                            <button type="submit" name="remove_from_cart" class="remove-btn">Usuń</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>Łączna kwota:
            <?= array_reduce($_SESSION['cart'], fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0) ?> zł 
        </p>
        <form method="GET" action="platnosci.php">
            <button type="submit" class="checkout-btn">Przejdź do płatności</button>
        </form>
    <?php else: ?>
        <p>Koszyk jest pusty.</p>
    <?php endif; ?>
</div>


                </li>
            </ul>
        </nav>
    </div>
</header>
    <main>
        <div class="container">
                <?php
                $stmt = $pdo->query("SELECT * FROM categories");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

            <section class="filter-container">
             <h2>Filtruj według kategorii:</h2>
                <div class="filter-options">
             <button class="filter-button active" onclick="filterProducts('')">Wszystkie</button>        
            <?php foreach ($categories as $category): ?>
                <button class="filter-button" onclick="filterProducts('<?php echo htmlspecialchars($category['type']); ?>')">
                <?php echo htmlspecialchars($category['type']); ?>
            </button>
        <?php endforeach; ?>
    </div>
</section>


            <section class="product-list">
                <h2>Dostępne Motocykle</h2>
                <div id="products">
                jkh
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

<div id="accountModal" class="modal_custom">

            <span class="close_custom" onclick="document.getElementById('accountModal').style.display='none'">&times;</span>

        <div class="modal_custom-content">
   
         <div class="container_dane_custom">
      
         <div class="karta_dane">
        <h2>Informacje o koncie</h2>
        <div class="info_block">
          <span class="info_label"><strong>Login:</strong></span>
          <span class="info_value"><?= htmlspecialchars($userData['username']) ?></span>
        </div>
        <div class="info_block">
          <span class="info_label"><strong>Email:</strong></span>
          <span class="info_value"><?= htmlspecialchars($userData['email']) ?></span>
        </div>
        <div class="info_block">
          <span class="info_label"><strong>Hasło:</strong></span>
          <span class="info_value">**zabezpieczone**</span>
          <br></br>
          <form method="POST" action="index.php"><a class="btn btn-secondary " name="zmiana_hasla" onclick="openpasswordModal()"> Zmień Dane</a></form>
        </div>
      </div>
      <?php if ($useradres == null):?><div class="karta_dane"><h2>informacje o adresie</h2>  <form method="POST" action="index.php"><a class="btn btn-danger " name="dodaj_adres" onclick="openadresModal()" > Dodaj adres</a></form></div>
        <?php endif; ?>
      <?php if (!empty($useradres)): ?>
        <div class="karta_dane">
          <h2>Informacje o adresie</h2>
          <div class="info_block">
            <span class="info_label"><strong>Imię i nazwisko:</strong></span>
            <span class="info_value"><?= htmlspecialchars($useradres['Imie']) ?> <?= htmlspecialchars($useradres['Nazwisko']) ?></span>
          </div>
          <div class="info_block">
            <span class="info_label"><strong>Ulica</strong></span>
            <span class="info_value"> <?= htmlspecialchars($useradres['ulica']) ?></span>
          </div>
          <div class="info_block">
            <span class="info_label"><strong>Kod pocztowy:</strong></span>
            <span class="info_value"><?= htmlspecialchars($useradres['kod']) ?></span>
          </div>
          <div class="info_block">
            <span class="info_label"><strong>Miasto:</strong></span>
            <span class="info_value"><?= htmlspecialchars($useradres['miasto']) ?></span>
          </div>
          <div class="info_block">
            <span class="info_label"><strong>Numer telefonu:</strong></span>
            <span class="info_value"><?= htmlspecialchars($useradres['tel']) ?></span>
          </div>
        </div>
      <?php endif; ?>

<?php
      $stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE user_id = ? AND status != 'Zrealizowane' 
    ORDER BY order_date DESC
");
$stmt->execute([$userId]);
$currentOrders = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE user_id = ? AND status = 'Zrealizowane' 
    ORDER BY order_date DESC
");
$stmt->execute([$userId]);
$completedOrders = $stmt->fetchAll();
?>
 


 <?php if (!empty($currentOrders)): ?>
    <div class="karta_dane">
        <h2>Aktualne zamówienia</h2>
        <?php foreach ($currentOrders as $order): ?>
            <div class="info_block">
                <span class="info_label"><strong>ID Zamówienia:</strong></span>
                <span class="info_value"><?= htmlspecialchars($order['id']) ?></span>
            </div>
            <div class="info_block">
                <span class="info_label"><strong>Data:</strong></span>
                <span class="info_value"><?= htmlspecialchars($order['order_date']) ?></span>
            </div>
            <div class="info_block">
                <span class="info_label"><strong>Status:</strong></span>
                <span class="info_value"><?= htmlspecialchars($order['status']) ?></span>
            </div>
            
            <h4>Produkty:</h4>
            <ul>
                <?php
         
                $stmt = $pdo->prepare("
                    SELECT oi.*, p.name, p.price 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.id 
                    WHERE oi.order_id = ?
                ");
                $stmt->execute([$order['id']]);
                $orderedItems = $stmt->fetchAll();

                if (!empty($orderedItems)) {
                    foreach ($orderedItems as $item) {
                        echo "<li>" . htmlspecialchars($item['name']) . " - " . htmlspecialchars($item['quantity']) . " x " . number_format($item['price'], 2) . " PLN</li>";
                    }
                } else {
                    echo "<li>Brak produktów w zamówieniu.</li>";
                }
                ?>
            </ul>
            
            <hr style="border-color: rgba(255, 255, 255, 0.2);">
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="karta_dane">
        <h2>Aktualne zamówienia</h2>
        <p style="text-align: center; color: #f05454;">Brak aktualnych zamówień.</p>
    </div>
<?php endif; ?>


<?php if (!empty($completedOrders)): ?>
    <div class="karta_dane">
        <h2>Zakończone zamówienia</h2>
        <?php foreach ($completedOrders as $order): ?>
            <div class="info_block">
                <span class="info_label"><strong>ID Zamówienia:</strong></span>
                <span class="info_value"><?= htmlspecialchars($order['id']) ?></span>
            </div>
            <div class="info_block">
                <span class="info_label"><strong>Data:</strong></span>
                <span class="info_value"><?= htmlspecialchars($order['order_date']) ?></span>
            </div>
            <div class="info_block">
                <span class="info_label"><strong>Status:</strong></span>
                <span class="info_value"><?= htmlspecialchars($order['status']) ?></span>
            </div>
            
            <h4>Produkty:</h4>
            <ul>
                <?php
                
                $stmt = $pdo->prepare("
                    SELECT oi.*, p.name, p.price 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.id 
                    WHERE oi.order_id = ?
                ");
                $stmt->execute([$order['id']]);
                $orderedItems = $stmt->fetchAll();

                if (!empty($orderedItems)) {
                    foreach ($orderedItems as $item) {
                        echo "<li>" . htmlspecialchars($item['name']) . " - " . htmlspecialchars($item['quantity']) . " x " . number_format($item['price'], 2) . " PLN</li>";
                    }
                } else {
                    echo "<li>Brak produktów w zamówieniu.</li>";
                }
                ?>
            </ul>
            
            <hr style="border-color: rgba(255, 255, 255, 0.2);">
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="karta_dane">
        <h2>Zakończone zamówienia</h2>
        <p style="text-align: center; color: #f05454;">Brak zakończonych zamówień.</p>
    </div>
<?php endif; ?>
 





    </table>
    
    
            
    <?php
if ($loggedIn) {
 
    if ($userRole === 'admin') {
        echo ' 
        <div class="karta_dane">
            <section class="admin-panel">
                <h2>Panel Administracyjny</h2>
                <section class="admin-options">
                    <a href="uzytkownicy.php" class="btn btn-secondary" role="button">Zarządzaj użytkownikami</a><br> </br>
                    <a href="admin.php" class="btn btn-secondary">Zarządzaj produktami</a><br> </br>
                    <a href="zamowienia.php" class="btn btn-secondary">Przeglądaj zamówienia</a><br> </br>
                    <a href="kategorie.php" class="btn btn-secondary">Zarządzaj kategoriami</a><br> </br>
                    <a href="podstrony.php" class="btn btn-secondary">Zarządzaj podstronami</a><br> </br>
                    <a href="dostawy.php" class="btn btn-secondary">Zarządzaj dostawami</a><br> </br>
                </section>
            </section>
        </div>';
    } elseif ($userRole === 'pracownik') {
      
        echo ' 
        <div class="karta_dane">
            <section class="admin-panel">
                <h2>Panel Pracownika</h2>
                <section class="admin-options">
                    <a href="admin.php" class="btn btn-secondary">Zarządzaj produktami</a><br> </br>
                    <a href="zamowienia.php" class="btn btn-secondary">Przeglądaj zamówienia</a><br> </br>
                    <a href="kategorie.php" class="btn btn-secondary">Zarządzaj kategoriami</a><br> </br>
                </section>
            </section>
        </div>';
    }
}
?>

    </section>

    </div>
        </div>

        <div id="passwordModal" class="modal">
        <div class="modal-content" style="background-color:transparent;">
        <span class="close" onclick="closepasswordModal()">&times;</span>
        <h2>Informacje o koncie</h2>
        <?php if ($userData): ?>
            <form method="POST" action="index.php" class="form-container" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="new_username">Nazwa użytkownika</label>
            <input type="text" class="form-control" id="new_username" name="new_username" placeholder="Wprowadź nazwę użytkownika" value="<?php echo $userData[
                'username'
            ]; ?>" required>
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
    <label for="new_email">Adres e-mail</label>
    <input type="email" class="form-control" id="new_email" name="new_email" required placeholder="Wprowadź adres e-mail" value="<?php echo $userData[
        'email'
    ]; ?>" required>
</div>
        <button type="submit" name="zmianadanych" class="btn btn-danger btn-block">Zmień dane</button>
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


       
    </div>
        </div>

        </div>
       


<div id="adresModal" class="modal">
    <div class="modal-content" style="background-color:transparent;">
        <span class="close" onclick="closeadresModal()">&times;</span>
        <h2>Dodaj adres</h2>
        <form method="POST" action="index.php" class="form-container" onsubmit="return validateAddressForm()">
        <div class="form-group">
                <label for="new_street">Imię</label>
                <input type="text" class="form-control" id="new_name" name="new_name" placeholder="Wprowadź Imię" required>
            </div>
            <div class="form-group">
                <label for="new_street">Nazwisko</label>
                <input type="text" class="form-control" id="new_surname" name="new_surname" placeholder="Wprowadź Nazwisko" required>
            </div>    
        
        <div class="form-group">
                <label for="new_street">Ulica</label>
                <input type="text" class="form-control" id="new_street" name="new_street" placeholder="Wprowadź nazwę ulicy" required>
            </div>
            <div class="form-group">
                <label for="new_city">Miasto</label>
                <input type="text" class="form-control" id="new_city" name="new_city" placeholder="Wprowadź nazwę miasta" required>
            </div>
            <div class="form-group">
                <label for="new_postcode">Kod pocztowy</label>
                <input type="text" class="form-control" id="new_postcode" name="new_postcode" placeholder="Wprowadź kod pocztowy (np. 00-123)" required>
            </div>
            
            <div class="form-group">
                <label for="new_phone">Numer telefonu</label>
                <input type="text" class="form-control" id="new_phone" name="new_phone" placeholder="Wprowadź numer telefonu (np. +48 123 456 789)" required>
            </div>
            <input type="hidden" name="user_id"  value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
    <button type="submit" name="add_address" class="btn btn-danger btn-block">Dodaj adres</button>
   

            <?php if (!empty($address_error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($address_error) ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>


    <script>


function validateAddressForm() {
   
   const postcode = document.getElementById('new_postcode').value.trim();
   const phone = document.getElementById('new_phone').value.trim();


   const postcodeRegex = /^\d{2}-\d{3}$/;
   if (!postcodeRegex.test(postcode)) {
       alert("Kod pocztowy musi być w formacie XX-XXX, np. 00-123.");
       return false;
   }

   const phoneRegex = /^(\+?\d{1,3})? ?\d{3} ?\d{3} ?\d{3}$/;
   if (!phoneRegex.test(phone)) {
       alert("Numer telefonu musi być w formacie np. +48 123 456 789 lub 123456789.");
       return false;
   }

   return true; 
}
        function toggleAccountMenu() {
            const menu = document.getElementById('account-menu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }
        function openadresModal() {
            document.getElementById('adresModal').style.display = "block";
            
        }
        function openModal() {
            document.getElementById('accountModal').style.display = "block";
        }
        function closeadresModal() {
            document.getElementById('adresModal').style.display = "none";
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
        };
        
    </script>
</body>
</html>