-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 05, 2026 at 07:02 AM
-- Server version: 11.8.6-MariaDB-ubu2404-log
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jadelyn_pharmacy`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadelyn_pharmacy_generic_name`
--

CREATE TABLE `jadelyn_pharmacy_generic_name` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadelyn_pharmacy_generic_name`
--

INSERT INTO `jadelyn_pharmacy_generic_name` (`id`, `name`, `created_at`, `updated_at`, `active`) VALUES
(1, 'Amlodipine 5mg', '2026-03-21 20:44:31', '2026-03-21 20:44:31', 1),
(2, 'Amlodipine 10mg', '2026-03-21 20:44:31', '2026-03-21 20:44:31', 1),
(3, 'Losartan 50mg', '2026-03-21 20:44:31', '2026-03-21 20:44:31', 1),
(4, 'Losartan 100mg', '2026-03-21 20:44:31', '2026-03-21 20:44:31', 1),
(5, 'Fill in the blank 100mg', '2026-03-21 23:03:48', '2026-03-21 23:24:59', 1),
(6, 'Fill in the blank 200mg', '2026-03-21 23:06:08', '2026-03-21 23:25:08', 1),
(7, 'Test 5mg', '2026-03-21 23:09:10', '2026-03-21 23:25:17', 1),
(8, 'Test 10mg', '2026-03-21 23:10:32', '2026-03-21 23:25:26', 1),
(9, 'test drug', '2026-03-21 23:28:21', '2026-03-21 23:28:21', 1),
(10, 'delette this', '2026-03-21 23:41:12', '2026-03-21 23:41:27', 0),
(11, '10th entry', '2026-03-21 23:41:37', '2026-03-21 23:41:55', 0),
(12, 'New Generic Names', '2026-03-22 17:54:36', '2026-03-22 22:32:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadelyn_pharmacy_product_list`
--

