-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql-server
-- Generation Time: Jun 05, 2022 at 02:05 PM
-- Server version: 8.0.27
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `credit_card`
--

CREATE TABLE `credit_card` (
  `no` int NOT NULL,
  `card_number` varchar(100) NOT NULL,
  `expiration` date NOT NULL,
  `cvv` int NOT NULL,
  `type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credit_card`
--

INSERT INTO `credit_card` (`no`, `card_number`, `expiration`, `cvv`, `type`) VALUES
(1, '111111', '2025-10-10', 411, 1),
(2, '222222', '2022-11-11', 443, 2),
(3, '333333', '2022-12-12', 577, 3);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `tcode` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `fee` float NOT NULL,
  `fee_of` text NOT NULL,
  `time` text NOT NULL,
  `status` varchar(100) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`tcode`, `username`, `receiver`, `type`, `amount`, `fee`, `fee_of`, `time`, `status`, `message`) VALUES
('140137202206050857751234', '0857751234', '', 'Despoit', 6000000, 0, '', '2022-06-05 14:01:37', 'Success', ''),
('140153202206050857751234', '0857751234', '', 'Withdraw', 1050000, 50000, '', '2022-06-05 14:01:53', 'Success', ''),
('140306202206050857751234', '0857751234', '0343555902', 'Transfer', 1050000, 50000, 'sender', '2022-06-05 14:03:06', 'Success', 'Hello Son<br>From: 0857751234'),
('140306202206050857751234', '0343555902', '0343555902', 'Receive', 1050000, 50000, 'sender', '2022-06-05 14:03:06', 'Success', 'Hello Son<br>From: 0857751234'),
('140401202206050343555902', '0343555902', '', 'Buy Phone Card', 180000, 0, '', '2022-06-05 14:04:01', 'Success', '10.000 VND: 1111130357<br>20.000 VND: 1111192987<br>50.000 VND: 1111125951<br>100.000 VND: 1111112060<br>');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `phonenumber` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `fullname` text NOT NULL,
  `birthday` date NOT NULL,
  `address` text NOT NULL,
  `identity_card_1` text NOT NULL,
  `identity_card_2` text NOT NULL,
  `balance` float NOT NULL,
  `first_login` tinyint(1) NOT NULL,
  `count_password_wrong` int NOT NULL,
  `OTP` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `disable_auto` tinyint(1) NOT NULL,
  `disable_admin` tinyint(1) NOT NULL,
  `time_register_upload_image` datetime NOT NULL,
  `time_count_password_wrong` datetime DEFAULT NULL,
  `time_otp` datetime DEFAULT NULL,
  `time_active` datetime DEFAULT NULL,
  `time_disable_auto` datetime DEFAULT NULL,
  `time_disable_admin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `phonenumber`, `email`, `fullname`, `birthday`, `address`, `identity_card_1`, `identity_card_2`, `balance`, `first_login`, `count_password_wrong`, `OTP`, `active`, `disable_auto`, `disable_admin`, `time_register_upload_image`, `time_count_password_wrong`, `time_otp`, `time_active`, `time_disable_auto`, `time_disable_admin`) VALUES
('0857751234', '11967d5e9addc5416ea9224eee0e91fc', '0857751234', 'huyremoving9031@gmail.com', 'Huy', '2022-06-05', 'None', 'images/0857751234_1.png', 'images/0857751234_2.png', 3900000, 1, 0, '728441', 1, 0, 0, '2022-06-05 13:52:47', NULL, '2022-06-05 14:02:49', NULL, NULL, NULL),
('0343555902', '498d3c6bfa033f6dc1be4fcc3c370aa7', '0343555902', 'son13012002@gmail.com', 'Son', '2022-06-05', 'None', 'images/0343555902_1.png', 'images/0343555902_2.png', 820000, 1, 0, '', 1, 0, 0, '2022-06-05 13:55:57', NULL, NULL, NULL, NULL, NULL),
('0903700092', '498d3c6bfa033f6dc1be4fcc3c370aa7', '0903700092', 'phat@gmail.com', 'Phat', '2022-06-05', 'None', 'images/0343555902_1.png', 'images/0343555902_2.png', 820000, 0, 0, '', 0, 0, 0, '2022-06-05 13:55:57', NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
