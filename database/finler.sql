-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 31, 2026 at 03:49 PM
-- Server version: 8.4.7
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finler`
--

-- --------------------------------------------------------

--
-- Table structure for table `financial_profiles`
--

DROP TABLE IF EXISTS `financial_profiles`;
CREATE TABLE IF NOT EXISTS `financial_profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `monthly_income` decimal(10,2) DEFAULT '75000.00',
  `fixed_costs` decimal(10,2) DEFAULT '25000.00',
  `emergency_fund` decimal(10,2) DEFAULT '150000.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

DROP TABLE IF EXISTS `goals`;
CREATE TABLE IF NOT EXISTS `goals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `goal_name` varchar(255) NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `target_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `goal_name`, `target_amount`, `target_date`, `created_at`) VALUES
(1, 4, 'House Construction', 500000.00, '2027-10-01', '2026-10-31 12:10:41');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sub_name` varchar(255) NOT NULL,
  `sub_amount` decimal(10,2) NOT NULL,
  `sub_cycle` varchar(50) DEFAULT 'Monthly',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('credit','debit') NOT NULL DEFAULT 'debit',
  `category` varchar(50) DEFAULT 'Uncategorized',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `date`, `description`, `amount`, `type`, `category`, `created_at`) VALUES
(1, 2, '2026-10-30', 'Zomato - Online Order', 450.00, 'debit', 'Food', '2026-10-31 10:54:04'),
(2, 2, '2026-10-29', 'Netflix Subscription', 199.00, 'debit', 'Entertainment', '2026-10-31 10:54:04'),
(3, 2, '2026-10-28', 'Uber Ride', 320.00, 'debit', 'Transport', '2026-10-31 10:54:04'),
(4, 2, '2026-10-26', 'Salary Credit - RapidEzy', 75000.00, 'credit', 'Income', '2026-10-31 10:54:04'),
(5, 2, '2026-10-25', 'Amazon - Groceries', 1250.00, 'debit', 'Shopping', '2026-10-31 10:54:04'),
(6, 3, '2026-10-30', 'Zomato / Swiggy Order', 450.00, 'debit', 'Food', '2026-10-31 11:07:10'),
(7, 3, '2026-10-28', 'Amazon India', 1250.00, 'debit', 'Shopping', '2026-10-31 11:07:10'),
(8, 3, '2026-10-27', 'Electricity Bill', 1500.00, 'debit', 'Utilities', '2026-10-31 11:07:10'),
(9, 3, '2026-10-26', 'Salary - Axis Bank', 85000.00, 'credit', 'Income', '2026-10-31 11:07:10'),
(10, 4, '2026-09-06', 'UPI/P2A/129141682051/Mr MAHESH/CBIN/UPI', 10.00, 'credit', 'Income', '2026-10-31 11:56:26'),
(11, 4, '2026-09-07', 'UPI/P2M/625022555784/Mr Rathod Ankush Raje/Pay to/UNITY SMALL FINANCE', 10.00, 'debit', 'Payment', '2026-10-31 11:56:26'),
(12, 4, '2026-09-15', 'UPI/P2M/625827436176/Deepa pawar kirana/UPI/YES BANK LIMITED', 65.00, 'debit', 'Food', '2026-10-31 11:56:26'),
(13, 4, '2026-09-22', 'UPI/P2A/626573945027/MAHESH VISHNU CHAVAN/UPI/BANK OF BARODA', 1000.00, 'debit', 'Transfer', '2026-10-31 11:56:26'),
(14, 4, '2026-09-25', 'UPI/P2A/663423789073/MR SANDIP/SCBL/salary f/', 15000.00, 'credit', 'Income', '2026-10-31 11:56:26'),
(15, 4, '2026-09-26', 'ATM-CASH/DIGRAS NCR TOM/YAVATMAL/260926', 2000.00, 'debit', 'Cash Withdrawal', '2026-10-31 11:56:26'),
(16, 4, '2026-09-30', 'UPI/P2A/663966458184/BHARAT VASUDEO KAJBE/UPI/BANK OF INDIA', 416.00, 'credit', 'Income', '2026-10-31 11:56:26'),
(17, 4, '2026-10-31', 'Manual Sub: Yoga Class (Monthly)', 49.00, 'debit', 'Utilities', '2026-10-31 12:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT '0.00',
  `fixed_costs` decimal(10,2) DEFAULT '0.00',
  `emergency_fund` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `failed_attempts` int NOT NULL DEFAULT '0',
  `locked_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `google_id`, `monthly_income`, `fixed_costs`, `emergency_fund`, `created_at`, `failed_attempts`, `locked_until`) VALUES
(4, 'Ishwar Rathod', 'irrathodbanjara@gmail.com', '$2y$10$ANOpz5zN/Qz9yABVfMbfBuBDvJTOukYYhFGYHNLZtjtlntETKzIKK', NULL, 0.00, 0.00, 0.00, '2026-10-31 11:21:43', 0, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
