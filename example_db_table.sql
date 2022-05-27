-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 27 Maj 2022, 12:29
-- Wersja serwera: 10.4.24-MariaDB
-- Wersja PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `tree`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tree`
--

CREATE TABLE `tree` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `tree`
--

INSERT INTO `tree` (`id`, `title`, `parent_id`) VALUES
(48, 'Poziom 3 #2', 45),
(47, 'Poziom 3 #1', 44),
(46, 'Poziom 2 #3', 40),
(45, 'Poziom 2 #2', 38),
(44, 'Poziom 2 #1', 38),
(39, 'Poziom 1 #2', 0),
(40, 'Poziom 1 #3', 0),
(49, 'Poziom 3 #3', 45),
(38, 'Poziom 1 #1', 0),
(50, 'Poziom 3 #4', 45),
(51, 'Poziom 4 #1', 48),
(52, 'Poziom 5 #1', 51);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `tree`
--
ALTER TABLE `tree`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `tree`
--
ALTER TABLE `tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
