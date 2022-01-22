-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2022 at 11:03 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kayakalp_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `patient_bills`
--

DROP TABLE IF EXISTS `patient_bills`;
CREATE TABLE `patient_bills` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `bill_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bill_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `consultation` double(8,2) DEFAULT NULL,
  `room_rent` double(8,2) DEFAULT NULL,
  `diet` double(8,2) DEFAULT NULL,
  `treatments` double(8,2) DEFAULT NULL,
  `lab` double(8,2) DEFAULT NULL,
  `physiotherapy` double(8,2) DEFAULT NULL,
  `naturopathy_and_yoga` double(8,2) DEFAULT NULL,
  `ayurveda` double(8,2) DEFAULT NULL,
  `discount` double(8,2) DEFAULT NULL,
  `misc` double(8,2) DEFAULT NULL,
  `bill_amount` double(8,2) DEFAULT NULL,
  `remaining_amount` double(8,2) DEFAULT NULL,
  `advance_amount` double(8,2) DEFAULT NULL,
  `refundable_amount` double(8,2) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patient_bills`
--
ALTER TABLE `patient_bills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patient_bills`
--
ALTER TABLE `patient_bills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `diet_chart` ADD `bill_id` INT NULL DEFAULT NULL AFTER `end_date`;
ALTER TABLE `opd_tokens` ADD `bill_id` INT(11) NULL DEFAULT NULL AFTER `charges`;
ALTER TABLE `treatment_tokens` ADD `bill_id` INT(11) NULL DEFAULT NULL AFTER `is_special`;
ALTER TABLE `patient_lab_tests` ADD `bill_id` INT(11) NULL DEFAULT NULL AFTER `report_type`;
ALTER TABLE `booking_discounts` ADD `bill_id` INT(11) NULL DEFAULT NULL AFTER `description`;
ALTER TABLE `wallet` ADD `bill_id` INT(11) NULL DEFAULT NULL AFTER `description`;
ALTER TABLE `miscs` ADD `bill_id` INT(11) NULL DEFAULT NULL AFTER `price`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
