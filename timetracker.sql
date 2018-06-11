-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 21, 2018 at 03:17 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timetracker`
--
CREATE DATABASE IF NOT EXISTS `timetracker` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `timetracker`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `SP_adminUpdatePW`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_adminUpdatePW` (IN `username` VARCHAR(50), IN `pw` VARCHAR(60))  NO SQL
UPDATE administrator
SET administrator.password = pw
WHERE administrator.name = username$$

DROP PROCEDURE IF EXISTS `SP_BulkInsert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_BulkInsert` (IN `id` INT(8), IN `fname` VARCHAR(50), IN `lname` VARCHAR(50), IN `pass` VARCHAR(60))  NO SQL
INSERT into users (users.student_id, users.first, users.last, users.password) VALUES (id, fname, lname, pass)$$

DROP PROCEDURE IF EXISTS `SP_deleteTime`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_deleteTime` (IN `rowid` INT)  NO SQL
DELETE FROM time WHERE time.entryID = rowid$$

DROP PROCEDURE IF EXISTS `SP_DeleteUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DeleteUser` (IN `id` INT(8))  NO SQL
DELETE from users WHERE users.student_id = id$$

DROP PROCEDURE IF EXISTS `SP_dropUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_dropUser` (IN `uid` INT(8))  NO SQL
DELETE FROM users where users.student_id = uid$$

DROP PROCEDURE IF EXISTS `SP_finalize`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_finalize` (IN `id` INT(7))  NO SQL
UPDATE time SET time.finalized = 1 WHERE time.student_id = id$$

DROP PROCEDURE IF EXISTS `SP_getAdmin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_getAdmin` (IN `id` VARCHAR(8))  NO SQL
SELECT * FROM administrator where administrator.name = id$$

DROP PROCEDURE IF EXISTS `SP_getAllUsers`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_getAllUsers` ()  NO SQL
SELECT * FROM users$$

DROP PROCEDURE IF EXISTS `SP_getGroups`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_getGroups` ()  NO SQL
SELECT * FROM teams$$

DROP PROCEDURE IF EXISTS `SP_getPassword`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_getPassword` (IN `id` INT(8))  NO SQL
SELECT users.password from users WHERE users.student_id = id$$

DROP PROCEDURE IF EXISTS `SP_getStudentID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_getStudentID` (IN `id` INT(8))  NO SQL
SELECT users.student_id from users WHERE users.student_id = id$$

DROP PROCEDURE IF EXISTS `SP_getTimes`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_getTimes` (IN `id` INT(7))  NO SQL
SELECT * from time WHERE time.student_id = id AND time.finalized IS NULL ORDER BY time.date$$

DROP PROCEDURE IF EXISTS `SP_insertHours`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_insertHours` (IN `vtask` VARCHAR(255), IN `vhours` INT(2), IN `vUser` INT(7), IN `vDate` DATE, IN `vPaid` VARCHAR(10))  NO SQL
INSERT INTO time (time.description, time.hours, time.student_id, time.date, time.pay_type) VALUES (vtask, vhours, vUser, vDate, vPaid)$$

DROP PROCEDURE IF EXISTS `SP_insertStudent`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_insertStudent` (IN `id` INT(8), IN `fname` VARCHAR(50), IN `lname` VARCHAR(50), IN `pass` VARCHAR(60))  NO SQL
INSERT into users (users.student_id, users.first, users.last, users.password) VALUES (id, fname, lname, pass)$$

DROP PROCEDURE IF EXISTS `SP_runDateReport`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_runDateReport` (IN `startdate` DATETIME, IN `enddate` DATETIME)  NO SQL
SELECT time.date as date, time.student_id, time.hours, time.pay_type, users.first, users.last
FROM time 
INNER JOIN users ON time.student_id = users.student_id
WHERE (time.date >= startdate AND time.date <= enddate) 
ORDER BY time.student_id, time.date$$

DROP PROCEDURE IF EXISTS `SP_selectName`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_selectName` (IN `id` INT(8))  NO SQL
SELECT users.first, users.last FROM users where users.student_id = id$$

DROP PROCEDURE IF EXISTS `SP_updatePassword`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_updatePassword` (IN `id` INT(8), IN `pw` VARCHAR(60))  NO SQL
UPDATE users 
SET users.password = pw 
WHERE users.student_id = id$$

DROP PROCEDURE IF EXISTS `SP_updateTime`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_updateTime` (IN `id` INT(8), IN `vdate` VARCHAR(50), IN `vhours` INT(2), IN `vDesc` VARCHAR(255), IN `vPayType` VARCHAR(25))  NO SQL
UPDATE time 
SET 
time.date = vdate,
time.hours = vhours,
time.description = vDesc,
time.pay_type = vPayType
WHERE time.entryID = id$$

DROP PROCEDURE IF EXISTS `SP_wipeUsers`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_wipeUsers` ()  DELETE FROM users$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `admin_id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(80) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `administrator`
--

TRUNCATE TABLE `administrator`;
--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`admin_id`, `name`, `password`) VALUES
(1, 'admin', '$2y$10$E/b.m4s.iwHxrM3CMNpPAeGw/qxiFVnM6CIscWSWZOL1xDcnJ1VCi');

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

