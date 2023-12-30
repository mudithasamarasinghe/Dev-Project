-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2023 at 06:55 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medix_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_profiles`
--

CREATE TABLE `admin_profiles` (
  `profile_id` int(11) NOT NULL,
  `profile_name` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_profiles`
--

INSERT INTO `admin_profiles` (`profile_id`, `profile_name`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Super', 1, '2023-07-03 20:54:21', '2023-07-03 20:58:36'),
(2, 'Manager1', 1, '2023-07-03 20:54:21', '2023-07-03 20:58:36'),
(3, 'Manager2', 1, '2023-07-03 20:54:21', '2023-07-03 21:00:28'),
(4, 'Manager3', 1, '2023-07-03 20:54:21', '2023-07-03 20:58:36'),
(5, 'Cleark01', 1, '2023-07-03 20:54:21', '2023-07-18 19:53:11'),
(6, 'Cleark2', 1, '2023-07-03 20:54:21', '2023-07-03 20:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE `alert` (
  `id` int(11) NOT NULL,
  `approval_for` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alert`
--

INSERT INTO `alert` (`id`, `approval_for`, `status`, `date_created`, `date_updated`) VALUES
(1, 'purchasereturns', 1, '2023-07-26 00:00:47', '2023-07-26 00:19:39');

-- --------------------------------------------------------

--
-- Table structure for table `back_orders`
--

CREATE TABLE `back_orders` (
  `id` int(30) NOT NULL,
  `receiving_id` int(30) NOT NULL,
  `po_id` int(30) NOT NULL,
  `bo_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = partially received, 2 =received',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `back_orders`
--

INSERT INTO `back_orders` (`id`, `receiving_id`, `po_id`, `bo_code`, `supplier_id`, `amount`, `remarks`, `status`, `date_created`, `date_updated`) VALUES
(48, 68, 60, 'BO-0001', 16, 0, NULL, 2, '2023-07-29 22:13:26', '2023-07-29 22:15:33'),
(49, 70, 61, 'BO-0002', 16, 0, NULL, 0, '2023-07-29 22:28:18', '2023-07-29 22:28:18'),
(50, 71, 61, 'BO-0003', 16, 0, NULL, 0, '2023-07-29 23:22:06', '2023-07-29 23:22:06'),
(51, 72, 61, 'BO-0004', 16, 0, NULL, 0, '2023-07-29 23:24:43', '2023-07-29 23:24:43'),
(52, 73, 61, 'BO-0005', 16, 0, NULL, 0, '2023-07-29 23:24:59', '2023-07-29 23:24:59'),
(53, 74, 61, 'BO-0006', 16, 0, NULL, 0, '2023-07-29 23:25:18', '2023-07-29 23:25:18'),
(54, 75, 61, 'BO-0007', 16, 0, NULL, 0, '2023-07-29 23:27:59', '2023-07-29 23:27:59'),
(55, 76, 61, 'BO-0008', 16, 0, NULL, 0, '2023-07-29 23:29:29', '2023-07-29 23:29:29'),
(56, 77, 62, 'BO-0009', 20, 0, NULL, 0, '2023-07-29 23:30:19', '2023-07-29 23:30:19'),
(57, 78, 62, 'BO-0010', 20, 0, NULL, 0, '2023-07-29 23:30:33', '2023-07-29 23:30:33'),
(58, 79, 62, 'BO-0011', 20, 0, NULL, 0, '2023-07-29 23:30:43', '2023-07-29 23:30:43'),
(59, 80, 62, 'BO-0012', 20, 0, NULL, 0, '2023-07-29 23:48:33', '2023-07-29 23:48:33'),
(60, 81, 62, 'BO-0013', 20, 0, NULL, 0, '2023-07-29 23:49:35', '2023-07-29 23:49:35'),
(61, 82, 62, 'BO-0014', 20, 0, NULL, 0, '2023-07-29 23:51:43', '2023-07-29 23:51:43'),
(62, 83, 62, 'BO-0015', 20, 0, NULL, 0, '2023-07-29 23:52:12', '2023-07-29 23:52:12'),
(63, 84, 62, 'BO-0016', 20, 0, NULL, 0, '2023-07-29 23:52:57', '2023-07-29 23:52:57'),
(64, 85, 63, 'BO-0017', 20, 0, NULL, 0, '2023-07-30 08:59:51', '2023-07-30 08:59:51'),
(65, 86, 64, 'BO-0018', 15, 0, NULL, 0, '2023-07-30 09:01:26', '2023-07-30 09:01:26'),
(66, 87, 64, 'BO-0019', 15, 0, NULL, 0, '2023-07-30 09:02:01', '2023-07-30 09:02:01'),
(67, 88, 65, 'BO-0020', 16, 0, NULL, 0, '2023-07-30 10:36:24', '2023-07-30 10:36:24'),
(68, 89, 65, 'BO-0021', 16, 0, NULL, 0, '2023-07-30 10:36:33', '2023-07-30 10:36:33'),
(69, 90, 65, 'BO-0022', 16, 0, NULL, 0, '2023-07-30 10:39:09', '2023-07-30 10:39:09'),
(70, 91, 64, 'BO-0023', 15, 0, NULL, 0, '2023-07-30 10:39:18', '2023-07-30 10:39:18'),
(75, 96, 67, 'BO-0024', 2, 0, NULL, 0, '2023-07-30 10:45:53', '2023-07-30 10:45:53'),
(76, 97, 67, 'BO-0025', 2, 0, NULL, 0, '2023-07-30 10:45:59', '2023-07-30 10:45:59'),
(77, 98, 67, 'BO-0026', 2, 0, NULL, 0, '2023-07-30 10:46:12', '2023-07-30 10:46:12'),
(78, 99, 67, 'BO-0027', 2, 0, NULL, 0, '2023-07-30 10:53:07', '2023-07-30 10:53:07'),
(79, 100, 67, 'BO-0028', 2, 0, NULL, 0, '2023-07-30 10:53:15', '2023-07-30 10:53:15'),
(80, 101, 67, 'BO-0029', 2, 0, NULL, 0, '2023-07-30 10:54:05', '2023-07-30 10:54:05'),
(81, 102, 68, 'BO-0030', 20, 0, NULL, 0, '2023-07-30 10:55:10', '2023-07-30 10:55:10'),
(82, 103, 68, 'BO-0031', 20, 0, NULL, 0, '2023-07-30 10:55:17', '2023-07-30 10:55:17'),
(83, 104, 68, 'BO-0032', 20, 0, NULL, 0, '2023-07-30 10:58:24', '2023-07-30 10:58:24'),
(84, 105, 68, 'BO-0033', 20, 0, NULL, 0, '2023-07-30 10:59:39', '2023-07-30 10:59:39'),
(85, 106, 68, 'BO-0034', 20, 0, NULL, 0, '2023-07-30 11:01:18', '2023-07-30 11:01:18'),
(86, 107, 69, 'BO-0035', 22, 0, NULL, 0, '2023-07-30 11:02:27', '2023-07-30 11:02:27'),
(87, 108, 69, 'BO-0036', 22, 0, NULL, 0, '2023-07-30 11:02:40', '2023-07-30 11:02:40');

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_materials`
--

CREATE TABLE `bill_of_materials` (
  `bom_id` int(11) NOT NULL,
  `bom_device_name` varchar(255) NOT NULL DEFAULT '',
  `bom_remark` varchar(255) DEFAULT NULL,
  `bom_type` varchar(45) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_of_materials`
--

INSERT INTO `bill_of_materials` (`bom_id`, `bom_device_name`, `bom_remark`, `bom_type`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Manual Wheelchair', 'Manual Wheelchair', NULL, 1, '2023-07-07 19:09:03', '2023-07-07 21:42:56'),
(3, 'FACE SHIELD', 'FACE SHIELD', NULL, 1, '2023-07-10 13:05:43', '2023-07-10 13:05:43'),
(4, 'Cannula', 'Cannula', NULL, 1, '2023-07-12 12:27:36', '2023-07-12 12:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_materials_items`
--

CREATE TABLE `bill_of_materials_items` (
  `bomi_id` int(11) NOT NULL,
  `bom_id` int(11) NOT NULL,
  `bomi_item_name` varchar(255) NOT NULL DEFAULT '',
  `bom_quantity` int(11) NOT NULL DEFAULT 0,
  `bomi_remark` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_of_materials_items`
--

INSERT INTO `bill_of_materials_items` (`bomi_id`, `bom_id`, `bomi_item_name`, `bom_quantity`, `bomi_remark`, `status`, `date_created`, `date_updated`) VALUES
(1, 1, 'Wheelchair Tires', 2, 'Wheelchair Tires', 1, '2023-07-07 19:10:18', '2023-07-07 21:46:16'),
(4, 1, 'Wheels', 2, 'Wheels', 1, '2023-07-07 21:46:16', '2023-07-07 21:46:16'),
(5, 1, 'Casters', 2, 'Casters', 1, '2023-07-07 21:46:16', '2023-07-07 21:49:59'),
(6, 1, 'Tubes for Wheelchairs', 2, 'Tubes for Wheelchairs', 1, '2023-07-07 21:49:59', '2023-07-07 21:49:59'),
(7, 1, 'Tools', 1, 'Tools', 1, '2023-07-07 21:49:59', '2023-07-07 21:49:59'),
(8, 1, 'Wheelchair Legrests', 2, 'Wheelchair Legrests', 1, '2023-07-07 21:49:59', '2023-07-07 21:49:59'),
(9, 1, 'Wheelchair Upholstery', 1, 'Wheelchair Upholstery', 1, '2023-07-07 21:49:59', '2023-07-07 21:49:59'),
(10, 1, 'Armrest Pads', 2, 'Armrest Pads', 1, '2023-07-07 21:49:59', '2023-07-07 21:49:59'),
(11, 1, 'Armrests', 2, 'Armrests', 1, '2023-07-07 21:49:59', '2023-07-07 21:49:59'),
(12, 1, 'Anti-Tippers', 2, 'Anti-Tippers', 1, '2023-07-07 21:49:59', '2023-07-07 21:49:59'),
(23, 3, 'Casters', 1, 'ddddd', 1, '2023-07-12 12:17:03', '2023-07-12 12:17:03'),
(24, 3, 'Wheelchair Tires', 2, 'ddddd', 1, '2023-07-12 12:17:03', '2023-07-12 12:17:03'),
(33, 4, 'Luer Lock plug', 1, 'Luer Lock plug', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(34, 4, 'Flashback chamber', 1, 'Flashback chamber', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(35, 4, 'Needle Grip', 1, 'Needle Grip', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(36, 4, 'Luer Connector', 1, 'Luer Connector', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(37, 4, 'Injection port cap', 1, 'Injection port cap', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(38, 4, 'Wings', 1, 'Wings', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(39, 4, 'Valve', 1, 'Valve', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(40, 4, 'Bushing', 1, 'Bushing', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(41, 4, 'Catheter', 1, 'Catheter', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15'),
(42, 4, 'Needle', 1, 'Needle', 1, '2023-07-12 12:31:15', '2023-07-12 12:31:15');

-- --------------------------------------------------------

--
-- Table structure for table `bo_items`
--

CREATE TABLE `bo_items` (
  `bo_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL,
  `total` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bo_items`
--

INSERT INTO `bo_items` (`bo_id`, `item_id`, `quantity`, `price`, `unit`, `total`) VALUES
(48, 4, 10, 0, 'Pieces', 0),
(49, 4, 90, 0, 'Pieces', 0),
(50, 4, 90, 0, 'Pieces', 0),
(51, 4, 90, 0, 'Pieces', 0),
(52, 4, 90, 0, 'Pieces', 0),
(53, 4, 90, 0, 'Pieces', 0),
(54, 4, 50, 0, 'Pieces', 0),
(55, 4, 100, 0, 'Pieces', 0),
(56, 7, 100, 0, 'Pieces', 0),
(57, 7, 60, 0, 'Pieces', 0),
(58, 7, 80, 0, 'Pieces', 0),
(59, 7, 120, 0, 'Pieces', 0),
(60, 7, 120, 0, 'Pieces', 0),
(61, 7, 120, 0, 'Pieces', 0),
(62, 7, 120, 0, 'Pieces', 0),
(63, 7, 120, 0, 'Pieces', 0),
(64, 7, 5, 0, 'Pieces', 0),
(65, 5, 30, 0, 'Pieces', 0),
(66, 5, 10, 0, 'Pieces', 0),
(67, 4, 14, 0, 'Pieces', 0),
(68, 4, 20, 0, 'Pieces', 0),
(69, 4, 34, 0, 'Pieces', 0),
(70, 5, 40, 0, 'Pieces', 0),
(75, 1, 6, 0, 'Pieces', 0),
(76, 1, 4, 0, 'Pieces', 0),
(77, 1, 10, 0, 'Pieces', 0),
(78, 1, 10, 0, 'Pieces', 0),
(79, 1, 10, 0, 'Pieces', 0),
(80, 1, 10, 0, 'Pieces', 0),
(81, 7, 5, 0, 'Pieces', 0),
(82, 7, 7, 0, 'Pieces', 0),
(83, 7, 12, 0, 'Pieces', 0),
(84, 7, 12, 0, 'Pieces', 0),
(85, 7, 12, 0, 'Pieces', 0),
(86, 2, 4, 0, 'Pieces', 0),
(87, 2, 8, 0, 'Pieces', 0);

-- --------------------------------------------------------

--
-- Table structure for table `disposal`
--

CREATE TABLE `disposal` (
  `disposal_id` int(30) NOT NULL,
  `disposal_code` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(3) NOT NULL DEFAULT 1,
  `disposal_reason` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = handled',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `supplier_id`, `unit`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Cannula', '', 2, 'Pieces', 1, '2023-07-21 22:35:46', '2023-07-22 15:28:22'),
(2, 'Blood Pressure Monitor', '', 22, 'Pieces', 1, '2023-07-22 11:27:22', '2023-07-22 20:53:14'),
(3, 'Digital Thermometer ', '', 22, 'Pieces', 1, '2023-07-22 11:27:47', '2023-07-22 16:45:28'),
(4, 'Stethoscope ', '', 16, 'Pieces', 1, '2023-07-22 11:28:04', '2023-07-22 15:28:42'),
(5, 'Nebulizer', '', 15, 'Pieces', 1, '2023-07-22 11:28:23', '2023-07-22 20:53:03'),
(6, 'Surgical Gloves', '', 21, 'Boxes', 1, '2023-07-22 11:35:34', '2023-07-22 15:28:58'),
(7, 'X-ray Films', '', 20, 'Pieces', 1, '2023-07-22 11:35:50', '2023-07-22 15:29:04'),
(8, 'Oxygen Tank', '', 21, 'Pieces', 1, '2023-07-22 11:36:15', '2023-07-22 22:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `profileid` int(11) DEFAULT NULL,
  `title` varchar(128) NOT NULL DEFAULT '',
  `role_name` varchar(120) DEFAULT NULL,
  `event_id` int(10) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  `url` text DEFAULT NULL,
  `viewed_date` timestamp NULL DEFAULT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0,
  `type` varchar(10) DEFAULT NULL,
  `message` text NOT NULL,
  `repeated` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `profileid`, `title`, `role_name`, `event_id`, `priority`, `start_date`, `end_date`, `url`, `viewed_date`, `viewed`, `type`, `message`, `repeated`, `status`, `date_created`, `date_updated`) VALUES
(37, 2, 'Approval for return', NULL, 2, 0, '2023-07-29 22:29:16', NULL, NULL, NULL, 0, NULL, 'Approval for return', 0, 1, '2023-07-29 22:29:16', '2023-07-30 16:57:29'),
(39, 2, 'Approval for return return id - \'1', 'returns', 1, 0, '2023-07-30 16:55:29', NULL, NULL, NULL, 0, 'delete', 'SU5TRVJUIElOVE8gYHN0b2NrX2xpc3RgIHNldCBpdGVtX2lkPScyJywgYHF1YW50aXR5YCA9ICcxJywgYHVuaXRgID0gJ1BpZWNlcycsYHR5cGVgID0gMiA=', 0, 1, '2023-07-30 16:55:29', '2023-07-30 16:55:29'),
(41, 2, 'This is expired 4', 'expiry', 27, 0, '2023-07-30 17:31:05', NULL, NULL, NULL, 0, '', 'This is expired 4', 0, 0, '2023-07-30 17:31:05', '2023-07-30 18:32:43');

-- --------------------------------------------------------

--
-- Table structure for table `messages_items`
--

CREATE TABLE `messages_items` (
  `msgi_id` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `msg_sql` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE `po_items` (
  `po_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `po_items`
--

INSERT INTO `po_items` (`po_id`, `item_id`, `quantity`, `unit`) VALUES
(60, 4, 25, 'Pieces'),
(61, 4, 100, 'Pieces'),
(62, 7, 120, 'Pieces'),
(63, 7, 10, 'Pieces'),
(64, 5, 40, 'Pieces'),
(65, 4, 34, 'Pieces'),
(66, 1, 34, 'Pieces'),
(67, 1, 10, 'Pieces'),
(68, 7, 12, 'Pieces'),
(69, 2, 12, 'Pieces');

-- --------------------------------------------------------

--
-- Table structure for table `profile_alert`
--

CREATE TABLE `profile_alert` (
  `id` int(11) NOT NULL DEFAULT 0,
  `profileid` int(11) NOT NULL,
  `alertid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_alert`
--

INSERT INTO `profile_alert` (`id`, `profileid`, `alertid`, `status`, `date_created`, `date_updated`) VALUES
(1, 2, 1, 1, '2023-07-26 20:54:06', '2023-07-26 20:54:06'),
(2, 4, 1, 1, '2023-07-27 22:52:23', '2023-07-27 22:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `profile_role`
--

CREATE TABLE `profile_role` (
  `id` int(11) NOT NULL,
  `profileid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_role`
--

INSERT INTO `profile_role` (`id`, `profileid`, `roleid`, `status`, `date_created`, `date_updated`) VALUES
(4, 2, 1, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(7, 2, 2, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(6, 2, 3, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(2, 2, 4, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(8, 2, 5, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(10, 2, 6, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(9, 2, 7, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(15, 2, 8, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(11, 2, 10, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(16, 2, 11, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(18, 2, 12, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(14, 2, 13, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(17, 2, 14, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(12, 2, 15, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(3, 2, 16, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(5, 2, 19, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(13, 2, 20, 1, '2023-07-24 18:06:05', '2023-07-24 18:06:05'),
(3, 4, 15, 1, '2023-07-18 19:55:03', '2023-07-18 19:55:03'),
(2, 4, 16, 1, '2023-07-18 19:55:03', '2023-07-18 19:55:03'),
(2, 5, 4, 1, '2023-07-17 14:41:24', '2023-07-17 14:41:24'),
(5, 5, 6, 1, '2023-07-17 14:41:24', '2023-07-17 14:41:24'),
(4, 5, 7, 1, '2023-07-17 14:41:24', '2023-07-17 14:41:24'),
(7, 5, 12, 1, '2023-07-17 14:41:24', '2023-07-17 14:41:24'),
(6, 5, 13, 1, '2023-07-17 14:41:24', '2023-07-17 14:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(30) NOT NULL,
  `po_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `remarks` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = partially received, 2 =received',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_code`, `supplier_id`, `remarks`, `status`, `date_created`, `date_updated`) VALUES
(60, 'PO-0001', 16, '', 2, '2023-07-29 22:13:00', '2023-07-29 22:15:33'),
(61, 'PO-0002', 16, '', 2, '2023-07-29 22:28:02', '2023-07-29 23:29:29'),
(62, 'PO-0003', 20, '', 2, '2023-07-29 23:30:04', '2023-07-29 23:52:57'),
(63, 'PO-0004', 20, '', 2, '2023-07-30 08:59:06', '2023-07-30 08:59:51'),
(64, 'PO-0005', 15, '', 2, '2023-07-30 09:00:50', '2023-07-30 10:39:18'),
(65, 'PO-0006', 16, '', 2, '2023-07-30 10:36:13', '2023-07-30 10:39:09'),
(67, 'PO-0007', 2, '', 2, '2023-07-30 10:45:35', '2023-07-30 10:54:05'),
(68, 'PO-0008', 20, '', 2, '2023-07-30 10:54:59', '2023-07-30 11:01:18'),
(69, 'PO-0009', 22, '', 1, '2023-07-30 11:02:18', '2023-07-30 11:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `receivings`
--

CREATE TABLE `receivings` (
  `id` int(30) NOT NULL,
  `form_id` int(30) NOT NULL,
  `from_order` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=PO ,2 = BO',
  `amount` float NOT NULL DEFAULT 0,
  `stock_ids` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receivings`
--

INSERT INTO `receivings` (`id`, `form_id`, `from_order`, `amount`, `stock_ids`, `remarks`, `date_created`, `date_updated`) VALUES
(68, 60, 1, 0, '27', '', '2023-07-29 22:13:26', '2023-07-29 22:13:26'),
(69, 48, 2, 0, '28', '', '2023-07-29 22:15:33', '2023-07-29 22:15:33'),
(70, 61, 1, 0, '29', '', '2023-07-29 22:28:18', '2023-07-29 22:28:18'),
(71, 61, 1, 0, '30', '', '2023-07-29 23:22:06', '2023-07-29 23:22:06'),
(72, 61, 1, 0, '31', '', '2023-07-29 23:24:43', '2023-07-29 23:24:43'),
(73, 61, 1, 0, '32', '', '2023-07-29 23:24:59', '2023-07-29 23:24:59'),
(74, 61, 1, 0, '33', '', '2023-07-29 23:25:18', '2023-07-29 23:25:18'),
(75, 61, 1, 0, '34', '', '2023-07-29 23:27:59', '2023-07-29 23:27:59'),
(76, 61, 1, 0, '35', '', '2023-07-29 23:29:29', '2023-07-29 23:29:29'),
(77, 62, 1, 0, '36', '', '2023-07-29 23:30:19', '2023-07-29 23:30:19'),
(78, 62, 1, 0, '37', '', '2023-07-29 23:30:33', '2023-07-29 23:30:33'),
(79, 62, 1, 0, '38', '', '2023-07-29 23:30:43', '2023-07-29 23:30:43'),
(80, 62, 1, 0, '39', '', '2023-07-29 23:48:33', '2023-07-29 23:48:33'),
(81, 62, 1, 0, '40', '', '2023-07-29 23:49:35', '2023-07-29 23:49:35'),
(82, 62, 1, 0, '41', '', '2023-07-29 23:51:43', '2023-07-29 23:51:43'),
(83, 62, 1, 0, '42', '', '2023-07-29 23:52:12', '2023-07-29 23:52:12'),
(84, 62, 1, 0, '43', '', '2023-07-29 23:52:57', '2023-07-29 23:52:57'),
(85, 63, 1, 0, '44', '', '2023-07-30 08:59:51', '2023-07-30 08:59:51'),
(86, 64, 1, 0, '45', '', '2023-07-30 09:01:26', '2023-07-30 09:01:26'),
(87, 64, 1, 0, '46', '', '2023-07-30 09:02:01', '2023-07-30 09:02:01'),
(88, 65, 1, 0, '47', '', '2023-07-30 10:36:24', '2023-07-30 10:36:24'),
(89, 65, 1, 0, '48', '', '2023-07-30 10:36:33', '2023-07-30 10:36:33'),
(90, 65, 1, 0, '49', '', '2023-07-30 10:39:09', '2023-07-30 10:39:09'),
(91, 64, 1, 0, '50', '', '2023-07-30 10:39:18', '2023-07-30 10:39:18'),
(96, 67, 1, 0, '55', '', '2023-07-30 10:45:53', '2023-07-30 10:45:53'),
(97, 67, 1, 0, '56', '', '2023-07-30 10:45:59', '2023-07-30 10:45:59'),
(98, 67, 1, 0, '57', '', '2023-07-30 10:46:12', '2023-07-30 10:46:12'),
(99, 67, 1, 0, '58', '', '2023-07-30 10:53:07', '2023-07-30 10:53:07'),
(100, 67, 1, 0, '59', '', '2023-07-30 10:53:15', '2023-07-30 10:53:15'),
(101, 67, 1, 0, '60', '', '2023-07-30 10:54:05', '2023-07-30 10:54:05'),
(102, 68, 1, 0, '61', '', '2023-07-30 10:55:10', '2023-07-30 10:55:10'),
(103, 68, 1, 0, '62', '', '2023-07-30 10:55:17', '2023-07-30 10:55:17'),
(104, 68, 1, 0, '63', '', '2023-07-30 10:58:23', '2023-07-30 10:58:23'),
(105, 68, 1, 0, '64', '', '2023-07-30 10:59:39', '2023-07-30 10:59:39'),
(106, 68, 1, 0, '65', '', '2023-07-30 11:01:18', '2023-07-30 11:01:18'),
(107, 69, 1, 0, '66', '', '2023-07-30 11:02:27', '2023-07-30 11:02:27'),
(108, 69, 1, 0, '67', '', '2023-07-30 11:02:40', '2023-07-30 11:02:40');

-- --------------------------------------------------------

--
-- Table structure for table `return_list`
--

CREATE TABLE `return_list` (
  `id` int(30) NOT NULL,
  `return_code` varchar(50) NOT NULL,
  `return_approval` int(11) DEFAULT NULL,
  `date_approval` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_list`
--

INSERT INTO `return_list` (`id`, `return_code`, `return_approval`, `date_approval`, `supplier_id`, `amount`, `remarks`, `stock_ids`, `date_created`, `date_updated`) VALUES
(1, 'R-0001', NULL, '1970-01-01 00:00:01', 0, 0, '', '39', '2023-07-30 16:55:29', '2023-07-30 16:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `pagename` varchar(45) DEFAULT NULL,
  `role_order` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `aweclass` varchar(45) DEFAULT NULL,
  `maintenance` tinyint(1) NOT NULL DEFAULT 1,
  `db_name` varchar(45) DEFAULT NULL,
  `bg_color` varchar(45) DEFAULT 'bg-light',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `pagename`, `role_order`, `status`, `aweclass`, `maintenance`, `db_name`, `bg_color`, `date_created`, `date_updated`) VALUES
(1, 'Dashboard', 'admin/index.php', 1, 1, 'fas fa-tachometer-alt', 0, 'notable', 'bg-info', '2021-11-02 10:01:55', '2023-07-18 15:16:53'),
(2, 'Purchase Order', 'admin/index.php?page=purchase_order', 1, 1, 'fas fa-th-list', 0, 'purchase_orders', 'bg-secondary', '2021-11-02 10:01:55', '2023-07-18 15:09:56'),
(3, 'Good received note.', 'admin/index.php?page=receiving', 2, 1, 'fas fa-boxes', 0, 'receivings', 'bg-warning', '2021-11-02 10:02:12', '2023-07-17 09:59:23'),
(4, 'Back order', 'admin/index.php?page=back_order', 3, 1, 'fas fa-exchange-alt', 0, 'back_orders', 'bg-gray', '2021-11-02 10:02:47', '2023-07-18 19:52:50'),
(5, 'Purchase Return', 'admin/index.php?page=return', 4, 1, 'fas fa-undo', 0, 'returns', 'bg-danger', '2021-11-02 10:01:55', '2023-07-25 17:50:20'),
(6, 'Stocks', 'admin/index.php?page=stocks', 5, 1, 'fas fa-table', 0, 'stock_list', 'bg-success', '2021-11-02 10:02:12', '2023-07-25 17:48:23'),
(7, 'Sale list', 'admin/index.php?page=sales', 6, 1, 'fas fa-file-invoice-dollar', 0, 'sales_list', 'bg-pink', '2021-11-02 10:02:27', '2023-07-25 17:49:15'),
(8, 'Supplier list', 'admin/index.php?page=maintenance/supplier', 2, 1, 'fas fa-truck-loading', 1, 'suppliers', 'bg-maroon', '2021-11-02 10:02:47', '2023-07-17 21:57:14'),
(10, 'Item list', 'admin/index.php?page=maintenance/item', 1, 1, 'fas fa-boxes', 1, 'items', 'bg-dark', '2023-07-03 06:46:22', '2023-07-17 21:48:06'),
(11, 'User list', 'admin/index.php?page=user/list', 8, 1, 'fas fa-users', 1, 'users', 'bg-cyan', '2023-07-03 06:56:46', '2023-07-17 22:00:54'),
(12, 'User role', 'admin/index.php?page=maintenance/role', 9, 1, 'fas fa-users', 1, 'role', 'bg-purple', '2023-07-03 06:56:46', '2023-07-17 22:00:18'),
(13, 'Settings', 'admin/index.php?page=system_info', 10, 1, 'fas fa-cogs', 1, 'system_info', 'bg-teal', '2023-07-03 06:57:04', '2023-07-17 22:01:36'),
(14, 'User profile', 'admin/index.php?page=maintenance/userprofile', 11, 1, 'fas fa-users', 1, 'users', 'bg-orange', '2023-07-03 22:07:23', '2023-07-17 21:59:20'),
(15, 'Profile role', 'admin/index.php?page=maintenance/profilerole', 12, 1, 'fas fa-users', 1, 'profile_role', 'bg-success', '2023-07-03 22:18:20', '2023-07-17 21:47:25'),
(16, 'BOM', 'admin/index.php?page=bill_of_materials', 3, 1, 'fas fa-list-ol', 0, 'bill_of_materials', 'bg-info', '2023-07-11 06:09:17', '2023-07-17 21:55:33'),
(19, 'Disposal', 'admin/index.php?page=disposal', 31, 1, 'fas fa-exchange-alt', 0, 'disposal', 'bg-dark', '2023-07-18 20:12:12', '2023-07-18 20:14:56'),
(20, 'Reports', 'admin/index.php?page=reports', 0, 1, '', 1, 'notable', '', '2023-07-24 18:03:59', '2023-07-24 18:07:02');

-- --------------------------------------------------------

--
-- Table structure for table `sales_list`
--

CREATE TABLE `sales_list` (
  `id` int(30) NOT NULL,
  `sales_code` varchar(50) NOT NULL,
  `client` text DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_list`
--

INSERT INTO `sales_list` (`id`, `sales_code`, `client`, `amount`, `remarks`, `stock_ids`, `date_created`, `date_updated`) VALUES
(2, 'SALE-0001', 'Guest', 0, '', '', '2023-07-29 22:29:16', '2023-07-29 22:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `stock_list`
--

CREATE TABLE `stock_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `unit` varchar(250) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=IN , 2=OUT',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_list`
--

INSERT INTO `stock_list` (`id`, `item_id`, `quantity`, `expiry_date`, `unit`, `type`, `date_created`) VALUES
(27, 4, 15, '2023-08-29 00:00:00', 'Pieces', 1, '2023-07-29 22:13:26'),
(28, 4, 10, '2023-07-20 00:00:00', 'Pieces', 1, '2023-07-29 22:15:33'),
(29, 4, 10, '2023-07-13 00:00:00', 'Pieces', 1, '2023-07-29 22:28:18'),
(30, 4, 10, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-29 23:22:06'),
(31, 4, 10, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-29 23:24:43'),
(32, 4, 10, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-29 23:24:59'),
(33, 4, 10, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-29 23:25:18'),
(34, 4, 50, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-29 23:27:59'),
(35, 4, 0, '2023-07-06 00:00:00', 'Pieces', 1, '2023-07-29 23:29:29'),
(36, 7, 20, '2023-07-11 00:00:00', 'Pieces', 1, '2023-07-29 23:30:19'),
(37, 7, 60, '2023-07-14 00:00:00', 'Pieces', 1, '2023-07-29 23:30:33'),
(38, 7, 40, '2023-07-18 00:00:00', 'Pieces', 1, '2023-07-29 23:30:43'),
(39, 7, 0, '2023-06-27 00:00:00', 'Pieces', 1, '2023-07-29 23:48:33'),
(40, 7, 0, '2023-06-27 00:00:00', 'Pieces', 1, '2023-07-29 23:49:35'),
(41, 7, 0, '2023-06-27 00:00:00', 'Pieces', 1, '2023-07-29 23:51:43'),
(42, 7, 0, '2023-06-27 00:00:00', 'Pieces', 1, '2023-07-29 23:52:12'),
(43, 7, 0, '2023-06-27 00:00:00', 'Pieces', 1, '2023-07-29 23:52:57'),
(44, 7, 5, '2023-09-30 00:00:00', 'Pieces', 1, '2023-07-30 08:59:51'),
(45, 5, 10, '2024-01-30 00:00:00', 'Pieces', 1, '2023-07-30 09:01:26'),
(46, 5, 30, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 09:02:01'),
(47, 4, 20, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:36:24'),
(48, 4, 14, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:36:33'),
(49, 4, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:39:09'),
(50, 5, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:39:18'),
(55, 1, 4, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:45:53'),
(56, 1, 6, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:45:59'),
(57, 1, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:46:12'),
(58, 1, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:53:07'),
(59, 1, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:53:15'),
(60, 1, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:54:05'),
(61, 7, 7, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:55:10'),
(62, 7, 5, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:55:17'),
(63, 7, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:58:23'),
(64, 7, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 10:59:39'),
(65, 7, 0, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 11:01:18'),
(66, 2, 8, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 11:02:27'),
(67, 2, 4, '0000-00-00 00:00:00', 'Pieces', 1, '2023-07-30 11:02:40');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `email` varchar(45) NOT NULL,
  `cperson` text NOT NULL,
  `contact` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `email`, `cperson`, `contact`, `status`, `date_created`, `date_updated`) VALUES
(1, 'ABC Medical Supplies', '123 Main Street, City A', 'john.smith@abcmedical.com', 'John Smith', '+1 123-456-7890', 1, '2023-07-21 22:07:31', '2023-07-22 06:50:07'),
(2, 'XYZ Healthcare Solutions', '456 Oak Avenue, City B', 'jane.doe@xyzhealthcare.com', 'Jane Doe', '+2 987-654-3210', 1, '2023-07-21 22:09:46', '2023-07-21 22:19:06'),
(15, 'HealthTech Innovations', '789 Elm Street, City C', 'michael.johnson@healthtech.com', 'Michael Johnson', '+3 111-222-3333', 1, '2023-07-21 22:27:07', '2023-07-21 22:27:07'),
(16, 'MediCo', '234 Maple Road, City D', 'sarah.lee@medico.com', 'Sarah Lee', '+4 444-555-6666', 1, '2023-07-21 22:27:40', '2023-07-21 22:27:40'),
(17, 'Global MedTech Corp', '567 Pine Lane, City E', 'david.wilson@globalmedtech.com', 'David Wilson', '+5 777-888-9999', 1, '2023-07-21 22:28:09', '2023-07-21 22:28:09'),
(18, 'MedEquip Solutions', '890 Cedar Avenue, City F', 'lisa.brown@medequip.com', 'Lisa Brown', '+6 222-333-4444', 1, '2023-07-21 22:28:38', '2023-07-21 22:28:38'),
(19, 'MediPro Services', '1234 Birch Street, City G', 'robert.miller@medipro.com', 'Robert Miller', '+7 999-888-7777', 1, '2023-07-21 22:29:06', '2023-07-21 22:29:06'),
(20, 'HealthMax Technologies', '4321 Willow Court, City H', 'emily.white@healthmax.com', 'Emily White', '+8 333-444-5555', 1, '2023-07-21 22:29:32', '2023-07-21 22:29:32'),
(21, 'MediSurg Innovations', '5678 Aspen Drive, City I', 'james.taylor@medisurg.com', 'James Taylor', '+9 777-666-5555', 1, '2023-07-21 22:30:01', '2023-07-21 22:30:01'),
(22, 'LifeScience Solutions', '876 Oakwood Lane, City J', 'jennifer.adams@lifescience.com', 'Jennifer Adams', '+10 555-444-3333', 1, '2023-07-21 22:30:32', '2023-07-21 22:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'MEDIX'),
(6, 'short_name', 'Medix'),
(11, 'logo', 'uploads/logo-1635816671.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1635816671.png'),
(15, 'content', 'Array');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatar-1.png?v=1635556826', NULL, 2, '2021-01-20 14:02:37', '2023-07-18 22:04:48'),
(10, 'John', NULL, 'Smith', 'jsmith', '39ce7e2a8573b41ce73b5ba41617f8f7', 'uploads/avatar-10.png?v=1635920488', NULL, 2, '2021-11-03 14:21:28', '2021-11-03 14:21:28'),
(11, 'Claire', NULL, 'Blake', 'cblake', 'cd74fae0a3adf459f73bbf187607ccea', 'uploads/avatar-11.png?v=1690287733', NULL, 1, '2021-11-03 14:22:46', '2023-07-25 17:52:13'),
(13, 'Harindra', NULL, 'Ranaweera', 'manager1', 'c240642ddef994358c96da82c0361a58', 'uploads/avatar-13.png?v=1690288197', NULL, 2, '2023-07-03 08:38:04', '2023-07-25 17:59:57'),
(14, 'Muditha ', NULL, 'samarasinghe', 'muditha', 'cd2a04850a9abdeea9ccacb43f0b3065', 'uploads/avatar-14.png?v=1690289845', NULL, 2, '2023-07-25 17:53:45', '2023-07-25 18:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `user_id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `back_orders`
--
ALTER TABLE `back_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `receiving_id` (`receiving_id`);

--
-- Indexes for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  ADD PRIMARY KEY (`bom_id`);

--
-- Indexes for table `bill_of_materials_items`
--
ALTER TABLE `bill_of_materials_items`
  ADD PRIMARY KEY (`bomi_id`),
  ADD KEY `bom_items_ibfk_1` (`bom_id`);

--
-- Indexes for table `bo_items`
--
ALTER TABLE `bo_items`
  ADD KEY `item_id` (`item_id`),
  ADD KEY `bo_id` (`bo_id`);

--
-- Indexes for table `disposal`
--
ALTER TABLE `disposal`
  ADD PRIMARY KEY (`disposal_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `messages_items`
--
ALTER TABLE `messages_items`
  ADD PRIMARY KEY (`msgi_id`),
  ADD KEY `msg_items_ibfk_1` (`msg_id`);

--
-- Indexes for table `po_items`
--
ALTER TABLE `po_items`
  ADD KEY `po_id` (`po_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `profile_alert`
--
ALTER TABLE `profile_alert`
  ADD PRIMARY KEY (`profileid`,`alertid`),
  ADD KEY `alertID` (`profileid`),
  ADD KEY `alert_ibfk_2` (`alertid`);

--
-- Indexes for table `profile_role`
--
ALTER TABLE `profile_role`
  ADD PRIMARY KEY (`profileid`,`roleid`),
  ADD KEY `roleID` (`profileid`),
  ADD KEY `role_ibfk_2` (`roleid`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `receivings`
--
ALTER TABLE `receivings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_list`
--
ALTER TABLE `return_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_list`
--
ALTER TABLE `sales_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_list`
--
ALTER TABLE `stock_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `alert`
--
ALTER TABLE `alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `back_orders`
--
ALTER TABLE `back_orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  MODIFY `bom_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bill_of_materials_items`
--
ALTER TABLE `bill_of_materials_items`
  MODIFY `bomi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `disposal`
--
ALTER TABLE `disposal`
  MODIFY `disposal_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `messages_items`
--
ALTER TABLE `messages_items`
  MODIFY `msgi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `receivings`
--
ALTER TABLE `receivings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `return_list`
--
ALTER TABLE `return_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sales_list`
--
ALTER TABLE `sales_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_list`
--
ALTER TABLE `stock_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `back_orders`
--
ALTER TABLE `back_orders`
  ADD CONSTRAINT `back_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_orders_ibfk_2` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_orders_ibfk_3` FOREIGN KEY (`receiving_id`) REFERENCES `receivings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_of_materials_items`
--
ALTER TABLE `bill_of_materials_items`
  ADD CONSTRAINT `bom_items_ibfk_1` FOREIGN KEY (`bom_id`) REFERENCES `bill_of_materials` (`bom_id`) ON DELETE CASCADE;

--
-- Constraints for table `bo_items`
--
ALTER TABLE `bo_items`
  ADD CONSTRAINT `bo_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bo_items_ibfk_2` FOREIGN KEY (`bo_id`) REFERENCES `back_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages_items`
--
ALTER TABLE `messages_items`
  ADD CONSTRAINT `msg_ibfk_1` FOREIGN KEY (`msg_id`) REFERENCES `messages` (`msg_id`) ON DELETE CASCADE;

--
-- Constraints for table `profile_alert`
--
ALTER TABLE `profile_alert`
  ADD CONSTRAINT `admina_profiles_ibfk_1` FOREIGN KEY (`profileid`) REFERENCES `admin_profiles` (`profile_id`),
  ADD CONSTRAINT `alert_ibfk_2` FOREIGN KEY (`alertid`) REFERENCES `alert` (`id`);

--
-- Constraints for table `profile_role`
--
ALTER TABLE `profile_role`
  ADD CONSTRAINT `adminr_profiles_ibfk_1` FOREIGN KEY (`profileid`) REFERENCES `admin_profiles` (`profile_id`),
  ADD CONSTRAINT `role_ibfk_2` FOREIGN KEY (`roleid`) REFERENCES `role` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `archive_expiry` ON SCHEDULE EVERY 1 DAY STARTS '2023-07-30 17:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
		INSERT INTO messages (profileid, role_name,event_id, type,title, message,status) 
		select '2' as profileid,'expiry' as role_name,id as event_id,'' as type,CONCAT('This is expired', ' ', item_id) AS title,CONCAT('This is expired', ' ', item_id) AS message,'1' as status from stock_list
                where expiry_date between now() and adddate(now(), INTERVAL 30 DAY);   
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