CREATE TABLE `jadelyn_pharmacy_product_list` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `product_type_id` int(11) UNSIGNED NOT NULL,
  `generic_name_id` int(11) UNSIGNED NOT NULL,
  `lot_number` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadelyn_pharmacy_product_list`
--

INSERT INTO `jadelyn_pharmacy_product_list` (`id`, `name`, `description`, `price`, `product_type_id`, `generic_name_id`, `lot_number`, `expiry_date`, `purchase_date`, `created_at`, `updated_at`, `active`) VALUES
(1, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 01:33:37', '2026-03-21 11:09:48', 0),
(2, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:30:59', '2026-03-21 11:09:55', 0),
(3, 'Abbott Syrup Product 1', NULL, 150.90, 2, 1, '5677721', '2031-03-28', '2026-03-28', '2026-03-21 03:31:00', '2026-03-21 21:18:47', 1),
(4, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:01', '2026-03-21 11:15:46', 0),
(5, 'Amlodipine', NULL, 5.00, 1, 1, '13413984', '2026-03-21', '2026-03-31', '2026-03-21 03:31:01', '2026-03-21 20:31:12', 1),
(6, 'Abbott Product 1 Sample', NULL, 10.00, 1, 4, 'LN2139821', '2050-03-26', '2026-03-23', '2026-03-21 03:31:01', '2026-03-21 21:14:16', 1),
(7, 'Not Losartan', NULL, 5.00, 1, 3, 'LN6000', '2030-03-21', '2026-03-21', '2026-03-21 03:31:01', '2026-03-21 21:15:56', 1),
(8, 'Ritemed', NULL, 6.00, 4, 1, '98217', '2030-03-21', '2026-03-21', '2026-03-21 03:31:02', '2026-03-21 23:19:37', 1),
(9, 'Abbott Product 1 Sample', NULL, 20.15, 1, 9, '51239', '2030-03-21', '2026-03-21', '2026-03-21 03:31:02', '2026-03-21 23:29:36', 1),
(10, 'Abbott Product 1 Sample', NULL, 5.24, 5, 1, '12231', '2028-03-21', '2026-03-21', '2026-03-21 03:31:02', '2026-03-21 23:32:19', 1),
(11, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:02', '2026-03-21 03:31:02', 1),
(12, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:03', '2026-03-21 03:31:03', 1),
(13, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:03', '2026-03-21 03:31:03', 1),
(14, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:03', '2026-03-21 03:31:03', 1),
(15, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:03', '2026-03-21 03:31:03', 1),
(16, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:03', '2026-03-21 03:31:03', 1),
(17, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:04', '2026-03-21 03:31:04', 1),
(18, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:04', '2026-03-21 03:31:04', 1),
(19, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:04', '2026-03-21 03:31:04', 1),
(20, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:04', '2026-03-21 03:31:04', 1),
(21, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:05', '2026-03-21 03:31:05', 1),
(22, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:05', '2026-03-21 03:31:05', 1),
(23, 'Abbott Product 1 Sample', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 03:31:05', '2026-03-21 03:31:05', 1),
(24, 'Abbott Product 1 Testingg', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 09:32:59', '2026-03-21 09:32:59', 1),
(25, 'Not A Sample Product', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 09:33:06', '2026-03-21 09:33:06', 1),
(26, 'Pipi', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 09:33:09', '2026-03-21 09:33:09', 1),
(27, 'Gamot Gamotan', NULL, 0.00, 1, 1, NULL, NULL, NULL, '2026-03-21 09:45:27', '2026-03-21 09:45:27', 1),
(28, 'Brand Name Test', NULL, 5.50, 2, 1, '88655', '2030-03-21', '2026-03-21', '2026-03-21 09:59:40', '2026-03-21 21:10:30', 1),
(29, 'Suspension Test', NULL, 0.00, 3, 1, NULL, NULL, NULL, '2026-03-21 09:59:42', '2026-03-21 09:59:42', 1),
(30, 'Suspension Test', NULL, 0.00, 2, 1, '110117', '2030-02-25', '2026-02-25', '2026-03-21 10:16:20', '2026-03-21 21:19:25', 1),
(31, 'Test Product', NULL, 100.50, 2, 1, '11233', '2026-03-21', '2030-03-31', '2026-03-21 11:38:16', '2026-03-21 11:48:59', 1),
(32, 'Sample Product Name', NULL, 50.00, 3, 1, '556782823', '2026-03-16', '2026-03-31', '2026-03-21 19:32:03', '2026-03-21 19:32:03', 1),
(33, 'Brand Name Test', NULL, 5.00, 1, 4, 'LN87868', '2026-03-21', '2026-03-31', '2026-03-21 20:59:34', '2026-03-21 21:13:41', 1),
(34, 'abc', NULL, 4.00, 1, 1, '1234', '2028-06-18', '2024-06-19', '2026-03-21 21:08:26', '2026-03-21 21:08:26', 1),
(35, 'FITB200', NULL, 50.00, 3, 6, '56744', '2030-03-21', '2026-03-21', '2026-03-21 23:58:57', '2026-03-21 23:58:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadelyn_pharmacy_product_types`
--

CREATE TABLE `jadelyn_pharmacy_product_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadelyn_pharmacy_product_types`
--

