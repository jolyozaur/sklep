-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 10:17 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.0.30

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
-- Struktura tabeli dla tabeli `addresses`
--

CREATE TABLE `addresses` (
  `id_adresu` int(11) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `Imie` varchar(255) NOT NULL,
  `Nazwisko` varchar(255) NOT NULL,
  `ulica` varchar(255) NOT NULL,
  `miasto` varchar(255) NOT NULL,
  `kod` varchar(6) NOT NULL,
  `tel` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id_adresu`, `user_id`, `Imie`, `Nazwisko`, `ulica`, `miasto`, `kod`, `tel`) VALUES
(2, 3, 'Natan', 'Ossolinski', 'ujrzanów', 'ujrzanów', '08-110', '881482276'),
(3, 1, 'Karol3', 'Okniński2', 'dąbrówka ług 16', 'dąbrówka łuh', '08-103', '123456789'),
(4, NULL, 'adam', 'asdmwao', 'waotgja', 'qwoajgaw', '08-103', '214123123'),
(5, NULL, 'adam', 'warf', 'awgawg', 'awgagw', '08-103', '516558162'),
(6, NULL, 'hjgjghghj', 'ghjghjghj', 'warszawska 4', 'Stare Opole', '08-103', '516558162'),
(7, NULL, '1241453', '14412', '124124', 'Stare Opole', '08-103', '516558162');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`, `product_id`, `product_name`, `product_price`, `quantity`) VALUES
(50, 2, 23, 'KTM Duke 390', 18000.00, 1),
(52, 1, 19, 'Moto Guzzi V7', 43000.00, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `type`) VALUES
(1, 'cruiser'),
(2, 'naked'),
(3, 'sport'),
(4, 'skuter'),
(5, 'adventure');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opinie`
--

INSERT INTO `opinie` (`id`, `produkt_id`, `autor`, `tresc`, `data_utworzenia`) VALUES
(2, 1, 'Adam Nowak', 'Motocykl jest bardzo wygodny i doskonale się prowadzi. Świetna jakość wykonania!', '2024-12-03 13:10:00'),
(3, 1, 'Marek Kowalski', 'Jeden z najlepszych motocykli, jakie miałem. Idealny do długich tras i codziennego użytku.', '2024-12-03 13:20:00'),
(4, 1, 'Krzysztof Lewandowski', 'Harley-Davidson Sport Glide to motocykl, który nigdy mnie nie zawiódł. Niezawodność i komfort.', '2024-12-03 13:30:00'),
(5, 1, 'Tomasz Nowak', 'Świetny cruiser! Idealny na długie podróże, ale sprawdza się też w mieście.', '2024-12-03 13:40:00'),
(6, 1, 'Ewa Kowalczyk', 'Mój ulubiony motocykl! Łączy w sobie wygodę i styl. Zdecydowanie polecam.', '2024-12-03 13:50:00'),
(7, 2, 'Krzysztof Lewandowski', 'MT-07 to naprawdę szybki motocykl. Czuje się go na drodze, świetna przyczepność.', '2024-12-03 13:30:00'),
(8, 2, 'Piotr Zieliński', 'Świetny motocykl na miasto, a także na wyjazdy poza miasto. Zdecydowanie polecam!', '2024-12-03 13:40:00'),
(9, 2, 'Jakub Wiśniewski', 'Idealny motocykl na codzienną jazdę. Zwinny, dynamiczny i świetnie wygląda.', '2024-12-03 13:50:00'),
(10, 2, 'Andrzej Kwiatkowski', 'MT-07 jest idealnym wyborem dla tych, którzy cenią sobie sportową jazdę. Bardzo polecam.', '2024-12-03 14:00:00'),
(11, 2, 'Marek Adamczak', 'Lubię ten motocykl za to, jak dobrze sprawdza się w miejskim ruchu. Jest kompaktowy i mocny.', '2024-12-03 14:10:00'),
(12, 3, 'Jakub Wiśniewski', 'Doskonały motocykl dla początkujących i średniozaawansowanych. Lekki, zwinny, świetnie się prowadzi.', '2024-12-03 13:50:00'),
(13, 3, 'Tomasz Nowak', 'CBR500R to motocykl, który łączy osiągi z komfortem. Idealny do codziennych dojazdów.', '2024-12-03 14:00:00'),
(14, 3, 'Kamil Pawlak', 'Zdecydowanie polecam. Motocykl doskonale sprawdza się na długich trasach, a jazda jest komfortowa.', '2024-12-03 14:10:00'),
(15, 3, 'Piotr Zieliński', 'CBR500R to świetny wybór dla osób szukających motocykla do codziennej jazdy. Lekki, ekonomiczny i komfortowy.', '2024-12-03 14:20:00'),
(16, 3, 'Michał Baran', 'Kupiłem CBR500R i nie żałuję. Jest idealny na miasto, a także na weekendowe wypady.', '2024-12-03 14:30:00'),
(17, 4, 'Marcin Kwiatkowski', 'Ducati Monster to legenda. Potężna moc i świetny dźwięk silnika. Uwielbiam go!', '2024-12-03 14:10:00'),
(18, 4, 'Kamil Pawlak', 'Niesamowite osiągi i wygląd. Jeden z najlepszych motocykli, jakie testowałem.', '2024-12-03 14:20:00'),
(19, 4, 'Jakub Nowak', 'Idealny motocykl na miejskie uliczki i szybkie wypady za miasto. Ducati Monster to frajda z jazdy.', '2024-12-03 14:30:00'),
(20, 4, 'Marek Adamczak', 'Ducati Monster to motocykl, który przyciąga spojrzenia. Świetne osiągi i design.', '2024-12-03 14:40:00'),
(21, 4, 'Anna Wojciechowska', 'Zdecydowanie polecam, jeśli szukasz sportowego motocykla o niezawodnej mocy.', '2024-12-03 14:50:00'),
(22, 5, 'Anna Wojciechowska', 'Świetny skuter do miasta. Szybko się nim poruszam i nie zużywa dużo paliwa.', '2024-12-03 14:30:00'),
(23, 5, 'Paweł Adamczak', 'Idealny skuter na codzienne dojazdy do pracy. Bardzo łatwy w prowadzeniu.', '2024-12-03 14:40:00'),
(24, 5, 'Katarzyna Zawisza', 'Lekki, oszczędny i zwrotny. Idealny do miasta, szczególnie w godzinach szczytu.', '2024-12-03 14:50:00'),
(25, 5, 'Marek Nowicki', 'Bardzo ekonomiczny skuter, idealny na krótkie trasy. Oszczędza paliwo, a jego prowadzenie jest przyjemne.', '2024-12-03 15:00:00'),
(26, 5, 'Krzysztof Lewandowski', 'Świetny skuter do poruszania się po mieście. Polecam każdemu, kto chce zaoszczędzić na paliwie.', '2024-12-03 15:10:00'),
(27, 6, 'Ewa Kowalczyk', 'Idealny motocykl na długie wyprawy. Niezawodny i bardzo komfortowy, polecam.', '2024-12-03 14:50:00'),
(28, 6, 'Robert Zawisza', 'R 1250 GS to motocykl dla prawdziwych podróżników. Doskonała stabilność i napęd.', '2024-12-03 15:00:00'),
(29, 6, 'Janusz Nowak', 'BMW R 1250 GS to motocykl, na którym czuję się pewnie w każdych warunkach. Wygodny i świetny na długie trasy.', '2024-12-03 15:10:00'),
(30, 6, 'Kamil Adamczak', 'Niezawodny i bardzo wszechstronny motocykl. Idealny zarówno do jazdy po autostradach, jak i po trudnym terenie.', '2024-12-03 15:20:00'),
(31, 6, 'Magdalena Zielińska', 'R 1250 GS to mój wybór na każdą podróż. Komfort, moc i niezawodność.', '2024-12-03 15:30:00'),
(32, 7, 'Magda Piątek', 'Skuter idealny do poruszania się po mieście. Lekki, szybki i oszczędny.', '2024-12-03 15:10:00'),
(33, 7, 'Krzysztof Nowakowski', 'PCX 125 to wygodny skuter, na którym można bez problemu codziennie dojeżdżać do pracy.', '2024-12-03 15:20:00'),
(34, 7, 'Tomasz Wiśniewski', 'Świetny wybór na codzienne dojazdy, zwłaszcza w zatłoczonym mieście. Niskie koszty eksploatacji.', '2024-12-03 15:30:00'),
(35, 7, 'Patryk Sierżant', 'Jest to bardzo dobry skuter do miasta, zwinny i oszczędny.', '2024-12-03 15:40:00'),
(36, 7, 'Karolina Kowalska', 'Bardzo polecam ten skuter. Cichy, zwrotny, idealny na miasto i na krótkie trasy.', '2024-12-03 15:50:00'),
(37, 1, 'Adam Nowak', 'Motocykl jest bardzo wygodny i doskonale się prowadzi. Świetna jakość wykonania!', '2024-12-03 13:10:00'),
(38, 1, 'Marek Kowalski', 'Jeden z najlepszych motocykli, jakie miałem. Idealny do długich tras i codziennego użytku.', '2024-12-03 13:20:00'),
(39, 2, 'Krzysztof Lewandowski', 'MT-07 to naprawdę szybki motocykl. Czuje się go na drodze, świetna przyczepność.', '2024-12-03 13:30:00'),
(40, 2, 'Piotr Zieliński', 'Świetny motocykl na miasto, a także na wyjazdy poza miasto. Zdecydowanie polecam!', '2024-12-03 13:40:00'),
(41, 3, 'Jakub Wiśniewski', 'Doskonały motocykl dla początkujących i średniozaawansowanych. Lekki, zwinny, świetnie się prowadzi.', '2024-12-03 13:50:00'),
(42, 3, 'Tomasz Nowak', 'CBR500R to motocykl, który łączy osiągi z komfortem. Idealny do codziennych dojazdów.', '2024-12-03 14:00:00'),
(43, 4, 'Marcin Kwiatkowski', 'Ducati Monster to legenda. Potężna moc i świetny dźwięk silnika. Uwielbiam go!', '2024-12-03 14:10:00'),
(44, 4, 'Kamil Pawlak', 'Niesamowite osiągi i wygląd. Jeden z najlepszych motocykli, jakie testowałem.', '2024-12-03 14:20:00'),
(45, 5, 'Anna Wojciechowska', 'Świetny skuter do miasta. Szybko się nim poruszam i nie zużywa dużo paliwa.', '2024-12-03 14:30:00'),
(46, 5, 'Paweł Adamczak', 'Idealny skuter na codzienne dojazdy do pracy. Bardzo łatwy w prowadzeniu.', '2024-12-03 14:40:00'),
(47, 6, 'Ewa Kowalczyk', 'Idealny motocykl na długie wyprawy. Niezawodny i bardzo komfortowy, polecam.', '2024-12-03 14:50:00'),
(48, 6, 'Robert Zawisza', 'R 1250 GS to motocykl dla prawdziwych podróżników. Doskonała stabilność i napęd.', '2024-12-03 15:00:00'),
(49, 7, 'Magda Piątek', 'Skuter idealny do poruszania się po mieście. Lekki, szybki i oszczędny.', '2024-12-03 15:10:00'),
(50, 7, 'Krzysztof Nowakowski', 'PCX 125 to wygodny skuter, na którym można bez problemu codziennie dojeżdżać do pracy.', '2024-12-03 15:20:00'),
(51, 8, 'Janusz Nowicki', 'Ninja 650 to motocykl, który daje sporo radości z jazdy. Ma świetną dynamikę i dobrą przyczepność.', '2024-12-03 15:30:00'),
(52, 8, 'Michał Baran', 'Jestem bardzo zadowolony z zakupu. Motocykl świetnie się prowadzi, idealny do długich tras.', '2024-12-03 15:40:00'),
(53, 8, 'Tomasz Nowak', 'Kawasaki Ninja 650 to świetny motocykl do codziennej jazdy. Bardzo dobra dynamika i wygoda.', '2024-12-03 15:50:00'),
(54, 8, 'Kamil Pawlak', 'Idealny motocykl na kręte drogi. Zwinny i komfortowy, bardzo łatwo się nim manewruje.', '2024-12-03 16:00:00'),
(55, 8, 'Paweł Zawisza', 'Doskonały wybór dla osób, które szukają motocykla sportowego z dużą wszechstronnością.', '2024-12-03 16:10:00'),
(56, 8, 'Magdalena Kwiatkowska', 'Jestem zachwycony Ninja 650. Komfort jazdy i osiągi są na najwyższym poziomie.', '2024-12-03 16:20:00'),
(57, 8, 'Jakub Baran', 'Motocykl jest świetny na długie trasy. Osiągi są naprawdę zadowalające.', '2024-12-03 16:30:00'),
(58, 9, 'Adam Kowalski', 'GSX-R750 to maszyna, która robi wrażenie na drodze. Dobre przyspieszenie i precyzyjne prowadzenie.', '2024-12-03 16:30:00'),
(59, 9, 'Marek Nowakowski', 'Idealny motocykl dla miłośników sportowej jazdy. Wyjątkowa dynamika, świetna aerodynamika.', '2024-12-03 16:40:00'),
(60, 9, 'Piotr Zieliński', 'Doskonały motocykl na tor, ale i na zwykłe drogi. Lekki, szybki, stabilny.', '2024-12-03 16:50:00'),
(61, 9, 'Krzysztof Lewandowski', 'Bardzo dobry wybór dla osób szukających prawdziwego sportowca. Moc i precyzja w jednym!', '2024-12-03 17:00:00'),
(62, 9, 'Tomasz Wiśniewski', 'Szybki, zwinny, a do tego świetnie wygląda. Motocykl godny polecenia.', '2024-12-03 17:10:00'),
(63, 10, 'Karol Nowak', '890 Adventure to świetny wybór na off-road, ale także na długie wyprawy. Silnik 890 cm³ jest naprawdę mocny.', '2024-12-03 17:10:00'),
(64, 10, 'Ewa Wojciechowska', 'Motocykl idealny do jazdy w trudnym terenie. Zaawansowane systemy jezdne robią ogromną różnicę.', '2024-12-03 17:20:00'),
(65, 10, 'Janusz Zawisza', 'Bardzo uniwersalny motocykl, zarówno do jazdy po asfalcie, jak i po trudniejszych drogach. Komfort i wydajność.', '2024-12-03 17:30:00'),
(66, 10, 'Kamil Pawlak', '890 Adventure to maszyna, która pozwala poczuć się pewnie w każdych warunkach. Doskonała na trudny teren.', '2024-12-03 17:40:00'),
(67, 10, 'Piotr Zieliński', 'Motocykl, który daje poczucie pełnej kontroli na każdym kilometrze. Bardzo wygodny na długich trasach.', '2024-12-03 17:50:00'),
(68, 11, 'Paweł Zawisza', 'Bonneville to klasyka w najlepszym wydaniu. Silnik 1200 cm³ zapewnia moc, ale jest również komfortowy.', '2024-12-03 17:50:00'),
(69, 11, 'Anna Kowalska', 'Styl i moc w jednym. Triumph Bonneville to motocykl, który nigdy nie wychodzi z mody.', '2024-12-03 18:00:00'),
(70, 11, 'Jakub Baran', 'Motocykl o wyjątkowym designie, a jednocześnie bardzo wygodny na codzienne przejażdżki.', '2024-12-03 18:10:00'),
(71, 11, 'Marek Adamczak', 'Niezwykła jakość wykonania i komfort jazdy. Dla miłośników klasyki to obowiązkowy wybór.', '2024-12-03 18:20:00'),
(72, 11, 'Magda Kowalczyk', 'Dzięki swojej mocy i stylowi, Bonneville to prawdziwa ikona motocyklizmu. Zdecydowanie polecam.', '2024-12-03 18:30:00'),
(73, 12, 'Katarzyna Nowak', 'Vespa Primavera to kultowy skuter. Wygodny, zwrotny i idealny na miejskie warunki.', '2024-12-03 18:30:00'),
(74, 12, 'Marek Kowalski', 'Świetny skuter na codzienne dojazdy. Lekki, ekonomiczny i stylowy.', '2024-12-03 18:40:00'),
(75, 12, 'Tomasz Wiśniewski', 'Vespa Primavera to połączenie klasyki z nowoczesnością. Doskonały skuter na miasto.', '2024-12-03 18:50:00'),
(76, 12, 'Agnieszka Zawisza', 'Idealny skuter na dojazdy do pracy. Oszczędny i wygodny, świetnie sprawdza się w ruchu miejskim.', '2024-12-03 19:00:00'),
(77, 12, 'Kamil Baran', 'Vespa Primavera to skuter, który łączy wygodę i retro design. Bardzo polecam!', '2024-12-03 19:10:00'),
(78, 13, 'Krzysztof Nowak', 'Panigale V4 to prawdziwy potwór na torze. Niezrównana moc i precyzyjne prowadzenie.', '2024-12-03 19:10:00'),
(79, 13, 'Paweł Adamczak', 'Motocykl, który oferuje nie tylko emocje, ale także pełen komfort jazdy. Mistrzostwo w każdym calu.', '2024-12-03 19:20:00'),
(80, 13, 'Tomasz Kwiatkowski', 'Ducati Panigale V4 to najlepszy wybór dla miłośników ekstremalnych osiągów na torze.', '2024-12-03 19:30:00'),
(81, 13, 'Kamil Pawlak', 'Motocykl, który dostarcza niesamowitych wrażeń na torze. Precyzyjne prowadzenie i ogromna moc.', '2024-12-03 19:40:00'),
(82, 13, 'Marek Nowicki', 'Wspaniały motocykl do jazdy sportowej. Panigale V4 to prawdziwa bestia na drodze.', '2024-12-03 19:50:00'),
(83, 14, 'Paweł Zieliński', 'Yamaha FZ1 to szybki motocykl, który zapewnia mnóstwo frajdy z jazdy. Lekki, zwinny, polecam!', '2024-12-03 19:50:00'),
(84, 14, 'Magda Kowalczyk', 'Idealny motocykl do miasta. Szybki, dynamiczny, a jednocześnie wygodny.', '2024-12-03 20:00:00'),
(85, 14, 'Adam Baran', 'FZ1 to motocykl, który nigdy nie zawodzi. Komfortowa jazda i ogromna moc.', '2024-12-03 20:10:00'),
(86, 14, 'Katarzyna Wiśniewska', 'Zdecydowanie najlepszy motocykl w tej kategorii. Wysoka jakość wykonania i świetne osiągi.', '2024-12-03 20:20:00'),
(87, 14, 'Piotr Kowalski', 'Sportowa maszyna, która sprawdzi się na każdej drodze. Fantastyczny wybór!', '2024-12-03 20:30:00'),
(88, 15, 'Tomasz Nowak', 'BMW R 1250 GS to prawdziwy motocykl adventure. Komfort jazdy i moc silnika są niezrównane.', '2024-12-03 20:30:00'),
(89, 15, 'Anna Kowalska', 'Motocykl, który sprawdzi się w każdych warunkach, zarówno na autostradach, jak i w trudnym terenie.', '2024-12-03 20:40:00'),
(90, 15, 'Jan Kowalski', 'R 1250 GS to wybór dla prawdziwych podróżników. Niezawodny, komfortowy i mocny.', '2024-12-03 20:50:00'),
(91, 15, 'Kamil Nowicki', 'BMW R 1250 GS to motocykl, który nie zawiedzie na żadnej trasie. Perfekcyjna jakość i komfort.', '2024-12-03 21:00:00'),
(92, 15, 'Piotr Zawisza', 'Idealny motocykl na wyprawy. Komfort, moc i technologia na najwyższym poziomie.', '2024-12-03 21:10:00'),
(93, 16, 'Jakub Baran', 'Kawasaki ZX-10R to motocykl wyścigowy, który dostarcza ogromnych emocji. Prędkości i precyzja prowadzenia na najwyższym poziomie.', '2024-12-03 21:10:00'),
(94, 16, 'Marek Zieliński', 'Motocykl, który nie zawodzi. Szybki, precyzyjny, idealny na tor wyścigowy.', '2024-12-03 21:20:00'),
(95, 16, 'Krzysztof Wiśniewski', 'ZX-10R to maszyna, która zapewnia niezrównaną dynamikę. Fantastyczna precyzja prowadzenia.', '2024-12-03 21:30:00'),
(96, 16, 'Tomasz Kowalski', 'Jeśli szukasz motocykla do wyścigów, to ten model spełni wszystkie Twoje oczekiwania.', '2024-12-03 21:40:00'),
(97, 16, 'Janusz Nowak', 'Motocykl, który gwarantuje emocje na najwyższym poziomie. Precyzyjny, szybki i niesamowity na torze.', '2024-12-03 21:50:00'),
(98, 17, 'Piotr Nowak', 'Africa Twin to najlepszy wybór dla miłośników off-roadu. Idealny do trudnego terenu, ale również komfortowy na autostradzie.', '2024-12-03 21:50:00'),
(99, 17, 'Karol Kowalski', 'Bardzo mocny motocykl, który daje poczucie pewności na każdej drodze. Niezawodny w trudnym terenie.', '2024-12-03 22:00:00'),
(100, 17, 'Marek Zawisza', 'Africa Twin to motocykl, który można zabrać wszędzie. Sprawdzi się w najcięższych warunkach terenowych.', '2024-12-03 22:10:00'),
(101, 17, 'Jakub Adamczak', 'Motocykl godny polecenia. Duża moc i komfort jazdy, idealny na wyprawy w teren.', '2024-12-03 22:20:00'),
(102, 17, 'Kamil Kowalski', 'Africa Twin to maszyna, która sprawdzi się wszędzie. Niezawodny, mocny, świetny na wyprawy.', '2024-12-03 22:30:00'),
(103, 18, 'Piotr Nowakowski', 'Harley Davidson Sportster to klasyczny motocykl cruiser. Dźwięk silnika i komfort jazdy na najwyższym poziomie.', '2024-12-03 22:30:00'),
(104, 18, 'Tomasz Zawisza', 'Sportster to motocykl, który daje poczucie wolności na drodze. Stylowy, mocny, z niesamowitym silnikiem.', '2024-12-03 22:40:00'),
(105, 18, 'Jakub Nowicki', 'Idealny wybór dla osób, które szukają klasycznego cruisera. Silnik 883 cm³ zapewnia odpowiednią moc.', '2024-12-03 22:50:00'),
(106, 18, 'Magdalena Kowalczyk', 'Bardzo wygodny motocykl na długie trasy. Komfortowy, a do tego świetnie wygląda.', '2024-12-03 23:00:00'),
(107, 18, 'Paweł Nowak', 'Harley Davidson Sportster to legenda motocyklizmu. Daje ogromną radość z jazdy.', '2024-12-03 23:10:00'),
(108, 19, 'Kamil Zawisza', 'Moto Guzzi V7 to motocykl o klasycznym wyglądzie i bardzo dobrej dynamice. Idealny do jazdy po mieście.', '2024-12-03 23:10:00'),
(109, 19, 'Jakub Kowalski', 'Piękny design, komfort jazdy i dobra moc. V7 to wybór dla osób, które cenią klasykę.', '2024-12-03 23:20:00'),
(110, 19, 'Tomasz Adamczak', 'Moto Guzzi V7 to motocykl, który łączy retro wygląd z nowoczesną mocą. Dla miłośników klasyki i mocy.', '2024-12-03 23:30:00'),
(111, 19, 'Krzysztof Baran', 'Motocykl z duszą, który dostarcza ogromnej przyjemności z jazdy. Stylowy i komfortowy.', '2024-12-03 23:40:00'),
(112, 19, 'Paweł Nowakowski', 'Moto Guzzi V7 to piękny motocykl z niezawodnym silnikiem. Idealny do codziennej jazdy.', '2024-12-03 23:50:00'),
(113, 20, 'Jakub Nowakowski', 'Indian Scout to ikona amerykańskiego motocyklizmu. Mocny silnik i niesamowity design.', '2024-12-03 23:50:00'),
(114, 20, 'Krzysztof Kowalski', 'Harley w nowym wydaniu. Świetna moc i komfort jazdy. Doskonały wybór dla miłośników amerykańskich cruiserów.', '2024-12-04 00:00:00'),
(115, 20, 'Magda Kowalska', 'Stylowy motocykl, który przyciąga spojrzenia. Niezawodny i komfortowy na długie trasy.', '2024-12-04 00:10:00'),
(116, 20, 'Piotr Zieliński', 'Indian Scout to motocykl, który łączy tradycję z nowoczesnością. Bardzo wygodny w jeździe.', '2024-12-04 00:20:00'),
(117, 20, 'Tomasz Nowicki', 'Idealny motocykl na długie wyprawy. Mocny, komfortowy i bardzo stylowy.', '2024-12-04 00:30:00'),
(118, 21, 'Paweł Kowalski', 'Aprilia RS 660 to sportowy motocykl, który sprawdza się na torze, ale także na drogach publicznych. Świetna moc i prowadzenie.', '2024-12-04 00:30:00'),
(119, 21, 'Jakub Zawisza', 'Sportowa maszyna z doskonałymi osiągami. Lekka, szybka, świetna na każdą drogę.', '2024-12-04 00:40:00'),
(120, 21, 'Krzysztof Kowalski', 'Aprilia RS 660 to bardzo zwinny motocykl, który gwarantuje ogromną przyjemność z jazdy. Polecam!', '2024-12-04 00:50:00'),
(121, 21, 'Tomasz Adamczak', 'Doskonała maszyna do wyścigów i codziennej jazdy. Lekkie prowadzenie, duża moc.', '2024-12-04 01:00:00'),
(122, 21, 'Paweł Nowakowski', 'Aprilia RS 660 to motocykl, który łączy dynamikę i styl. Idealny wybór dla miłośników sportów motocyklowych.', '2024-12-04 01:10:00'),
(123, 22, 'Krzysztof Adamczak', 'MT-07 to motocykl, który oferuje świetną dynamikę w codziennej jeździe. Lekka konstrukcja, doskonała moc.', '2024-12-04 01:10:00'),
(124, 22, 'Jakub Kowalski', 'Yamaha MT-07 to motocykl, który zaskakuje swoją zwrotnością. Świetny do miasta i na długie trasy.', '2024-12-04 01:20:00'),
(125, 22, 'Piotr Zawisza', 'Zwinny, szybki motocykl, który daje dużo frajdy z jazdy. Doskonała jakość w tej cenie.', '2024-12-04 01:30:00'),
(126, 22, 'Tomasz Kowalski', 'Świetna maszyna, którą można zabrać wszędzie. Do miasta i na wycieczki. Doskonała do nauki jazdy.', '2024-12-04 01:40:00'),
(127, 22, 'Marek Zieliński', 'MT-07 to motocykl, który dostarcza radość z jazdy. Lekki, dynamiczny i wygodny.', '2024-12-04 01:50:00'),
(128, 23, 'Kamil Zawisza', 'KTM Duke 790 to szybki motocykl, który świetnie sprawdza się zarówno w mieście, jak i na trasie. Bardzo zwrotny.', '2024-12-04 01:50:00'),
(129, 23, 'Marek Nowakowski', 'Duke 790 to maszyna, która oferuje bardzo dobrą dynamikę i świetne prowadzenie na zakrętach. Polecam!', '2024-12-04 02:00:00'),
(130, 23, 'Piotr Nowak', 'Jestem zachwycony Duke 790. Niezwykle zwinny motocykl, który sprawdza się w każdych warunkach.', '2024-12-04 02:10:00'),
(131, 23, 'Jakub Baran', 'Duke 790 to motocykl o bardzo sportowych osiągach. Świetna jakość wykonania.', '2024-12-04 02:20:00'),
(132, 23, 'Krzysztof Zawisza', 'Motocykl idealny dla osób, które szukają dużej mocy w lekkiej konstrukcji. Sprawdzi się zarówno na torze, jak i na ulicy.', '2024-12-04 02:30:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `shipping_method_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Oczekujące',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `shipping_method_id`, `status`, `order_date`, `payment_method_id`, `address_id`) VALUES
(33, 2, 50000.00, 1, 'W realizacji', '2024-12-03 15:51:30', 2, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`) VALUES
(1, 'BLIK'),
(2, 'Płatność kartą'),
(3, 'Płatność przy odbiorze');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `podstrony`
--

CREATE TABLE `podstrony` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL,
  `tresc` text NOT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `podstrony`
--

INSERT INTO `podstrony` (`id`, `tytul`, `tresc`, `data_utworzenia`) VALUES
(1, 'O nas', 'O nas\r\n\r\nWitaj w naszym sklepie motocyklowym! – Twoim zaufanym partnerze w świecie motocykli! Jesteśmy pasjonatami motocykli, którzy połączyli swoją miłość do dwóch kółek z chęcią dostarczania najwyższej jakości produktów i akcesoriów motocyklowych. Nasz sklep to miejsce, gdzie motocykliści mogą znaleźć wszystko, czego potrzebują do swoich maszyn – od części zamiennych, przez akcesoria, odzież ochronną, aż po akcesoria do tuningu i stylizacji.\r\n\r\nNasza misja\r\n\r\nNaszą misją jest zapewnienie motocyklistom pełnej satysfakcji z jazdy. Oferujemy szeroki wybór części i akcesoriów od renomowanych producentów, dbając o jakość, bezpieczeństwo i komfort naszych klientów. Niezależnie od tego, czy jesteś doświadczonym motocyklistą, czy dopiero zaczynasz swoją przygodę z motocyklami – w naszym sklepie znajdziesz wszystko, czego potrzebujesz, aby cieszyć się jazdą na dwóch kółkach.\r\n\r\nDlaczego warto wybrać nas?\r\n\r\nSzeroka oferta – posiadamy bogaty asortyment motocykli, części zamiennych, odzieży i akcesoriów. Nasza oferta jest stale aktualizowana, aby dostarczyć Ci najnowsze i najlepsze produkty na rynku.\r\n\r\nJakość i bezpieczeństwo – współpracujemy tylko z zaufanymi i sprawdzonymi producentami, którzy oferują produkty spełniające wysokie standardy jakości i bezpieczeństwa.\r\n\r\nProfesjonalne doradztwo – nasz zespół to prawdziwi pasjonaci motocykli, którzy z chęcią podzielą się swoją wiedzą i doświadczeniem. Doradzimy Ci, jakie akcesoria i części najlepiej pasują do Twojego motocykla i stylu jazdy.\r\n\r\nWygodne zakupy online – dzięki naszej stronie internetowej możesz wygodnie przeglądać produkty, składać zamówienia i korzystać z szybkiej dostawy do domu lub odbioru osobistego w sklepie.', '2024-12-03 16:58:07');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `Opis` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `Opis`, `price`, `category_id`) VALUES
(1, 'Harley Davidson', 'images\\davidson.png', 'Harley-Davidson Sport Glide łączy w sobie zwinność cruisera oraz wygodę motocykla turystycznego. Idealny do długich podróży, oferuje komfort jazdy oraz niezawodność na długich trasach.', 45000.00, 1),
(2, 'Yamaha MT-07', 'images/mt07.png', 'Yamaha MT-07 to motocykl, który od momentu swojego debiutu stał się liderem w klasie średniej. Dzięki silnikowi o pojemności 689 cm³ zapewnia wyjątkowe osiągi i zwrotność, idealny do jazdy w miejskim ruchu oraz na krętych drogach.', 30000.00, 2),
(3, 'Honda CBR500R', 'images/hondacbr500.png', 'Honda CBR500R to sportowy motocykl, który łączy w sobie osiągi i wygodę, idealny do codziennej jazdy. Jest to świetny wybór dla osób szukających motocykla o dynamicznych osiągach przy jednoczesnej niskiej wadze.', 25000.00, 3),
(4, 'Ducati Monster', 'images/ducati.png', 'Ducati Monster to motocykl, który łączy sportowy charakter z miejską funkcjonalnością. Silnik o pojemności 937 cm³ zapewnia doskonałą dynamikę, a jego lekka konstrukcja sprawia, że jest idealny do codziennego użytkowania i szybkich wypadów za miasto.', 50000.00, 3),
(5, 'Piaggio Liberty 50', 'images/liberty50.png', 'Piaggio Liberty 50 to skuter, który łączy nowoczesny design z funkcjonalnością. Doskonały wybór do jazdy po mieście, zapewnia łatwość manewrowania i niskie zużycie paliwa, co czyni go idealnym środkiem transportu do codziennych dojazdów.', 7000.00, 4),
(6, 'BMW R 1250 GS', 'images/bmw1250gs.png', 'BMW R 1250 GS to motocykl adventure klasy premium, który oferuje niezrównany komfort i wszechstronność. Silnik o pojemności 1250 cm³ oraz zaawansowane technologie pozwalają na pokonywanie zarówno asfaltowych dróg, jak i trudnego terenu.', 60000.00, 5),
(7, 'Honda PCX 125', 'images/hondapcx125.png', 'Honda PCX 125 to skuter o nowoczesnym wyglądzie, który zapewnia komfort i oszczędność na każdym kilometrze. Idealny do miejskiej jazdy, łatwy do prowadzenia i wyjątkowo oszczędny, doskonały do codziennych dojazdów.', 12000.00, 4),
(8, 'Kawasaki Ninja 650', 'images/ninja650.png', 'Kawasaki Ninja 650 to sportowy motocykl, który łączy w sobie osiągi i komfort jazdy. Dzięki silnikowi o pojemności 649 cm³ oferuje płynność jazdy oraz dużą wszechstronność, sprawdzając się zarówno na krętych drogach, jak i w długich trasach.', 40000.00, 3),
(9, 'Suzuki GSX-R750', 'images/suzuki750.png', 'Suzuki GSX-R750 to motocykl sportowy o wyjątkowych osiągach, który łączy lekkość, precyzyjne prowadzenie i niezrównaną dynamikę. Doskonały wybór dla pasjonatów sportowej jazdy oraz dla tych, którzy szukają mocnych wrażeń na torze.', 38000.00, 3),
(10, 'KTM 890 Adventure', 'images/ktm890.png', 'KTM 890 Adventure to motocykl, który łączy sportowy charakter z możliwościami off-road. Wyjątkowy silnik o pojemności 890 cm³ oraz zaawansowane systemy jezdne pozwalają na odkrywanie nowych dróg i pokonywanie trudnych nawierzchni.', 62000.00, 5),
(11, 'Triumph Bonneville', 'images/bonneville.png', 'Triumph Bonneville to klasyczny motocykl, który łączy styl lat 60-tych z nowoczesnymi rozwiązaniami technologicznymi. Silnik o pojemności 1200 cm³ i charakterystyczny design sprawiają, że jest to motocykl wyjątkowy zarówno pod względem osiągów, jak i este', 43000.00, 1),
(12, 'Vespa Primavera 50', 'images/vespa.png', 'Vespa Primavera 50 to kultowy skuter o retro wyglądzie, który jest idealnym środkiem transportu do miasta. Jego zwrotność, nowoczesny silnik oraz elegancki design sprawiają, że jest to doskonały wybór na codzienne dojazdy.', 13000.00, 4),
(13, 'Ducati Panigale V4', 'images/ducativ4.png', 'Ducati Panigale V4 to jeden z najlepszych motocykli sportowych na rynku. Wyjątkowy silnik o pojemności 1103 cm³, aerodynamika oraz zaawansowane technologie sprawiają, że motocykl ten jest stworzony do ekstremalnych osiągów i emocji na torze.', 85000.00, 3),
(14, 'Yamaha FZ1', 'images/fz1.png', 'Yamaha FZ1 to sportowy motocykl, który łączy w sobie mocny silnik z dynamicznym designem. Silnik o pojemności 998 cm³ i nowoczesne technologie pozwalają na intensywną jazdę, zarówno w mieście, jak i na otwartych trasach.', 30000.00, 2),
(15, 'BMW R 1250 GS', 'images/bmw1250gs.png', 'BMW R 1250 GS to motocykl stworzony z myślą o podróżach po najtrudniejszych szlakach. Silnik o pojemności 1250 cm³ oraz zaawansowane systemy elektroniczne zapewniają komfort i bezpieczeństwo w każdych warunkach, od autostrad po wąskie, górskie drogi.', 75000.00, 5),
(16, 'Kawasaki ZX-10R', 'images/kawasaki.png', 'Kawasaki ZX-10R to wyścigowy motocykl stworzony do osiągania najwyższych prędkości. Z silnikiem o pojemności 998 cm³ i zaawansowaną elektroniką, ZX-10R jest idealnym wyborem dla tych, którzy szukają emocji i precyzji na torze wyścigowym.', 95000.00, 3),
(17, 'Honda CRF1000L Africa Twin', 'images/hondaafrica.png', 'Honda Africa Twin to motocykl adventure, który sprawdzi się w najbardziej ekstremalnych warunkach. Wyjątkowy silnik o pojemności 998 cm³ i nowoczesne technologie pozwalają na pokonywanie trudnych szlaków zarówno na drodze, jak i w terenie.', 68000.00, 5),
(18, 'Harley Davidson Sportster', 'images/harleysportster.png', 'Harley Davidson Sportster to klasyczny amerykański cruiser, który łączy w sobie charakterystyczny design z niezrównanymi osiągami. Silnik o pojemności 883 cm³ oferuje doskonałą dynamikę i komfort jazdy, zarówno w mieście, jak i na długich trasach.', 45000.00, 1),
(19, 'Moto Guzzi V7', 'images/motoguzzi.png', 'Moto Guzzi V7 to motocykl o wyjątkowym charakterze, który łączy klasyczny styl z nowoczesną technologią. Silnik o pojemności 744 cm³ zapewnia płynność jazdy, a elegancki design sprawia, że jest to motocykl, który przyciąga wzrok i zapewnia niezrównaną prz', 43000.00, 1),
(20, 'Indian Scout', 'images/indianscout.png', 'Indian Scout to ikona amerykańskiego motocyklizmu. Dzięki silnikowi o pojemności 1133 cm³, Scout łączy w sobie wyjątkową moc i komfort jazdy, będąc doskonałym wyborem dla osób szukających klasycznego, ale dynamicznego cruisera.', 47000.00, 1),
(21, 'Aprilia RS 660', 'images/aprilia.png', 'Aprilia RS 660 to nowoczesny motocykl sportowy o średniej pojemności, który oferuje doskonałe osiągi oraz precyzyjne prowadzenie. Silnik o pojemności 660 cm³ sprawia, że RS 660 jest idealnym wyborem zarówno na tor, jak i na drogach publicznych.', 35000.00, 3),
(22, 'Royal Enfield Himalayan', 'images/himalayan.png', 'Royal Enfield Himalayan to motocykl adventure o klasycznym wyglądzie, który sprawdzi się w najtrudniejszych warunkach terenowych. Silnik o pojemności 411 cm³ zapewnia odpowiednią moc do długich wypraw i pokonywania trudnych szlaków.', 29000.00, 5),
(23, 'KTM Duke 390', 'images/ktmduke.png', 'KTM Duke 390 to motocykl naked, który oferuje doskonałe osiągi w miejskim ruchu. Dzięki silnikowi o pojemności 373 cm³ i lekkiej konstrukcji, Duke 390 jest idealnym wyborem dla tych, którzy szukają dynamicznej jazdy w mieście oraz na krótkich trasach.', 18000.00, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `cost`) VALUES
(1, 'DPD', 15.00),
(2, 'FedEx', 20.00),
(3, 'Odbiór osobisty', 0.00),
(4, 'Paczkomat', 12.00);

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
  `rodzaj` enum('klient','pracownik','admin') NOT NULL DEFAULT 'klient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`, `password`, `rodzaj`) VALUES
