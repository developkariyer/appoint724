-- phpMyAdmin SQL Dump
-- version 5.2.1deb2ubuntu2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2024 at 09:04 PM
-- Server version: 8.0.36-2ubuntu3
-- PHP Version: 8.3.4

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `authidentities`
--

INSERT INTO `authidentities` (`id`, `user_id`, `type`, `secret`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`, `authKey`) VALUES
(2, 4, 'password', '$2y$13$NFLeI1TIGJE67nYb56G2yODJFBWOHwzRczunJhe2jSWEXS/W9aEXS', NULL, NULL, 0, NULL, '2024-04-11 16:10:28', NULL, NULL),
(4, 4, 'sms_otp', '$2y$13$S32XkeqPND5nMuayEhhCTO0pCfhzGWVJ7F.n0hVB4UiPOjmUEZkJ.', '2024-04-11 16:12:41', NULL, 0, NULL, '2024-04-11 16:12:33', NULL, '8G7xNx4hkujcsel64YRYzU64XnAT-GG4'),
(7, 1, 'sms_otp', '$2y$13$xGDHQsK/EXlL6QDOGL6A6e8Oa1ajtOWKa6bzkNnp0LbA.Gu11jaEG', '2024-04-11 20:31:43', NULL, 0, NULL, '2024-04-11 20:28:43', NULL, 'SO5UTmGExTAZIucTurHyAgCK4e1maqIh'),
(9, 1, 'sms_otp', '$2y$13$sGt3dIBOUaPXPkV1mgYaguJzqXHR2k.BWhdjQCGLzfe5nEjmFQfJy', '2024-04-14 18:17:20', NULL, 0, NULL, '2024-04-14 18:17:12', NULL, 'BEV3eaNDb4koKkn8AD7-52HwsBVXBCER'),
(11, 1, 'sms_otp', '$2y$13$NuvHikmBwyidKF0QS6icNOg1Kl2OEXE00RCJplGqxnYeaMAwrRvVi', '2024-04-15 07:15:43', NULL, 0, NULL, '2024-04-15 07:15:35', NULL, 'VrO_XMSMndDsONxjLPb78slApsX1-fLF'),
(12, 1, 'sms_otp', '$2y$13$uJ.N/jTMklYWXsSXmk.dVusiflvAxIxySrNT5rVBBYZfn7xTS9fee', '2024-04-15 14:40:14', NULL, 0, NULL, '2024-04-15 14:40:05', NULL, '1wjtp0jtVcOGi9oXX8Cywb9DydekR5sG'),
(16, 1, 'sms_otp', '$2y$13$KkxwP0IaNyQ3yaX7vYyJ9OeU.mpcngLGuVNXkuBF8w.ojC85yfP0O', '2024-04-16 06:53:48', NULL, 0, NULL, '2024-04-16 06:53:40', NULL, 'bq0g03rXi44oPgI2cxEePzLweJLn0Tww'),
(17, 1, 'sms_otp', '$2y$13$JCu5owo7Ohhvs/I6KP8fPuF4NWMQCJvYYn6Bm3WXqy0SGaZnIrciO', '2024-04-16 06:56:32', NULL, 0, NULL, '2024-04-16 06:56:24', NULL, 'oy2EfgMX1jCHjaCIEhjc6A6Qiy5ThBbV'),
(18, 1, 'sms_otp', '$2y$13$JkeZOJC1XV2gQDIWdLrvPusDA2NbiAcY77w1ng8mlr5o.2rcHmTsW', '2024-04-16 07:04:54', NULL, 0, NULL, '2024-04-16 07:04:46', NULL, 'jmhai1t1nc2mbGIrIAN9uRY6nv7j7sfL'),
(19, 1, 'sms_otp', '$2y$13$x1J/.EdXU92OSrzWNNjf1OThXGpejyhNgA6MjXhYU61ol8b3bkIFO', '2024-04-16 08:49:22', NULL, 0, NULL, '2024-04-16 08:49:11', NULL, 'curbyp0MZaVs4IDYUcf1H6CwIB5AZITb'),
(20, 1, 'email_token', '$2y$13$VtYjToIMVMCfJe2DshXmcOZwvrI2U.oT8cwtc8Fme59nRPfde44eK', '2024-04-16 09:02:23', NULL, 0, NULL, '2024-04-16 09:01:38', NULL, '0UoICFL5gPu8LVAj47aLpkuUrIEtV5bM'),
(21, 1, 'email_token', '$2y$13$2zEkKGr.DIHCWcwa1JNP8eaYmrzbUuvTIsTvSjh/.OpaLWjbZE/rq', '2024-04-16 09:17:56', NULL, 0, NULL, '2024-04-16 09:17:44', NULL, 'ZS8-qo0dizV5vihJ9za-L9qasyXjKY4F'),
(22, 1, 'sms_otp', '$2y$13$b7zODR0GhbyUO7xanWdXMeLpGWXU0sgps1qTMEYEr3lRJbHqvNP6K', '2024-04-16 15:21:14', NULL, 0, NULL, '2024-04-16 15:21:04', NULL, 'Qf9-PXE-2kCYJ39uezu478iciQ-dybxl'),
(23, 1, 'sms_otp', '$2y$13$szlXDdzFZ3dr4S2UJM5Ig.rPuLzvwIQ3IXnFez6S3iXNW7qdWu5xS', '2024-04-16 15:22:28', NULL, 0, NULL, '2024-04-16 15:22:19', NULL, 'vk2ONiT2DkaEho-WbumiF6HVbgvrM2Vw'),
(24, 1, 'sms_otp', '$2y$13$QT8Y0D8rVVH4ATvpZrJ9FOE10clJh.CCCuXDjuE1mOnQ7KWL5ivea', '2024-04-16 15:24:31', NULL, 0, NULL, '2024-04-16 15:24:23', NULL, 'jpQ43Vj6UMG6nNYDLcTmO7ei9OJ6Ins_'),
(25, 1, 'sms_otp', '$2y$13$l8aOqG86e6M4owAROa1MC.T89dzKlTl6XZt1eVlCABM1lJ7NrrRc2', '2024-04-16 15:26:12', NULL, 0, NULL, '2024-04-16 15:26:05', NULL, 'EfyTiMZo9u0LHy-73TANwsDPe_56hJf4'),
(26, 1, 'email_token', '$2y$13$crW/s9CfgIr4k1aOelBZpOu6ieDrLTnGOCZ18.hGsRzhFMzDVHC5q', '2024-04-16 19:51:09', NULL, 0, NULL, '2024-04-16 19:41:09', NULL, 'Al4W5EGlbKIHIEO3rLhHhMHP-baQK4TJ'),
(27, 1, 'email_token', '$2y$13$wqo/ZpbkwHxEFHfYujyEsukbEpajzW7wM5YbjzxH6qc8S8YeU52S6', '2024-04-16 19:42:07', NULL, 0, NULL, '2024-04-16 19:41:55', NULL, 'FJO4xSFXP5lm7pB0Ev27YrRY2MqJ0Dsk'),
(28, 1, 'sms_otp', '$2y$13$Sq1/4NurR2m6ibU72FGLyuqaOpCpU.mviZ1SzKOROQ.YxJ.g1bCEC', '2024-04-16 20:01:27', NULL, 0, NULL, '2024-04-16 20:01:18', NULL, 'beu_7TX9h5xHXpbusqNR0TYhtJSeczRy'),
(29, 1, 'email_token', '$2y$13$0TjUxXdwq9vtp/KfLwVHC.v4a9keJTs4G/O7g99fBrZA0a/oRssaC', '2024-04-16 20:03:16', NULL, 0, NULL, '2024-04-16 20:03:04', NULL, '5ss3YQyGO6AfEm4kKFS8JWMrSRKCID-g'),
(30, 1, 'sms_otp', '$2y$13$FEh4.uSqlw53MZJyabnkc.IhSHxa6siksDaHHjoI08eocybMfgNK.', '2024-04-16 20:03:32', NULL, 0, NULL, '2024-04-16 20:03:22', NULL, 'jHnD_oPQk7rY5L-1Z8WT1_kRWYN6NlGE'),
(31, 1, 'sms_otp', '$2y$13$TxLC/vx2wj8j393t.azTpOAmqAQMTtvB1wPES7p.VOFl/Xd.Dt0E6', '2024-04-16 20:17:24', NULL, 0, NULL, '2024-04-16 20:17:15', NULL, 'gUseJwopAKt2E5k1KxZZCCQwguJPkJNo'),
(32, 1, 'email_token', '$2y$13$KqPXCOPHMcJ4j3ogPdpY7uQyD0S/eSxtcPmupgngMZLCPSDqbcOVe', '2024-04-16 20:50:04', NULL, 0, NULL, '2024-04-16 20:49:52', NULL, 'BUIuIIEmiV6KJ9FUGrc9uoGH-48sRY44');

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

DROP TABLE IF EXISTS `businesses`;
CREATE TABLE `businesses` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Europe/Istanbul',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `name`, `slug`, `timezone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dental Dentist', 'dentaldent', 'Europe/Istanbul', NULL, NULL, NULL),
(2, 'Super Dent', 'superdent', 'Europe/Istanbul', NULL, NULL, NULL),
(6, 'Appointment SAAS', 'appointmentsaas', 'Europe/Istanbul', '2024-04-15 07:56:23', NULL, NULL);

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
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'sms_validate', '5330338197', NULL, '2024-04-16 20:01:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int UNSIGNED NOT NULL,
  `event_type` enum('user_created','user_updated','user_deleted','user_auth_added','user_business_added','user_business_updated','user_business_deleted','business_created','business_updated','business_deleted','appointment_created','appointment_updated','appointment_deleted','resource_created','resource_updated','resource_deleted','rule_created','rule_updated','rule_deleted','service_created','service_updated','service_deleted','appointment_resource_added','appointment_resource_deleted','appointment_user_added','appointment_user_deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` json NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `event_type`, `event`, `created_at`) VALUES
(1, 'user_auth_added', '\"{\\\"id\\\":1,\\\"changed\\\":{\\\"id\\\":27,\\\"user_id\\\":1,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$wqo\\\\/ZpbkwHxEFHfYujyEsukbEpajzW7wM5YbjzxH6qc8S8YeU52S6\\\",\\\"expires\\\":\\\"2024-04-16 19:51:55\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"FJO4xSFXP5lm7pB0Ev27YrRY2MqJ0Dsk\\\"}}\"', '2024-04-16 19:41:55'),
(2, 'user_updated', '\"{\\\"id\\\":1,\\\"changed\\\":[]}\"', '2024-04-16 19:41:57'),
(3, 'business_updated', '\"{\\\"id\\\":1,\\\"changed\\\":{\\\"name\\\":\\\"Dental Dent\\\"}}\"', '2024-04-16 19:44:14'),
(4, 'user_auth_added', '\"{\\\"id\\\":1,\\\"changed\\\":{\\\"id\\\":28,\\\"user_id\\\":1,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$Sq1\\\\/4NurR2m6ibU72FGLyuqaOpCpU.mviZ1SzKOROQ.YxJ.g1bCEC\\\",\\\"expires\\\":\\\"2024-04-16 20:04:18\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"beu_7TX9h5xHXpbusqNR0TYhtJSeczRy\\\"}}\"', '2024-04-16 20:01:18'),
(5, 'user_auth_added', '\"{\\\"id\\\":1,\\\"changed\\\":{\\\"id\\\":29,\\\"user_id\\\":1,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$0TjUxXdwq9vtp\\\\/KfLwVHC.v4a9keJTs4G\\\\/O7g99fBrZA0a\\\\/oRssaC\\\",\\\"expires\\\":\\\"2024-04-16 20:13:04\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"5ss3YQyGO6AfEm4kKFS8JWMrSRKCID-g\\\"}}\"', '2024-04-16 20:03:04'),
(6, 'user_auth_added', '\"{\\\"id\\\":1,\\\"changed\\\":{\\\"id\\\":30,\\\"user_id\\\":1,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$FEh4.uSqlw53MZJyabnkc.IhSHxa6siksDaHHjoI08eocybMfgNK.\\\",\\\"expires\\\":\\\"2024-04-16 20:06:22\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"jHnD_oPQk7rY5L-1Z8WT1_kRWYN6NlGE\\\"}}\"', '2024-04-16 20:03:22'),
(7, 'user_auth_added', '\"{\\\"id\\\":31,\\\"changed\\\":{\\\"id\\\":31,\\\"user_id\\\":1,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$TxLC\\\\/vx2wj8j393t.azTpOAmqAQMTtvB1wPES7p.VOFl\\\\/Xd.Dt0E6\\\",\\\"expires\\\":\\\"2024-04-16 20:20:15\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"gUseJwopAKt2E5k1KxZZCCQwguJPkJNo\\\"}}\"', '2024-04-16 20:17:15'),
(8, 'user_auth_added', '\"{\\\"id\\\":32,\\\"changed\\\":{\\\"id\\\":32,\\\"user_id\\\":1,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$KqPXCOPHMcJ4j3ogPdpY7uQyD0S\\\\/eSxtcPmupgngMZLCPSDqbcOVe\\\",\\\"expires\\\":\\\"2024-04-16 20:59:52\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"BUIuIIEmiV6KJ9FUGrc9uoGH-48sRY44\\\"}}\"', '2024-04-16 20:49:52');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `status`, `status_message`, `first_name`, `last_name`, `tcno`, `gsm`, `email`, `dogum_yili`, `tcnoverified`, `gsmverified`, `emailverified`, `language`, `superadmin`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, 'Umut', 'Demirhan', '23416086000', '5330338197', 'umut@kariyerfora.com', 1977, 1, 1, 1, NULL, 1, NULL, NULL, NULL, NULL),
(2, NULL, NULL, 'Hüseyin', 'Mumay', NULL, '+905445868624', 'ideametrik@gmail.com', 1982, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL),
(3, NULL, NULL, 'Burhan', 'Çalhan', NULL, '+905057958150', 'calhan.bur@gmail.com', 1981, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL),
(4, NULL, NULL, 'Test', 'Customer', '12345678901', '1234567890', 'develop@kariyerfora.com', 1990, 0, 1, 1, 'tr', 0, NULL, '2024-04-11 16:10:28', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD KEY `user_id` (`user_id`) ;

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_users_user_id_fk_idx` (`user_id`),
  ADD KEY `permissions_businesses_business_id_fk_idx` (`business_id`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users_businesses`
--
ALTER TABLE `users_businesses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `authidentities`
--
ALTER TABLE `authidentities`
  ADD CONSTRAINT `authidentities_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_businesses_business_id_fk` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`),
  ADD CONSTRAINT `permissions_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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

--
-- Constraints for table `users_businesses`
--
ALTER TABLE `users_businesses`
  ADD CONSTRAINT `businesses_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_businesses_business_id_fk` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
