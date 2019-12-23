-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Dic 23, 2019 alle 13:29
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
-- Struttura della tabella `Communication`
--

CREATE TABLE `Communication` (
  `ID` int(11) NOT NULL,
  `Title` varchar(535) NOT NULL,
  `Description` text NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `OfficerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Communication`
--

INSERT INTO `Communication` (`ID`, `Title`, `Description`, `Timestamp`, `OfficerID`) VALUES
(1, 'Christmas holidays', 'All lectures are suspended from 20/12/2019 until 07/01/2020', '2019-12-09 16:34:14', 1),
(3, 'Lecture suspended', 'All lectures are suspended on 11/12/2019', '2019-12-09 16:35:07', 1),
(8, 'Holydays', 'description of holydays', '2019-12-17 14:53:18', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `FinalGrades`
--

CREATE TABLE `FinalGrades` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `Mark` int(11) NOT NULL,
  `TermID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Struttura della tabella `MarksRecord`
--

CREATE TABLE `MarksRecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Mark` float NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Struttura della tabella `Note`
--

CREATE TABLE `Note` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Note`
--

INSERT INTO `Note` (`ID`, `TeacherID`, `SpecificClassID`, `Date`, `Description`) VALUES
(1, 1, 1, '2019-12-09 23:00:00', 'note1'),
(4, 1, 1, '2019-12-09 23:00:00', 'Di Leo and Riba talk during lesson'),
(5, 1, 1, '2019-12-12 23:00:00', 'Student talks with classmates'),
(6, 1, 1, '2019-12-09 23:00:00', 'Di Leo is doing E2E testing with his teammates'),
(7, 7, 2, '2019-12-08 23:00:00', 'Dries plays football during the lecture'),
(8, 7, 3, '2019-12-12 23:00:00', 'dries mertens plays with nintendo switch during the lecture'),
(9, 1, 1, '2019-12-15 23:00:00', 'Di Leo and Sordi note'),
(12, 1, 1, '2019-12-16 23:00:00', 'di leo and sordi are talking during demo');

-- --------------------------------------------------------

--
-- Struttura della tabella `NoteRecord`
--

CREATE TABLE `NoteRecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `NoteID` int(11) NOT NULL,
  `Seen` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `NoteRecord`
--

INSERT INTO `NoteRecord` (`ID`, `StudentID`, `NoteID`, `Seen`) VALUES
(1, 2, 1, 1),
(2, 6, 1, 0),
(3, 2, 4, 1),
(4, 5, 4, 0),
(5, 4, 5, 1),
(6, 2, 6, 1),
(7, 6, 6, 0),
(8, 3, 6, 0),
(9, 5, 6, 0),
(10, 8, 6, 0),
(11, 4, 6, 1),
(12, 13, 8, 0),
(13, 2, 9, 1),
(14, 4, 9, 1),
(18, 2, 12, 1),
(19, 4, 12, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `NotPresentRecord`
--

CREATE TABLE `NotPresentRecord` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Late` tinyint(1) NOT NULL DEFAULT '0',
  `ExitHour` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `NotPresentRecord`
--

INSERT INTO `NotPresentRecord` (`ID`, `StudentID`, `SpecificClassID`, `Date`, `Late`, `ExitHour`) VALUES
(1, 1, 1, '2019-11-28', 1, 4),
(2, 2, 1, '2019-11-28', 0, 0),
(3, 3, 1, '2019-11-28', 0, 4),
(12, 6, 1, '2019-12-02', 0, 0),
(13, 3, 1, '2019-12-02', 1, 6),
(14, 2, 1, '2019-12-02', 1, 6),
(15, 5, 1, '2019-12-02', 0, 0),
(16, 4, 1, '2019-12-02', 0, 0),
(20, 6, 1, '2019-12-12', 1, 6),
(22, 4, 1, '2019-12-13', 0, 4),
(23, 4, 1, '2019-12-12', 0, 0),
(24, 2, 1, '2019-12-12', 0, 4),
(25, 6, 1, '2019-12-11', 0, 0),
(26, 1, 1, '2019-12-11', 0, 2),
(27, 5, 1, '2019-12-11', 0, 4),
(28, 3, 1, '2019-12-11', 1, 6),
(29, 5, 1, '2019-12-16', 1, 6),
(30, 2, 1, '2019-12-16', 0, 0),
(31, 1, 1, '2019-12-16', 0, 4),
(81, 4, 1, '2019-12-17', 0, 0),
(82, 2, 1, '2019-12-17', 0, 4),
(83, 1, 1, '2019-12-17', 1, 6),
(84, 3, 1, '2019-12-17', 1, 6);

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
(8, 3, 44),
(10, 4, 2),
(11, 12, 55),
(12, 13, 56),
(14, 12, 1);

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
(-1, -1, 'noC', '', 19),
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
(18, 'Lorenzo', 'Riba', 9, 'WTCPGG93M51H398P', 1),
(23, 'Ross', 'Trebbiani', 9.25, 'TRBRSS80A01F839Q', -1);

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
(1, 0, 3, 'MTFiscalCode'),
(2, 0, 4, 'fc2'),
(3, 0, 5, 'fc3'),
(4, 0, 6, 'fc4'),
(5, 0, 7, 'fc5'),
(6, 0, 8, 'fc6'),
(7, 0, 9, 'fc7'),
(8, 0, 57, 'VTRNTN80M01F839G'),
(11, 0, 64, 'fc10'),
(12, 0, 65, 'fc11'),
(13, 0, 66, 'fc10'),
(14, 0, 67, 'fc10'),
(15, 0, 68, 'fc10'),
(16, 0, 69, 'fc10'),
(17, 0, 70, 'fc10'),
(18, 0, 71, 'fc10'),
(19, -1, 72, 'noteacherfc1');

-- --------------------------------------------------------

--
-- Struttura della tabella `Terms`
--

CREATE TABLE `Terms` (
  `ID` int(11) NOT NULL,
  `LimitDay` date NOT NULL,
  `Stamp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Terms`
--

INSERT INTO `Terms` (`ID`, `LimitDay`, `Stamp`) VALUES
(1, '2019-12-21', '2019/2020 - 01'),
(2, '2019-06-14', '2018/2019 - 02');

-- --------------------------------------------------------

--
-- Struttura della tabella `Timetables`
--

CREATE TABLE `Timetables` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `DayOfWeek` int(11) NOT NULL,
  `HourSlot` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Timetables`
--

INSERT INTO `Timetables` (`ID`, `TeacherID`, `TopicID`, `SpecificClassID`, `DayOfWeek`, `HourSlot`) VALUES
(392, 18, 8, 2, 0, 0),
(393, 18, 8, 2, 1, 0),
(394, 18, 8, 2, 2, 0),
(395, 15, 5, 2, 3, 0),
(396, 13, 3, 2, 4, 0),
(397, 18, 8, 2, 0, 1),
(398, 18, 8, 2, 1, 1),
(399, 16, 6, 2, 2, 1),
(400, 14, 4, 2, 3, 1),
(401, 12, 2, 2, 4, 1),
(402, 17, 7, 2, 0, 2),
(403, 13, 3, 2, 1, 2),
(404, 16, 6, 2, 2, 2),
(405, 14, 4, 2, 3, 2),
(406, 12, 2, 2, 4, 2),
(407, 13, 3, 2, 0, 3),
(408, 17, 7, 2, 1, 3),
(409, 13, 3, 2, 2, 3),
(410, 13, 3, 2, 3, 3),
(411, 11, 1, 2, 4, 3),
(413, 18, 8, 2, 1, 4),
(414, 13, 3, 2, 2, 4),
(415, 13, 3, 2, 3, 4),
(416, 11, 1, 2, 4, 4),
(419, 15, 5, 2, 2, 5),
(422, 1, 1, 1, 0, 0),
(423, 3, 3, 1, 1, 0),
(424, 3, 3, 1, 2, 0),
(425, 3, 3, 1, 3, 0),
(426, 8, 8, 1, 4, 0),
(427, 1, 1, 1, 0, 1),
(428, 3, 3, 1, 1, 1),
(429, 3, 3, 1, 2, 1),
(430, 7, 7, 1, 3, 1),
(431, 8, 8, 1, 4, 1),
(432, 1, 2, 1, 0, 2),
(433, 4, 4, 1, 1, 2),
(434, 5, 5, 1, 2, 2),
(435, 8, 8, 1, 3, 2),
(436, 7, 7, 1, 4, 2),
(437, 1, 2, 1, 0, 3),
(438, 3, 3, 1, 1, 3),
(439, 6, 6, 1, 2, 3),
(440, 8, 8, 1, 3, 3),
(441, 3, 3, 1, 4, 3),
(442, 4, 4, 1, 0, 4),
(443, 5, 5, 1, 1, 4),
(444, 6, 6, 1, 2, 4),
(445, 8, 8, 1, 3, 4),
(449, 8, 8, 1, 2, 5);

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
(7, 'Music', 'Subject Description 7'),
(8, 'Literature', 'Subject Description 8'),
(9, 'Physical Education', 'Subject Description 9'),
(10, 'Philosophy', 'Subject Description 10');

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
(3, 1, '2019-12-02 07:00:00', 'Italy enters the first world war ', 1, 3),
(5, 1, '2019-12-02 11:00:00', 'The Scientific Revolution ', 1, 3),
(9, 2, '2019-12-01 23:00:00', 'Italy enters the first world war', 1, 2),
(10, 1, '2019-12-02 10:00:00', 'Fluid dynamics', 2, 1),
(12, 1, '2019-12-03 09:00:00', 'angular momentum', 2, 1),
(13, 7, '2019-12-01 23:00:00', 'Atoms', 4, 3),
(14, 7, '2019-12-01 23:00:00', 'Lagrangean Relaxation', 3, 3);

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
(5, 1, 2, 1),
(7, 2, 2, 1),
(8, 3, 3, 1),
(10, 4, 4, 1),
(11, 5, 5, 1),
(12, 6, 6, 1),
(13, 7, 7, 1),
(14, 8, 8, 1),
(16, 11, 1, 2),
(17, 12, 2, 2),
(18, 13, 3, 2),
(19, 14, 4, 2),
(20, 15, 5, 2),
(21, 16, 6, 2),
(22, 17, 7, 2),
(23, 18, 8, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `UploadedClassDocuments`
--

CREATE TABLE `UploadedClassDocuments` (
  `ID` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `DiskFileName` varchar(255) NOT NULL,
  `SpecificClassID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SubjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `UploadedClassDocuments`
--

INSERT INTO `UploadedClassDocuments` (`ID`, `FileName`, `DiskFileName`, `SpecificClassID`, `Description`, `Date`, `SubjectID`) VALUES
(3, 'emailSec.pdf', '8290566acbb0d5e1388dc6008275431a_emailSec.pdf', 1, 'Email security lioy', '2019-12-15 16:45:20', 2),
(4, 'Designers_Personas_Presentation_en.pptx', '3292103ac1749a4ec06dc15b592ef270_Designers_Personas_Presentation_en.pptx', 1, 'Personas Template', '2019-12-15 17:34:35', 1),
(5, 'HistoryNotes.txt', '4147fc3ec062e49b5f19c5a4c9078329_HistoryNotes.txt', 1, 'History Notes', '2019-12-16 12:11:52', 1),
(6, 'README.md', 'dc6ee570c4469821a4f4977fe7a4cf33_README.md', 2, 'This is a readme file', '2019-12-17 10:50:00', 1),
(7, 'Dockerfile', 'c4de739d81ce66af524a8d1eb4d7b945_Dockerfile', 1, 'M dockerfile', '2019-12-17 11:03:32', 2),
(9, 'Notes.txt', '5dc4c3e30a85be5a5099b423cd6361ac_Notes.txt', 1, 'notes ', '2019-12-17 14:51:39', 1);

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
(1, 'Mary', 'Smith', 'pns1a@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
(2, 'Joseph', 'Geller', 'pns2a@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'parent'),
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
(57, 'Antonio', 'Vetro', 'antonio.vetro@io.io', '$2y$12$wh/CWpnhPY/pQ2xzNLZMIemWcJ62UnNJv0omRDt5.Px8gFUp8rfim', 'teacher'),
(64, 'Mario', 'Baldi', 'mario.baldi@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(65, 'Lorenzo', 'Insigne', 'lorenzo.insigne@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(66, 'Luca', 'Ardito', 'luca.ardito@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(67, 'Rino', 'Gattuso', 'rino.gattuso@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(68, 'Marek', 'Hamsik', 'marek.hamsik@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(69, 'Maurizio', 'Rebaudengo', 'maurizio.rebaudengo@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(70, 'Paolo', 'Garza', 'paolo.garza@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(71, 'Bufalino', 'Stefania', 'stefania.bufalino@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher'),
(72, 'No', 'Teacher', 'NoTeacher@io.io', '$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher');

-- --------------------------------------------------------

--
-- Struttura della tabella `YearClass`
--

CREATE TABLE `YearClass` (
  `ID` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `TotHours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `YearClass`
--

INSERT INTO `YearClass` (`ID`, `Year`, `TotHours`) VALUES
(1, 1, 25),
(2, 2, 27),
(3, 3, 30);

-- --------------------------------------------------------

--
-- Struttura della tabella `YearTopicHour`
--

CREATE TABLE `YearTopicHour` (
  `ID` int(11) NOT NULL,
  `YearClassID` int(11) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `Hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `YearTopicHour`
--

INSERT INTO `YearTopicHour` (`ID`, `YearClassID`, `TopicID`, `Hours`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 2),
(3, 1, 3, 7),
(4, 1, 4, 2),
(5, 1, 5, 2),
(6, 1, 6, 2),
(7, 1, 7, 2),
(8, 1, 8, 6),
(9, 2, 1, 2),
(10, 2, 2, 3),
(11, 2, 3, 8),
(12, 2, 4, 2),
(13, 2, 5, 2),
(14, 2, 6, 2),
(15, 2, 7, 2),
(16, 2, 8, 6),
(17, 3, 1, 2),
(18, 3, 2, 3),
(19, 3, 3, 10),
(20, 3, 4, 3),
(21, 3, 5, 2),
(22, 3, 6, 2),
(23, 3, 7, 2),
(24, 3, 8, 6);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Communication`
--
ALTER TABLE `Communication`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OfficerID` (`OfficerID`);

--
-- Indici per le tabelle `FinalGrades`
--
ALTER TABLE `FinalGrades`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_finalgrades_studentID` (`StudentID`),
  ADD KEY `fk_finalgrades_topicID` (`TopicID`),
  ADD KEY `fk_finalgrades_termID` (`TermID`);

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
-- Indici per le tabelle `Note`
--
ALTER TABLE `Note`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `NoteRecord`
--
ALTER TABLE `NoteRecord`
  ADD PRIMARY KEY (`ID`);

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
-- Indici per le tabelle `Terms`
--
ALTER TABLE `Terms`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Timetables`
--
ALTER TABLE `Timetables`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `TeacherID_2` (`TeacherID`,`DayOfWeek`,`HourSlot`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `TopicID` (`TopicID`),
  ADD KEY `SpecificClassID` (`SpecificClassID`);

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
-- Indici per le tabelle `UploadedClassDocuments`
--
ALTER TABLE `UploadedClassDocuments`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `YearClass`
--
ALTER TABLE `YearClass`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `YearTopicHour`
--
ALTER TABLE `YearTopicHour`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `YearClassID` (`YearClassID`),
  ADD KEY `TopicID` (`TopicID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Communication`
--
ALTER TABLE `Communication`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT per la tabella `FinalGrades`
--
ALTER TABLE `FinalGrades`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `Homework`
--
ALTER TABLE `Homework`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT per la tabella `MarksRecord`
--
ALTER TABLE `MarksRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT per la tabella `Note`
--
ALTER TABLE `Note`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT per la tabella `NoteRecord`
--
ALTER TABLE `NoteRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT per la tabella `NotPresentRecord`
--
ALTER TABLE `NotPresentRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
--
-- AUTO_INCREMENT per la tabella `Officer`
--
ALTER TABLE `Officer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT per la tabella `Parent`
--
ALTER TABLE `Parent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT per la tabella `SpecificClass`
--
ALTER TABLE `SpecificClass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT per la tabella `Student`
--
ALTER TABLE `Student`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT per la tabella `Teacher`
--
ALTER TABLE `Teacher`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT per la tabella `Terms`
--
ALTER TABLE `Terms`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `Timetables`
--
ALTER TABLE `Timetables`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=452;
--
-- AUTO_INCREMENT per la tabella `Topic`
--
ALTER TABLE `Topic`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT per la tabella `TopicRecord`
--
ALTER TABLE `TopicRecord`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT per la tabella `TopicTeacherClass`
--
ALTER TABLE `TopicTeacherClass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT per la tabella `UploadedClassDocuments`
--
ALTER TABLE `UploadedClassDocuments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT per la tabella `User`
--
ALTER TABLE `User`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT per la tabella `YearClass`
--
ALTER TABLE `YearClass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT per la tabella `YearTopicHour`
--
ALTER TABLE `YearTopicHour`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Communication`
--
ALTER TABLE `Communication`
  ADD CONSTRAINT `fk_officerID2` FOREIGN KEY (`OfficerID`) REFERENCES `Officer` (`ID`);

--
-- Limiti per la tabella `FinalGrades`
--
ALTER TABLE `FinalGrades`
  ADD CONSTRAINT `fk_finalgrades_studentID` FOREIGN KEY (`StudentID`) REFERENCES `Student` (`ID`),
  ADD CONSTRAINT `fk_finalgrades_termID` FOREIGN KEY (`TermID`) REFERENCES `Terms` (`ID`),
  ADD CONSTRAINT `fk_finalgrades_topicID` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`);

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
  ADD CONSTRAINT `fk_specificClassID5` FOREIGN KEY (`SpecificClassID`) REFERENCES `SpecificClass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_studentID3` FOREIGN KEY (`StudentID`) REFERENCES `Student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Limiti per la tabella `Timetables`
--
ALTER TABLE `Timetables`
  ADD CONSTRAINT `fk_timetables_specificclass` FOREIGN KEY (`SpecificClassID`) REFERENCES `SpecificClass` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_timetables_teacher` FOREIGN KEY (`TeacherID`) REFERENCES `Teacher` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_timetables_topic` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`) ON DELETE CASCADE;

--
-- Limiti per la tabella `TopicTeacherClass`
--
ALTER TABLE `TopicTeacherClass`
  ADD CONSTRAINT `fk_specificclassID` FOREIGN KEY (`SpecificClassID`) REFERENCES `SpecificClass` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacherID2` FOREIGN KEY (`TeacherID`) REFERENCES `Teacher` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_topicID` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `YearTopicHour`
--
ALTER TABLE `YearTopicHour`
  ADD CONSTRAINT `fk_TopicID_YTH` FOREIGN KEY (`TopicID`) REFERENCES `Topic` (`ID`),
  ADD CONSTRAINT `fk_YearClassID_YTH` FOREIGN KEY (`YearClassID`) REFERENCES `YearClass` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
