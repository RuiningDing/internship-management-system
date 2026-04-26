-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2026-04-24 13:46:13
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `internship_system`
--

-- --------------------------------------------------------

--
-- 表的结构 `assessment_criteria`
--

CREATE TABLE `assessment_criteria` (
  `criteria_id` int(11) NOT NULL,
  `criteria_name` varchar(200) NOT NULL,
  `weightage` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `assessment_criteria`
--

INSERT INTO `assessment_criteria` (`criteria_id`, `criteria_name`, `weightage`, `description`) VALUES
(1, 'Undertaking Tasks/Projects', 10, NULL),
(2, 'Health and Safety Requirements', 10, NULL),
(3, 'Connectivity & Use of Theoretical Knowledge', 10, NULL),
(4, 'Written Report Presentation', 15, NULL),
(5, 'Clarity of Language & Illustration', 10, NULL),
(6, 'Lifelong Learning Activities', 15, NULL),
(7, 'Project Management', 15, NULL),
(8, 'Time Management', 15, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `assessment_score`
--

CREATE TABLE `assessment_score` (
  `score_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `comments` text DEFAULT NULL,
  `assessed_by` int(11) NOT NULL,
  `assessed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `assessor`
--

CREATE TABLE `assessor` (
  `assessor_id` int(11) NOT NULL,
  `assessor_name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `internship`
--

CREATE TABLE `internship` (
  `internship_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `assessor_id` int(11) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `programme`
--

CREATE TABLE `programme` (
  `programme_id` int(11) NOT NULL,
  `programme_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `programme`
--

INSERT INTO `programme` (`programme_id`, `programme_name`) VALUES
(1, 'Computer Science'),
(2, 'Business Administration');

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE `student` (
  `student_id` varchar(20) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `programme_id` int(11) NOT NULL,
  `contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Assessor') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `status`) VALUES
(1, 'admin', 'admin123', 'Admin', 'Active');

--
-- 转储表的索引
--

--
-- 表的索引 `assessment_criteria`
--
ALTER TABLE `assessment_criteria`
  ADD PRIMARY KEY (`criteria_id`);

--
-- 表的索引 `assessment_score`
--
ALTER TABLE `assessment_score`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `internship_id` (`internship_id`),
  ADD KEY `criteria_id` (`criteria_id`),
  ADD KEY `assessed_by` (`assessed_by`);

--
-- 表的索引 `assessor`
--
ALTER TABLE `assessor`
  ADD PRIMARY KEY (`assessor_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- 表的索引 `internship`
--
ALTER TABLE `internship`
  ADD PRIMARY KEY (`internship_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `assessor_id` (`assessor_id`);

--
-- 表的索引 `programme`
--
ALTER TABLE `programme`
  ADD PRIMARY KEY (`programme_id`);

--
-- 表的索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `programme_id` (`programme_id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `assessment_criteria`
--
ALTER TABLE `assessment_criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `assessment_score`
--
ALTER TABLE `assessment_score`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `assessor`
--
ALTER TABLE `assessor`
  MODIFY `assessor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `internship`
--
ALTER TABLE `internship`
  MODIFY `internship_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `programme`
--
ALTER TABLE `programme`
  MODIFY `programme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 限制导出的表
--

--
-- 限制表 `assessment_score`
--
ALTER TABLE `assessment_score`
  ADD CONSTRAINT `assessment_score_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internship` (`internship_id`),
  ADD CONSTRAINT `assessment_score_ibfk_2` FOREIGN KEY (`criteria_id`) REFERENCES `assessment_criteria` (`criteria_id`),
  ADD CONSTRAINT `assessment_score_ibfk_3` FOREIGN KEY (`assessed_by`) REFERENCES `assessor` (`assessor_id`);

--
-- 限制表 `assessor`
--
ALTER TABLE `assessor`
  ADD CONSTRAINT `assessor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- 限制表 `internship`
--
ALTER TABLE `internship`
  ADD CONSTRAINT `internship_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `internship_ibfk_2` FOREIGN KEY (`assessor_id`) REFERENCES `assessor` (`assessor_id`);

--
-- 限制表 `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`programme_id`) REFERENCES `programme` (`programme_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
