-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 08, 2021 at 08:04 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock_pwa`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` char(255) NOT NULL,
  `category_status` char(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` char(255) NOT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_pin` varchar(255) NOT NULL,
  `activation_key` varchar(255) NOT NULL,
  `key_validity` tinyint(1) NOT NULL DEFAULT 1,
  `trial` tinyint(1) NOT NULL DEFAULT 0,
  `subscription_uuid` varchar(255) NOT NULL,
  `subscription_expiry` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '00',
  `login_token` varchar(255) NOT NULL,
  `code` int(11) NOT NULL DEFAULT 0,
  `token_valid` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` char(255) NOT NULL,
  `credit_limit` char(255) NOT NULL,
  `contact_number` char(255) NOT NULL,
  `location_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `contact_person_name` char(255) NOT NULL,
  `contact_person_email` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_acquisition`
--

CREATE TABLE `inventory_acquisition` (
  `acquisition_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `stock_subtracted` int(11) DEFAULT NULL,
  `stock_remaining` int(11) DEFAULT NULL,
  `issued_by` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `removed_by` varchar(255) DEFAULT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `location_description` char(255) NOT NULL,
  `location_status` char(255) NOT NULL,
  `created_by` char(255) DEFAULT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `material_id` int(11) NOT NULL,
  `item_name` char(255) NOT NULL,
  `material_type` enum('Hardware','Aluminum','Powder') NOT NULL,
  `material_code` char(255) NOT NULL,
  `serial_number` char(255) NOT NULL,
  `image_url` char(255) NOT NULL,
  `min_threshold` int(11) NOT NULL,
  `max_threshold` int(11) NOT NULL,
  `pricing` int(11) NOT NULL,
  `quantity` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_in`
--

CREATE TABLE `stock_in` (
  `stockin_entryid` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `location_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `lpo` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `delivery_note_number` int(11) NOT NULL,
  `vehicle` varchar(255) NOT NULL,
  `start_mileage` int(11) NOT NULL,
  `stop_mileage` int(11) NOT NULL,
  `powder` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `material` varchar(255) NOT NULL,
  `price_per_item` int(11) NOT NULL,
  `cost_per_item` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `minimum_threshold` int(11) NOT NULL,
  `maximum_threshold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `subcategory_description` char(255) NOT NULL,
  `subcategory_status` char(255) NOT NULL,
  `created_by` char(255) NOT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `super_users`
--

CREATE TABLE `super_users` (
  `entry_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `code` int(11) NOT NULL,
  `token_validity` tinyint(1) NOT NULL DEFAULT 0,
  `reset_token` varchar(255) NOT NULL DEFAULT '0',
  `reset_validity` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `entry_id` int(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `super_admin` tinyint(1) NOT NULL DEFAULT 0

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `vendor_email` char(255) NOT NULL,
  `vendor_mobile` char(255) NOT NULL,
  `vendor_description` char(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `warehouse_name` varchar(255) NOT NULL,
  `location_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `warehouse_status` char(255) DEFAULT NULL,
  `warehouse_description` char(255) NOT NULL,
  `created_by` char(255) NOT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchase order`
--

CREATE TABLE `purchase_order` (
  `purchase_order_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `record_date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `quotation_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `terms_and_conditions` char(255) DEFAULT NULL,
  `quotation_reference` char(255) DEFAULT NULL,
  `select_project` char(255) DEFAULT NULL,
  `cash_purchase` tinyint(1) NOT NULL DEFAULT 1,
  `tax_inclusive` tinyint(1) NOT NULL DEFAULT 0,
  `po_status` char(255) NOT NULL,
  `item` int(11) NOT NULL,
  `item_description` char(255) NOT NULL,
  `document` char(255) DEFAULT NULL,
  `memo` char(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `discount` int(11) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `created_by` char(255) DEFAULT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `record_date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `quotation_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `terms_and_conditions` char(255) DEFAULT NULL,
  `quotation_reference` char(255) DEFAULT NULL,
  `select_project` char(255) DEFAULT NULL,
  `cash_purchase` tinyint(1) NOT NULL DEFAULT 1,
  `tax_inclusive` tinyint(1) NOT NULL DEFAULT 0,
  `purchase_status` char(255) NOT NULL,
  `item` int(11) NOT NULL,
  `purchase_description` char(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `discount` int(11) DEFAULT NULL,
  `document` char(255) DEFAULT NULL,
  `memo` char(255) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `created_by` char(255) DEFAULT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_to` varchar(255) NOT NULL,
  `payment_amount` int(11) NOT NULL,
  `payment_description` char(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `payment_mode` enum('Cash','Cheque','Credit/Debit Card', 'Internet Banking') NOT NULL,
  `payment_from` char(255) DEFAULT NULL,
  `payment_type` enum('Against Purchases/Expenses','Adavance Payment','Other Payment') NOT NULL,
  `tds_deducted` int(11) NOT NULL,
  `created_by` char(255) DEFAULT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `expense_amount` int(11) NOT NULL,
  `expense_date` datetime DEFAULT NULL,
  `paid_from` varchar(255) DEFAULT NULL,
  `tax` int(11) NOT NULL,
  `expense_status` char(255) DEFAULT NULL,
  `document` char(255) DEFAULT NULL,
  `memo` char(255) DEFAULT NULL,
  `narration` char(255) DEFAULT NULL,
  `expense_type` varchar(255) NOT NULL,
  `created_by` char(255) DEFAULT NULL,
  `updated_by` char(255) DEFAULT NULL,
  `expense_description` char(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`);


--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`purchase_order_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `activation_key` (`activation_key`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `inventory_acquisition`
--
ALTER TABLE `inventory_acquisition`
  ADD PRIMARY KEY (`acquisition_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_id`);

--
-- Indexes for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`stockin_entryid`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`subcategory_id`);

--
-- Indexes for table `super_users`
--
ALTER TABLE `super_users`
  ADD PRIMARY KEY (`entry_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`entry_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_acquisition`
--
ALTER TABLE `inventory_acquisition`
  MODIFY `acquisition_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `stockin_entryid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `super_users`
--
ALTER TABLE `super_users`
  MODIFY `entry_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `entry_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
