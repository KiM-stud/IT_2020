-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 31 Gru 2020, 03:52
-- Wersja serwera: 10.4.17-MariaDB
-- Wersja PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `it`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gracze`
--

CREATE TABLE `gracze` (
  `id` int(11) NOT NULL,
  `login` text COLLATE utf8_polish_ci NOT NULL,
  `haslo` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL,
  `snakepkt` int(11) NOT NULL,
  `tetrispkt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `gracze`
--

INSERT INTO `gracze` (`id`, `login`, `haslo`, `email`, `snakepkt`, `tetrispkt`) VALUES
(1, 'Mati', '$2y$10$3tqCh5rC1NLAO1q5.SCLZ.ldqzgU7LSoX1vwAHiLgEHVo7W2HHHia', 'mati@interia.pl', 7, 1),
(2, 'Oklej', '$2y$10$0wEjabnL4KKRzGf9ySVDp.zq2pEudzTIkssDbERBlKJPYG4Db4rB2', 'yolo@69.com', 0, 2),
(4, '12345678901234567890', '', '', 1000, 1000),
(5, 'test1', '', '', 32, 22),
(6, 'test2', '', '', 454, 45),
(7, 'test3', '', '', 78, 453),
(8, 'test4', '', '', 11, 1),
(9, 'test5', '', '', 65, 4),
(10, 'test6', '', '', 325, 100),
(11, 'test7', '', '', 9, 90),
(12, 'jedenasty', '', '', 0, 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `gracze`
--
ALTER TABLE `gracze`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `gracze`
--
ALTER TABLE `gracze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
