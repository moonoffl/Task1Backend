-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 03:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college`
--

-- --------------------------------------------------------

--
-- Table structure for table `course_details`
--

CREATE TABLE `course_details` (
  `Course_Id` int(11) NOT NULL,
  `Course_Code` varchar(10) NOT NULL,
  `Course_Name` varchar(50) NOT NULL,
  `Course_Duration` varchar(5) NOT NULL,
  `Added_By` int(10) DEFAULT NULL,
  `Update_By` int(10) DEFAULT NULL,
  `Add_Date_Time` datetime(5) DEFAULT NULL,
  `Update_Date_Time` datetime(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `course_details`
--

INSERT INTO `course_details` (`Course_Id`, `Course_Code`, `Course_Name`, `Course_Duration`, `Added_By`, `Update_By`, `Add_Date_Time`, `Update_Date_Time`) VALUES
(1, 'C1000', 'CS', '24', 7, 7, NULL, '2026-01-22 10:15:35.00000'),
(2, 'C1001', 'IT', '12', NULL, 7, NULL, '2026-01-22 06:51:36.00000'),
(5, 'C1002', 'ECE', '12', NULL, NULL, NULL, NULL),
(7, 'C1003', 'EEE', '12', NULL, NULL, NULL, NULL),
(8, 'C1004', 'CIVIL', '12', NULL, NULL, NULL, NULL),
(10, 'C1005', 'Mech', '24', NULL, NULL, NULL, NULL),
(11, 'C1212', 'aaa', '12', NULL, NULL, NULL, NULL),
(12, 'C1006', 'CS', '24', 7, NULL, '2026-01-22 08:10:29.00000', NULL),
(13, 'C1007', 'CS', '24', 7, NULL, '2026-01-22 10:15:44.00000', NULL),
(14, 'C1009', 'BCA', '36', 7, 7, '2026-01-22 10:21:32.00000', '2026-01-22 10:22:16.00000');

-- --------------------------------------------------------

--
-- Table structure for table `educational_details`
--

CREATE TABLE `educational_details` (
  `Educational_Id` int(11) NOT NULL,
  `Member_Id` int(11) DEFAULT NULL,
  `Degree` varchar(20) NOT NULL,
  `Institute` text NOT NULL,
  `RegNo` varchar(20) NOT NULL,
  `Year` year(4) NOT NULL,
  `Percentage` int(10) NOT NULL,
  `Class` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `educational_details`
--

INSERT INTO `educational_details` (`Educational_Id`, `Member_Id`, `Degree`, `Institute`, `RegNo`, `Year`, `Percentage`, `Class`) VALUES
(69, 62, 'PG', 'aaaaaaaaaaaaaaaaaaa', '1212125', '0000', 12, 'I class'),
(70, 62, 'UG', 'aaaaaaaaaaaaaaaaaaa', '1212126', '0000', 12, 'III class'),
(82, 63, 'UG', 'aaaaaaaaaaaaaaaaaaa', '12343438', '2026', 12, 'I class with distinc'),
(83, 46, 'UG', 'aaaaaaaaaaaaaaaaaaa', '1212121', '0000', 12, 'I class with distinc'),
(84, 46, 'PG', 'aaaaaaaaaaaaaaaaaaa', '12347776', '2003', 23, 'I class with distinc'),
(85, 60, 'UG', 'aaaaaaaaaaaaaaaaaaa', '1212143', '0000', 12, 'I class with distinc'),
(86, 60, 'PG', 'aaaaaaaaaaaaaaaaaaa', '1212145', '0000', 12, 'II class'),
(88, 64, 'UG', 'aaaaaaaaaaaaaaaaaaa', '1212134', '2026', 12, 'I class with distinc');

-- --------------------------------------------------------

--
-- Table structure for table `member_details`
--

CREATE TABLE `member_details` (
  `Member_Id` int(11) NOT NULL,
  `Member_Code` varchar(50) DEFAULT NULL,
  `Member_Name` varchar(50) NOT NULL,
  `Number` varchar(20) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `DOB` date NOT NULL,
  `Course_Id` int(11) NOT NULL,
  `DOJ` date NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `Image` varchar(50) NOT NULL,
  `Added_By` int(10) DEFAULT NULL,
  `Update_By` int(10) DEFAULT NULL,
  `Update_Date_Time` datetime(5) DEFAULT NULL,
  `Add_Date_Time` datetime(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `member_details`
--

INSERT INTO `member_details` (`Member_Id`, `Member_Code`, `Member_Name`, `Number`, `Email`, `DOB`, `Course_Id`, `DOJ`, `Gender`, `Image`, `Added_By`, `Update_By`, `Update_Date_Time`, `Add_Date_Time`) VALUES
(46, 'M1004', 'Anand S', '9666666665', 'fgdfgdfgdfg@outlook.com', '2003-08-12', 2, '2026-01-01', 'Male', '1769229523_download (1).jpg', NULL, 7, '2026-01-24 08:12:51.00000', NULL),
(60, 'M1001', 'Anand S', '9666666665', 'fgdfgdfgdfg@outlook.com', '2003-08-12', 12, '2026-01-01', 'Male', '1769239044_download.jpg', 7, 7, '2026-01-24 08:17:24.00000', '2026-01-24 05:43:41.00000'),
(62, 'M1000', 'Anand S', '9666666665', 'fgdfgdfgdfg@outlook.com', '2003-08-12', 5, '2026-01-01', 'Female', '1769229961_download (1).jpg', 7, 7, '2026-01-24 05:55:21.00000', '2026-01-24 05:44:34.00000'),
(63, 'M1005', 'Anand S', '9666666665', 'fgdfgdfgdfg@outlook.com', '2003-08-12', 2, '2026-01-01', 'Female', '1769235817_download (1).jpg', 7, 7, '2026-01-24 07:24:29.00000', '2026-01-24 07:23:37.00000'),
(64, 'M1009', 'Anand S', '9666666665', 'fgdfgdfgdfg@outlook.com', '2003-08-12', 1, '2026-01-01', 'Male', '1769242105_download (1).jpg', 7, 7, '2026-01-24 13:09:45.00000', '2026-01-24 09:08:25.00000');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `User_Id` int(10) NOT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `Encrypt` varchar(50) NOT NULL,
  `UserType` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`User_Id`, `UserName`, `Password`, `Encrypt`, `UserType`) VALUES
(7, 'Anand', 'An@nd93455', '477a6fcd7b8e2d6f5a851fd77e23e4a5', 1),
(8, 'LeoMoon', 'An@nd93455', '477a6fcd7b8e2d6f5a851fd77e23e4a5', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course_details`
--
ALTER TABLE `course_details`
  ADD PRIMARY KEY (`Course_Id`),
  ADD UNIQUE KEY `Course_Code` (`Course_Code`),
  ADD KEY `fk_course_details` (`Added_By`),
  ADD KEY `fk_Course` (`Update_By`);

