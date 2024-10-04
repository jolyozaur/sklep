-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 04 Paź 2024, 11:50
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
-- Baza danych: `sklepik`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin1', '$2y$10$r1v0NZULXggf5MIJfCN91Of3fwpwmOLjl6NnPpZoHFF2B.4sRzqGS'),
(2, 'User123', '$2y$10$PM.Z/JJrJ4ss80rByNMNEOu0UXe70i1ZtVbSm3ny.ZkZezm5MYzna'),
(3, 'nataniel', '$2y$10$2j0tmljrwXlxRNA/liSJ8.lRkMmaVTEPjDopNZArwclSiwOTL7jtm'),
(4, 'test1234', '$2y$10$S3ocW4QkQQXtpXiX8g.IZONfDn8qAD0us9X5kKBLYeNgr0Bmf5UYq'),
(5, 'tescik123', '$2y$10$w7mwWt0XkJVTAOTJ7wEEie/zOXBDjMnEGqhzFiOgt4a6g22f4LZUa'),
(6, 'awfawf', '$2y$10$z2.BDM3ys3Pf7NpcqbYLPumBxFCkSOs0GOR/h1ZAJQ.KokLepX2Gy'),
(7, '12345678', '$2y$10$hPLfaHp7MuW0lqeSxw/3TuiD3Br.h389hzAS2.t6mb0Ar7QFFLkGe');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