DROP TABLE IF EXISTS `time`;
CREATE TABLE IF NOT EXISTS `time` (
  `entryID` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(8) NOT NULL,
  `date` datetime NOT NULL,
  `hours` decimal(5,0) NOT NULL,
  `description` varchar(255) NOT NULL,
  `pay_type` varchar(25) NOT NULL,
  `finalized` int(1) DEFAULT NULL,
  PRIMARY KEY (`entryID`),
  KEY `student_id` (`student_id`),
  KEY `pay_type` (`pay_type`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `time`
--

TRUNCATE TABLE `time`;
--
-- Dumping data for table `time`
--

INSERT INTO `time` (`entryID`, `student_id`, `date`, `hours`, `description`, `pay_type`, `finalized`) VALUES
(25, 4011, '2018-03-12 00:00:00', '5', 'test1', 'PAID', 1),
(26, 4011, '2018-03-13 00:00:00', '5', 'test2', 'PAID', 1),
(27, 4011, '2018-03-14 00:00:00', '5', 'test3', 'PAID', 1),
(28, 4011, '2018-03-15 00:00:00', '5', 'test4', 'PAID', 1),
(29, 4011, '2018-03-16 00:00:00', '5', 'test5', 'PAID', 1),
(30, 4011, '2018-03-12 00:00:00', '5', 'test4', 'PAID', 1),
(31, 4066, '2018-03-12 00:00:00', '10', 'test1', 'PAID', 1),
(32, 4066, '2018-03-13 00:00:00', '10', 'test2', 'PAID', 1),
(33, 4066, '2018-03-14 00:00:00', '10', 'test4', 'PAID', 1),
(34, 4033, '2018-03-12 00:00:00', '12', 'asdfasdf', 'PAID', 1),
(35, 4033, '2018-03-13 00:00:00', '5', 'asdfasdfa', 'UNPAID', 1),
(36, 4033, '2018-03-15 00:00:00', '12', 'test', 'HOLIDAY', 1),
(37, 4033, '2018-03-22 00:00:00', '4', 'aaaaaa', 'SICK', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `student_id` int(8) NOT NULL,
  `first` varchar(50) NOT NULL,
  `last` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`student_id`, `first`, `last`, `password`) VALUES
(4011, 'Bradley', 'Butler', '$2y$10$gaJGOon9LmYF4qxQwbAVPOs5XpAZOWcK.mMe65GTh1BRZZP.0kdji'),
(4022, 'Brian', 'Nason', '$2y$10$KtefWykWG5QpGq7ta4XVzu1DXMQ/d6cF4vkHeJEsHD/NAl5fF6Yeq'),
(4033, 'Christian', 'Monteith', '$2y$10$y5I.6tvgOUAgqDQRsZb/euEIUCUkYWUnG1n.NmNCfZtWAm2dKD0J.'),
(4044, 'Christopher', 'Pickard', '$2y$10$dg6pLiDG0XM1HOWSHQPS0.vtgdiwS7KnHfS5YcGFLV2Q2g9ySYCeO'),
(4055, 'Gabriel', 'Rodriguez', '$2y$10$/tLHzqrv2wJQicGuv.NJ..tKoL0hcUzU8eI/OjQFqZsonE5bBXYqi'),
(4066, 'Jed', 'Palmater', '$2y$10$Anh7xsuoXOpT234QEo54yequCggJmLCXYGMG1YbgcI/h55jXalsN6'),
(4077, 'Jeff', 'Hooper', '$2y$10$2zucsT/mHJHMl74ccXI45.CSxD0Qu.uZNQHOUmDSKeo61ewnTVmLy'),
(4088, 'Kyle', 'Hurley', '$2y$10$KY61N5PSF1Qzc/b8Dev31OsOsoINT9X8tAdlUa/3AmCtuVMg/o4bm'),
(4099, 'Luke', 'Appleby', '$2y$10$pDUSptGhy3BxAmOw2quq..T7aLSfBU78ZQ7wVgxCwSHX5P9dKz5fy'),
(5010, 'Mark', 'Patterson', '$2y$10$98Y6N8gF/1Vk0jceaALnSOEVkYFfHHVFA7E0pgwKPVk3kI.pGzSAy'),
(5020, 'Matthew', 'Jones', '$2y$10$l.1yqqdij6Ji4jrLZAbwju70TNc.RSARejcanPhDbbs03a38imurq'),
(5030, 'Peter', 'Groom', '$2y$10$maOU/AHvTma4jh3w66x9EumuQqTTBrKLTw1jiahxCPmyonfJD2pP2'),
(5040, 'Sam', 'Brown', '$2y$10$RTDnqmPeisJUJznqUFmXD.tFmC3B1gH2m3.ePWSJvVv3PuslXS1cy'),
(5050, 'Shelby', 'Cleghorn', '$2y$10$Qt63eB9EFLsC7A5JqIafn.HjVtY5D2pTQ1b3Pi3wV2oV83XQhqASm');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `time`
--
ALTER TABLE `time`
  ADD CONSTRAINT `time_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