INSERT INTO `jadelyn_pharmacy_product_types` (`id`, `name`, `description`, `created_at`, `updated_at`, `active`) VALUES
(1, 'Drug', 'Lists all the tablets and capsules', '2026-03-21 01:22:36', '2026-03-21 01:22:36', 1),
(2, 'Syrup', '', '2026-03-21 01:23:31', '2026-03-21 01:23:31', 1),
(3, 'Suspension', '', '2026-03-21 01:23:32', '2026-03-21 01:23:32', 1),
(4, 'Capsule', NULL, '2026-03-21 23:18:48', '2026-03-21 23:18:48', 1),
(5, 'Test Product Type Edit Update', NULL, '2026-03-21 23:25:48', '2026-03-21 23:34:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadelyn_pharmacy_users`
--

CREATE TABLE `jadelyn_pharmacy_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by_user_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadelyn_pharmacy_users`
--

INSERT INTO `jadelyn_pharmacy_users` (`id`, `username`, `email`, `password`, `role_id`, `last_login`, `created_at`, `created_by_user_id`, `updated_at`, `active`) VALUES
(1, 'jadelyn_admin', '', '$2y$10$iO1aqRdUGkN7alQJp62xEuDdoxRIDATcU2jVFnsrd5e9DGJr7Ki/m', 1, NULL, '2026-03-22 11:43:18', NULL, '2026-03-22 11:45:15', 1),
(3, 'jadelyn_encoder', 'test', '$2y$10$iO1aqRdUGkN7alQJp62xEuDdoxRIDATcU2jVFnsrd5e9DGJr7Ki/m', 2, NULL, '2026-03-22 12:00:33', NULL, '2026-03-22 12:00:56', 1),
(4, 'takanobu_bear', 'calambajanber@gmail.com', '$2y$10$bx/C96MwQoygJ5Tkbru13O9uu6nVefW204k4ctcrl7HVzwi0SpYX6', 1, NULL, '2026-03-22 18:04:16', 1, '2026-03-22 22:42:09', 1),
(6, 'takanobu_bear_encoder', 'janbercalamba10@gmail.com', '$2y$10$1VoLDvxRHtO/8A.sLpsKdO0FzEMBJMKXXz8U3.TSyjCX.fZg3hJ1.', 2, NULL, '2026-03-22 18:22:56', 4, '2026-03-22 18:25:20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jadelyn_pharmacy_user_roles`
--

CREATE TABLE `jadelyn_pharmacy_user_roles` (
  `id` int(11) UNSIGNED NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by_user_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadelyn_pharmacy_user_roles`
--

INSERT INTO `jadelyn_pharmacy_user_roles` (`id`, `role_name`, `role_code`, `description`, `created_at`, `created_by_user_id`, `updated_at`, `active`) VALUES
(1, 'Admin', 'admin', 'The administrator of the whole system', '2026-03-22 11:41:48', NULL, '2026-03-22 11:41:48', 1),
(2, 'Encoder', 'encoder', 'Encoder of the system', '2026-03-22 11:41:48', NULL, '2026-03-22 11:41:48', 1),
(3, 'Sample Role', '', NULL, '2026-03-22 18:05:18', NULL, '2026-03-22 18:25:32', 0),
(4, 'Test Role Edit', 'rolecode_testrole1', 'only editing the description', '2026-03-22 22:51:03', NULL, '2026-03-22 22:54:06', 0),
(5, 'Test Encoder Edit', '1test_encoder', 'just a test encoder role edit', '2026-03-22 22:54:49', NULL, '2026-03-22 22:55:25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-03-20-170842', 'App\\Database\\Migrations\\CreateBrandsTable', 'default', 'App', 1774026923, 1),
(3, '2026-03-20-171714', 'App\\Database\\Migrations\\CreateProductTypesTable', 'default', 'App', 1774027285, 2),
(4, '2026-03-20-172420', 'App\\Database\\Migrations\\CreateProductList', 'default', 'App', 1774027622, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadelyn_pharmacy_generic_name`
--
ALTER TABLE `jadelyn_pharmacy_generic_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadelyn_pharmacy_product_list`
--
ALTER TABLE `jadelyn_pharmacy_product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadelyn_pharmacy_product_types`
--
ALTER TABLE `jadelyn_pharmacy_product_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadelyn_pharmacy_users`
--
ALTER TABLE `jadelyn_pharmacy_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `jadelyn_pharmacy_user_roles`
--
ALTER TABLE `jadelyn_pharmacy_user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_role_code` (`role_code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadelyn_pharmacy_generic_name`
--
ALTER TABLE `jadelyn_pharmacy_generic_name`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jadelyn_pharmacy_product_list`
--
ALTER TABLE `jadelyn_pharmacy_product_list`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `jadelyn_pharmacy_product_types`
--
ALTER TABLE `jadelyn_pharmacy_product_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadelyn_pharmacy_users`
--
ALTER TABLE `jadelyn_pharmacy_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jadelyn_pharmacy_user_roles`
--
ALTER TABLE `jadelyn_pharmacy_user_roles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
