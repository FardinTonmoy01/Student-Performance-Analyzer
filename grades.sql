-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2024 at 06:00 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online-school`
--

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `institute_id` bigint(20) UNSIGNED DEFAULT NULL,
  `grade` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double(8,2) NOT NULL,
  `upper_limit` int(11) NOT NULL,
  `lower_limit` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Inactive',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `institute_id`, `grade`, `value`, `upper_limit`, `lower_limit`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 'A+', 5.00, 100, 80, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(2, NULL, 'A', 4.75, 79, 75, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(3, NULL, 'A-', 4.50, 74, 70, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(4, NULL, 'B+', 4.00, 69, 65, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(5, NULL, 'B', 3.75, 64, 60, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(6, NULL, 'B-', 3.50, 59, 55, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(7, NULL, 'C+', 3.00, 54, 50, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(8, NULL, 'C', 2.75, 49, 45, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(9, NULL, 'C-', 2.50, 44, 40, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(10, NULL, 'D', 2.00, 39, 33, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13'),
(11, NULL, 'F', 1.75, 32, 0, 1, NULL, NULL, '2023-12-19 18:18:13', '2023-12-19 18:18:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
