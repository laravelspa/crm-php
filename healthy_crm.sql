-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2021 at 07:34 PM
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
-- Database: `healthy_crm`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`healthy`@`localhost` PROCEDURE `add_project` (IN `name` VARCHAR(210), IN `phone` VARCHAR(50), IN `address` VARCHAR(250), IN `city` VARCHAR(50), IN `pname` VARCHAR(200), IN `prname` VARCHAR(200), IN `prpieces` INT(11), IN `prprice` FLOAT, IN `prcurrency` CHAR(5), IN `emp_id` INT(11), IN `status` CHAR(10), IN `pending_comment` VARCHAR(250), IN `pending_date` VARCHAR(30), IN `pending_time` VARCHAR(30), IN `doo` DATE, IN `dod` DATE, IN `created_at` TIMESTAMP, IN `created_by` INT(11), IN `db_id` INT, IN `lead_from` VARCHAR(255))  MODIFIES SQL DATA
BEGIN 
    DECLARE duplicate_key INT DEFAULT 0;
    BEGIN
        DECLARE EXIT HANDLER FOR 1062 SET duplicate_key = 1;

        INSERT INTO pending(db_id,name,phone,address,pname,prname,prprice,prpieces,prcurrency,emp_id,status,doo,dod,created_at,created_by,lead_from)     VALUES(db_id,name,phone,address,pname,prname,prprice,prpieces,prcurrency,emp_id,status,doo,dod,created_at,created_by,lead_from);
    END;

IF duplicate_key = 1 THEN
        INSERT INTO  pending_dublicate(db_id,name,phone,address,pname,prname,prprice,prpieces,prcurrency,emp_id,status,doo,dod,created_at,created_by,lead_from) 
                VALUES(db_id,name,phone,address,pname,prname,prprice,prpieces,prcurrency,emp_id,status,doo,dod,created_at,created_by,lead_from);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT 1,
  `wall` tinyint(2) NOT NULL DEFAULT 0,
  `projects` varchar(200) DEFAULT NULL,
  `online` tinyint(4) NOT NULL DEFAULT 0,
  `supervisor` int(11) DEFAULT NULL,
  `location` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `permission`, `wall`, `projects`, `online`, `supervisor`, `location`) VALUES

