-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 01:17 PM
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
-- Database: `job_hunting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `status` enum('Pending','Shortlisted','Rejected') NOT NULL DEFAULT 'Pending',
  `resume_path` varchar(255) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `job_id`, `seeker_id`, `status`, `resume_path`, `applied_at`) VALUES
(1, 1, 1, 'Shortlisted', 'uploads/resumes/resume_1_1_1770554080.pdf', '2026-02-08 12:34:40'),
(2, 3, 5, 'Shortlisted', 'uploads/resumes/resume_5_3_1770722587.pdf', '2026-02-10 11:23:07'),
(3, 2, 5, 'Pending', 'uploads/resumes/resume_5_2_1770722880.pdf', '2026-02-10 11:28:00'),
(4, 4, 5, 'Shortlisted', 'uploads/resumes/resume_5_4_1770723970.pdf', '2026-02-10 11:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `employer_id`, `title`, `description`, `category`, `salary_range`, `location`, `deadline`, `created_at`) VALUES
(1, 2, 'software engineering', 'qof dulqad badan', 'Engineering', '100', 'Mogadishu', '2026-03-07 00:00:00', '2026-02-08 12:33:43'),
(2, 2, 'Xisaabiye', 'hard worker', 'Finance', '1000', 'Mogadishu', '2026-03-06 21:55:00', '2026-02-08 17:56:21'),
(3, 4, 'gaari lawawaado', 'wa inuu gaariga wadi yaqaanaa', 'Other', '100', 'mogadishu', '2026-02-26 14:05:00', '2026-02-10 11:06:13'),
(4, 4, 'cuntekariye', 'waa inuu yaqanaa sida cuntada lookariyo siwanaagsan', 'Other', '100', 'mogadishu', '2026-02-24 14:42:00', '2026-02-10 11:42:19');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','employer','seeker') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'isco', 'isco@gmail.com', '$2y$10$7WSHA9ft6R5WxJhOkhXuK.hWfeUBS9rk3xNOUGPZWiq/ITvgbuDHi', 'seeker', '2026-02-08 12:29:02'),
(2, 'rabsho', 'rabsho@gmail.com', '$2y$10$GYH4egIFQ1H/KVnTtTZsGujp4RQJQJ3x0eqkNpTaTQUFih21ihm32', 'employer', '2026-02-08 12:31:47'),
(3, 'admin', 'admin@gmail.com', '$2y$10$nVzdtjtrXzwSFqU0gO72Ue7SnBoEMwm5lctVYVp/khXzd1wNjpCK2', 'admin', '2026-02-08 12:36:52'),
(4, 'daahir maxamed cabdi', 'daahir@gmail.com', '$2y$10$Yhwgnj0wOtCA0NKnuPTXGO6IPr.eBrZq3vuduafHME1rppUAe9s6i', 'employer', '2026-02-10 11:04:34'),
(5, 'yaasain', 'yasin@gmail.com', '$2y$10$lPnu6g6lVo0PYClnJPk1QeoYjQCy5FByKIywfFwmTESVSZwqsvt26', 'seeker', '2026-02-10 11:09:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `seeker_id` (`seeker_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `employer_id` (`employer_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_applications_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_applications_seeker` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_employer` FOREIGN KEY (`employer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
