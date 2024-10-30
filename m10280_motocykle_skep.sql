-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql12.serv00.com
-- Generation Time: Paź 30, 2024 at 12:49 PM
-- Wersja serwera: 8.0.39
-- Wersja PHP: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m10280_motocykle_skep`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `opinie`
--

CREATE TABLE `opinie` (
  `id` int NOT NULL,
  `produkt_id` int NOT NULL,
  `autor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tresc` text COLLATE utf8mb4_general_ci NOT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opinie`
--

INSERT INTO `opinie` (`id`, `produkt_id`, `autor`, `tresc`, `data_utworzenia`) VALUES
(1, 1, 'Jan Kowalski', 'Świetny motocykl! Bardzo wygodny i szybki. Polecam wszystkim!', '2024-10-26 18:35:29'),
(2, 1, 'Anna Nowak', 'Doskonała jakość wykonania. Idealny do jazdy w mieście.', '2024-10-26 18:35:29'),
(3, 1, 'Piotr Wiśniewski', 'Nie mogłem się zdecydować na wybór, ale to był najlepszy wybór. Warto!', '2024-10-26 18:35:29'),
(4, 1, 'Katarzyna Zielińska', 'Jestem bardzo zadowolona z zakupu. Motocykl ma niesamowitą moc!', '2024-10-26 18:35:29'),
(5, 1, 'Tomasz Kaczmarek', 'Trochę drogi, ale naprawdę wart swojej ceny. Fantastyczna jazda!', '2024-10-26 18:35:29');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Opis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `image`, `Opis`, `price`) VALUES
(1, 'Harley Davidson', 'harley', 'images\\davidson.png', 'Harley-Davidson Sport Glide to idealne połączenie zwinnego cruisera i wygodnego turystyka, któremu niestraszne są dalekie wyprawy.', 45000.00),
(2, 'Yamaha MT-07', 'scigacz', 'images/mt07.png', 'MT-07 zajmuje pierwsze miejsce w swojej klasie od momentu pojawienia się na rynku. Wysoki moment obrotowy silnika CP2 o pojemności 690 cm3, kompaktowe i zwinne podwozie oraz wspaniała wszechstronność sprawiają, iż trudno nie pokochać tego motocykla.', 30000.00),
(3, 'Honda CBR500R', 'scigacz', 'images/hondacbr500.png', '', 25000.00),
(4, 'Ducati Monster', 'scigacz', 'images/ducati.png', '', 50000.00),
(5, 'Piaggio Liberty 50', 'skuter', 'images/liberty50.png', '', 7000.00),
(6, 'BMW R 1250 GS', 'adventure', 'images/bmw1250gs.png', '', 60000.00),
(7, 'Honda PCX 125', 'skuter', 'images/hondapcx125.png', '', 12000.00),
(8, 'Kawasaki Ninja 650', 'scigacz', 'images/ninja650.png', '', 40000.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`, `password`) VALUES
(2, 'admin1234', 'as@wp.pl', '$2y$10$FOJr4dgfqqcNXiUqsuAY.OLgOfxSnPyydeEQynvJRf7vddfhlygbq', '2024-10-27 07:44:05', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_opis`
--

CREATE TABLE `zdjecia_opis` (
  `id` int NOT NULL,
  `produkt_id` int NOT NULL,
  `sciezka` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zdjecia_opis`
--

INSERT INTO `zdjecia_opis` (`id`, `produkt_id`, `sciezka`) VALUES
(1, 1, 'zdjecia_opis\\1\\1.png'),
(2, 1, 'zdjecia_opis\\1\\2.png'),
(3, 1, 'zdjecia_opis\\1\\3.png'),
(7, 2, 'zdjecia_opis\\2\\1.png'),
(8, 2, 'zdjecia_opis\\2\\2.png'),
(9, 2, 'zdjecia_opis\\2\\3.png'),
(10, 2, 'zdjecia_opis\\2\\4.png');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `opinie`
--
ALTER TABLE `opinie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zdjecia_opis`
--
ALTER TABLE `zdjecia_opis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `opinie`
--
ALTER TABLE `opinie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `zdjecia_opis`
--
ALTER TABLE `zdjecia_opis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `opinie`
--
ALTER TABLE `opinie`
  ADD CONSTRAINT `opinie_ibfk_1` FOREIGN KEY (`produkt_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zdjecia_opis`
--
ALTER TABLE `zdjecia_opis`
  ADD CONSTRAINT `zdjecia_opis_ibfk_1` FOREIGN KEY (`produkt_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
