-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Nov 07, 2019 alle 13:38
-- Versione del server: 5.7.27-0ubuntu0.16.04.1
-- Versione PHP: 7.0.33-0ubuntu0.16.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softeng2Final`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `MarksRecord`
--

DROP TABLE IF EXISTS `MarksRecord`;
CREATE TABLE `MarksRecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Mark` float NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `MarksRecord`
--

TRUNCATE TABLE `MarksRecord`;
-- --------------------------------------------------------

--
-- Struttura della tabella `Parent`
--

DROP TABLE IF EXISTS `Parent`;
CREATE TABLE `Parent` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `StudentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `Parent`
--

TRUNCATE TABLE `Parent`;
--
-- Dump dei dati per la tabella `Parent`
--

INSERT INTO `Parent` (`ID`, `Name`, `Surname`, `Email`, `Password`, `StudentID`) VALUES
(1, 'pn1', 'ps1', 'pns1@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 1),
(2, 'pn1', 'ps1', 'pns1@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 1),
(3, 'pn2', 'sn2', 'pns2@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 2),
(4, 'pn2', 'sn2', 'pns2@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `Student`
--

DROP TABLE IF EXISTS `Student`;
CREATE TABLE `Student` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `AverageLastSchool` float NOT NULL,
  `CF` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `Student`
--

TRUNCATE TABLE `Student`;
--
-- Dump dei dati per la tabella `Student`
--

INSERT INTO `Student` (`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`) VALUES
(1, 'name1', 'sur1', 10, 'cf1'),
(2, 'name1', 'sur1', 10, 'cf1'),
(3, 'name2', 'sur2', 10, 'cf2'),
(4, 'name3', 'sur3', 10, 'cf3'),
(5, 'name4', 'sur4', 10, 'cf4');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `MarksRecord`
--
ALTER TABLE `MarksRecord`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indici per le tabelle `Parent`
--
ALTER TABLE `Parent`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indici per le tabelle `Student`
--
ALTER TABLE `Student`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `MarksRecord`
--
ALTER TABLE `MarksRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `Parent`
--
ALTER TABLE `Parent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `Student`
--
ALTER TABLE `Student`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `MarksRecord`
--
ALTER TABLE `MarksRecord`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`StudentID`) REFERENCES `Student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Parent`
--
ALTER TABLE `Parent`
  ADD CONSTRAINT `fk_studentID` FOREIGN KEY (`StudentID`) REFERENCES `Student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
