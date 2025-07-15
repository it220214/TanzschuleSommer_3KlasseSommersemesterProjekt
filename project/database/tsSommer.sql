-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db_server
-- Erstellungszeit: 09. Jun 2025 um 14:08
-- Server-Version: 8.3.0
-- PHP-Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `tsSommer`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Course`
--

CREATE TABLE `Course` (
  `CourseId` int NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Level` varchar(50) NOT NULL,
  `Categorie` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `TeacherId` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Day` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  `RoomId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `Course`
--

INSERT INTO `Course` (`CourseId`, `Name`, `Level`, `Categorie`, `TeacherId`, `Day`, `StartTime`, `EndTime`, `RoomId`)
VALUES (1, 'Grundkurs', '01', 'Ballroom', 'LE0001', 'Monday', '15:30:00', '17:00:00', 2),
       (2, 'Fortgeschritten', '02', 'Ballroom', 'LE0008', 'Montag', '14:15:00', '15:15:00', 4),
       (3, 'Bronze', '03', 'Ballroom', 'LE0001', 'Montag', '09:00:00', '10:30:00', 1),
       (4, 'Silber', '04', 'Ballroom', 'LE0007', 'Montag', '10:45:00', '12:15:00', 1),
       (5, 'Gold', '05', 'Ballroom', 'LE0007', 'Mittwoch', '14:15:00', '15:15:00', 2),
       (6, 'Gold Star', '06', 'Ballroom', 'LE0004', 'Montag', '17:15:00', '18:45:00', 4),
       (7, 'Platinum', '07', 'Ballroom', 'LE0005', 'Dienstag', '19:00:00', '21:00:00', 3),
       (8, 'Technik Club', '08', 'Ballroom', 'LE0004', 'Montag', '12:30:00', '14:00:00', 3),
       (9, 'Ballett', '01', 'Classic', 'LE0001', 'Montag', '10:45:00', '12:15:00', 2),
       (10, 'Jazz', '01', 'Classic', 'LE0006', 'Montag', '12:30:00', '14:00:00', 4),
       (11, 'Contemporary', '01', 'Classic', 'LE0006', 'Montag', '09:00:00', '10:30:00', 1),
       (12, 'Ballett + Jazz', '02', 'Classic', 'LE0001', 'Montag', '17:15:00', '18:45:00', 1),
       (13, 'Hip Hop', '01', 'Urban', 'LE0004', 'Montag', '10:45:00', '12:15:00', 2),
       (14, 'House', '02', 'Urban', 'LE0002', 'Montag', '12:30:00', '14:00:00', 1),
       (15, 'Akrobatik', '03', 'Urban', 'LE0002', 'Montag', '10:45:00', '12:15:00', 1),
       (16, 'Level 1 - 3', '01', 'Lablast', 'LE0005', 'Montag', '09:00:00', '10:30:00', 4),
       (17, 'Level 4 - 6', '02', 'Lablast', 'LE0003', 'Montag', '12:30:00', '14:00:00', 3),
       (18, 'Level 7 - 9', '03', 'Lablast', 'LE0005', 'Montag', '14:15:00', '15:15:00', 3),
       (19, 'Pilates', '04', 'Lablast', 'LE0003', 'Montag', '15:30:00', '17:00:00', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `CourseSchedule`
--

CREATE TABLE `CourseSchedule`
(
    `ScheduleId` int         NOT NULL,
    `CourseId`   int         DEFAULT NULL,
    `Day`        varchar(20) DEFAULT NULL,
    `StartTime`  time        DEFAULT NULL,
    `EndTime`    time        DEFAULT NULL,
    `RoomId`     int         DEFAULT NULL,
    `TeacherId`  varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `CourseSchedule`
--

INSERT INTO `CourseSchedule` (`ScheduleId`, `CourseId`, `Day`, `StartTime`, `EndTime`, `RoomId`, `TeacherId`)
VALUES (569, 1, 'Dienstag', '09:00:00', '10:30:00', 1, 'LE0001'),
       (646, 1, 'Donnerstag', '09:00:00', '10:30:00', 4, 'LE0001'),
       (681, 1, 'Freitag', '09:00:00', '10:30:00', 1, 'LE0003'),
       (627, 1, 'Mittwoch', '15:30:00', '17:00:00', 1, 'LE0005'),
       (274, 1, 'Montag', '15:30:00', '17:00:00', 1, 'LE0001'),
       (739, 1, 'Samstag', '15:30:00', '17:00:00', 1, 'LE0005'),
       (575, 2, 'Dienstag', '10:45:00', '12:15:00', 1, 'LE0007'),
       (662, 2, 'Donnerstag', '14:15:00', '15:15:00', 4, 'LE0008'),
       (686, 2, 'Freitag', '10:45:00', '12:15:00', 1, 'LE0007'),
       (624, 2, 'Mittwoch', '14:15:00', '15:15:00', 4, 'LE0006'),
       (272, 2, 'Montag', '14:15:00', '15:15:00', 4, 'LE0008'),
       (736, 2, 'Samstag', '14:15:00', '15:15:00', 4, 'LE0008'),
       (579, 3, 'Dienstag', '12:30:00', '14:00:00', 1, 'LE0001'),
       (670, 3, 'Donnerstag', '17:15:00', '18:45:00', 1, 'LE0008'),
       (691, 3, 'Freitag', '12:30:00', '14:00:00', 1, 'LE0008'),
       (608, 3, 'Mittwoch', '09:00:00', '10:30:00', 4, 'LE0007'),
       (631, 3, 'Mittwoch', '15:30:00', '17:00:00', 4, 'LE0008'),
       (256, 3, 'Montag', '09:00:00', '10:30:00', 4, 'LE0001'),
       (278, 3, 'Montag', '15:30:00', '17:00:00', 4, 'LE0007'),
       (721, 3, 'Samstag', '09:00:00', '10:30:00', 4, 'LE0007'),
       (743, 3, 'Samstag', '15:30:00', '17:00:00', 4, 'LE0008'),
       (584, 4, 'Dienstag', '14:15:00', '15:15:00', 1, 'LE0005'),
       (650, 4, 'Donnerstag', '10:45:00', '12:15:00', 3, 'LE0007'),
       (696, 4, 'Freitag', '14:15:00', '15:15:00', 1, 'LE0005'),
       (611, 4, 'Mittwoch', '10:45:00', '12:15:00', 3, 'LE0007'),
       (632, 4, 'Mittwoch', '17:15:00', '18:45:00', 1, 'LE0004'),
       (260, 4, 'Montag', '10:45:00', '12:15:00', 3, 'LE0007'),
       (724, 4, 'Samstag', '10:45:00', '12:15:00', 3, 'LE0007'),
       (589, 5, 'Dienstag', '15:30:00', '17:00:00', 1, 'LE0002'),
       (659, 5, 'Donnerstag', '14:15:00', '15:15:00', 2, 'LE0007'),
       (701, 5, 'Freitag', '15:30:00', '17:00:00', 1, 'LE0002'),
       (615, 5, 'Mittwoch', '12:30:00', '14:00:00', 1, 'LE0005'),
       (269, 5, 'Montag', '14:15:00', '15:15:00', 2, 'LE0007'),
       (733, 5, 'Samstag', '14:15:00', '15:15:00', 2, 'LE0007'),
       (594, 6, 'Dienstag', '17:15:00', '18:45:00', 1, 'LE0003'),
       (664, 6, 'Donnerstag', '15:30:00', '17:00:00', 1, 'LE0004'),
       (707, 6, 'Freitag', '17:15:00', '18:45:00', 1, 'LE0008'),
       (621, 6, 'Mittwoch', '14:15:00', '15:15:00', 2, 'LE0004'),
       (280, 6, 'Montag', '17:15:00', '18:45:00', 1, 'LE0004'),
       (744, 6, 'Samstag', '17:15:00', '18:45:00', 1, 'LE0004'),
       (599, 7, 'Dienstag', '19:00:00', '21:00:00', 1, 'LE0006'),
       (675, 7, 'Donnerstag', '19:00:00', '21:00:00', 1, 'LE0007'),
       (712, 7, 'Freitag', '19:00:00', '21:00:00', 1, 'LE0006'),
       (638, 7, 'Mittwoch', '19:00:00', '21:00:00', 1, 'LE0007'),
       (285, 7, 'Montag', '19:00:00', '21:00:00', 1, 'LE0005'),
       (750, 7, 'Samstag', '19:00:00', '21:00:00', 1, 'LE0007'),
       (591, 8, 'Dienstag', '15:30:00', '17:00:00', 2, 'LE0005'),
       (601, 8, 'Dienstag', '19:00:00', '21:00:00', 2, 'LE0003'),
       (655, 8, 'Donnerstag', '12:30:00', '14:00:00', 2, 'LE0008'),
       (676, 8, 'Donnerstag', '19:00:00', '21:00:00', 2, 'LE0004'),
       (703, 8, 'Freitag', '15:30:00', '17:00:00', 2, 'LE0005'),
       (714, 8, 'Freitag', '19:00:00', '21:00:00', 2, 'LE0003'),
       (616, 8, 'Mittwoch', '12:30:00', '14:00:00', 2, 'LE0006'),
       (639, 8, 'Mittwoch', '19:00:00', '21:00:00', 2, 'LE0004'),
       (264, 8, 'Montag', '12:30:00', '14:00:00', 2, 'LE0004'),
       (287, 8, 'Montag', '19:00:00', '21:00:00', 2, 'LE0004'),
       (729, 8, 'Samstag', '12:30:00', '14:00:00', 2, 'LE0008'),
       (751, 8, 'Samstag', '19:00:00', '21:00:00', 2, 'LE0004'),
       (588, 9, 'Dienstag', '10:45:00', '12:15:00', 2, 'LE0007'),
       (595, 9, 'Dienstag', '17:15:00', '18:45:00', 2, 'LE0001'),
       (649, 9, 'Donnerstag', '10:45:00', '12:15:00', 2, 'LE0001'),
       (673, 9, 'Donnerstag', '17:15:00', '18:45:00', 4, 'LE0001'),
       (687, 9, 'Freitag', '10:45:00', '12:15:00', 2, 'LE0004'),
       (708, 9, 'Freitag', '17:15:00', '18:45:00', 2, 'LE0001'),
       (610, 9, 'Mittwoch', '10:45:00', '12:15:00', 2, 'LE0002'),
       (259, 9, 'Montag', '10:45:00', '12:15:00', 2, 'LE0001'),
       (723, 9, 'Samstag', '10:45:00', '12:15:00', 2, 'LE0001'),
       (572, 10, 'Dienstag', '09:00:00', '10:30:00', 3, 'LE0006'),
       (587, 10, 'Dienstag', '14:15:00', '15:15:00', 4, 'LE0006'),
       (653, 10, 'Donnerstag', '12:30:00', '14:00:00', 1, 'LE0006'),
       (668, 10, 'Donnerstag', '15:30:00', '17:00:00', 4, 'LE0006'),
       (682, 10, 'Freitag', '09:00:00', '10:30:00', 2, 'LE0006'),
       (699, 10, 'Freitag', '14:15:00', '15:15:00', 4, 'LE0006'),
       (605, 10, 'Mittwoch', '09:00:00', '10:30:00', 2, 'LE0006'),
       (629, 10, 'Mittwoch', '15:30:00', '17:00:00', 3, 'LE0006'),
       (263, 10, 'Montag', '12:30:00', '14:00:00', 1, 'LE0006'),
       (277, 10, 'Montag', '15:30:00', '17:00:00', 3, 'LE0006'),
       (727, 10, 'Samstag', '12:30:00', '14:00:00', 1, 'LE0006'),
       (741, 10, 'Samstag', '15:30:00', '17:00:00', 3, 'LE0006'),
       (580, 11, 'Dienstag', '12:30:00', '14:00:00', 2, 'LE0002'),
       (597, 11, 'Dienstag', '17:15:00', '18:45:00', 4, 'LE0006'),
       (645, 11, 'Donnerstag', '09:00:00', '10:30:00', 3, 'LE0006'),
       (666, 11, 'Donnerstag', '15:30:00', '17:00:00', 2, 'LE0006'),
       (683, 11, 'Freitag', '09:00:00', '10:30:00', 3, 'LE0004'),
       (692, 11, 'Freitag', '12:30:00', '14:00:00', 2, 'LE0002'),
       (710, 11, 'Freitag', '17:15:00', '18:45:00', 4, 'LE0006'),
       (606, 11, 'Mittwoch', '09:00:00', '10:30:00', 3, 'LE0004'),
       (634, 11, 'Mittwoch', '17:15:00', '18:45:00', 2, 'LE0006'),
       (255, 11, 'Montag', '09:00:00', '10:30:00', 3, 'LE0006'),
       (282, 11, 'Montag', '17:15:00', '18:45:00', 2, 'LE0006'),
       (719, 11, 'Samstag', '09:00:00', '10:30:00', 3, 'LE0006'),
       (746, 11, 'Samstag', '17:15:00', '18:45:00', 2, 'LE0006'),
       (585, 12, 'Dienstag', '14:15:00', '15:15:00', 2, 'LE0007'),
       (658, 12, 'Donnerstag', '14:15:00', '15:15:00', 1, 'LE0001'),
       (697, 12, 'Freitag', '14:15:00', '15:15:00', 2, 'LE0007'),
       (620, 12, 'Mittwoch', '14:15:00', '15:15:00', 1, 'LE0007'),
       (635, 12, 'Mittwoch', '17:15:00', '18:45:00', 3, 'LE0001'),
       (268, 12, 'Montag', '14:15:00', '15:15:00', 1, 'LE0001'),
       (283, 12, 'Montag', '17:15:00', '18:45:00', 3, 'LE0001'),
       (732, 12, 'Samstag', '14:15:00', '15:15:00', 1, 'LE0001'),
       (747, 12, 'Samstag', '17:15:00', '18:45:00', 3, 'LE0001'),
       (582, 13, 'Dienstag', '12:30:00', '14:00:00', 4, 'LE0006'),
       (603, 13, 'Dienstag', '19:00:00', '21:00:00', 4, 'LE0001'),
       (648, 13, 'Donnerstag', '10:45:00', '12:15:00', 1, 'LE0004'),
       (694, 13, 'Freitag', '12:30:00', '14:00:00', 4, 'LE0006'),
       (716, 13, 'Freitag', '19:00:00', '21:00:00', 4, 'LE0001'),
       (609, 13, 'Mittwoch', '10:45:00', '12:15:00', 1, 'LE0005'),
       (636, 13, 'Mittwoch', '17:15:00', '18:45:00', 4, 'LE0002'),
       (258, 13, 'Montag', '10:45:00', '12:15:00', 1, 'LE0004'),
       (284, 13, 'Montag', '17:15:00', '18:45:00', 4, 'LE0002'),
       (722, 13, 'Samstag', '10:45:00', '12:15:00', 1, 'LE0004'),
       (748, 13, 'Samstag', '17:15:00', '18:45:00', 4, 'LE0002'),
       (578, 14, 'Dienstag', '10:45:00', '12:15:00', 4, 'LE0006'),
       (593, 14, 'Dienstag', '15:30:00', '17:00:00', 4, 'LE0006'),
       (657, 14, 'Donnerstag', '12:30:00', '14:00:00', 4, 'LE0002'),
       (679, 14, 'Donnerstag', '19:00:00', '21:00:00', 4, 'LE0002'),
       (689, 14, 'Freitag', '10:45:00', '12:15:00', 4, 'LE0006'),
       (705, 14, 'Freitag', '15:30:00', '17:00:00', 4, 'LE0006'),
       (619, 14, 'Mittwoch', '12:30:00', '14:00:00', 4, 'LE0002'),
       (642, 14, 'Mittwoch', '19:00:00', '21:00:00', 4, 'LE0006'),
       (267, 14, 'Montag', '12:30:00', '14:00:00', 4, 'LE0002'),
       (290, 14, 'Montag', '19:00:00', '21:00:00', 4, 'LE0002'),
       (731, 14, 'Samstag', '12:30:00', '14:00:00', 4, 'LE0002'),
       (754, 14, 'Samstag', '19:00:00', '21:00:00', 4, 'LE0002'),
       (573, 15, 'Dienstag', '09:00:00', '10:30:00', 4, 'LE0002'),
       (596, 15, 'Dienstag', '17:15:00', '18:45:00', 3, 'LE0002'),
       (652, 15, 'Donnerstag', '10:45:00', '12:15:00', 4, 'LE0002'),
       (671, 15, 'Donnerstag', '17:15:00', '18:45:00', 2, 'LE0002'),
       (684, 15, 'Freitag', '09:00:00', '10:30:00', 4, 'LE0002'),
       (709, 15, 'Freitag', '17:15:00', '18:45:00', 3, 'LE0002'),
       (613, 15, 'Mittwoch', '10:45:00', '12:15:00', 4, 'LE0004'),
       (262, 15, 'Montag', '10:45:00', '12:15:00', 4, 'LE0002'),
       (726, 15, 'Samstag', '10:45:00', '12:15:00', 4, 'LE0002'),
       (571, 16, 'Dienstag', '09:00:00', '10:30:00', 2, 'LE0005'),
       (592, 16, 'Dienstag', '15:30:00', '17:00:00', 3, 'LE0007'),
       (644, 16, 'Donnerstag', '09:00:00', '10:30:00', 2, 'LE0005'),
       (667, 16, 'Donnerstag', '15:30:00', '17:00:00', 3, 'LE0007'),
       (704, 16, 'Freitag', '15:30:00', '17:00:00', 3, 'LE0007'),
       (604, 16, 'Mittwoch', '09:00:00', '10:30:00', 1, 'LE0003'),
       (254, 16, 'Montag', '09:00:00', '10:30:00', 2, 'LE0005'),
       (718, 16, 'Samstag', '09:00:00', '10:30:00', 2, 'LE0005'),
       (577, 17, 'Dienstag', '10:45:00', '12:15:00', 3, 'LE0005'),
       (656, 17, 'Donnerstag', '12:30:00', '14:00:00', 3, 'LE0003'),
       (688, 17, 'Freitag', '10:45:00', '12:15:00', 3, 'LE0005'),
       (618, 17, 'Mittwoch', '12:30:00', '14:00:00', 3, 'LE0003'),
       (641, 17, 'Mittwoch', '19:00:00', '21:00:00', 3, 'LE0003'),
       (266, 17, 'Montag', '12:30:00', '14:00:00', 3, 'LE0003'),
       (730, 17, 'Samstag', '12:30:00', '14:00:00', 3, 'LE0003'),
       (581, 18, 'Dienstag', '12:30:00', '14:00:00', 3, 'LE0003'),
       (661, 18, 'Donnerstag', '14:15:00', '15:15:00', 3, 'LE0005'),
       (678, 18, 'Donnerstag', '19:00:00', '21:00:00', 3, 'LE0003'),
       (693, 18, 'Freitag', '12:30:00', '14:00:00', 3, 'LE0003'),
       (623, 18, 'Mittwoch', '14:15:00', '15:15:00', 3, 'LE0003'),
       (271, 18, 'Montag', '14:15:00', '15:15:00', 3, 'LE0005'),
       (289, 18, 'Montag', '19:00:00', '21:00:00', 3, 'LE0003'),
       (735, 18, 'Samstag', '14:15:00', '15:15:00', 3, 'LE0005'),
       (753, 18, 'Samstag', '19:00:00', '21:00:00', 3, 'LE0003'),
       (586, 19, 'Dienstag', '14:15:00', '15:15:00', 3, 'LE0003'),
       (602, 19, 'Dienstag', '19:00:00', '21:00:00', 3, 'LE0004'),
       (643, 19, 'Donnerstag', '09:00:00', '10:30:00', 1, 'LE0003'),
       (672, 19, 'Donnerstag', '17:15:00', '18:45:00', 3, 'LE0003'),
       (698, 19, 'Freitag', '14:15:00', '15:15:00', 3, 'LE0003'),
       (715, 19, 'Freitag', '19:00:00', '21:00:00', 3, 'LE0004'),
       (628, 19, 'Mittwoch', '15:30:00', '17:00:00', 2, 'LE0003'),
       (253, 19, 'Montag', '09:00:00', '10:30:00', 1, 'LE0003'),
       (276, 19, 'Montag', '15:30:00', '17:00:00', 2, 'LE0003'),
       (717, 19, 'Samstag', '09:00:00', '10:30:00', 1, 'LE0003'),
       (740, 19, 'Samstag', '15:30:00', '17:00:00', 2, 'LE0003');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Room`
--

CREATE TABLE `Room`
(
    `RoomId` int         NOT NULL,
    `Name`   varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Teacher`
--

CREATE TABLE `Teacher` (
  `TeacherId` varchar(10) NOT NULL,
  `FirstName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `SurName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Experience` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `CourseId` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `Teacher`
--

INSERT INTO `Teacher` (`TeacherId`, `FirstName`, `SurName`, `Email`, `Password`, `Experience`, `CourseId`) VALUES
('LE0001', 'Katharina', 'Sommer', 'k.sommer@gmail.com', NULL, NULL, 8),
('LE0002', 'Matthias', 'Sommer', 'm.sommer@gmail.com', NULL, NULL, NULL),
('LE0003', 'Nina', 'Keller', 'n.keller@gmail.com', NULL, NULL, 16),
('LE0004', 'Julian', 'Huber', 'j.huber@gmail.com', NULL, NULL, NULL),
('LE0005', 'Lisa', 'Graf', 'l.graf@gmail.com', NULL, NULL, NULL),
('LE0006', 'Tobias', 'Lenz', 't.lenz@gmail.com', NULL, NULL, NULL),
('LE0007', 'Ana', 'Balladanca', 'a.balladanca@gmail.com', NULL, 'Ballett, Jazz, Contemporary', 9);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

CREATE TABLE `User` (
  `UserId` varchar(10) NOT NULL,
  `FirstName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `SurName` text NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Level` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ProfileImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `User`
--

INSERT INTO `User` (`UserId`, `FirstName`, `SurName`, `Email`, `Password`, `Level`, `ProfileImage`)
VALUES
('TE0001', 'max', 'musterman', 'm.mus@d.com', '$2y$10$zUbRZmP9iyUsfn0F8iCs8OCU96goLhSG1dUyNuAZY5Rm3cPLY68BW', ' ', NULL),
('TE0002', 'k', 'km', 'km@fd.com', '$2y$10$hzWirdPpjJS.H9nVCcA1HeS821k31zFTmBOGSG3XqHolS69HVs4Y.', ' ', NULL),
('TE0003', 'eins', 'eins', 'eins.eins@eins.com', '$2y$10$a2mPZWgLdTJ/zyKrDWGoveh89RjCi.wPiD0gHo2XgQ/baW5.8LpIq', ' ', NULL),
('TE0004', 'ffökdsf', 'fkldsfj', 'fdalsfjLs@adf.cof', '$2y$10$eRP1D68Pj5isgbR9Ii4Mjece34gwHDw3RDlJSxkDoWwktq7GjS1MO', ' ', NULL),
('TE0005', 'ceec', ' cd', 'd@jmu.uu', '$2y$10$Df5jWe.qy/zUXYehDOmDMe9fP65xhf.erTsnQ6cw3T56SO/2LGesa', ' ', NULL),
('TE0006', 'Carla', 'test', 'test@test.test', '$2y$10$O.rpjP.u4qNEOuR4d/pR4e.wp/sHAyLcWKGZ/gxRCUI.XsIavLpfy', ' ', NULL),
('TE0007', 'e', 'e', 'e@e.e', '$2y$10$EOXWExEoQXsjUIPU5gktkenB7VFjC1Y4oMISIP0VfdcuOOJjQtHbS', ' ',
 'uploads/profile_TE0007_1749477897.png');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `UserCourse`
--

CREATE TABLE `UserCourse` (
  `UserId` varchar(10) NOT NULL,
  `CourseId` int NOT NULL,
  `EnrolledAt` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `UserCourse`
--

INSERT INTO `UserCourse` (`UserId`, `CourseId`, `EnrolledAt`)
VALUES ('TE0006', 3, '2025-05-18 10:10:42'),
       ('TE0007', 1, '2025-05-27 08:11:00');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Course`
--
ALTER TABLE `Course`
  ADD PRIMARY KEY (`CourseId`),
  ADD KEY `CourseId` (`CourseId`),
  ADD KEY `TeacherId` (`TeacherId`),
  ADD KEY `RoomId` (`RoomId`);

--
-- Indizes für die Tabelle `CourseSchedule`
--
ALTER TABLE `CourseSchedule`
    ADD PRIMARY KEY (`ScheduleId`),
  ADD UNIQUE KEY `unique_schedule` (`CourseId`,`Day`,`StartTime`,`EndTime`,`RoomId`,`TeacherId`);

--
-- Indizes für die Tabelle `Teacher`
--
ALTER TABLE `Teacher`
  ADD PRIMARY KEY (`TeacherId`),
  ADD KEY `TeacherId` (`TeacherId`),
  ADD KEY `CourseId` (`CourseId`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `UserId_2` (`UserId`),
  ADD UNIQUE KEY `UserId_5` (`UserId`),
  ADD UNIQUE KEY `UserId_6` (`UserId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `UserId_3` (`UserId`),
  ADD KEY `UserId_7` (`UserId`);
ALTER TABLE `User` ADD FULLTEXT KEY `UserId_4` (`UserId`);

--
-- Indizes für die Tabelle `UserCourse`
--
ALTER TABLE `UserCourse`
  ADD PRIMARY KEY (`UserId`,`CourseId`),
  ADD KEY `CourseId` (`CourseId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Course`
--
ALTER TABLE `Course`
  MODIFY `CourseId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `CourseSchedule`
--
ALTER TABLE `CourseSchedule`
    MODIFY `ScheduleId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=755;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `CourseSchedule`
--
ALTER TABLE `CourseSchedule`
    ADD CONSTRAINT `CourseSchedule_ibfk_1` FOREIGN KEY (`CourseId`) REFERENCES `Course` (`CourseId`);

--
-- Constraints der Tabelle `UserCourse`
--
ALTER TABLE `UserCourse`
  ADD CONSTRAINT `UserCourse_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `User` (`UserId`),
  ADD CONSTRAINT `UserCourse_ibfk_2` FOREIGN KEY (`CourseId`) REFERENCES `Course` (`CourseId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
