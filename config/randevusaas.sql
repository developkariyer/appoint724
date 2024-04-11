-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 11, 2024 at 11:17 AM
-- Server version: 8.0.36-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `randevusaas`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL COMMENT 'Always, UTC. So MySQL''s date and time related functions cannot be used in queries!',
  `end_time` datetime NOT NULL,
  `status` enum('New','Approved','Set','Business Cancelled','Customer Cancelled','Customer Noshow','Rescheduled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New' COMMENT '''New'': Customer created\\\\\\\\n''Approved'': Customer created, business approved\\\\\\\\n''Set'': Business created\\\\\\\\n''Business Cancelled'': Business cancelled\\\\\\\\n''Customer Cancelled: Customer cancelled\\\\\\\\n''Customer Noshow'': Customer did not come\\\\\\\\n''Rescheduled'': Rescheduled by agreement',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `appointments`
--

TRUNCATE TABLE `appointments`;
-- --------------------------------------------------------

--
-- Table structure for table `appointments_resources`
--

DROP TABLE IF EXISTS `appointments_resources`;
CREATE TABLE `appointments_resources` (
  `id` int UNSIGNED NOT NULL,
  `appointment_id` int UNSIGNED NOT NULL,
  `resource_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `appointments_resources`
--

TRUNCATE TABLE `appointments_resources`;
-- --------------------------------------------------------

--
-- Table structure for table `appointments_users`
--

DROP TABLE IF EXISTS `appointments_users`;
CREATE TABLE `appointments_users` (
  `id` int UNSIGNED NOT NULL,
  `appointment_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `role` enum('expert','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `appointments_users`
--

TRUNCATE TABLE `appointments_users`;
-- --------------------------------------------------------

--
-- Table structure for table `authidentities`
--

DROP TABLE IF EXISTS `authidentities`;
CREATE TABLE `authidentities` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `type` enum('email_token','sms_otp','password','google','facebook','twitter') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` mediumtext COLLATE utf8mb4_unicode_ci,
  `force_reset` tinyint(1) NOT NULL DEFAULT '0',
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `authKey` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ;

--
-- Truncate table before insert `authidentities`
--

TRUNCATE TABLE `authidentities`;
-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

DROP TABLE IF EXISTS `businesses`;
CREATE TABLE `businesses` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Europe/Istanbul',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `businesses`
--

TRUNCATE TABLE `businesses`;
--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `name`, `timezone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dental Dent', 'Europe/Istanbul', NULL, NULL, NULL),
(2, 'Super Dent', 'Europe/Istanbul', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logappointments`
--

DROP TABLE IF EXISTS `logappointments`;
CREATE TABLE `logappointments` (
  `id` int UNSIGNED NOT NULL,
  `event_type` enum('created','updated','resource_added','resource_deleted','customer_added','customer_deleted','expert_added','expert_deleted') COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` json NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `logappointments`
--

TRUNCATE TABLE `logappointments`;
-- --------------------------------------------------------

--
-- Table structure for table `logbusinesses`
--

DROP TABLE IF EXISTS `logbusinesses`;
CREATE TABLE `logbusinesses` (
  `id` int UNSIGNED NOT NULL,
  `event_type` enum('created','updated','resource_added','resource_updated','customer_added','customer_updated','expert_added','expert_updated','rule_added','rule_updated','service_added','service_updated') COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` json NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `logbusinesses`
--

TRUNCATE TABLE `logbusinesses`;
-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

DROP TABLE IF EXISTS `logins`;
CREATE TABLE `logins` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `logins`
--

TRUNCATE TABLE `logins`;
-- --------------------------------------------------------

--
-- Table structure for table `logusers`
--

DROP TABLE IF EXISTS `logusers`;
CREATE TABLE `logusers` (
  `id` int UNSIGNED NOT NULL,
  `event_type` enum('created','updated','deleted','auth_added') COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` json NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `logusers`
--

TRUNCATE TABLE `logusers`;
-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `permission` json NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ;

--
-- Truncate table before insert `permissions`
--

TRUNCATE TABLE `permissions`;
-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `resource_type` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `resources`
--

TRUNCATE TABLE `resources`;
--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `business_id`, `resource_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Muayene', '2024-04-11 08:13:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

DROP TABLE IF EXISTS `rules`;
CREATE TABLE `rules` (
  `id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `rules`
--

TRUNCATE TABLE `rules`;
-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int UNSIGNED NOT NULL COMMENT 'Preset services given to customers. Used for fast appointment setting.',
  `business_id` int UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_type` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expert_type` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `services`
--

TRUNCATE TABLE `services`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tcno` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gsm` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dogum_yili` smallint UNSIGNED DEFAULT NULL,
  `tcnoverified` tinyint(1) NOT NULL DEFAULT '0',
  `gsmverified` tinyint(1) NOT NULL DEFAULT '0',
  `emailverified` tinyint(1) NOT NULL DEFAULT '0',
  `language` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'tr',
  `superadmin` tinyint NOT NULL DEFAULT '0',
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `status`, `status_message`, `first_name`, `last_name`, `tcno`, `gsm`, `email`, `dogum_yili`, `tcnoverified`, `gsmverified`, `emailverified`, `language`, `superadmin`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, 'Umut', 'Demirhan', '23416086000', '+905330338197', 'umut@kariyerfora.com', 1977, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL),
(2, NULL, NULL, 'Hüseyin', 'Mumay', NULL, '+905445868624', 'ideametrik@gmail.com', 1982, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL),
(3, NULL, NULL, 'Burhan', 'Çalhan', NULL, '+905057958150', 'calhan.bur@gmail.com', 1981, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_businesses`
--

DROP TABLE IF EXISTS `users_businesses`;
CREATE TABLE `users_businesses` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `role` enum('admin','secretary','expert','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ;

--
-- Truncate table before insert `users_businesses`
--

TRUNCATE TABLE `users_businesses`;
--
-- Dumping data for table `users_businesses`
--

INSERT INTO `users_businesses` (`id`, `user_id`, `business_id`, `role`, `created_at`, `deleted_at`) VALUES
(1, 1, 1, 'admin', '2024-04-11 08:13:46', NULL),
(2, 1, 2, 'customer', '2024-04-11 08:13:46', NULL),
(3, 2, 1, 'customer', '2024-04-11 08:13:46', NULL),
(4, 3, 1, 'customer', '2024-04-11 08:13:46', NULL),
(5, 3, 2, 'customer', '2024-04-11 08:13:46', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_businesses_business_id_fk_idx` (`business_id`);

--
-- Indexes for table `appointments_resources`
--
ALTER TABLE `appointments_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_resources_resource_id_fk_idx` (`resource_id`),
  ADD KEY `resources_appointments_appointment_id_fk_idx` (`appointment_id`);

--
-- Indexes for table `appointments_users`
--
ALTER TABLE `appointments_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_users_user_id_fk_idx` (`user_id`),
  ADD KEY `users_appointments_appointment_id_fk_idx` (`appointment_id`);

--
-- Indexes for table `authidentities`
--
ALTER TABLE `authidentities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logappointments`
--
ALTER TABLE `logappointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logbusinesses`
--
ALTER TABLE `logbusinesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `logusers`
--
ALTER TABLE `logusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_users_user_id_fk_idx` (`user_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resources_businesses_business_id_fk_idx` (`business_id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rules_businesses_business_id_fk_idx` (`business_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `services_businesses_business_id_fk_idx` (`business_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gsm_UNIQUE` (`gsm`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexes for table `users_businesses`
--
ALTER TABLE `users_businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_businesses_unique` (`user_id`,`business_id`),
  ADD KEY `users_businesses_business_id_fk_idx` (`business_id`),
  ADD KEY `businesses_users_user_id_fk_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments_resources`
--
ALTER TABLE `appointments_resources`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments_users`
--
ALTER TABLE `appointments_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `authidentities`
--
ALTER TABLE `authidentities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logappointments`
--
ALTER TABLE `logappointments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logbusinesses`
--
ALTER TABLE `logbusinesses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Preset services given to customers. Used for fast appointment setting.';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_businesses`
--
ALTER TABLE `users_businesses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_businesses_business_id_fk` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`);

--
-- Constraints for table `appointments_resources`
--
ALTER TABLE `appointments_resources`
  ADD CONSTRAINT `appointments_resources_resource_id_fk` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`),
  ADD CONSTRAINT `resources_appointments_appointment_id_fk` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

--
-- Constraints for table `appointments_users`
--
ALTER TABLE `appointments_users`
  ADD CONSTRAINT `appointments_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_appointments_appointment_id_fk` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

--
-- Constraints for table `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_businesses_business_id_fk` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`);

--
-- Constraints for table `rules`
--
ALTER TABLE `rules`
  ADD CONSTRAINT `rules_businesses_business_id_fk` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_businesses_business_id_fk` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
