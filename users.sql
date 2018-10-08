-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 26, 2018 alle 16:25
-- Versione del server: 10.1.32-MariaDB
-- Versione PHP: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `Name` varchar(40) CHARACTER SET utf8 NOT NULL,
  `Password` varchar(64) CHARACTER SET utf8 NOT NULL,
  `Departure` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `Arrival` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `Seats` int(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dump dei dati per la tabella `members`
--

INSERT INTO `members` (`Name`, `Password`, `Departure`, `Arrival`, `Seats`) VALUES
('u1@p.it', '$2y$10$9uHjsPfCj6jHxjBNG2k5sODazDvStLNtqhXW4seVfsMLFLfbhjfGy', 'FF', 'KK', 4),
('u2@p.it', '$2y$10$t00tzccbsQ/XckslXVM23Oj1AUMJH923r744xyeSdAEJv0oU8l7sW', 'BB', 'EE', 1),
('u3@p.it', '$2y$10$dl3yrYIGmZsTcO157YC50.ny1ye1n6IW0V61aJT9JejvQtZTHnQfK', 'DD', 'EE', 1),
('u4@p.it', '$2y$10$gZxJF0QEebonBSksS/Q4teP8mcyfEErkq/xFLtZeOKBtBGBas7uE2', 'AL', 'DD', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `stops`
--

DROP TABLE IF EXISTS `stops`;
CREATE TABLE `stops` (
  `stop` varchar(64) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dump dei dati per la tabella `stops`
--

INSERT INTO `stops` (`stop`) VALUES
('AL'),
('BB'),
('DD'),
('EE'),
('FF'),
('KK');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`Name`);

--
-- Indici per le tabelle `stops`
--
ALTER TABLE `stops`
  ADD PRIMARY KEY (`stop`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
