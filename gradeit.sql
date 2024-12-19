-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 06:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gradeit`
--

-- --------------------------------------------------------

--
-- Table structure for table `components_weights`
--

CREATE TABLE `components_weights` (
  `comp_id` int(3) NOT NULL,
  `gradingsystem_id` int(10) NOT NULL,
  `weight1` decimal(5,2) DEFAULT NULL,
  `component1` varchar(255) DEFAULT NULL,
  `weight2` decimal(5,2) DEFAULT NULL,
  `component2` varchar(255) DEFAULT NULL,
  `weight3` decimal(5,2) DEFAULT NULL,
  `component3` varchar(255) DEFAULT NULL,
  `weight4` decimal(5,2) DEFAULT NULL,
  `component4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=tis620 COLLATE=tis620_bin;

--
-- Dumping data for table `components_weights`
--

INSERT INTO `components_weights` (`comp_id`, `gradingsystem_id`, `weight1`, `component1`, `weight2`, `component2`, `weight3`, `component3`, `weight4`, `component4`) VALUES
(4, 2, 0.40, 'examn', 0.20, 'assessment', 0.30, 'Bwakanangshet', 0.10, 'Ayokona');

-- --------------------------------------------------------

--
-- Table structure for table `gradingsystem`
--

CREATE TABLE `gradingsystem` (
  `gradingsystem_id` int(10) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `section` varchar(255) NOT NULL,
  `term` int(1) NOT NULL,
  `acad_year` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=tis620 COLLATE=tis620_bin;

--
-- Dumping data for table `gradingsystem`
--

INSERT INTO `gradingsystem` (`gradingsystem_id`, `subject_name`, `section`, `term`, `acad_year`) VALUES
(2, 'CCADML', 'COM222', 2, '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `score_id` int(3) NOT NULL,
  `subcomp_id` int(3) NOT NULL,
  `student_id` int(10) NOT NULL,
  `subcompscores1` decimal(5,2) DEFAULT NULL,
  `subcompscores2` decimal(5,2) DEFAULT NULL,
  `subcompscores3` decimal(5,2) DEFAULT NULL,
  `subcompscores4` decimal(5,2) DEFAULT NULL,
  `subcompscores5` decimal(5,2) DEFAULT NULL,
  `subcompscores6` decimal(5,2) DEFAULT NULL,
  `subcompscores7` decimal(5,2) DEFAULT NULL,
  `subcompscores8` decimal(5,2) DEFAULT NULL,
  `subcompscores9` decimal(5,2) DEFAULT NULL,
  `subcompscores10` decimal(5,2) DEFAULT NULL,
  `subcompscores11` decimal(5,2) DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=tis620 COLLATE=tis620_bin;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`score_id`, `subcomp_id`, `student_id`, `subcompscores1`, `subcompscores2`, `subcompscores3`, `subcompscores4`, `subcompscores5`, `subcompscores6`, `subcompscores7`, `subcompscores8`, `subcompscores9`, `subcompscores10`, `subcompscores11`, `grade`) VALUES
(34, 9, 7, 60.00, 1.00, 1.00, 0.00, 1.00, 1.00, 0.00, 1.00, 1.00, 0.00, 1.00, 0.00),
(35, 9, 8, 22.00, 1.00, 1.00, 0.00, 1.00, 1.00, 0.00, 1.00, 1.00, 0.00, 1.00, 0.00),
(37, 9, 9, 60.00, 10.00, 10.00, 10.00, 10.00, 10.00, 10.00, 10.00, 10.00, 10.00, 10.00, 0.00),
(38, 9, 10, 60.00, 60.00, 60.00, 60.00, 60.00, 60.00, 60.00, 60.00, 60.00, 60.00, 60.00, 0.00),
(45, 9, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 9, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 9, 6, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(48, 9, 6, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(49, 9, 11, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(50, 9, 15, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(51, 9, 16, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(52, 9, 17, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(10) NOT NULL,
  `student_num` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `course` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=tis620 COLLATE=tis620_bin;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_num`, `fullname`, `course`) VALUES
(6, '2022-104741', 'Diana Danga', 'BSCS'),
(7, '2020-202020', 'Bini Lat', 'BSIT'),
(8, '2021-212121', 'Jepoy', 'BSCS'),
(9, '2021-212125', 'Jggcepoy', 'BSCS'),
(10, '2021-252125', 'Jgg66cepoy', 'BSCS'),
(11, '', '', ''),
(15, NULL, '', ''),
(16, NULL, '', ''),
(17, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `subcomponents`
--

CREATE TABLE `subcomponents` (
  `subcomp_id` int(3) NOT NULL,
  `comp_id` int(10) NOT NULL,
  `subcomponent1` varchar(255) DEFAULT NULL,
  `subcomponent2` varchar(255) DEFAULT NULL,
  `subcomponent3` varchar(255) DEFAULT NULL,
  `subcomponent4` varchar(255) DEFAULT NULL,
  `subcomponent5` varchar(255) DEFAULT NULL,
  `subcomponent6` varchar(255) DEFAULT NULL,
  `subcomponent7` varchar(255) DEFAULT NULL,
  `subcomponent8` varchar(255) DEFAULT NULL,
  `subcomponent9` varchar(255) DEFAULT NULL,
  `subcomponent10` varchar(255) DEFAULT NULL,
  `subcomponent11` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=tis620 COLLATE=tis620_bin;

--
-- Dumping data for table `subcomponents`
--

INSERT INTO `subcomponents` (`subcomp_id`, `comp_id`, `subcomponent1`, `subcomponent2`, `subcomponent3`, `subcomponent4`, `subcomponent5`, `subcomponent6`, `subcomponent7`, `subcomponent8`, `subcomponent9`, `subcomponent10`, `subcomponent11`) VALUES
(9, 4, 'Hotdog', 'Burger', 'Spag', 'Egg', 'Palabok', 'Bonak', 'Panot', 'si', 'Romano', 'Science', 'EK');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `user_fname` varchar(255) NOT NULL,
  `user_lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=tis620 COLLATE=tis620_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fname`, `user_lname`, `username`, `email`, `password`) VALUES
(16, 'diana', 'danga', 'dayana', '@gmail.com', '12345678'),
(17, 'Nicole', 'Danga', 'dsadsada', 'dayana@gmail.com', '$2y$10$B5bfkSWX49YC7fNEss6XTOEgJ33Sldv8e4DpJmLuEvis5/2xwqjk2'),
(18, 'jef', 'jef', 'jef', 'jef@gmail.com', '$2y$10$cdyr6IfqLVxexedad8Px/udYBQyCIa7gE4lDhuv24vtZ6uBTzBLte'),
(19, 'Nicole', 'Danga', 'sadad', 'dayana123@gmail.com', '12345678'),
(20, 'Lotlot', 'Deleon', 'lotty', 'lotty@gmail.com', '12345678'),
(21, 'JASCENT PEARL', 'NAVARRO', 'pearl', 'navarrojg@students.national-u.edu.ph', '12345678');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `components_weights`
--
ALTER TABLE `components_weights`
  ADD PRIMARY KEY (`comp_id`),
  ADD KEY `gradingsystem_id_fk` (`gradingsystem_id`);

--
-- Indexes for table `gradingsystem`
--
ALTER TABLE `gradingsystem`
  ADD PRIMARY KEY (`gradingsystem_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `subcomp_id_fk` (`subcomp_id`),
  ADD KEY `student_id_fk` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_num` (`student_num`),
  ADD UNIQUE KEY `student_num_2` (`student_num`);

--
-- Indexes for table `subcomponents`
--
ALTER TABLE `subcomponents`
  ADD PRIMARY KEY (`subcomp_id`),
  ADD KEY `comp_id_fk` (`comp_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `components_weights`
--
ALTER TABLE `components_weights`
  MODIFY `comp_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gradingsystem`
--
ALTER TABLE `gradingsystem`
  MODIFY `gradingsystem_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `score_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `subcomponents`
--
ALTER TABLE `subcomponents`
  MODIFY `subcomp_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `components_weights`
--
ALTER TABLE `components_weights`
  ADD CONSTRAINT `gradingsystem_id_fk` FOREIGN KEY (`gradingsystem_id`) REFERENCES `gradingsystem` (`gradingsystem_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `student_id_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subcomp_id_fk` FOREIGN KEY (`subcomp_id`) REFERENCES `subcomponents` (`subcomp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcomponents`
--
ALTER TABLE `subcomponents`
  ADD CONSTRAINT `comp_id_fk` FOREIGN KEY (`comp_id`) REFERENCES `components_weights` (`comp_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
