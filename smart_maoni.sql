-- Smart Feedback & Reward System - Database Setup
-- ================================================
-- Instructions:
-- 1. Open phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Click "Import" tab
-- 3. Select this file and click "Go"
--
-- After import, copy the 'smart_feedback' folder to: C:\xampp\htdocs\
-- Then access: http://localhost/smart_feedback/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `smart_maoni` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `smart_maoni`;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_location` varchar(200) NOT NULL,
  `manager_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `qr_code_url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_location`, `manager_name`, `phone`, `status`, `qr_code_url`, `created_at`) VALUES
(1, 'Posta (HQ)', 'Posta Road, Dar es Salaam', 'Juma Ali', '+255712345678', 'active', NULL, '2026-05-26 16:16:43'),
(2, 'Dodoma Branch', 'Kikuyu Street, Dodoma', 'Amina Said', '+255723456789', 'active', NULL, '2026-05-26 16:16:43'),
(3, 'Arusha Branch', 'Sokoine Road, Arusha', 'John Mtui', '+255734567890', 'active', NULL, '2026-05-26 16:16:43'),
(4, 'Mwanza Branch', 'Nile Road, Mwanza', 'Grace Mushi', '+255745678901', 'active', NULL, '2026-05-26 16:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT 'Anonymous',
  `customer_email` varchar(100) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `category` varchar(50) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `sentiment` enum('Positive','Neutral','Negative') DEFAULT 'Neutral',
  `status` enum('Pending','Resolved','Replied') DEFAULT 'Pending',
  `image_url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `branch_id`, `customer_name`, `customer_email`, `rating`, `category`, `comment`, `sentiment`, `status`, `image_url`, `created_at`) VALUES
(15, 2, 'ROBERT MABULULU', 'mabululu@gmail.com', 3, 'Mengineyo', 'naombeni kujitolea kufanya maintanace kwa sector ya information technology\n', 'Neutral', 'Pending', NULL, '2026-05-26 21:54:13'),
(16, 4, 'chaudele', 'chaudele@gmail.com', 3, 'Others', 'we are so exited in your service of produce good production...🥰🥰😂', 'Neutral', 'Pending', NULL, '2026-05-26 22:48:34'),
(17, 4, 'Anonymous', '', 3, 'Huduma kwa Wateja', '', 'Neutral', 'Pending', NULL, '2026-05-27 00:25:50'),
(18, 3, 'chasambi', 'chasambi@gmail.com', 5, 'Ubora wa Bidhaa', 'bidhaa ni nzuri sana pia nahitaji niongeze mzigo', 'Positive', 'Pending', NULL, '2026-05-28 22:21:46'),
(19, 3, 'fidelis', '', 3, 'Mazingira/Usafi', 'mazingila yapo gud lkn uchawi kwenye maua', 'Neutral', 'Pending', NULL, '2026-05-31 14:58:27'),
(20, 3, 'mwakimba ', 'mwakimba@gmail.com', 5, 'Huduma kwa Wateja', 'huma mlionipa ni nzuri sana mbaka nimefurahia', 'Positive', 'Pending', NULL, '2026-05-31 15:00:09'),
(21, 3, 'chondoma', 'chondo@gmail.com', 3, 'Ubora wa Bidhaa', 'bidhaa azikizi vigezo na ubora kwa bei mlioiweka', 'Neutral', 'Pending', NULL, '2026-05-31 15:15:05'),
(22, 1, 'kidomasta', '', 3, 'Ubora wa Bidhaa', 'qwertyui', 'Neutral', 'Pending', NULL, '2026-05-31 17:04:06'),
(23, 1, 'kidomasta', '', 3, 'Ubora wa Bidhaa', 'asdfghjk', 'Neutral', 'Pending', NULL, '2026-05-31 17:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `qr_code_data` text NOT NULL,
  `qr_code_image` text DEFAULT NULL,
  `scan_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `last_scanned_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `promo_code` varchar(50) NOT NULL,
  `discount_percent` int(11) DEFAULT 15,
  `feedback_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `used_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `promo_code`, `discount_percent`, `feedback_id`, `customer_name`, `customer_email`, `is_used`, `used_at`, `expires_at`, `created_at`) VALUES
(6, 'SMART-595B8E983', 15, 15, 'ROBERT MABULULU', 'mabululu@gmail.com', 0, NULL, NULL, '2026-05-26 21:54:13'),
(7, 'SMART-290A65131', 15, 16, 'chaudele', 'chaudele@gmail.com', 0, NULL, NULL, '2026-05-26 22:48:34'),
(8, 'SMART-E686E8685', 15, 17, 'Anonymous', '', 0, NULL, NULL, '2026-05-27 00:25:50'),
(9, 'SMART-A3D66E126', 15, 18, 'chasambi', 'chasambi@gmail.com', 0, NULL, NULL, '2026-05-28 22:21:46'),
(10, 'SMART-31507B388', 15, 19, 'fidelis', '', 0, NULL, NULL, '2026-05-31 14:58:27'),
(11, 'SMART-9086E8800', 15, 20, 'mwakimba ', 'mwakimba@gmail.com', 0, NULL, NULL, '2026-05-31 15:00:09'),
(12, 'SMART-9DBF4A673', 15, 21, 'chondoma', 'chondo@gmail.com', 0, NULL, NULL, '2026-05-31 15:15:05'),
(13, 'SMART-662C91505', 15, 22, 'kidomasta', '', 0, NULL, NULL, '2026-05-31 17:04:06'),
(14, 'SMART-45DDF7718', 15, 23, 'kidomasta', '', 0, NULL, NULL, '2026-05-31 17:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `log_type` varchar(50) DEFAULT NULL,
  `log_message` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_admin`
--

CREATE TABLE `user_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('super_admin','admin') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_seen` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_admin`
--

INSERT INTO `user_admin` (`id`, `username`, `password`, `fullname`, `email`, `phone`, `role`, `status`, `last_seen`, `created_at`) VALUES
(1, 'admin', '$2y$10$ulPeTTPkg5KPFnxAx6UjfegNBacBUOxK08uzj7ddsxRBPHaZJtKMu', 'System Administrator', 'kidodombi02@gmail.com', '0615257868', 'super_admin', 'active', NULL, NOW());

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_code` (`promo_code`),
  ADD KEY `feedback_id` (`feedback_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_admin`
--
ALTER TABLE `user_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_admin`
--
ALTER TABLE `user_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rewards`
--
ALTER TABLE `rewards`
  ADD CONSTRAINT `rewards_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
