-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 11, 2019 alle 11:07
-- Versione del server: 10.4.8-MariaDB
-- Versione PHP: 7.3.11

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
-- Struttura della tabella `communication`
--

CREATE TABLE `communication` (
  `ID` int(11) NOT NULL,
  `Title` varchar(535) NOT NULL,
  `Description` text NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `OfficerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `communication`
--

INSERT INTO `communication` (`ID`, `Title`, `Description`, `Timestamp`, `OfficerID`) VALUES
(1, 'Christmas holidays', 'All lectures are suspended from 20/12/2019 until 07/01/2020', '2019-12-09 16:34:14', 1),
(2, 'All labs will be closed', 'The access to all laboratories will be restored on 10/01/2020', '2019-12-09 17:34:14', 2),
(3, 'Lecture suspended', 'All lectures are suspended on 11/12/2019', '2019-12-09 16:35:07', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `homework`
--

CREATE TABLE `homework` (
  `ID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `Deadline` date DEFAULT NULL,
  `TopicID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `homework`
--

INSERT INTO `homework` (`ID`, `Description`, `SpecificClassID`, `TeacherID`, `Deadline`, `TopicID`) VALUES
(1, 'desc1', 1, 1, '2020-01-08', 1),
(2, 'desc2', 1, 1, '2020-01-09', 2),
(4, 'desc4', 1, 1, '2019-11-26', 1),
(5, 'desc5', 1, 1, '2019-11-27', 2),
(7, 'desc7', 2, 2, '2020-01-08', 1),
(9, 'desc9', 3, 3, '2019-11-28', 1),
(11, 'Exercise in class', 2, 7, '2019-12-01', 3),
(12, 'Elephant carpaccio', 2, 7, '2019-11-26', 4),
(13, 'Scrum', 1, 1, '2019-11-28', 1),
(14, 'prova prova', 1, 1, '2019-11-27', 2),
(15, 'Study everything', 1, 1, '2019-12-04', 2),
(16, 'Demo + release. End of 2nd sprint', 1, 1, '2019-12-03', 2),
(17, 'Retrospective', 1, 1, '2019-12-04', 1),
(18, 'E2E testing', 1, 1, '2019-12-02', 2),
(19, 'assignment 1', 1, 1, '2019-12-04', 1),
(20, 'Code refactoring', 1, 1, '2019-12-05', 1);

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
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `Laude` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `marksrecord`
--

INSERT INTO `marksrecord` (`ID`, `StudentID`, `Mark`, `TeacherID`, `TopicID`, `Timestamp`, `Laude`) VALUES
(1, 2, 7, 1, 1, '2019-11-09 07:00:00', 0),
(2, 2, 7, 2, 2, '2019-11-09 08:00:00', 0),
(3, 3, 4, 3, 3, '2019-11-09 09:00:00', 0),
(4, 4, 2, 4, 4, '2019-11-09 10:00:00', 0),
(7, 5, 9, 7, 7, '2019-11-09 13:00:00', 0),
(10, 1, 1, 1, 1, '2019-11-26 14:02:30', 0),
(11, 5, 8, 1, 1, '2019-12-01 16:31:43', 0),
(12, 1, 3, 1, 1, '2019-12-01 16:32:05', 0),
(13, 4, 10, 1, 1, '2019-12-02 08:00:00', 0),
(14, 6, 7, 1, 1, '2019-12-01 16:32:09', 0),
(15, 3, 7, 1, 1, '2019-12-01 16:32:09', 0),
(16, 2, 10, 1, 1, '2019-12-01 16:32:16', 1),
(17, 8, 8, 1, 1, '2019-12-01 16:36:53', 0),
(18, 2, 5, 1, 2, '2019-12-01 17:58:06', 0),
(19, 6, 10, 1, 1, '2019-12-01 17:58:06', 1),
(20, 3, 8, 1, 2, '2019-12-02 12:10:40', 0),
(21, 5, 3, 1, 2, '2019-12-02 16:23:56', 0),
(22, 2, 8, 1, 1, '2019-12-03 08:49:11', 0),
(23, 12, 9, 1, 2, '2019-12-03 08:00:00', 0),
(24, 12, 4, 2, 1, '2019-12-03 09:00:00', 0),
(25, 12, 6, 3, 3, '2019-12-03 10:00:00', 0),
(26, 12, 10, 4, 4, '2019-12-03 11:00:00', 0),
(27, 12, 9, 5, 5, '2019-11-26 09:00:00', 0),
(28, 12, 2, 6, 6, '2019-11-28 09:00:00', 0),
(29, 4, 5, 2, 2, '2019-12-02 07:00:00', 0),
(30, 4, 7, 4, 4, '2019-12-02 09:00:00', 0),
(31, 4, 6, 5, 5, '2019-12-03 09:00:00', 0),
(32, 4, 6, 6, 6, '2019-12-03 10:00:00', 0),
(33, 4, 10, 7, 7, '2019-12-03 11:00:00', 0),
(34, 2, 10, 1, 2, '2019-12-03 10:43:54', 1),
(35, 4, 10, 1, 1, '2019-12-03 10:43:54', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `note`
--

CREATE TABLE `note` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `noterecord`
--

CREATE TABLE `noterecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `NoteID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `notpresentrecord`
--

CREATE TABLE `notpresentrecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Late` tinyint(1) NOT NULL DEFAULT 0,
  `ExitHour` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `notpresentrecord`
--

INSERT INTO `notpresentrecord` (`ID`, `StudentID`, `SpecificClassID`, `Date`, `Late`, `ExitHour`) VALUES
(1, 1, 1, '2019-11-28', 1, 4),
(2, 2, 1, '2019-11-28', 0, 0),
(3, 3, 1, '2019-11-28', 0, 4),
(12, 6, 1, '2019-12-02', 0, 0),
(13, 3, 1, '2019-12-02', 1, 6),
(14, 2, 1, '2019-12-02', 1, 6),
(15, 5, 1, '2019-12-02', 0, 0),
(16, 4, 1, '2019-12-02', 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `officer`
--

CREATE TABLE `officer` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FiscalCode` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `officer`
--

INSERT INTO `officer` (`ID`, `UserID`, `FiscalCode`) VALUES
(1, 10, 'FSCOFFICER1'),
(2, 11, 'FSCOFFICER2');

-- --------------------------------------------------------

--
-- Struttura della tabella `parent`
--

CREATE TABLE `parent` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `parent`
--

INSERT INTO `parent` (`ID`, `StudentID`, `UserID`) VALUES
(2, 2, 2),
(5, 4, 1),
(7, 3, 45),
(8, 3, 44),
(10, 4, 2),
(11, 12, 55),
(12, 13, 56),
(14, 12, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `specificclass`
--

CREATE TABLE `specificclass` (
  `ID` int(11) NOT NULL,
  `YearClassID` int(11) NOT NULL,
  `Section` varchar(5) NOT NULL,
  `UploadedPath` varchar(50) NOT NULL,
  `CoordinatorTeacherID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `specificclass`
--

INSERT INTO `specificclass` (`ID`, `YearClassID`, `Section`, `UploadedPath`, `CoordinatorTeacherID`) VALUES
(-1, -1, 'noC', '', 1),
(1, 1, 'A', 'uploadedPath1', 1),
(2, 1, 'B', 'uploadedPath2', 2),
(3, 1, 'C', 'uploadedPath3', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `student`
--

CREATE TABLE `student` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `AverageLastSchool` float NOT NULL,
  `CF` varchar(16) NOT NULL,
  `SpecificClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `student`
--

INSERT INTO `student` (`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`, `SpecificClassID`) VALUES
(1, 'Hirving', 'Lozano', 10, 'LGGLPM50L71Z356X', 1),
(2, 'Vittorio', 'Di Leo', 10, 'PHGKRF55P70E908R', 1),
(3, 'Emanuele', 'Munafo', 10, 'DLGLYL71H30E159S', 1),
(4, 'Davide', 'Sordi', 10, 'HYFWMS36B11A963E', 1),
(5, 'Francesco', 'Riba', 10, 'JFVYMM92P59A229O', 1),
(6, 'Riccardo', 'Mamone', 10, 'ZGSQPD62P61F443K', 1),
(8, 'Antonio', 'Santoro', 10, 'GHFNDJ51S10L730U', 1),
(9, 'Michael', 'Bing', 7, 'RRQDWW41C60G670Z', 2),
(11, 'Mario', 'Rossi', 7, 'LVMLVS80T70L552B', 2),
(12, 'Javier', 'Lautaro', 10, 'LTRJVR97A01F839O', 2),
(13, 'Dries', 'Mertens', 10, 'MRTDRS89L03F839J', 3),
(18, 'Francesco', 'Riba', 9, 'WTCPGG93M51H398P', 1),
(23, 'Ross', 'Trebbiani', 9.25, 'TRBRSS80A01F839Q', -1);

-- --------------------------------------------------------

--
-- Struttura della tabella `teacher`
--

CREATE TABLE `teacher` (
  `ID` int(11) NOT NULL,
  `MeetingHourID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FiscalCode` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `teacher`
--

INSERT INTO `teacher` (`ID`, `MeetingHourID`, `UserID`, `FiscalCode`) VALUES
(1, 0, 3, 'MTFiscalCode'),
(2, 0, 4, 'fc2'),
(3, 0, 5, 'fc3'),
(4, 0, 6, 'fc4'),
(5, 0, 7, 'fc5'),
(6, 0, 8, 'fc6'),
(7, 0, 9, 'fc7'),
(8, 0, 57, 'VTRNTN80M01F839G');

-- --------------------------------------------------------

--
-- Struttura della tabella `topic`
--

CREATE TABLE `topic` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `topic`
--

INSERT INTO `topic` (`ID`, `Name`, `Description`) VALUES
(1, 'History', 'Subject Description 1'),
(2, 'Physics', 'Subject Description 2'),
(3, 'Maths', 'Subject Description 3'),
(4, 'Science', 'Subject Description 4'),
(5, 'Geography', 'Subject Description 5'),
(6, 'Art', 'Subject Description 6'),
(7, 'Music', 'Subject Description 7');

-- --------------------------------------------------------

--
-- Struttura della tabella `topicrecord`
--

CREATE TABLE `topicrecord` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `Description` varchar(512) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `topicrecord`
--

INSERT INTO `topicrecord` (`ID`, `TeacherID`, `Timestamp`, `Description`, `TopicID`, `SpecificClassID`) VALUES
(3, 1, '2019-12-02 07:00:00', 'Italy enters the first world war ', 1, 3),
(5, 1, '2019-12-02 11:00:00', 'The Scientific Revolution ', 1, 3),
(9, 2, '2019-12-01 23:00:00', 'Italy enters the first world war', 1, 2),
(10, 1, '2019-12-02 10:00:00', 'Fluid dynamics', 2, 1),
(12, 1, '2019-12-03 09:00:00', 'angular momentum', 2, 1),
(13, 7, '2019-12-01 23:00:00', 'Atoms', 4, 3),
(14, 7, '2019-12-01 23:00:00', 'Lagrangean Relaxation', 3, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `topicteacherclass`
--

CREATE TABLE `topicteacherclass` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `hourSlot` int(11) NOT NULL,
  `dayOfWeek` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `topicteacherclass`
--

INSERT INTO `topicteacherclass` (`ID`, `TeacherID`, `TopicID`, `SpecificClassID`, `hourSlot`, `dayOfWeek`) VALUES
(139, 1, 1, -1, 0, 0),
(140, 2, 2, -1, 0, 0),
(141, 3, 3, -1, 0, 0),
(142, 4, 4, -1, 0, 0),
(143, 5, 5, -1, 0, 0),
(144, 6, 6, -1, 0, 0),
(145, 7, 7, -1, 0, 0),
(146, 8, 7, -1, 0, 0),
(229, 8, 7, 1, 0, 0),
(230, 8, 7, 1, 1, 0),
(231, 8, 7, 1, 2, 0),
(232, 8, 7, 1, 3, 0),
(233, 8, 7, 1, 4, 0),
(234, 8, 7, 1, 5, 0),
(235, 6, 6, 1, 0, 1),
(236, 6, 6, 1, 1, 1),
(237, 6, 6, 1, 2, 1),
(238, 6, 6, 1, 3, 1),
(239, 6, 6, 1, 4, 1),
(240, 6, 6, 1, 5, 1),
(241, 4, 4, 1, 0, 2),
(242, 4, 4, 1, 1, 2),
(243, 4, 4, 1, 2, 2),
(244, 4, 4, 1, 3, 2),
(245, 4, 4, 1, 4, 2),
(246, 4, 4, 1, 5, 2),
(247, 1, 1, 1, 0, 3),
(248, 1, 1, 1, 1, 3),
(249, 1, 1, 1, 2, 3),
(250, 1, 1, 1, 3, 3),
(251, 1, 1, 1, 4, 3),
(252, 1, 1, 1, 5, 3),
(253, 2, 2, 1, 0, 4),
(254, 2, 2, 1, 1, 4),
(255, 2, 2, 1, 2, 4),
(256, 2, 2, 1, 3, 4),
(257, 2, 2, 1, 4, 4),
(258, 2, 2, 1, 5, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `uploadedclassdocuments`
--

CREATE TABLE `uploadedclassdocuments` (
  `ID` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `DiskFileName` varchar(255) NOT NULL,
  `SpecificClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Surname` varchar(200) NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `UserGroup` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`ID`, `Name`, `Surname`, `Email`, `Password`, `UserGroup`) VALUES
(1, 'Mary', 'Smith', 'pns1a@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
(2, 'Joseph', 'ParentSurname2', 'pns2a@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
(3, 'Marco', 'Torchiano', 'marco.torchiano@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(4, 'Paolo', 'Montuschi', 'teach2@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(5, 'Renato', 'Ferrero', 'teach3@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(6, 'Elen', 'Baralis', 'teach4@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(7, 'Mauro', 'Morisio', 'teach5@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(8, 'Bartolo', 'Montrucchio', 'teach6@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(9, 'Tony', 'Lioy', 'tony.lioy@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(10, 'John', 'Price', 'john.price@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'officer'),
(11, 'Paul', 'MacMillan', 'paul.macmillan@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'officer'),
(44, 'Jude', 'surname', 'd1226143@urhen.com', '$2y$12$30A4FAueTEgqlQBS8tFsbeRcqpB6MNvkEfSk5odHdJHoEJkF7Z4h2', 'parent'),
(45, 'Elisabeth', 'surname', 'pns4@io.io', '$2y$12$30A4FAueTEgqlQBS8tFsbeRcqpB6MNvkEfSk5odHdJHoEJkF7Z4h2', 'parent'),
(46, 'System', 'Administrator', 'sysadmin@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'admin'),
(55, 'Tony', 'Lioy', 'tony.lioy@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
(56, 'Fei Fei', 'Li', 'lifeifei@gmail.com', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
(57, 'Antonio', 'Vetro', 'antonio.vetro@io.io', '$2y$12$wh/CWpnhPY/pQ2xzNLZMIemWcJ62UnNJv0omRDt5.Px8gFUp8rfim', 'teacher');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `communication`
--
ALTER TABLE `communication`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OfficerID` (`OfficerID`);

--
-- Indici per le tabelle `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `HOMEWORK_TOPIC_INDEX` (`TopicID`);

--
-- Indici per le tabelle `marksrecord`
--
ALTER TABLE `marksrecord`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `TopicID` (`TopicID`);

--
-- Indici per le tabelle `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `noterecord`
--
ALTER TABLE `noterecord`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `notpresentrecord`
--
ALTER TABLE `notpresentrecord`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`);

--
-- Indici per le tabelle `officer`
--
ALTER TABLE `officer`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indici per le tabelle `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indici per le tabelle `specificclass`
--
ALTER TABLE `specificclass`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CoordinatorTeacherID` (`CoordinatorTeacherID`);

--
-- Indici per le tabelle `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`);

--
-- Indici per le tabelle `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indici per le tabelle `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `topicrecord`
--
ALTER TABLE `topicrecord`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `topicteacherclass`
--
ALTER TABLE `topicteacherclass`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `TopicID` (`TopicID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`);

--
-- Indici per le tabelle `uploadedclassdocuments`
--
ALTER TABLE `uploadedclassdocuments`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `communication`
--
ALTER TABLE `communication`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `homework`
--
ALTER TABLE `homework`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `marksrecord`
--
ALTER TABLE `marksrecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT per la tabella `note`
--
ALTER TABLE `note`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `noterecord`
--
ALTER TABLE `noterecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `notpresentrecord`
--
ALTER TABLE `notpresentrecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `officer`
--
ALTER TABLE `officer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `parent`
--
ALTER TABLE `parent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `specificclass`
--
ALTER TABLE `specificclass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `student`
--
ALTER TABLE `student`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT per la tabella `teacher`
--
ALTER TABLE `teacher`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `topic`
--
ALTER TABLE `topic`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `topicrecord`
--
ALTER TABLE `topicrecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `topicteacherclass`
--
ALTER TABLE `topicteacherclass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;

--
-- AUTO_INCREMENT per la tabella `uploadedclassdocuments`
--
ALTER TABLE `uploadedclassdocuments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `communication`
--
ALTER TABLE `communication`
  ADD CONSTRAINT `fk_officerID2` FOREIGN KEY (`OfficerID`) REFERENCES `officer` (`ID`);

--
-- Limiti per la tabella `homework`
--
ALTER TABLE `homework`
  ADD CONSTRAINT `FK_HOMEWORK_TOPIC` FOREIGN KEY (`TopicID`) REFERENCES `topic` (`ID`),
  ADD CONSTRAINT `fk_specificClassID2` FOREIGN KEY (`SpecificClassID`) REFERENCES `specificclass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacherID3` FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `marksrecord`
--
ALTER TABLE `marksrecord`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_topic` FOREIGN KEY (`TopicID`) REFERENCES `topic` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `notpresentrecord`
--
ALTER TABLE `notpresentrecord`
  ADD CONSTRAINT `fk_specificClassID5` FOREIGN KEY (`SpecificClassID`) REFERENCES `specificclass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_studentID3` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `officer`
--
ALTER TABLE `officer`
  ADD CONSTRAINT `fk_officerID` FOREIGN KEY (`UserID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `parent`
--
ALTER TABLE `parent`
  ADD CONSTRAINT `fk_parentID` FOREIGN KEY (`UserID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_studentID` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `specificclass`
--
ALTER TABLE `specificclass`
  ADD CONSTRAINT `fk_coordTeacherID` FOREIGN KEY (`CoordinatorTeacherID`) REFERENCES `teacher` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_specificClassID3` FOREIGN KEY (`SpecificClassID`) REFERENCES `specificclass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_teacherID` FOREIGN KEY (`UserID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `topicteacherclass`
--
ALTER TABLE `topicteacherclass`
  ADD CONSTRAINT `fk_specificclassID` FOREIGN KEY (`SpecificClassID`) REFERENCES `specificclass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacherID2` FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_topicID` FOREIGN KEY (`TopicID`) REFERENCES `topic` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
