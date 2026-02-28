-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2026 at 11:20 AM
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
-- Database: `my_db`
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
(1, 'C1000', 'CSE', '24', 7, 7, NULL, '2026-02-25 05:32:29.00000'),
(2, 'C1001', 'IT', '24', NULL, 7, NULL, '2026-02-28 10:52:59.00000'),
(5, 'C1002', 'ECE', '24', NULL, 7, NULL, '2026-02-25 05:33:27.00000'),
(8, 'C1004', 'CIVIL', '24', NULL, 7, NULL, '2026-02-28 10:37:41.00000'),
(11, 'C1212', 'EEE', '24', NULL, 7, NULL, '2026-02-25 05:33:46.00000'),
(12, 'C1006', 'MECH', '24', 7, 7, '2026-01-22 08:10:29.00000', '2026-02-25 05:32:41.00000'),
(14, 'C1009', 'AUTO MOBILE', '36', 7, 7, '2026-01-22 10:21:32.00000', '2026-02-25 05:33:07.00000');

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
(142, 64, 'Diploma', 'MSPVL', '24503673', '2026', 95, 'I class'),
(143, 46, 'PG', 'MGR College', '24503678', '2026', 58, 'III class'),
(149, 63, 'Diploma', 'MSPVL', '24503677', '2026', 93, 'I class'),
(154, 62, 'UG', 'MSPVL', '24503685', '2026', 93, 'I class with distinc'),
(155, 62, 'PG', 'MSPVL', '24503679', '2026', 93, 'I class'),
(156, 67, '12th', 'MSPVL', '24503671', '2026', 87, ''),
(158, 66, 'Diploma', 'MSPVL', '24503675', '2026', 89, 'II class');

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
(46, 'M1004', 'Santa', '9840741242', 'santa@gmail.com', '2003-08-12', 2, '2026-01-01', 'Male', '1769229523_download (1).jpg', NULL, 7, '2026-01-24 08:12:51.00000', NULL),
(62, 'M1000', 'MOON', '9444704717', 'moon@gmail.com', '2003-08-12', 5, '2026-01-01', 'Female', '1771583269_6998372553e6e.jpg', 7, NULL, '2026-02-17 08:07:34.00000', '2026-01-24 05:44:34.00000'),
(63, 'M1005', 'Troll', '9856742563', 'troll@gmail.com', '2003-08-12', 8, '2026-01-01', 'Male', '1769235817_download (1).jpg', 7, 7, '2026-01-24 07:24:29.00000', '2026-01-24 07:23:37.00000'),
(64, 'M1009', 'Anand S', '9345583163', 'anandrajbites@gmail.com', '2003-08-12', 1, '2026-01-01', 'Male', '1769242105_download (1).jpg', 7, NULL, '2026-02-17 08:01:30.00000', '2026-01-24 09:08:25.00000'),
(66, 'M3425', 'OGGY', '9574629848', 'oggy@gmail.com', '2003-08-30', 14, '2026-02-25', 'Male', '1771313158_69941806afefd.jpg', NULL, NULL, NULL, NULL),
(67, 'M4566', 'Yuvan', '8659747458', 'yuvan@gmail.com', '2003-08-12', 12, '2026-01-01', 'Male', '1771313374_699418de78982.jpg', NULL, NULL, NULL, NULL);

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
(7, 'Anand', 'An@nd123', '4b1d6a2fd6c676468c0aaa41c8f0c8ad', 1),
(12, 'Moon', 'An@nd123', '4b1d6a2fd6c676468c0aaa41c8f0c8ad', 2),
(19, 'S_Anand', 'An@nd123', '4b1d6a2fd6c676468c0aaa41c8f0c8ad', 2);

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
  MODIFY `Course_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `educational_details`
--
ALTER TABLE `educational_details`
  MODIFY `Educational_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `member_details`
--
ALTER TABLE `member_details`
  MODIFY `Member_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `User_Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
