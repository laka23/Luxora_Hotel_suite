-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 08:50 AM
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
-- Database: `hotel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_logs`
--

CREATE TABLE `action_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `profile_image`) VALUES
(1, 'admin', '123456', 'istockphoto-1399788030-612x612.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_vip` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mybooking`
--

CREATE TABLE `mybooking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `num_adults` int(11) NOT NULL,
  `num_children` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(50) DEFAULT 'fa-bell',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `payment_method` enum('Cash','Card') DEFAULT NULL,
  `status` enum('booked','cancelled','checked_in','checked_out','no_show') DEFAULT 'booked',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `guest_name`, `email`, `phone`, `room_type`, `check_in`, `check_out`, `payment_method`, `status`, `created_at`) VALUES
(1, 'midum', 'midumhansika1997@gmail.com', '0713109876', 'Double', '2025-05-24', '2025-05-29', 'Cash', 'cancelled', '2025-05-23 08:59:44'),
(2, 'Hasnika', 'asc@gmail.com', '0713109875', 'Single', '2025-05-24', '2025-05-26', 'Card', 'cancelled', '2025-05-23 09:00:43'),
(3, 'tharu', 'tharu1234@gmail.com', '0745673456', 'Single room', '2025-06-03', '2025-06-13', 'Card', 'booked', '2025-05-23 09:36:16'),
(4, 'K.K.M.Hansika', 'midum123@gmail.com', '0765860099', 'single room', '2025-06-06', '2025-06-07', 'Card', 'checked_out', '2025-06-02 09:05:20'),
(5, 'Tharindi', 'tharindi@gmail.com', '0719875117', 'Double', '2025-06-02', '2025-06-04', 'Cash', 'cancelled', '2025-06-02 09:10:47'),
(6, 'Nilmini', 'nilmini12@gmail.com', '0765860099', 'Family Room', '2025-06-06', '2025-06-08', 'Cash', 'cancelled', '2025-06-04 07:26:20'),
(7, 'iresha', 'iresh12@gmail.com', '0723456789', 'Single room', '2025-06-07', '2025-06-08', 'Cash', 'cancelled', '2025-06-06 05:03:58'),
(8, 'ganga', 'ganga@gmail.com', '0789876567', 'Single room', '2025-06-07', '2025-06-18', 'Cash', 'cancelled', '2025-06-06 06:21:09'),
(9, 'hiru', 'hiru@gmail.com', '0760987890', 'Penthouse Suite', '2025-06-30', '2025-06-30', 'Cash', 'booked', '2025-06-06 06:23:44'),
(10, 'hiru', 'hiru@gmail.com', '0760987890', 'Honeymoon Suite', '2025-06-30', '2025-06-30', 'Cash', 'booked', '2025-06-06 06:24:11'),
(11, 'midum', 'midumhansika1997@gmail.com', '0713109875', 'Family Room', '2025-06-26', '2025-06-27', 'Cash', 'booked', '2025-06-06 06:25:41'),
(12, 'midum', 'midumhansika1997@gmail.com', '0713109875', 'deluxr room', '2025-07-03', '2025-07-06', 'Cash', 'booked', '2025-06-06 06:28:02'),
(13, 'midum', 'tharindi@gmail.com', '0745678643', 'single room', '2025-06-07', '2025-07-03', 'Cash', 'checked_in', '2025-06-07 07:47:35'),
(14, 'Mahagamage Rusith Nipuna Lakshan', 'rusithnipunalakshan@gmail.com', '0743784919', 'Suite', '2025-07-04', '2025-06-10', 'Card', 'cancelled', '2025-06-08 02:37:36'),
(15, 'jon', 'mahagamage22@icloud.com', '0771145521', 'Luxury Room', '2025-06-14', '2025-06-18', 'Cash', 'booked', '2025-06-08 06:42:52'),
(16, 'kh', 'rusithnipunalakshan@gmail.com', '+2', 'Penthouse Suite', '2025-06-17', '2025-06-27', 'Card', 'booked', '2025-06-08 06:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `beds` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `status` enum('available','reserved','occupied','maintenance') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `beds`, `price`, `image_path`, `rate`, `status`) VALUES
(0, 'jzsbkc', 'Single Room', 2, 160.00, '', NULL, 'available'),
(0, 'jzsbkc', 'Single Room', 2, 160.00, '', NULL, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `service_name` varchar(100) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(250) NOT NULL,
  `address` varchar(255) NOT NULL,
  `Phone` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `profile_picture`, `address`, `Phone`) VALUES
(7, 'kevin', '11', 'kevin11pqrs@gmail.com', '$2y$10$qM9HsIW4AE.S5PX5rCXkXeMDYaH4WQYeII5kOsLJ4MMqb742L3O9O', '2025-05-24 05:52:40', '', '', 0),
(8, 'kevin', '11', 'kevinmh11pqrs@gmail.com', '$2y$10$Q5I54qik8K7B7exXiyTF9u/uIAWwj/wVwVWQp1vFU/aMdgquAO0Ly', '2025-05-24 05:53:31', '', '', 0),
(17, 'azxczx', 'axzxczxc', 'mahagamacszczge22@iclouazxzxxd.com', '$2y$10$Cx1wIY3X2dNaEET2dfxXj.ZsJs.y9RQDwdIRj3TAA/ArsfJGXaSoO', '2025-06-02 11:40:41', '', '', 0),
(18, 'azxczx', 'axzxczxc', 'mahagamacszczge22@isclouazxzxxd.com', '$2y$10$9Z0YRcD3f.FHahYBhjd1U.WBGJH1.vAiQtZxvAsjmqgk/4iGf8KTK', '2025-06-02 11:44:04', '', '', 0),
(15, 'a', 'ax', 'mahagamaczczge22@iclouaxd.com', '$2y$10$w8pEP6.1pSZBDSeEGtk7cOHyiw6f/YpehVepB76zUe0m4NpXxPb56', '2025-06-02 11:35:27', '', '', 0),
(16, 'azxczx', 'axzxczxc', 'mahagamaczczge22@iclouazxzxxd.com', '$2y$10$9zI8Aqt0bkI39EniXjUax.3utzQPXxsrR.vXTy67U.4qV02AZuwOS', '2025-06-02 11:37:50', '', '', 0),
(14, 'a', 'ax', 'mahagamage22@iclouaxd.com', '$2y$10$7DVvQz0NWNGFOZ7FNDZ8gOTPUBMPJT/xHpfjNsnUZjxvdqMR/M4Om', '2025-06-02 11:32:50', '', '', 0),
(1, 'jon', 'dpe', 'mahagamage22@icloud.com', '$2y$10$jvToti9XXqJCONmQrThpq.WMYvXeKAqEoV4Dt93XkZSqRS1cYAYoe', '2025-05-24 05:16:29', '', '', 0),
(9, 'jon', 'dpe', 'mahagamagerusith25@gmail.com', '$2y$10$25pVMSfUHKwPirN/Fp6sNeUcuZBqCXCUuZG4ke22EavHNehtqEahq', '2025-05-24 06:37:54', '', '', 0),
(10, 'jon', 'dpe', 'mahagamzagverusith25@gmail.com', '$2y$10$hB9bJDyQDDLzvU0jSYH7Au87plgg2RxqTf7BGgNQOY07BbTP6GUMS', '2025-05-24 06:42:42', '', '', 0),
(2, 'jon', 'dpe', 'masce22@icloud.com', '$2y$10$wi8O0i0s.ZrFNHWt8t5CTecR183MBWa5237RlNoF/oxaMcYJbmsAW', '2025-05-24 05:26:53', '', '', 0),
(3, 'jon', 'dpe', 'mXsce22@icloud.com', '$2y$10$U0KZNYRtEQgBt1Ue/QigBO5l29kFTlPB4mRstRkaiKee2DJDeC16G', '2025-05-24 05:27:31', '', '', 0),
(4, 'jon', 'dpe', 'mXsceXS22@icloud.com', '$2y$10$iTjLd2rOMvBK1w4JwdNs6eyUnP/DR5jMSuRO3Cqk2yt0Mxyt193Za', '2025-05-24 05:28:49', '', '', 0),
(5, 'jon', 'dpe', 'mXsceXSsfs22@icloud.com', '$2y$10$VnNnZTCqv1/i78sqy3H1zOIvOxRNRtfkkVVu27uUg8KQF33ZEY5Ku', '2025-05-24 05:29:19', '', '', 0),
(6, 'sdcczczczc', 'dpeSxczscz', 'rusithnipunalakshan@gmail.com', '$2y$10$L0HIi1Vt2csjToKNg/JtUeL5D1gnWVzak6Q2iLKxPuFshS7d/UXre', '2025-05-24 05:37:55', 'uploads/user_6_1749051441.png', 'colombo, sri lanaka', 764840005),
(11, 'ddd', 'sss', 'yamadasun@gmail.com', '$2y$10$emj0m3QZGDBjaNZG1xDIAeJMTZKCQaou7CqTAWpFmjC3FL7p61o5q', '2025-06-02 11:21:58', '', '', 0),
(13, 'ddd', 'sss', 'yamasdaccsun@hmoe.com', '$2y$10$RtFpn7XtkPA1FWLBxkhaN.iDmZdrf0o.WYTSdFeedSiyF02y5OxAu', '2025-06-02 11:30:23', '', '', 0),
(12, 'ddd', 'sss', 'yamasdasun@gmail.com', '$2y$10$NF3N/O1ygrduMB0akNMw/OWVrDZONeU/7vL.ENiXCf45fgG/MUUba', '2025-06-02 11:27:31', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `userss`
--

CREATE TABLE `userss` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('clerk','manager','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_logs`
--
ALTER TABLE `action_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`);

--
-- Indexes for table `mybooking`
--
ALTER TABLE `mybooking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `userss`
--
ALTER TABLE `userss`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_logs`
--
ALTER TABLE `action_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mybooking`
--
ALTER TABLE `mybooking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userss`
--
ALTER TABLE `userss`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_logs`
--
ALTER TABLE `action_logs`
  ADD CONSTRAINT `action_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userss` (`user_id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