--
-- Indexes for table `educational_details`
--
ALTER TABLE `educational_details`
  ADD PRIMARY KEY (`Educational_Id`),
  ADD UNIQUE KEY `RegNo` (`RegNo`),
  ADD KEY `fk_educational` (`Member_Id`);

--
-- Indexes for table `member_details`
--
ALTER TABLE `member_details`
  ADD PRIMARY KEY (`Member_Id`),
  ADD UNIQUE KEY `Member_Code` (`Member_Code`),
  ADD KEY `fk_course_member` (`Course_Id`),
  ADD KEY `fk_member_details_user` (`Added_By`),
  ADD KEY `fk_member` (`Update_By`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`User_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course_details`
--
ALTER TABLE `course_details`
  MODIFY `Course_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `educational_details`
--
ALTER TABLE `educational_details`
  MODIFY `Educational_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `member_details`
--
ALTER TABLE `member_details`
  MODIFY `Member_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `User_Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_details`
--
ALTER TABLE `course_details`
  ADD CONSTRAINT `fk_Course` FOREIGN KEY (`Update_By`) REFERENCES `user_details` (`User_Id`),
  ADD CONSTRAINT `fk_course_details` FOREIGN KEY (`Added_By`) REFERENCES `user_details` (`User_Id`);

--
-- Constraints for table `educational_details`
--
ALTER TABLE `educational_details`
  ADD CONSTRAINT `fk_educational` FOREIGN KEY (`Member_Id`) REFERENCES `member_details` (`Member_Id`);

--
-- Constraints for table `member_details`
--
ALTER TABLE `member_details`
  ADD CONSTRAINT `fk_course_member` FOREIGN KEY (`Course_Id`) REFERENCES `course_details` (`Course_Id`),
  ADD CONSTRAINT `fk_member` FOREIGN KEY (`Update_By`) REFERENCES `user_details` (`User_Id`),
  ADD CONSTRAINT `fk_member_details` FOREIGN KEY (`Added_By`) REFERENCES `user_details` (`User_Id`),
  ADD CONSTRAINT `fk_member_details_user` FOREIGN KEY (`Added_By`) REFERENCES `user_details` (`User_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
