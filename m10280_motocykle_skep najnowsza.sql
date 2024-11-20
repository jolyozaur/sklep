-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 20 Lis 2024, 13:16
-- Wersja serwera: 10.4.17-MariaDB
-- Wersja PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `m10280_motocykle_skep`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `opinie`
--

CREATE TABLE `opinie` (
  `id` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `tresc` text NOT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `opinie`
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
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `Opis` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `image`, `Opis`, `price`) VALUES
(1, 'Harley Davidson', 'cruiser', 'images\\davidson.png', 'Harley-Davidson Sport Glide to idealne połączenie zwinnego cruisera i wygodnego turystyka, któremu niestraszne są dalekie wyprawy.', '45000.00'),
(2, 'Yamaha MT-07', 'naked', 'images/mt07.png', 'MT-07 zajmuje pierwsze miejsce w swojej klasie od momentu pojawienia się na rynku. Wysoki moment obrotowy silnika CP2 o pojemności 690 cm3, kompaktowe i zwinne podwozie oraz wspaniała wszechstronność sprawiają, iż trudno nie pokochać tego motocykla.', '30000.00'),
(3, 'Honda CBR500R', 'sport', 'images/hondacbr500.png', '', '25000.00'),
(4, 'Ducati Monster', 'naked', 'images/ducati.png', '', '50000.00'),
(5, 'Piaggio Liberty 50', 'skuter', 'images/liberty50.png', '', '7000.00'),
(6, 'BMW R 1250 GS', 'adventure', 'images/bmw1250gs.png', '', '60000.00'),
(7, 'Honda PCX 125', 'skuter', 'images/hondapcx125.png', '', '12000.00'),
(8, 'Kawasaki Ninja 650', 'sport', 'images/ninja650.png', '', '40000.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `czy_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`, `password`, `czy_admin`) VALUES
(0, 'testtest', 'test@sw.pl', '$2y$10$yVuV2JnA19qRBhd4ACE5UuAS.T0Z7cwxoNjiZt72u.cRtAzTxSrp6', '2024-11-20 10:51:58', NULL, 0),
(2, 'admin1234', 'as@wp.pl', '$2y$10$FOJr4dgfqqcNXiUqsuAY.OLgOfxSnPyydeEQynvJRf7vddfhlygbq', '2024-10-27 07:44:05', NULL, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_opis`
--

CREATE TABLE `zdjecia_opis` (
  `id` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `sciezka` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `zdjecia_opis`
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
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zdjecia_opis`
--
ALTER TABLE `zdjecia_opis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `opinie`
--
ALTER TABLE `opinie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `zdjecia_opis`
--
ALTER TABLE `zdjecia_opis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `opinie`
--
ALTER TABLE `opinie`
  ADD CONSTRAINT `opinie_ibfk_1` FOREIGN KEY (`produkt_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `zdjecia_opis`
--
ALTER TABLE `zdjecia_opis`
  ADD CONSTRAINT `zdjecia_opis_ibfk_1` FOREIGN KEY (`produkt_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
