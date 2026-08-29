-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2026 at 12:54 PM
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
-- Database: `smart_emergency_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('Super Admin','Admin') NOT NULL DEFAULT 'Admin',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`, `phone`, `role`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Test Admin', 'admin@test.com', 'TEST_ONLY_NOT_FOR_LOGIN', '01700000001', 'Admin', 'Active', '2026-08-29 10:45:55', '2026-08-29 10:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `blood_request_id` int(10) UNSIGNED NOT NULL,
  `citizen_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(10) UNSIGNED DEFAULT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `units_needed` int(10) UNSIGNED NOT NULL,
  `location` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `request_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Matched','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`blood_request_id`, `citizen_id`, `org_id`, `blood_group`, `units_needed`, `location`, `details`, `request_time`, `status`, `updated_at`) VALUES
(1, 2, NULL, 'A+', 2, 'Dhanmondi, Dhaka', 'Urgent blood needed for patient', '2026-08-29 10:50:00', 'Completed', '2026-08-29 10:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `blood_request_donors`
--

CREATE TABLE `blood_request_donors` (
  `match_id` int(10) UNSIGNED NOT NULL,
  `blood_request_id` int(10) UNSIGNED NOT NULL,
  `donor_id` int(10) UNSIGNED NOT NULL,
  `status` enum('Notified','Accepted','Rejected','Completed') NOT NULL DEFAULT 'Notified',
  `matched_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_request_donors`
--

INSERT INTO `blood_request_donors` (`match_id`, `blood_request_id`, `donor_id`, `status`, `matched_at`) VALUES
(1, 1, 1, 'Completed', '2026-08-29 10:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `citizens`
--

CREATE TABLE `citizens` (
  `citizen_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citizens`
--

INSERT INTO `citizens` (`citizen_id`, `name`, `email`, `password`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Rahim Ahmed', 'rahim@test.com', 'TEST_ONLY_NOT_FOR_LOGIN', '01700000002', 'Dhanmondi, Dhaka', '', '2026-08-29 10:45:55', '2026-08-29 10:45:55'),
(3, 'Karim Hasan', 'karim@test.com', 'TEST_ONLY_NOT_FOR_LOGIN', '01700000005', 'Mohammadpur, Dhaka', '', '2026-08-29 10:50:00', '2026-08-29 10:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `donor_id` int(10) UNSIGNED NOT NULL,
  `citizen_id` int(10) UNSIGNED NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `last_donation_date` date DEFAULT NULL,
  `availability_status` enum('Available','Unavailable') NOT NULL DEFAULT 'Available',
  `verification_status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`donor_id`, `citizen_id`, `blood_group`, `last_donation_date`, `availability_status`, `verification_status`, `created_at`, `updated_at`) VALUES
(1, 3, 'A+', '2026-08-29', 'Unavailable', 'Verified', '2026-08-29 10:50:00', '2026-08-29 10:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_requests`
--

CREATE TABLE `emergency_requests` (
  `request_id` int(10) UNSIGNED NOT NULL,
  `citizen_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(10) UNSIGNED DEFAULT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `provider_id` int(10) UNSIGNED DEFAULT NULL,
  `vehicle_id` int(10) UNSIGNED DEFAULT NULL,
  `service_type` varchar(50) NOT NULL,
  `emergency_type` varchar(100) NOT NULL,
  `people_count` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `vehicles_requested` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `location` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `wheelchair_required` tinyint(1) NOT NULL DEFAULT 0,
  `wheelchair_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `injury_present` tinyint(1) NOT NULL DEFAULT 0,
  `injury_level` enum('Minor','Moderate','Severe','Critical') DEFAULT NULL,
  `injury_description` text DEFAULT NULL,
  `status` enum('Pending','Accepted','On The Way','Completed','Rejected','Cancelled') NOT NULL DEFAULT 'Pending',
  `request_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_requests`
--

INSERT INTO `emergency_requests` (`request_id`, `citizen_id`, `org_id`, `service_id`, `provider_id`, `vehicle_id`, `service_type`, `emergency_type`, `people_count`, `vehicles_requested`, `location`, `details`, `wheelchair_required`, `wheelchair_count`, `injury_present`, `injury_level`, `injury_description`, `status`, `request_time`, `updated_at`) VALUES
(1, 2, 1, 1, 1, 1, 'Ambulance', 'Road Accident', 2, 1, 'Dhanmondi 27', 'Two people injured in road accident', 0, 0, 0, NULL, NULL, 'Completed', '2026-08-29 10:45:56', '2026-08-29 10:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `fund_campaigns`
--

CREATE TABLE `fund_campaigns` (
  `campaign_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(10) UNSIGNED NOT NULL,
  `campaign_name` varchar(150) NOT NULL,
  `target_amount` decimal(12,2) NOT NULL,
  `collected_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `end_date` date NOT NULL,
  `status` enum('Active','Completed','Closed') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fund_requests`
--

CREATE TABLE `fund_requests` (
  `fund_request_id` int(10) UNSIGNED NOT NULL,
  `citizen_id` int(10) UNSIGNED DEFAULT NULL,
  `requester_name` varchar(150) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `reviewed_by` int(10) UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `target_type` enum('All','Citizens','Providers','Organizations') NOT NULL DEFAULT 'All',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `org_id` int(10) UNSIGNED NOT NULL,
  `org_name` varchar(150) NOT NULL,
  `org_type` enum('Hospital','Blood Bank','NGO','Other') NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Inactive') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`org_id`, `org_name`, `org_type`, `email`, `password`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dhaka Emergency Organization', 'Hospital', 'organization@test.com', 'TEST_ONLY_NOT_FOR_LOGIN', '01700000003', 'Dhanmondi, Dhaka', 'Approved', '2026-08-29 10:45:55', '2026-08-29 10:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `organization_services`
--

CREATE TABLE `organization_services` (
  `service_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(10) UNSIGNED NOT NULL,
  `service_name` varchar(150) NOT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization_services`
--

INSERT INTO `organization_services` (`service_id`, `org_id`, `service_name`, `service_type`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Emergency Ambulance Service', 'Ambulance', '24 hour emergency ambulance service', 'Active', '2026-08-29 10:45:56', '2026-08-29 10:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `provider_availability`
--

CREATE TABLE `provider_availability` (
  `availability_id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `availability_status` enum('Available','Busy','Offline') NOT NULL DEFAULT 'Offline',
  `working_from` time DEFAULT NULL,
  `working_to` time DEFAULT NULL,
  `is_24_hours` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provider_availability`
--

INSERT INTO `provider_availability` (`availability_id`, `provider_id`, `availability_status`, `working_from`, `working_to`, `is_24_hours`, `updated_at`) VALUES
(1, 1, 'Available', NULL, NULL, 1, '2026-08-29 10:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `provider_vehicles`
--

CREATE TABLE `provider_vehicles` (
  `vehicle_id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `vehicle_type` varchar(100) NOT NULL,
  `total_vehicles` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `available_vehicles` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provider_vehicles`
--

INSERT INTO `provider_vehicles` (`vehicle_id`, `provider_id`, `vehicle_type`, `total_vehicles`, `available_vehicles`, `created_at`, `updated_at`) VALUES
(1, 1, 'Basic Ambulance', 5, 3, '2026-08-29 10:45:56', '2026-08-29 10:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `request_status_history`
--

CREATE TABLE `request_status_history` (
  `history_id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `status` enum('Pending','Accepted','On The Way','Completed','Rejected','Cancelled') NOT NULL,
  `updated_by_type` enum('Citizen','Provider','Organization','Admin','System') NOT NULL,
  `updated_by_id` int(10) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_status_history`
--

INSERT INTO `request_status_history` (`history_id`, `request_id`, `status`, `updated_by_type`, `updated_by_id`, `note`, `updated_at`) VALUES
(1, 1, 'Pending', 'Citizen', 2, 'Emergency request created', '2026-08-29 10:45:56'),
(2, 1, 'Accepted', 'Provider', 1, 'Provider accepted the emergency request', '2026-08-29 10:47:47'),
(3, 1, 'On The Way', 'Provider', 1, 'Provider is on the way', '2026-08-29 10:48:33'),
(4, 1, 'Completed', 'Provider', 1, 'Emergency request completed', '2026-08-29 10:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(10) UNSIGNED NOT NULL,
  `citizen_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(10) UNSIGNED DEFAULT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `provider_id` int(10) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_areas`
--

CREATE TABLE `service_areas` (
  `area_id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `base_area` varchar(150) NOT NULL,
  `service_range_km` int(10) UNSIGNED NOT NULL,
  `covered_areas` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_areas`
--

INSERT INTO `service_areas` (`area_id`, `provider_id`, `base_area`, `service_range_km`, `covered_areas`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dhanmondi', 10, 'Dhanmondi, Kalabagan, Mohammadpur, Farmgate', '2026-08-29 10:45:56', '2026-08-29 10:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `provider_id` int(10) UNSIGNED NOT NULL,
  `org_id` int(10) UNSIGNED DEFAULT NULL,
  `provider_name` varchar(150) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Pending','Verified','Rejected','Inactive') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`provider_id`, `org_id`, `provider_name`, `service_type`, `email`, `password`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'ABC Ambulance Service', 'Ambulance', 'provider@test.com', 'TEST_ONLY_NOT_FOR_LOGIN', '01700000004', 'Dhanmondi, Dhaka', 'Verified', '2026-08-29 10:45:56', '2026-08-29 10:45:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`blood_request_id`),
  ADD KEY `fk_blood_request_citizen` (`citizen_id`),
  ADD KEY `fk_blood_request_org` (`org_id`);

--
-- Indexes for table `blood_request_donors`
--
ALTER TABLE `blood_request_donors`
  ADD PRIMARY KEY (`match_id`),
  ADD UNIQUE KEY `unique_blood_donor_match` (`blood_request_id`,`donor_id`),
  ADD KEY `fk_match_donor` (`donor_id`);

--
-- Indexes for table `citizens`
--
ALTER TABLE `citizens`
  ADD PRIMARY KEY (`citizen_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `citizen_id` (`citizen_id`);

--
-- Indexes for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_request_citizen` (`citizen_id`),
  ADD KEY `fk_request_org` (`org_id`),
  ADD KEY `fk_request_service` (`service_id`),
  ADD KEY `fk_request_provider` (`provider_id`),
  ADD KEY `fk_request_vehicle` (`vehicle_id`);

--
-- Indexes for table `fund_campaigns`
--
ALTER TABLE `fund_campaigns`
  ADD PRIMARY KEY (`campaign_id`),
  ADD KEY `fk_campaign_org` (`org_id`);

--
-- Indexes for table `fund_requests`
--
ALTER TABLE `fund_requests`
  ADD PRIMARY KEY (`fund_request_id`),
  ADD KEY `fk_fund_request_citizen` (`citizen_id`),
  ADD KEY `fk_fund_request_admin` (`reviewed_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notification_admin` (`admin_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`org_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `organization_services`
--
ALTER TABLE `organization_services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `fk_org_service_org` (`org_id`);

--
-- Indexes for table `provider_availability`
--
ALTER TABLE `provider_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD UNIQUE KEY `provider_id` (`provider_id`);

--
-- Indexes for table `provider_vehicles`
--
ALTER TABLE `provider_vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `unique_provider_vehicle_type` (`provider_id`,`vehicle_type`);

--
-- Indexes for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_history_request` (`request_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_review_citizen` (`citizen_id`),
  ADD KEY `fk_review_org` (`org_id`),
  ADD KEY `fk_review_service` (`service_id`),
  ADD KEY `fk_review_provider` (`provider_id`);

--
-- Indexes for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD PRIMARY KEY (`area_id`),
  ADD UNIQUE KEY `provider_id` (`provider_id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`provider_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_provider_org` (`org_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `blood_request_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blood_request_donors`
--
ALTER TABLE `blood_request_donors`
  MODIFY `match_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `citizens`
--
ALTER TABLE `citizens`
  MODIFY `citizen_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `donor_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  MODIFY `request_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fund_campaigns`
--
ALTER TABLE `fund_campaigns`
  MODIFY `campaign_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fund_requests`
--
ALTER TABLE `fund_requests`
  MODIFY `fund_request_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `org_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organization_services`
--
ALTER TABLE `organization_services`
  MODIFY `service_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `provider_availability`
--
ALTER TABLE `provider_availability`
  MODIFY `availability_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `provider_vehicles`
--
ALTER TABLE `provider_vehicles`
  MODIFY `vehicle_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_status_history`
--
ALTER TABLE `request_status_history`
  MODIFY `history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_areas`
--
ALTER TABLE `service_areas`
  MODIFY `area_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `provider_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD CONSTRAINT `fk_blood_request_citizen` FOREIGN KEY (`citizen_id`) REFERENCES `citizens` (`citizen_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_blood_request_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `blood_request_donors`
--
ALTER TABLE `blood_request_donors`
  ADD CONSTRAINT `fk_match_blood_request` FOREIGN KEY (`blood_request_id`) REFERENCES `blood_requests` (`blood_request_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_match_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `fk_donor_citizen` FOREIGN KEY (`citizen_id`) REFERENCES `citizens` (`citizen_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  ADD CONSTRAINT `fk_request_citizen` FOREIGN KEY (`citizen_id`) REFERENCES `citizens` (`citizen_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_request_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_request_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`provider_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_request_service` FOREIGN KEY (`service_id`) REFERENCES `organization_services` (`service_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_request_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `provider_vehicles` (`vehicle_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fund_campaigns`
--
ALTER TABLE `fund_campaigns`
  ADD CONSTRAINT `fk_campaign_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fund_requests`
--
ALTER TABLE `fund_requests`
  ADD CONSTRAINT `fk_fund_request_admin` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fund_request_citizen` FOREIGN KEY (`citizen_id`) REFERENCES `citizens` (`citizen_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `organization_services`
--
ALTER TABLE `organization_services`
  ADD CONSTRAINT `fk_org_service_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `provider_availability`
--
ALTER TABLE `provider_availability`
  ADD CONSTRAINT `fk_availability_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`provider_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `provider_vehicles`
--
ALTER TABLE `provider_vehicles`
  ADD CONSTRAINT `fk_vehicle_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`provider_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD CONSTRAINT `fk_history_request` FOREIGN KEY (`request_id`) REFERENCES `emergency_requests` (`request_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_citizen` FOREIGN KEY (`citizen_id`) REFERENCES `citizens` (`citizen_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`provider_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_service` FOREIGN KEY (`service_id`) REFERENCES `organization_services` (`service_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD CONSTRAINT `fk_area_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`provider_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD CONSTRAINT `fk_provider_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
