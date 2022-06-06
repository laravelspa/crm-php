-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 13, 2021 at 08:27 PM
-- Server version: 10.2.41-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leadh_everest_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `terraleads`
--

CREATE TABLE `terraleads` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `tz` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `cost` varchar(255) DEFAULT NULL,
  `cost_delivery` varchar(255) DEFAULT NULL,
  `landing_cost` varchar(255) DEFAULT NULL,
  `landing_currency` varchar(255) DEFAULT NULL,
  `check_sum` varchar(255) DEFAULT NULL,
  `web_id` varchar(255) DEFAULT NULL,
  `stream_id` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `lead_status` int(11) DEFAULT 0,
  `add_date` datetime DEFAULT NULL,
  `test_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `terraleads`
--

INSERT INTO `terraleads` (`id`, `lead_id`, `campaign_id`, `name`, `country`, `phone`, `tz`, `address`, `cost`, `cost_delivery`, `landing_cost`, `landing_currency`, `check_sum`, `web_id`, `stream_id`, `ip`, `user_agent`, `lead_status`, `add_date`, `test_status`) VALUES
(1, 1, 5522, 'Test', 'Test', 'Test', '0', 'Test', '100', '1', '20', 'USD', '3d28a2a5930116c5a9afd3528db9cf9207c64c73', NULL, NULL, NULL, NULL, 0, '2021-12-09 20:34:53', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `terraleads`
--
ALTER TABLE `terraleads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `terraleads`
--
ALTER TABLE `terraleads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
