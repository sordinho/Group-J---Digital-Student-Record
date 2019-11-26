-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Nov 26, 2019 alle 10:48
-- Versione del server: 5.7.28-0ubuntu0.16.04.2
-- Versione PHP: 7.2.24-1+ubuntu16.04.1+deb.sury.org+1

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
-- Struttura della tabella `Homework`
--

CREATE TABLE `Homework` (
  `ID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `Deadline` date DEFAULT NULL,
  `TopicID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Homework`
--

INSERT INTO `Homework` (`ID`, `Description`, `SpecificClassID`, `TeacherID`, `Deadline`, `TopicID`) VALUES
(1, 'desc1', 1, 1, '2020-01-08', 1),
(2, 'desc2', 1, 1, '2020-01-09', 2),
(3, 'desc3', 1, 1, '2020-01-10', 3),
(4, 'desc4', 1, 1, '2019-11-26', 1),
(5, 'desc5', 1, 1, '2019-11-27', 2),
(6, 'desc6', 1, 1, '2019-11-28', 3),
(7, 'desc7', 2, 2, '2020-01-08', 1),
(8, 'desc8', 2, 2, '2019-11-27', 2),
(9, 'desc9', 3, 3, '2019-11-28', 3),
(10, 'desc10', 3, 3, '2019-11-28', 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `MarksRecord`
--

CREATE TABLE `MarksRecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Mark` float NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Laude` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `MarksRecord`
--

INSERT INTO `MarksRecord` (`ID`, `StudentID`, `Mark`, `TeacherID`, `TopicID`, `Timestamp`, `Laude`) VALUES
(1, 2, 7, 1, 1, '2019-11-09 07:00:00', 0),
(2, 2, 7, 2, 2, '2019-11-09 08:00:00', 0),
(3, 3, 4, 3, 3, '2019-11-09 09:00:00', 0),
(4, 4, 2, 4, 4, '2019-11-09 10:00:00', 0),
(7, 5, 9, 7, 7, '2019-11-09 13:00:00', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `NotPresentRecord`
--

CREATE TABLE `NotPresentRecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `HourSlotStart` int(11) NOT NULL,
  `HourSlotEnd` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LateFlag` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `Officer`
--

CREATE TABLE `Officer` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FiscalCode` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Officer`
--

INSERT INTO `Officer` (`ID`, `UserID`, `FiscalCode`) VALUES
(1, 10, 'FSCOFFICER1'),
(2, 11, 'FSCOFFICER2');

-- --------------------------------------------------------

--
-- Struttura della tabella `Parent`
--

CREATE TABLE `Parent` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Parent`
--

INSERT INTO `Parent` (`ID`, `StudentID`, `UserID`) VALUES
(2, 2, 2),
(5, 4, 1),
(7, 3, 45),
(8, 3, 44);

-- --------------------------------------------------------

--
-- Struttura della tabella `SpecificClass`
--

CREATE TABLE `SpecificClass` (
  `ID` int(11) NOT NULL,
  `YearClassID` int(11) NOT NULL,
  `Section` varchar(5) NOT NULL,
  `UploadedPath` varchar(50) NOT NULL,
  `CoordinatorTeacherID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `SpecificClass`
--

INSERT INTO `SpecificClass` (`ID`, `YearClassID`, `Section`, `UploadedPath`, `CoordinatorTeacherID`) VALUES
(1, 1, 'A', 'uploadedPath1', 1),
(2, 1, 'B', 'uploadedPath2', 2),
(3, 1, 'C', 'uploadedPath3', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `Student`
--

CREATE TABLE `Student` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `AverageLastSchool` float NOT NULL,
  `CF` varchar(16) NOT NULL,
  `SpecificClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Student`
--

INSERT INTO `Student` (`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`, `SpecificClassID`) VALUES
(1, 'Hirving', 'Lozano', 10, 'cf1', 1),
(2, 'Vittorio', 'Di Leo', 10, 'cf1b', 1),
(3, 'Emanuele', 'Munafo', 10, 'cf2', 1),
(4, 'Davide', 'Sordi', 10, 'cf3', 1),
(5, 'Francesco', 'Riba', 10, 'cf4', 1),
(6, 'Riccardo', 'Mamone', 10, 'rf5', 1),
(8, 'Antonio', 'Santoro', 10, 'cf6', 1),
(9, 'Michael', 'Bing', 7, 'cf7', 2),
(11, 'Mario', 'Rossi', 7, 'Fc11', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `Teacher`
--

CREATE TABLE `Teacher` (
  `ID` int(11) NOT NULL,
  `MeetingHourID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FiscalCode` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Teacher`
--

INSERT INTO `Teacher` (`ID`, `MeetingHourID`, `UserID`, `FiscalCode`) VALUES
(1, 0, 3, 'fc1'),
(2, 0, 4, 'fc2'),
(3, 0, 5, 'fc3'),
(4, 0, 6, 'fc4'),
(5, 0, 7, 'fc5'),
(6, 0, 8, 'fc6'),
(7, 0, 9, 'fc7');

-- --------------------------------------------------------

--
-- Struttura della tabella `TeacherTopic`
--

CREATE TABLE `TeacherTopic` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `TeacherTopic`
--

INSERT INTO `TeacherTopic` (`ID`, `TeacherID`, `TopicID`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `Topic`
--

CREATE TABLE `Topic` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Topic`
--

INSERT INTO `Topic` (`ID`, `Name`, `Description`) VALUES
(1, 'History', 'Subject Description 1'),
(2, 'Physics', 'Subject Description 2'),
(3, 'Maths', 'Subject Description 3'),
(4, 'Science', 'Subject Description 4'),
(5, 'Geography', 'Subject Description 5'),
(6, 'Art', 'Subject Description 6'),
(7, 'Music', 'Subject Description 7');

-- --------------------------------------------------------

--
-- Struttura della tabella `TopicRecord`
--

CREATE TABLE `TopicRecord` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Description` varchar(512) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `TopicRecord`
--

INSERT INTO `TopicRecord` (`ID`, `TeacherID`, `Timestamp`, `Description`, `TopicID`, `SpecificClassID`) VALUES
(3, 1, '2019-11-17 17:19:22', 'TopicLectureDescription for classID=3', 1, 3),
(5, 1, '2019-11-18 23:00:00', 'description1', 1, 3),
(6, 1, '2019-11-17 23:00:00', 'test description', 1, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `TopicTeacherClass`
--

CREATE TABLE `TopicTeacherClass` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `TopicTeacherClass`
--

INSERT INTO `TopicTeacherClass` (`ID`, `TeacherID`, `TopicID`, `SpecificClassID`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 2),
(3, 3, 1, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `User`
--

CREATE TABLE `User` (
  `ID` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Surname` varchar(200) NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `UserGroup` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `User`
--

INSERT INTO `User` (`ID`, `Name`, `Surname`, `Email`, `Password`, `UserGroup`) VALUES
(1, 'Mary', 'ParentSurname1', 'pns1a@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
(2, 'Joseph', 'ParentSurname2', 'pns2a@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
(3, 'Marco', 'Torchiano', 'teach1@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(4, 'Paolo', 'Montuschi', 'teach2@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(5, 'Renato', 'Ferrero', 'teach3@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(6, 'Elen', 'Baralis', 'teach4@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(7, 'Mauro', 'Morisio', 'teach5@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(8, 'Bartolo', 'Montrucchio', 'teach6@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(9, 'Tony', 'Lioy', 'teach7@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(10, 'John', 'Price', 'off1@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'officer'),
(11, 'Paul', 'MacMillan', 'off2@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'officer'),
(44, 'Jude', 'surname', 'd1226143@urhen.com', '$2y$12$30A4FAueTEgqlQBS8tFsbeRcqpB6MNvkEfSk5odHdJHoEJkF7Z4h2', 'parent'),
(45, 'Elisabeth', 'surname', 'pns4@io.io', '$2y$12$30A4FAueTEgqlQBS8tFsbeRcqpB6MNvkEfSk5odHdJHoEJkF7Z4h2', 'parent');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Homework`
--
ALTER TABLE `Homework`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `HOMEWORK_TOPIC_INDEX` (`TopicID`);

--
-- Indici per le tabelle `MarksRecord`
--
ALTER TABLE `MarksRecord`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `TopicID` (`TopicID`);

--
-- Indici per le tabelle `NotPresentRecord`
--
ALTER TABLE `NotPresentRecord`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`);

--
-- Indici per le tabelle `Officer`
--
ALTER TABLE `Officer`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indici per le tabelle `Parent`
--
ALTER TABLE `Parent`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indici per le tabelle `SpecificClass`
--
ALTER TABLE `SpecificClass`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CoordinatorTeacherID` (`CoordinatorTeacherID`);

--
-- Indici per le tabelle `Student`
--
ALTER TABLE `Student`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`);

--
-- Indici per le tabelle `Teacher`
--
ALTER TABLE `Teacher`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indici per le tabelle `TeacherTopic`
--
ALTER TABLE `TeacherTopic`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `TopicID` (`TopicID`);

--
-- Indici per le tabelle `Topic`
--
ALTER TABLE `Topic`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `TopicRecord`
--
ALTER TABLE `TopicRecord`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `TopicTeacherClass`
--
ALTER TABLE `TopicTeacherClass`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `TopicID` (`TopicID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`);

--
-- Indici per le tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Homework`
--
ALTER TABLE `Homework`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT per la tabella `MarksRecord`
--
ALTER TABLE `MarksRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT per la tabella `NotPresentRecord`
--
ALTER TABLE `NotPresentRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `Officer`
--
ALTER TABLE `Officer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `Parent`
--
ALTER TABLE `Parent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT per la tabella `SpecificClass`
--
ALTER TABLE `SpecificClass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT per la tabella `Student`
--
ALTER TABLE `Student`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT per la tabella `Teacher`
--
ALTER TABLE `Teacher`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT per la tabella `TeacherTopic`
--
ALTER TABLE `TeacherTopic`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `Topic`
--
ALTER TABLE `Topic`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT per la tabella `TopicRecord`
--
ALTER TABLE `TopicRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT per la tabella `TopicTeacherClass`
--
ALTER TABLE `TopicTeacherClass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT per la tabella `User`
--
ALTER TABLE `User`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Homework`
--
ALTER TABLE `Homework`
  ADD CONSTRAINT `FK_HOMEWORK_TOPIC` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`),
  ADD CONSTRAINT `fk_specificClassID2` FOREIGN KEY (`SpecificClassID`) REFERENCES `SpecificClass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacherID3` FOREIGN KEY (`TeacherID`) REFERENCES `Teacher` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `MarksRecord`
--
ALTER TABLE `MarksRecord`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`StudentID`) REFERENCES `Student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_topic` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `NotPresentRecord`
--
ALTER TABLE `NotPresentRecord`
  ADD CONSTRAINT `fk_specificClassID4` FOREIGN KEY (`SpecificClassID`) REFERENCES `SpecificClass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_studentID2` FOREIGN KEY (`StudentID`) REFERENCES `Student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Officer`
--
ALTER TABLE `Officer`
  ADD CONSTRAINT `fk_officerID` FOREIGN KEY (`UserID`) REFERENCES `User` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Parent`
--
ALTER TABLE `Parent`
  ADD CONSTRAINT `fk_parentID` FOREIGN KEY (`UserID`) REFERENCES `User` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_studentID` FOREIGN KEY (`StudentID`) REFERENCES `Student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `SpecificClass`
--
ALTER TABLE `SpecificClass`
  ADD CONSTRAINT `fk_coordTeacherID` FOREIGN KEY (`CoordinatorTeacherID`) REFERENCES `Teacher` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Student`
--
ALTER TABLE `Student`
  ADD CONSTRAINT `fk_specificClassID3` FOREIGN KEY (`SpecificClassID`) REFERENCES `SpecificClass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Teacher`
--
ALTER TABLE `Teacher`
  ADD CONSTRAINT `fk_teacherID` FOREIGN KEY (`UserID`) REFERENCES `User` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `TeacherTopic`
--
ALTER TABLE `TeacherTopic`
  ADD CONSTRAINT `fk_teacherTopicID` FOREIGN KEY (`TeacherID`) REFERENCES `Teacher` (`ID`),
  ADD CONSTRAINT `fk_topicTeacherID` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`);

--
-- Limiti per la tabella `TopicTeacherClass`
--
ALTER TABLE `TopicTeacherClass`
  ADD CONSTRAINT `fk_specificclassID` FOREIGN KEY (`SpecificClassID`) REFERENCES `SpecificClass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacherID2` FOREIGN KEY (`TeacherID`) REFERENCES `Teacher` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_topicID` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
