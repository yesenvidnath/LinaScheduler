-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 12:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `linascheduler`
--

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `Batch_ID` int(11) NOT NULL,
  `Batch_Name` varchar(100) DEFAULT NULL,
  `Batch_Student_Count` int(255) DEFAULT NULL,
  `Batch_Discription` text DEFAULT NULL,
  `Status` enum('1','0','1*') DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_requests`
--

CREATE TABLE `booking_requests` (
  `BookReqest_ID` int(11) NOT NULL,
  `Course_ID` int(11) DEFAULT NULL,
  `Batch_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `ERL_ID` int(11) DEFAULT NULL,
  `Class_Type` enum('Practical','Theory') DEFAULT NULL,
  `Expected_Student_Count` int(255) DEFAULT NULL,
  `Class_Start_Time` datetime DEFAULT NULL,
  `Class_End_Time` datetime DEFAULT NULL,
  `Status` enum('1','0','1*') DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `Branch_ID` int(11) NOT NULL,
  `Branch_Location` varchar(100) DEFAULT NULL,
  `Branch_Name` varchar(100) DEFAULT NULL,
  `Branch_Description` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_list`
--

CREATE TABLE `branch_list` (
  `Branch_List_ID` int(11) NOT NULL,
  `Branch_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `Cls_ID` int(11) NOT NULL,
  `Room_ID` int(11) DEFAULT NULL,
  `Cls_Number` varchar(200) DEFAULT NULL,
  `Cls_Discription` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `Course_ID` int(11) NOT NULL,
  `Course_Name` varchar(200) DEFAULT NULL,
  `Course_Discription` text DEFAULT NULL,
  `Course_Status` enum('1','0','1*') DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_list`
--

CREATE TABLE `course_list` (
  `Course_List_ID` int(11) NOT NULL,
  `Course_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `Equip_ID` int(11) NOT NULL,
  `Equip_Type_ID` int(11) DEFAULT NULL,
  `Equip_Discrption` text DEFAULT NULL,
  `Equip_Userbility_Status` enum('1','0','1*') DEFAULT NULL,
  `Is_Booked` enum('1','0','1*') DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipments_request_list`
--

CREATE TABLE `equipments_request_list` (
  `ERL_ID` int(11) NOT NULL,
  `Course_ID` int(11) DEFAULT NULL,
  `Equip_ID` int(11) DEFAULT NULL,
  `Class_Type` enum('Practical','Theory') DEFAULT NULL,
  `Status` enum('1','0','1*') DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_images`
--

