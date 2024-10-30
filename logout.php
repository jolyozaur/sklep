<?php
session_start();
session_unset(); // Usuwa wszystkie zmienne sesji
session_destroy(); // Zniszczenie sesji

header('Location: login.php');
exit;
