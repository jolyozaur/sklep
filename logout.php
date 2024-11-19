<?php
session_start();
session_destroy(); // Zniszczenie sesji
header("Location: login.php"); // Przekierowanie na stronę logowania
exit;
?>