CREATE TABLE `equipment_images` (
  `EQI_ID` int(11) NOT NULL,
  `Equip_ID` int(11) DEFAULT NULL,
  `EQI_Image` varchar(255) DEFAULT NULL,
  `EQI_Discription` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_types`
--

CREATE TABLE `equipment_types` (
  `Equip_Type_ID` int(11) NOT NULL,
  `Equip_Type` varchar(150) DEFAULT NULL,
  `Equip_Type_Discrption` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flows`
--

CREATE TABLE `flows` (
  `Fl_ID` int(11) NOT NULL,
  `Branch_ID` int(11) DEFAULT NULL,
  `FL_Name` varchar(100) DEFAULT NULL,
  `FL_Discription` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `honorifics`
--

CREATE TABLE `honorifics` (
  `Honorifics_ID` int(11) NOT NULL,
  `Honorific` varchar(100) DEFAULT NULL,
  `Honorific_Discription` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laboratory`
--

CREATE TABLE `laboratory` (
  `Lab_ID` int(11) NOT NULL,
  `Room_ID` int(11) DEFAULT NULL,
  `Lab_Type_ID` int(11) DEFAULT NULL,
  `Lab_Number` varchar(200) DEFAULT NULL,
  `Lab_Discription` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laboratory_type`
--

CREATE TABLE `laboratory_type` (
  `Lab_Type_ID` int(11) NOT NULL,
  `Lab_Type` varchar(100) DEFAULT NULL,
  `Lab_Type_Discription` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `Room_ID` int(11) NOT NULL,
  `Fl_ID` int(11) DEFAULT NULL,
  `Room_Number` varchar(200) DEFAULT NULL,
  `Room_Discrption` text DEFAULT NULL,
  `Room_Availability` enum('0','1','1*') DEFAULT NULL,
  `Room_Type` enum('Library','Class','Laboratory','StudyArea') DEFAULT NULL,
  `Max_Student_Count` int(11) DEFAULT NULL,
  `Max_Chair_Count` int(11) DEFAULT NULL,
  `Max_Power_Outlets` int(11) DEFAULT NULL,
  `Max_Table_Count` int(11) DEFAULT NULL,
  `Is_WhiteBoard_Avilable` tinyint(1) DEFAULT 0,
  `Is_Projector_Avilable` tinyint(1) DEFAULT 0,
  `Is_Smart_board_Avilable` tinyint(1) DEFAULT 0,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_image_list`
--

CREATE TABLE `room_image_list` (
  `RIL_ID` int(11) NOT NULL,
  `Room_ID` int(11) DEFAULT NULL,
  `Room_Image` varchar(255) DEFAULT NULL,
  `RIL_Discrption` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `Student_ID` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Course_List_ID` int(11) DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study`
--

CREATE TABLE `study` (
  `Study_ID` int(11) NOT NULL,
  `Room_ID` int(11) DEFAULT NULL,
  `Study_Number` varchar(200) DEFAULT NULL,
  `Study_Discrption` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `UD_ID` int(11) DEFAULT NULL,
  `Honorifics_ID` int(11) DEFAULT NULL,
  `First_Name` varchar(70) DEFAULT NULL,
  `Last_Name` varchar(70) DEFAULT NULL,
  `User_Discrption` text DEFAULT NULL,
  `Status` enum('1','0','1*') DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_designation`
--

CREATE TABLE `user_designation` (
  `UD_ID` int(11) NOT NULL,
  `UD_Type` varchar(100) DEFAULT NULL,
  `UD_Discrption` text DEFAULT NULL,
  `Is_Deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`Batch_ID`);

--
-- Indexes for table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD PRIMARY KEY (`BookReqest_ID`),
  ADD KEY `Course_ID` (`Course_ID`),
  ADD KEY `Batch_ID` (`Batch_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `ERL_ID` (`ERL_ID`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`Branch_ID`);

--
-- Indexes for table `branch_list`
--
ALTER TABLE `branch_list`
  ADD PRIMARY KEY (`Branch_List_ID`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`Cls_ID`),
  ADD KEY `Room_ID` (`Room_ID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`Course_ID`);

--
-- Indexes for table `course_list`
--
ALTER TABLE `course_list`
  ADD PRIMARY KEY (`Course_List_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Course_ID` (`Course_ID`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`Equip_ID`),
  ADD KEY `Equip_Type_ID` (`Equip_Type_ID`);

--
-- Indexes for table `equipments_request_list`
--
ALTER TABLE `equipments_request_list`
  ADD PRIMARY KEY (`ERL_ID`),
  ADD KEY `Course_ID` (`Course_ID`),
  ADD KEY `Equip_ID` (`Equip_ID`);

--
-- Indexes for table `equipment_images`
--
ALTER TABLE `equipment_images`
  ADD PRIMARY KEY (`EQI_ID`),
  ADD KEY `Equip_ID` (`Equip_ID`);

--
-- Indexes for table `equipment_types`
--
ALTER TABLE `equipment_types`
  ADD PRIMARY KEY (`Equip_Type_ID`);

--
-- Indexes for table `flows`
--
ALTER TABLE `flows`
  ADD PRIMARY KEY (`Fl_ID`),
  ADD KEY `Branch_ID` (`Branch_ID`);

--
-- Indexes for table `honorifics`
--
ALTER TABLE `honorifics`
  ADD PRIMARY KEY (`Honorifics_ID`);

--
-- Indexes for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD PRIMARY KEY (`Lab_ID`),
  ADD KEY `Room_ID` (`Room_ID`),
  ADD KEY `Lab_Type_ID` (`Lab_Type_ID`);

--
-- Indexes for table `laboratory_type`
--
ALTER TABLE `laboratory_type`
  ADD PRIMARY KEY (`Lab_Type_ID`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`Room_ID`),
  ADD KEY `Fl_ID` (`Fl_ID`);

--
-- Indexes for table `room_image_list`
--
ALTER TABLE `room_image_list`
  ADD PRIMARY KEY (`RIL_ID`),
  ADD KEY `Room_ID` (`Room_ID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`Student_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Course_List_ID` (`Course_List_ID`);

--
-- Indexes for table `study`
--
ALTER TABLE `study`
  ADD PRIMARY KEY (`Study_ID`),
  ADD KEY `Room_ID` (`Room_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD KEY `UD_ID` (`UD_ID`),
  ADD KEY `Honorifics_ID` (`Honorifics_ID`);

--
-- Indexes for table `user_designation`
--
ALTER TABLE `user_designation`
  ADD PRIMARY KEY (`UD_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `Batch_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_requests`
--
ALTER TABLE `booking_requests`
  MODIFY `BookReqest_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `Branch_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_list`
--
ALTER TABLE `branch_list`
  MODIFY `Branch_List_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `Cls_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `Course_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_list`
--
ALTER TABLE `course_list`
  MODIFY `Course_List_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `Equip_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipments_request_list`
--
ALTER TABLE `equipments_request_list`
  MODIFY `ERL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_images`
--
ALTER TABLE `equipment_images`
  MODIFY `EQI_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_types`
--
ALTER TABLE `equipment_types`
  MODIFY `Equip_Type_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flows`
--
ALTER TABLE `flows`
  MODIFY `Fl_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `honorifics`
--
ALTER TABLE `honorifics`
  MODIFY `Honorifics_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratory`
--
ALTER TABLE `laboratory`
  MODIFY `Lab_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratory_type`
--
ALTER TABLE `laboratory_type`
  MODIFY `Lab_Type_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Room_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_image_list`
--
ALTER TABLE `room_image_list`
  MODIFY `RIL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `Student_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study`
--
ALTER TABLE `study`
  MODIFY `Study_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_designation`
--
ALTER TABLE `user_designation`
  MODIFY `UD_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD CONSTRAINT `booking_requests_ibfk_1` FOREIGN KEY (`Course_ID`) REFERENCES `courses` (`Course_ID`),
  ADD CONSTRAINT `booking_requests_ibfk_2` FOREIGN KEY (`Batch_ID`) REFERENCES `batches` (`Batch_ID`),
  ADD CONSTRAINT `booking_requests_ibfk_3` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `booking_requests_ibfk_4` FOREIGN KEY (`ERL_ID`) REFERENCES `equipments_request_list` (`ERL_ID`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`Room_ID`) REFERENCES `rooms` (`Room_ID`);

--
-- Constraints for table `course_list`
--
ALTER TABLE `course_list`
  ADD CONSTRAINT `course_list_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `course_list_ibfk_2` FOREIGN KEY (`Course_ID`) REFERENCES `courses` (`Course_ID`);

--
-- Constraints for table `equipments`
--
ALTER TABLE `equipments`
  ADD CONSTRAINT `equipments_ibfk_1` FOREIGN KEY (`Equip_Type_ID`) REFERENCES `equipment_types` (`Equip_Type_ID`);

--
-- Constraints for table `equipments_request_list`
--
ALTER TABLE `equipments_request_list`
  ADD CONSTRAINT `equipments_request_list_ibfk_1` FOREIGN KEY (`Course_ID`) REFERENCES `courses` (`Course_ID`),
  ADD CONSTRAINT `equipments_request_list_ibfk_2` FOREIGN KEY (`Equip_ID`) REFERENCES `equipments` (`Equip_ID`);

--
-- Constraints for table `equipment_images`
--
ALTER TABLE `equipment_images`
  ADD CONSTRAINT `equipment_images_ibfk_1` FOREIGN KEY (`Equip_ID`) REFERENCES `equipments` (`Equip_ID`);

--
-- Constraints for table `flows`
--
ALTER TABLE `flows`
  ADD CONSTRAINT `flows_ibfk_1` FOREIGN KEY (`Branch_ID`) REFERENCES `branches` (`Branch_ID`);

--
-- Constraints for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD CONSTRAINT `laboratory_ibfk_1` FOREIGN KEY (`Room_ID`) REFERENCES `rooms` (`Room_ID`),
  ADD CONSTRAINT `laboratory_ibfk_2` FOREIGN KEY (`Lab_Type_ID`) REFERENCES `laboratory_type` (`Lab_Type_ID`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`Fl_ID`) REFERENCES `flows` (`Fl_ID`);

--
-- Constraints for table `room_image_list`
--
ALTER TABLE `room_image_list`
  ADD CONSTRAINT `room_image_list_ibfk_1` FOREIGN KEY (`Room_ID`) REFERENCES `rooms` (`Room_ID`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`Course_List_ID`) REFERENCES `course_list` (`Course_List_ID`);

--
-- Constraints for table `study`
--
ALTER TABLE `study`
  ADD CONSTRAINT `study_ibfk_1` FOREIGN KEY (`Room_ID`) REFERENCES `rooms` (`Room_ID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`UD_ID`) REFERENCES `user_designation` (`UD_ID`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`Honorifics_ID`) REFERENCES `honorifics` (`Honorifics_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