(1, 'testtest', 'test@sw.pl', '$2y$10$yVuV2JnA19qRBhd4ACE5UuAS.T0Z7cwxoNjiZt72u.cRtAzTxSrp6', '2024-11-20 10:51:58', NULL, 'klient'),
(2, 'admin1234', 'as@wp.pl', '$2y$10$PTJGQRFVagVr.WV4WtVz/eD1NrY/euCys6zcMBMOYHtm/esH0IEaS', '2024-10-27 07:44:05', NULL, 'admin'),
(3, 'nowy', 'nowy@wp.pl', '$2y$10$AoacH4SPqZ8xqawHyugHQe1pqyxjVW/0dC/SI3fAHoF6fJj0WyqNq', '2024-11-27 12:25:42', NULL, 'klient'),
(4, 'pracownik', 'pracownik@wp.pl', '$2y$10$MQAvMIZ0LWJO7QfvpynbNu87wlgJW8/Gknx2.j3y5VlpUR5qgzvdK', '2024-12-03 16:31:50', NULL, 'pracownik');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_opis`
--

CREATE TABLE `zdjecia_opis` (
  `id` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `sciezka` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zdjecia_opis`
--

INSERT INTO `zdjecia_opis` (`id`, `produkt_id`, `sciezka`) VALUES
(1, 1, 'zdjecia_opis\\1\\1.png');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id_adresu`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `opinie`
--
ALTER TABLE `opinie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shipping_method_id` (`shipping_method_id`);

--
-- Indeksy dla tabeli `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeksy dla tabeli `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `podstrony`
--
ALTER TABLE `podstrony`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indeksy dla tabeli `shipping_methods`
--
ALTER TABLE `shipping_methods`
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id_adresu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `opinie`
--
ALTER TABLE `opinie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `podstrony`
--
ALTER TABLE `podstrony`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zdjecia_opis`
--
ALTER TABLE `zdjecia_opis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `opinie`
--
ALTER TABLE `opinie`
  ADD CONSTRAINT `opinie_ibfk_1` FOREIGN KEY (`produkt_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