(1, 'admin', '$2y$10$1a.EbQfpVpvk4ZfZhFBazeHuDEGDPo9Z8Ek4dsgFqQLf5nU91Wsky', 0, 1, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `canceld`
--

CREATE TABLE `canceld` (
  `id` int(11) NOT NULL,
  `db_id` int(11) DEFAULT NULL,
  `pending_id` int(11) DEFAULT NULL,
  `name` varchar(210) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `pname` varchar(200) NOT NULL,
  `prname` varchar(200) NOT NULL,
  `prprice` varchar(5) NOT NULL,
  `prpieces` int(11) NOT NULL,
  `prcurrency` varchar(200) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `status` varchar(210) NOT NULL,
  `canceld_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `lead_from` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `canceld`
--


--
-- Triggers `canceld`
--
DELIMITER $$
CREATE TRIGGER `after_canceld_insert` AFTER INSERT ON `canceld` FOR EACH ROW REPLACE INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_id,NEW.pending_id,'cancel',NEW.status,NEW.canceld_at)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `databases_connections`
--

CREATE TABLE `databases_connections` (
  `id` int(11) NOT NULL,
  `db_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `db_table` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `db_user` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `db_password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `network_ads` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `landing_url` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `prname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `prprice` float NOT NULL,
  `prcurrency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `databases_connections`
--


-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderd`
--

CREATE TABLE `orderd` (
  `id` int(11) NOT NULL,
  `db_id` int(11) DEFAULT NULL,
  `pending_id` int(11) DEFAULT NULL,
  `name` varchar(210) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `pname` varchar(100) NOT NULL,
  `prname` varchar(100) NOT NULL,
  `prpieces` int(11) NOT NULL,
  `prprice` float NOT NULL,
  `prcurrency` varchar(100) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `address` varchar(210) NOT NULL,
  `city` varchar(50) NOT NULL,
  `doo` date NOT NULL,
  `dod` date NOT NULL,
  `tod` varchar(30) DEFAULT NULL,
  `wod` varchar(15) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `comment` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lead_from` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orderd`
--

--
-- Triggers `orderd`
--
DELIMITER $$
CREATE TRIGGER `after_orderd_insert` AFTER INSERT ON `orderd` FOR EACH ROW REPLACE INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_id,NEW.pending_id,'approved',NEW.comment,NEW.created_at)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_orderd_update` AFTER UPDATE ON `orderd` FOR EACH ROW IF(NEW.status = 2) THEN
UPDATE products_inventory SET quantity = quantity - NEW.prpieces WHERE product_name = NEW.prname AND inventory_id = 1;
ELSEIF(NEW.status = 3) THEN
UPDATE products_inventory SET quantity = quantity - NEW.prpieces WHERE product_name = NEW.prname AND inventory_id = 2;
ELSEIF(NEW.status = 4) THEN
UPDATE products_inventory SET quantity = quantity - NEW.prpieces WHERE product_name = NEW.prname AND inventory_id = 3;
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orderd_delivery`
--

CREATE TABLE `orderd_delivery` (
  `id` int(11) NOT NULL,
  `orderd_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `d_comment` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aramex_code` varchar(210) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approved` int(11) NOT NULL DEFAULT 0,
  `delivery_time` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_date` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_call_id` int(11) DEFAULT NULL,
  `emp_delivery_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `orderd_delivery`
--
DELIMITER $$
CREATE TRIGGER `after_od_insert` AFTER INSERT ON `orderd_delivery` FOR EACH ROW INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'distributed to call',NEW.d_comment,NEW.created_at)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_od_update` AFTER UPDATE ON `orderd_delivery` FOR EACH ROW IF(NEW.status = 1) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'waiting call',NEW.d_comment,NEW.updated_at);
ELSEIF (NEW.status = 2) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'ready',NEW.d_comment,NEW.updated_at);
ELSEIF (NEW.status = 3 AND NEW.approved = 0) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'taken a code',NULL,NEW.updated_at);
ELSEIF (NEW.status = 3 AND NEW.approved = 1) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'canceld approved',NULL,NEW.updated_at);
ELSEIF (NEW.status = 3 AND NEW.approved = 2) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'approved',NULL,NEW.updated_at);

ELSEIF (NEW.status = 4 AND NEW.emp_delivery_id IS NULL) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'distributed to call',NULL,NEW.updated_at);

ELSEIF (NEW.status = 5 AND NEW.emp_delivery_id IS NULL) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'waiting call',NEW.d_comment,NEW.updated_at);

ELSEIF (NEW.status = 6  AND NEW.emp_delivery_id IS NULL) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_call_id,NEW.orderd_id,'distributed to supervisor delivery',NEW.d_comment,NEW.updated_at);

ELSEIF (NEW.status = 6 AND NEW.emp_delivery_id IS NOT NULL AND NEW.approved = 0) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_delivery_id,NEW.orderd_id,'distributed to supervisor assistant',NULL,NEW.updated_at);

ELSEIF (OLD.status = 6 AND NEW.status = 7 AND NEW.emp_delivery_id IS NOT NULL AND NEW.emp_delivery_id !=  OLD.emp_delivery_id AND NEW.approved = 0) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_delivery_id,NEW.orderd_id,'distributed to delivery man',NULL,NEW.updated_at);

ELSEIF (OLD.status = 7 AND NEW.status = 7 AND NEW.emp_delivery_id = OLD.emp_delivery_id IS NOT NULL AND NEW.approved = 0) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_delivery_id,NEW.orderd_id,'edit order by delivery man',NULL,NEW.updated_at);

ELSEIF (NEW.status = 7 AND NEW.emp_delivery_id IS NOT NULL AND NEW.approved = 1) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_delivery_id,NEW.orderd_id,'delivered canceld by delivery man',NULL,NEW.updated_at);

ELSEIF (NEW.status = 7 AND NEW.emp_delivery_id IS NOT NULL AND NEW.approved = 2) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_delivery_id,NEW.orderd_id,'delivered by delivery man',NULL,NEW.updated_at);

ELSEIF (NEW.status = 8) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_delivery_id,NEW.orderd_id,'approved by supervisor assistant',NULL,NEW.updated_at);

ELSEIF (NEW.status = 9) THEN
INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_delivery_id,NEW.orderd_id,'approved by supervisor delivery',NULL,NEW.updated_at);
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pending`
--

CREATE TABLE `pending` (
  `id` int(11) NOT NULL,
  `db_id` int(11) DEFAULT NULL,
  `name` varchar(210) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `pname` varchar(200) NOT NULL,
  `prname` varchar(200) NOT NULL,
  `prprice` float NOT NULL,
  `prpieces` int(11) NOT NULL DEFAULT 1,
  `prcurrency` char(5) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `status` char(10) DEFAULT NULL,
  `pending_comment` varchar(250) DEFAULT NULL,
  `pending_date` varchar(30) DEFAULT NULL,
  `pending_time` varchar(30) DEFAULT NULL,
  `doo` date NOT NULL,
  `dod` date DEFAULT NULL,
  `tod` varchar(30) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lead_from` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pending`
--

--
-- Triggers `pending`
--
DELIMITER $$
CREATE TRIGGER `after_pending_insert` AFTER INSERT ON `pending` FOR EACH ROW UPDATE project_count SET count = count + 1, created_at = NEW.created_at WHERE name = NEW.pname
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_pending_update` AFTER UPDATE ON `pending` FOR EACH ROW INSERT INTO sales_history(emp_id,order_id,action,comment,created_at) VALUES(NEW.emp_id,NEW.id,NEW.status,NEW.pending_comment,NEW.created_at)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pending_dublicate`
--

CREATE TABLE `pending_dublicate` (
  `id` int(11) NOT NULL,
  `db_id` int(11) DEFAULT NULL,
  `name` varchar(210) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `pname` varchar(200) NOT NULL,
  `prname` varchar(200) NOT NULL,
  `prprice` float NOT NULL,
  `prpieces` int(11) NOT NULL DEFAULT 1,
  `prcurrency` char(5) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `status` char(10) DEFAULT NULL,
  `pending_comment` varchar(250) DEFAULT NULL,
  `pending_date` varchar(30) DEFAULT NULL,
  `pending_time` varchar(30) DEFAULT NULL,
  `doo` date NOT NULL,
  `dod` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lead_from` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pending_dublicate`
--


-- --------------------------------------------------------

--
-- Table structure for table `products_inventory`
--

CREATE TABLE `products_inventory` (
  `id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_count`
--

CREATE TABLE `project_count` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_count`
--


-- --------------------------------------------------------

--
-- Table structure for table `sales_history`
--

CREATE TABLE `sales_history` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_history`
--

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
  `lead_status` varchar(100) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `test_status` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `terraleads`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_supervisor` (`supervisor`);

--
-- Indexes for table `canceld`
--
ALTER TABLE `canceld`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_id` (`emp_id`),
  ADD KEY `fk_dbid4` (`db_id`);

--
-- Indexes for table `databases_connections`
--
ALTER TABLE `databases_connections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderd`
--
ALTER TABLE `orderd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_id2` (`emp_id`),
  ADD KEY `fk_dbid5` (`db_id`);

--
-- Indexes for table `orderd_delivery`
--
ALTER TABLE `orderd_delivery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emp_callID` (`emp_call_id`),
  ADD KEY `fk_emp_deliveyID` (`emp_delivery_id`),
  ADD KEY `fk_delivery_orderID` (`orderd_id`);

--
-- Indexes for table `pending`
--
ALTER TABLE `pending`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `fk_admin_id3` (`emp_id`),
  ADD KEY `fk_created_by` (`created_by`),
  ADD KEY `fk_dbid` (`db_id`);

--
-- Indexes for table `pending_dublicate`
--
ALTER TABLE `pending_dublicate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_id5` (`emp_id`),
  ADD KEY `fk_created_by1` (`created_by`),
  ADD KEY `fk_dbid6` (`db_id`);

--
-- Indexes for table `products_inventory`
--
ALTER TABLE `products_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventory_products` (`inventory_id`);

--
-- Indexes for table `project_count`
--
ALTER TABLE `project_count`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sales_history`
--
ALTER TABLE `sales_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_id4` (`emp_id`);

--
-- Indexes for table `terraleads`
--
ALTER TABLE `terraleads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `canceld`
--
ALTER TABLE `canceld`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=332;

--
-- AUTO_INCREMENT for table `databases_connections`
--
ALTER TABLE `databases_connections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderd`
--
ALTER TABLE `orderd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `orderd_delivery`
--
ALTER TABLE `orderd_delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending`
--
ALTER TABLE `pending`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=728;

--
-- AUTO_INCREMENT for table `pending_dublicate`
--
ALTER TABLE `pending_dublicate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `products_inventory`
--
ALTER TABLE `products_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_count`
--
ALTER TABLE `project_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sales_history`
--
ALTER TABLE `sales_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1521;

--
-- AUTO_INCREMENT for table `terraleads`
--
ALTER TABLE `terraleads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_supervisor` FOREIGN KEY (`supervisor`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `canceld`
--
ALTER TABLE `canceld`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`emp_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dbid4` FOREIGN KEY (`db_id`) REFERENCES `databases_connections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orderd`
--
ALTER TABLE `orderd`
  ADD CONSTRAINT `fk_admin_id2` FOREIGN KEY (`emp_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dbid5` FOREIGN KEY (`db_id`) REFERENCES `databases_connections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orderd_delivery`
--
ALTER TABLE `orderd_delivery`
  ADD CONSTRAINT `fk_delivery_orderID` FOREIGN KEY (`orderd_id`) REFERENCES `orderd` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emp_callID` FOREIGN KEY (`emp_call_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emp_deliveyID` FOREIGN KEY (`emp_delivery_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pending`
--
ALTER TABLE `pending`
  ADD CONSTRAINT `fk_admin_id3` FOREIGN KEY (`emp_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dbid` FOREIGN KEY (`db_id`) REFERENCES `databases_connections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pending_dublicate`
--
ALTER TABLE `pending_dublicate`
  ADD CONSTRAINT `fk_admin_id5` FOREIGN KEY (`emp_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_created_by1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dbid6` FOREIGN KEY (`db_id`) REFERENCES `databases_connections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `products_inventory`
--
ALTER TABLE `products_inventory`
  ADD CONSTRAINT `fk_inventory_products` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `sales_history`
--
ALTER TABLE `sales_history`
  ADD CONSTRAINT `fk_admin_id4` FOREIGN KEY (`emp_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
