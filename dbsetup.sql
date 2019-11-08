-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Nov 08, 2019 alle 17:28
-- Versione del server: 10.4.8-MariaDB
-- Versione PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softeng2final`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `marksrecord`
--

CREATE TABLE `marksrecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Mark` float NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `parent`
--

CREATE TABLE `parent` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `StudentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `parent`
--

INSERT INTO `parent` (`ID`, `Name`, `Surname`, `Email`, `Password`, `StudentID`) VALUES
(5, 'Peppino', 'Lavatrice', 'pns1@io.io', '$2y$12$z4NlHAWpIEGgw3pff.ZBVOd6tFFyjRPvup.atP5px136.UiQz0ifa', 3),
(6, 'Gianna', 'Lavatrice', 'gianna.lav@io.io', '$2y$12$BIuQCBgYvq37/FmonwvYVuof1gOvV3wLHQQpCAf/Umleoeg9wv8qu', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `student`
--

CREATE TABLE `student` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `AverageLastSchool` float NOT NULL,
  `CF` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `student`
--

INSERT INTO `student` (`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`) VALUES
(1, 'name1', 'sur1', 10, 'cf1'),
(2, 'name1', 'sur1', 10, 'cf1'),
(3, 'name2', 'sur2', 10, 'cf2'),
(4, 'name3', 'sur3', 10, 'cf3'),
(5, 'name4', 'sur4', 10, 'cf4');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `marksrecord`
--
ALTER TABLE `marksrecord`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indici per le tabelle `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indici per le tabelle `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `marksrecord`
--
ALTER TABLE `marksrecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `parent`
--
ALTER TABLE `parent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `student`
--
ALTER TABLE `student`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `marksrecord`
--
ALTER TABLE `marksrecord`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `parent`
--
ALTER TABLE `parent`
  ADD CONSTRAINT `fk_studentID` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
