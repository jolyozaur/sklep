<?php
require 'db.php'; 

session_start();


if (!isset($_SESSION['user_id'])) {
    echo "Zaloguj się, aby edytować kategorie.";
    exit;
}

$loggedIn = isset($_SESSION['user_id']);
$isAllowed = false;

if ($loggedIn) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT rodzaj FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($userData && ($userData['rodzaj'] === 'admin' || $userData['rodzaj'] === 'pracownik')) {
        $isAllowed = true;
    }
}

if (!$isAllowed) {
    echo "Brak dostępu. Tylko administratorzy i pracownicy mogą edytować kategorie.";
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Połączenie z bazą danych nieudane: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $type = trim($_POST['type']);

    if (empty($type)) {
        $error = "Nazwa kategorii nie może być pusta.";
    } else {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE categories SET type = ? WHERE id = ?");
            $stmt->execute([$type, $_POST['id']]);
            $success = "Kategoria została zaktualizowana.";
            header("Location: kategorie.php");
            exit;
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (type) VALUES (?)");
            $stmt->execute([$type]);
            $success = "Kategoria została dodana.";
        }
    }
}

$categoryToEdit = null;
if (isset($_GET['edit'])) {
    $idToEdit = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$idToEdit]);
    $categoryToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Kategoriami</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
.table{

    color:white;
}
    </style>
    
</head>
<body>
<header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="display-4">Panel Administracyjny</h1>
            <p class="lead">Zarządzanie kategoriami sklepu motocyklowego</p>
        </div>
    </header>

    <main>
        <div class="container mt-5">



    <a href="logout.php" class="btn btn-danger ">Wyloguj się</a>
    <a href="index.php" class="btn btn-danger ">główna strona</a>
    <a href="uzytkownicy.php" class="btn btn-danger ">Zarządzaj uzytkownikami</a>
    <a href="kategorie.php" class="btn btn-danger ">Zarządzaj kategoriami</a>
    <a href="zamowienia.php" class="btn btn-danger ">Zarządzaj zamówieniami</a>
    <br></br>



            <h2><?php echo $categoryToEdit ? 'Edytuj Kategorię' : 'Dodaj Nową Kategorię'; ?></h2>

            <?php if (isset($success)) { echo "<p class='text-success'>$success</p>"; } ?>
            <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="type" class="form-label">Nazwa kategorii:</label>
                    <input type="text" class="form-control" id="type" name="type" value="<?php echo $categoryToEdit ? htmlspecialchars($categoryToEdit['type']) : ''; ?>" required>
                </div>
                <button type="submit" class="btn btn-success"><?php echo $categoryToEdit ? 'Zaktualizuj' : 'Dodaj'; ?></button>

                <?php if ($categoryToEdit): ?>
                    <input type="hidden" name="id" value="<?php echo $categoryToEdit['id']; ?>">
                <?php endif; ?>
            </form>

            <h3 class="mt-5">Istniejące Kategorie</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Typ  </th>
                        <th class="text-center">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM categories");
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            echo '<tr class="text-center">';
                            echo "<td >" . htmlspecialchars($category['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($category['type']) . "</td>";
                            echo '<td class="text-center">';
                            echo "<a href='?edit={$category['id']}' class='btn btn-warning btn-sm'>Edytuj</a>";
                            echo " ";
                            echo "<a href='delete_category.php?id={$category['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Czy na pewno chcesz usunąć tę kategorię?\")'>Usuń</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Brak kategorii</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="text-center mt-5">
        <p>&copy; 2024 Sklep Motocyklowy</p>
    </footer>

</body>
</html>
