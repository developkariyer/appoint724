-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2024 at 11:28 AM
-- Server version: 8.0.36-2ubuntu3
-- PHP Version: 8.3.6

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

CREATE TABLE `appointments` (
  `id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL COMMENT 'Always, UTC. So MySQL''s date and time related functions cannot be used in queries!',
  `end_time` datetime NOT NULL,
  `status` enum('New','Approved','Set','Business Cancelled','Customer Cancelled','Customer Noshow','Rescheduled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New' COMMENT '''New'': Customer created\\\\\\\\n''Approved'': Customer created, business approved\\\\\\\\n''Set'': Business created\\\\\\\\n''Business Cancelled'': Business cancelled\\\\\\\\n''Customer Cancelled: Customer cancelled\\\\\\\\n''Customer Noshow'': Customer did not come\\\\\\\\n''Rescheduled'': Rescheduled by agreement',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments_resources`
--

CREATE TABLE `appointments_resources` (
  `id` int UNSIGNED NOT NULL,
  `appointment_id` int UNSIGNED NOT NULL,
  `resource_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments_users`
--

CREATE TABLE `appointments_users` (
  `id` int UNSIGNED NOT NULL,
  `appointment_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `role` enum('expert','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `authidentities`
--

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
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
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
(32, 1, 'email_token', '$2y$13$KqPXCOPHMcJ4j3ogPdpY7uQyD0S/eSxtcPmupgngMZLCPSDqbcOVe', '2024-04-16 20:50:04', NULL, 0, NULL, '2024-04-16 20:49:52', NULL, 'BUIuIIEmiV6KJ9FUGrc9uoGH-48sRY44'),
(33, 5, 'email_token', '$2y$13$dlj37EY4gAmcPfjENDghoue16JEMIeGvgt4GRCoU7ptdMd.XuzlQ2', '2024-04-17 20:59:16', NULL, 0, NULL, '2024-04-17 20:59:04', NULL, 'ZE-L1vkj6Lzs82OzFYalbF_2amu7w1ET'),
(34, 1, 'sms_otp', '$2y$13$SP0ciu9.RAzFXYMjqNYP5ee2Q60SSnvHq22FU3A8m4t0aS8pYey2S', '2024-04-18 06:52:01', NULL, 0, NULL, '2024-04-18 06:51:53', NULL, 'lUh7wdoDfAStcdKcYEve5_640yXdQeVn'),
(35, 1, 'email_token', '$2y$13$hXOZcIT9fTv.QNX5oI1cwu3mHTc0Buv2DeQEPdgOv7u1IlrEwmDnC', '2024-04-18 09:06:24', NULL, 0, NULL, '2024-04-18 09:06:12', NULL, 'FTSxbyw2YGzrHnURnt0waxyr5l47xBDG'),
(36, 1, 'email_token', '$2y$13$ubiUdXMuVvj9TL2WKwRUMuG8hUSO8NmRUu1Z2s9MfzScytNPHLXy2', '2024-04-19 08:28:14', NULL, 0, NULL, '2024-04-19 08:27:59', NULL, 'AVxAt8k81PS6yq2Z0bDfvGSX_8bxJdLS'),
(37, 2, 'sms_otp', '$2y$13$i1CZ7GxwdGBMrFf5IqRGRez05KTf7BoGkGJUh/b3yfYbEL3Zfgm56', '2024-04-19 08:40:35', NULL, 0, NULL, '2024-04-19 08:40:22', NULL, '7XLsA4AbTuuVNDlRSOIwDy-3QlGQKXH7'),
(38, 2, 'email_token', '$2y$13$1IbHFELPkLR2E6hWI.SycerxN0xmNNeYjB6S5u3CI51Rz8F9x84B6', '2024-04-19 08:40:56', NULL, 0, NULL, '2024-04-19 08:40:42', NULL, 'hRue5z1q8oIEmfHLhKQFY16Nf_gM97sV'),
(39, 1, 'email_token', '$2y$13$Oe8BALgJRv/CVosvn/NTv./gqPHvMi0yRpMf5WgShM9xf5we6qKZO', '2024-04-19 09:31:53', NULL, 0, NULL, '2024-04-19 09:31:34', NULL, 'G6aGFlceGRLnerPq2OW3cX6KrtFN69Bv'),
(40, 3, 'sms_otp', '$2y$13$5rixVLbDmN8eZcysPWewwetR6.2wdneFeIY3rsRsOtxZEzylNp//y', '2024-04-19 09:54:32', NULL, 0, NULL, '2024-04-19 09:54:12', NULL, 'uSILhd6EftsjGnAiuQ8pSlsik8Pe-Poj'),
(41, 3, 'sms_otp', '$2y$13$KKewOFXieeE3TUE9NPnt6uEYuNOejXrrBtHMM/Icj3OiUBdyJ.kgO', '2024-04-19 09:55:30', NULL, 0, NULL, '2024-04-19 09:55:15', NULL, 'IygmtBFdS0SkECaxSQxScbCLRQE58bMB'),
(42, 3, 'email_token', '$2y$13$mmIuhvV4GUaeiPvQ6z50IeEclkvSOKn5KfoEZD2uSUMdNS22B6mzW', '2024-04-19 09:55:41', NULL, 0, NULL, '2024-04-19 09:55:29', NULL, 'GPwCgNk9mvHeJ0FPkkSC9wPl_W640e4o'),
(43, 1, 'email_token', '$2y$13$AZ/zH9sHVjBneR.IPIXypOk4kN3y8Q/XWSSgiV2UCiQul.qmgz.He', '2024-04-19 09:57:05', NULL, 0, NULL, '2024-04-19 09:56:54', NULL, 'v0_p90LeytZx-gWMWD8IPj0ddz6kjZz4'),
(44, 1, 'sms_otp', '$2y$13$8I09qkQ9Mw5mEptcxlYiruRwLX8K4SR/zUdAhrGk16fJz2hzXHz.i', '2024-04-19 10:07:47', NULL, 0, NULL, '2024-04-19 10:07:39', NULL, 'hJCCqoNs2yTXhazf-XQQoQ5w1okuLZLO'),
(45, 8, 'password', '$2y$13$q47YeeLZwC.fPdDymcr8PuObb82uemq5cQYNQvsYfDoMSdoEL8pvC', NULL, NULL, 0, NULL, '2024-04-20 04:52:03', NULL, NULL),
(46, 10, 'password', '$2y$13$GkKJEwz0UkU3jB6nsGvse.K9MeKiM3ThIs0Gii5rl9B.GIWX9P5v6', NULL, NULL, 0, NULL, '2024-04-20 06:16:14', NULL, NULL),
(47, 9, 'sms_otp', '$2y$13$fzEb1FGWZ0Oz2dUsAK5jwOsayP7XkH04XX539hczwYnEuHqXa7VqC', '2024-04-20 06:17:18', NULL, 0, NULL, '2024-04-20 06:17:10', NULL, 'DdBfFAg2JKUErHWTO3nFa58IkjkzKyqB'),
(48, 10, 'email_token', '$2y$13$YTLbOLtHgUM4iB6ptrfw6.t10B7Lhr3pP7dig4E3rtWqw.zZ2Zq12', '2024-04-20 08:06:41', NULL, 0, NULL, '2024-04-20 07:56:41', NULL, 'H50ijahUNKJ1WI1rmHihNHN7coOGCxGg'),
(49, 10, 'sms_otp', '$2y$13$Sojx0WgTH95sbt7nJQ7oo.y6wL.j8IlKE7DTIjxOvj5uGwGsPSotm', '2024-04-20 11:14:07', NULL, 0, NULL, '2024-04-20 11:13:59', NULL, 't3EppslVgDmjvsl0r0-RjwVlk1UnqJmJ'),
(50, 10, 'sms_otp', '$2y$13$DFIAdYE99NjB5QDUF7dau.baSeKRVAv9lkxU2vsDuiDodGmsvZRYy', '2024-04-20 11:14:49', NULL, 0, NULL, '2024-04-20 11:14:42', NULL, '0C98OIHp1XR1MV0p0pnoHtnqLifJNu6z'),
(51, 11, 'password', '$2y$13$Vz5gTRYTFZEUBKUi0IivIOHlVhlwcNnjE/wExrNxkvehI9.bVAb3K', NULL, NULL, 0, NULL, '2024-04-20 15:08:05', NULL, NULL),
(52, 11, 'sms_otp', '$2y$13$SV5s4fIEE7MVe1zy689h3eDvbNH4BPxWi.YTOkHMguLjjM4dLuslO', '2024-04-20 16:11:18', NULL, 0, NULL, '2024-04-20 16:11:11', '2024-04-20 16:11:15', 'Ts071KdpGfcAWfm6fHMHEKPczB3jqryn'),
(53, 1, 'sms_otp', '$2y$13$e6EmXZfsYDmQq6JwKA6xCOJlK7MtWUeW63A0wkCW7bV/EMeaJDKC2', '2024-04-28 20:27:37', NULL, 0, NULL, '2024-04-28 20:27:29', '2024-04-28 20:27:34', '5fgRe9mfzvgpzSgmOHDmncQYP0Pdu3O0');

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Europe/Istanbul',
  `expert_type_list` json DEFAULT NULL,
  `resource_type_list` json DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `name`, `slug`, `timezone`, `expert_type_list`, `resource_type_list`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dental Dentist', 'dentaldent', 'Europe/Istanbul', '\"[\\\"\\\",\\\"Doktor\\\",\\\"Estetisyen\\\",\\\"Pediatrik Ortodondi\\\",\\\"\\\\u00c7ene Cerrahisi\\\"]\"', '\"[\\\"\\\",\\\"Muayene\\\",\\\"Panaromik R\\\\u00f6ntgen\\\",\\\"Pediatrik Muayene Odas\\\\u0131\\\"]\"', NULL, '2024-04-24 21:06:44', NULL),
(2, 'Super Dent', 'superdent', 'Europe/Istanbul', NULL, NULL, NULL, NULL, NULL),
(6, 'Appointment SAAS', 'appointmentsaas', 'Europe/Istanbul', '\"[\\\"\\\"]\"', NULL, '2024-04-15 07:56:23', '2024-04-24 19:30:18', NULL),
(10, 'Pala Pilates', 'pala-pilates', 'Europe/Istanbul', NULL, NULL, '2024-04-18 18:19:16', '2024-04-20 17:35:11', '2024-04-20 17:35:11'),
(11, 'Gezegen İnşaat', 'gezegen-insaat', 'Europe/Istanbul', NULL, NULL, '2024-04-18 18:25:23', '2024-04-20 17:35:24', '2024-04-20 17:35:24'),
(12, 'Gümüş Oto Yıkama', 'gumus-oto-yikama', 'Europe/Istanbul', NULL, NULL, '2024-04-18 18:26:14', '2024-04-20 17:34:56', '2024-04-20 17:34:56'),
(13, 'Görgülü Kuaför', 'gorgulu-kuafor', 'Europe/Istanbul', NULL, NULL, '2024-04-18 18:27:59', '2024-04-20 17:35:17', '2024-04-20 17:35:17'),
(14, 'ideametrik GM', 'ideametrik-gm', 'Europe/Istanbul', '\"[\\\"\\\",\\\"Bilgisayar\\\"]\"', '\"[\\\"\\\",\\\"Genel Muayene\\\"]\"', '2024-04-19 08:32:11', '2024-04-29 16:25:29', NULL),
(15, 'Titiz Oto Yıkama', 'titiz-oto-yikama', 'Europe/Istanbul', NULL, NULL, '2024-04-19 09:34:28', '2024-04-20 17:35:06', '2024-04-20 17:35:06'),
(16, 'Etsy Bitsy Corp', 'etsy-bitsy-corp', 'Europe/Istanbul', '\"[\\\"\\\"]\"', '\"[\\\"\\\"]\"', '2024-04-20 06:08:48', '2024-04-28 17:42:14', NULL),
(17, 'Etsy Bitsy Corps', 'etsy-bitsy-corps', 'Europe/Istanbul', NULL, NULL, '2024-04-20 11:15:02', '2024-04-20 17:35:32', '2024-04-20 17:35:32'),
(18, 'Zincir Taşımacılık', 'berna', 'Pacific/Pohnpei', NULL, NULL, '2024-04-20 15:08:46', '2024-04-20 17:35:01', '2024-04-20 17:35:01');

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

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
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'sms_validate', '5330338197', NULL, '2024-04-16 20:01:24', 1),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'Token', 'token', NULL, '2024-04-20 07:55:03', 0),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'Token', 'token', NULL, '2024-04-20 07:55:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `event_type` enum('user_created','user_updated','user_deleted','user_auth_added','user_business_added','user_business_updated','user_business_deleted','business_created','business_updated','business_deleted','appointment_created','appointment_updated','appointment_deleted','resource_created','resource_updated','resource_deleted','rule_created','rule_updated','rule_deleted','service_created','service_updated','service_deleted','appointment_resource_added','appointment_resource_deleted','appointment_user_added','appointment_user_deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` json NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `event_type`, `event`, `created_at`) VALUES
(1, 1, 'user_business_updated', '\"{\\\"id\\\":22,\\\"updated\\\":{\\\"id\\\":22,\\\"user_id\\\":1,\\\"business_id\\\":6,\\\"role\\\":\\\"customer\\\",\\\"created_at\\\":\\\"2024-04-18 04:29:29\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"role\\\":\\\"admin\\\"}}\"', '2024-04-18 18:05:48'),
(2, 1, 'user_business_updated', '\"{\\\"id\\\":22,\\\"updated\\\":{\\\"id\\\":22,\\\"user_id\\\":1,\\\"business_id\\\":6,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 04:29:29\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"role\\\":\\\"customer\\\"}}\"', '2024-04-18 18:06:18'),
(3, 1, 'business_updated', '\"{\\\"id\\\":6,\\\"updated\\\":{\\\"id\\\":6,\\\"name\\\":\\\"Appointment SAAS Inc.\\\",\\\"slug\\\":\\\"appointmentsaas\\\",\\\"timezone\\\":\\\"Europe\\\\/Isle_of_Man\\\",\\\"created_at\\\":\\\"2024-04-15 07:56:23\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\"}}\"', '2024-04-18 18:09:29'),
(4, 1, 'business_updated', '\"{\\\"id\\\":6,\\\"updated\\\":{\\\"id\\\":6,\\\"name\\\":\\\"Appointment SAAS Inc.\\\",\\\"slug\\\":\\\"appointmentsaas\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-15 07:56:23\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"timezone\\\":\\\"Europe\\\\/Isle_of_Man\\\"}}\"', '2024-04-18 18:11:46'),
(5, 1, 'business_created', '\"{\\\"id\\\":10,\\\"inserted\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Pala Pilates\\\",\\\"slug\\\":\\\"pala-pilates\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:19:16'),
(6, 1, 'user_business_added', '\"{\\\"id\\\":27,\\\"inserted\\\":{\\\"id\\\":27,\\\"user_id\\\":1,\\\"business_id\\\":10,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:19:38'),
(7, 1, 'business_created', '\"{\\\"id\\\":11,\\\"inserted\\\":{\\\"id\\\":11,\\\"name\\\":\\\"Gezegen \\\\u0130n\\\\u015faat\\\",\\\"slug\\\":\\\"gezegen-insaat\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:25:23'),
(8, 1, 'business_created', '\"{\\\"id\\\":12,\\\"inserted\\\":{\\\"id\\\":12,\\\"name\\\":\\\"G\\\\u00fcm\\\\u00fc\\\\u015f Oto Y\\\\u0131kama\\\",\\\"slug\\\":\\\"gumus-oto-yikama\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:26:14'),
(9, 1, 'user_business_added', '\"{\\\"id\\\":28,\\\"inserted\\\":{\\\"id\\\":28,\\\"user_id\\\":1,\\\"business_id\\\":12,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:26:14'),
(10, 1, 'business_created', '\"{\\\"id\\\":13,\\\"inserted\\\":{\\\"id\\\":13,\\\"name\\\":\\\"G\\\\u00f6rg\\\\u00fcl\\\\u00fc Kuaf\\\\u00f6r\\\",\\\"slug\\\":\\\"gorgulu-kuafor\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:27:59'),
(11, 1, 'user_business_added', '\"{\\\"id\\\":29,\\\"inserted\\\":{\\\"id\\\":29,\\\"user_id\\\":1,\\\"business_id\\\":13,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:27:59'),
(12, 1, 'user_business_added', '\"{\\\"id\\\":30,\\\"inserted\\\":{\\\"id\\\":30,\\\"user_id\\\":1,\\\"business_id\\\":11,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-18 18:31:43'),
(13, 1, 'resource_created', '\"{\\\"id\\\":2,\\\"inserted\\\":{\\\"id\\\":2,\\\"name\\\":\\\"2 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 05:03:29'),
(14, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"1 No\'lu Oda\\\"}}\"', '2024-04-19 05:04:04'),
(15, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 05:05:00'),
(16, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 05:05:05'),
(17, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 05:08:33'),
(18, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 05:09:08'),
(19, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"3 No\'lu Oda\\\"}}\"', '2024-04-19 05:09:15'),
(20, 1, 'resource_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"name\\\":\\\"4 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 05:03:29\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"2 No\'lu Oda\\\"}}\"', '2024-04-19 05:11:27'),
(21, 1, 'resource_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"name\\\":\\\"1 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 05:03:29\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"4 No\'lu Oda\\\"}}\"', '2024-04-19 05:14:12'),
(22, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"2 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"5 No\'lu Oda\\\"}}\"', '2024-04-19 05:15:47'),
(23, 1, 'resource_created', '\"{\\\"id\\\":3,\\\"inserted\\\":{\\\"id\\\":3,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"5 No\'lu Oda\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 06:31:20'),
(24, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"2 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 06:35:28'),
(25, 1, 'resource_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 06:31:20\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type\\\":\\\"5 No\'lu Oda\\\"}}\"', '2024-04-19 06:35:35'),
(26, 1, 'rule_created', '\"{\\\"id\\\":1,\\\"inserted\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Mesai Saatleri\\\",\\\"ruleset\\\":\\\"<>\\\",\\\"business_id\\\":1,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 07:39:38'),
(27, 1, 'resource_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 06:31:20\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 07:54:45'),
(28, 1, 'resource_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 06:31:20\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 07:54:50'),
(29, 1, 'resource_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"name\\\":\\\"1 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 05:03:29\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 07:55:33'),
(30, 1, 'resource_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 06:31:20\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 07:56:23'),
(31, 1, 'resource_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 06:31:20\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":\\\"2024-04-19 07:56:23\\\"}}\"', '2024-04-19 07:56:28'),
(32, 1, 'resource_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"name\\\":\\\"1 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 05:03:29\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 08:08:10'),
(33, 1, 'resource_created', '\"{\\\"id\\\":4,\\\"inserted\\\":{\\\"id\\\":4,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 08:08:16'),
(34, 1, 'resource_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"2 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-11 08:13:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 08:08:21'),
(35, 1, 'resource_updated', '\"{\\\"id\\\":4,\\\"updated\\\":{\\\"id\\\":4,\\\"name\\\":\\\"2 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 08:08:16\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"3 No\'lu Oda\\\"}}\"', '2024-04-19 08:08:27'),
(36, 0, 'user_auth_added', '\"{\\\"id\\\":36,\\\"inserted\\\":{\\\"id\\\":36,\\\"user_id\\\":1,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$ubiUdXMuVvj9TL2WKwRUMuG8hUSO8NmRUu1Z2s9MfzScytNPHLXy2\\\",\\\"expires\\\":\\\"2024-04-19 08:37:59\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"AVxAt8k81PS6yq2Z0bDfvGSX_8bxJdLS\\\"}}\"', '2024-04-19 08:27:59'),
(37, 1, 'business_created', '\"{\\\"id\\\":14,\\\"inserted\\\":{\\\"id\\\":14,\\\"name\\\":\\\"ideametrik GM\\\",\\\"slug\\\":\\\"ideametrik-gm\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 08:32:11'),
(38, 1, 'user_business_added', '\"{\\\"id\\\":31,\\\"inserted\\\":{\\\"id\\\":31,\\\"user_id\\\":1,\\\"business_id\\\":14,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 08:32:11'),
(39, 1, 'user_business_added', '\"{\\\"id\\\":32,\\\"inserted\\\":{\\\"id\\\":32,\\\"user_id\\\":2,\\\"business_id\\\":14,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 08:32:54'),
(40, 1, 'user_business_updated', '\"{\\\"id\\\":32,\\\"updated\\\":{\\\"id\\\":32,\\\"user_id\\\":2,\\\"business_id\\\":14,\\\"role\\\":\\\"expert\\\",\\\"created_at\\\":\\\"2024-04-19 08:32:54\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"role\\\":\\\"secretary\\\"}}\"', '2024-04-19 08:33:41'),
(41, 1, 'user_business_updated', '\"{\\\"id\\\":32,\\\"updated\\\":{\\\"id\\\":32,\\\"user_id\\\":2,\\\"business_id\\\":14,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-19 08:32:54\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"role\\\":\\\"expert\\\"}}\"', '2024-04-19 08:33:50'),
(42, 1, 'resource_created', '\"{\\\"id\\\":5,\\\"inserted\\\":{\\\"id\\\":5,\\\"name\\\":\\\"1 No\'lu Oda\\\",\\\"business_id\\\":14,\\\"resource_type\\\":\\\"Genel Muayene\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 08:34:24'),
(43, 1, 'resource_created', '\"{\\\"id\\\":6,\\\"inserted\\\":{\\\"id\\\":6,\\\"name\\\":\\\"2 No\'lu Oda\\\",\\\"business_id\\\":14,\\\"resource_type\\\":\\\"Genel Muayene\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 08:34:31'),
(44, 0, 'user_auth_added', '\"{\\\"id\\\":37,\\\"inserted\\\":{\\\"id\\\":37,\\\"user_id\\\":2,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$i1CZ7GxwdGBMrFf5IqRGRez05KTf7BoGkGJUh\\\\/b3yfYbEL3Zfgm56\\\",\\\"expires\\\":\\\"2024-04-19 08:43:22\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"7XLsA4AbTuuVNDlRSOIwDy-3QlGQKXH7\\\"}}\"', '2024-04-19 08:40:22'),
(45, 0, 'user_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"H\\\\u00fcseyin\\\",\\\"last_name\\\":\\\"Mumay\\\",\\\"tcno\\\":\\\"53149018900\\\",\\\"gsm\\\":\\\"5445868624\\\",\\\"email\\\":\\\"ideametrik@gmail.com\\\",\\\"dogum_yili\\\":1981,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"H\\\\u00fcseyin Mumay\\\"},\\\"deleted\\\":{\\\"gsmverified\\\":0}}\"', '2024-04-19 08:40:32'),
(46, 2, 'user_auth_added', '\"{\\\"id\\\":38,\\\"inserted\\\":{\\\"id\\\":38,\\\"user_id\\\":2,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$1IbHFELPkLR2E6hWI.SycerxN0xmNNeYjB6S5u3CI51Rz8F9x84B6\\\",\\\"expires\\\":\\\"2024-04-19 08:50:42\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"hRue5z1q8oIEmfHLhKQFY16Nf_gM97sV\\\"}}\"', '2024-04-19 08:40:42'),
(47, 2, 'user_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"H\\\\u00fcseyin\\\",\\\"last_name\\\":\\\"Mumay\\\",\\\"tcno\\\":\\\"53149018900\\\",\\\"gsm\\\":\\\"5445868624\\\",\\\"email\\\":\\\"ideametrik@gmail.com\\\",\\\"dogum_yili\\\":1981,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"H\\\\u00fcseyin Mumay\\\"},\\\"deleted\\\":{\\\"emailverified\\\":0}}\"', '2024-04-19 08:40:46'),
(48, 2, 'user_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"H\\\\u00fcseyin\\\",\\\"last_name\\\":\\\"Mumay\\\",\\\"tcno\\\":\\\"53149018900\\\",\\\"gsm\\\":\\\"5445868624\\\",\\\"email\\\":\\\"ideametrik@gmail.com\\\",\\\"dogum_yili\\\":1981,\\\"tcnoverified\\\":1,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"H\\\\u00fcseyin Mumay\\\"},\\\"deleted\\\":{\\\"tcnoverified\\\":0}}\"', '2024-04-19 08:41:00'),
(49, 0, 'user_auth_added', '\"{\\\"id\\\":39,\\\"inserted\\\":{\\\"id\\\":39,\\\"user_id\\\":1,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$Oe8BALgJRv\\\\/CVosvn\\\\/NTv.\\\\/gqPHvMi0yRpMf5WgShM9xf5we6qKZO\\\",\\\"expires\\\":\\\"2024-04-19 09:41:34\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"G6aGFlceGRLnerPq2OW3cX6KrtFN69Bv\\\"}}\"', '2024-04-19 09:31:34'),
(50, 1, 'business_created', '\"{\\\"id\\\":15,\\\"inserted\\\":{\\\"id\\\":15,\\\"name\\\":\\\"Titiz Oto Y\\\\u0131kama\\\",\\\"slug\\\":\\\"titiz-oto-yikama\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 09:34:28'),
(51, 1, 'user_business_added', '\"{\\\"id\\\":33,\\\"inserted\\\":{\\\"id\\\":33,\\\"user_id\\\":1,\\\"business_id\\\":15,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 09:34:28'),
(52, 1, 'user_business_added', '\"{\\\"id\\\":34,\\\"inserted\\\":{\\\"id\\\":34,\\\"user_id\\\":5,\\\"business_id\\\":15,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 09:34:47'),
(53, 1, 'user_business_updated', '\"{\\\"id\\\":33,\\\"updated\\\":{\\\"id\\\":33,\\\"user_id\\\":1,\\\"business_id\\\":15,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-19 09:34:28\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"role\\\":\\\"admin\\\"}}\"', '2024-04-19 09:34:51'),
(54, 1, 'user_business_updated', '\"{\\\"id\\\":34,\\\"updated\\\":{\\\"id\\\":34,\\\"user_id\\\":5,\\\"business_id\\\":15,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-19 09:34:47\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"role\\\":\\\"admin\\\"}}\"', '2024-04-19 09:34:57'),
(55, 1, 'user_business_updated', '\"{\\\"id\\\":33,\\\"updated\\\":{\\\"id\\\":33,\\\"user_id\\\":1,\\\"business_id\\\":15,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-19 09:34:28\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"role\\\":\\\"secretary\\\"}}\"', '2024-04-19 09:35:00'),
(56, 1, 'user_business_updated', '\"{\\\"id\\\":34,\\\"updated\\\":{\\\"id\\\":34,\\\"user_id\\\":5,\\\"business_id\\\":15,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-19 09:34:47\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 09:35:05'),
(57, 1, 'resource_created', '\"{\\\"id\\\":7,\\\"inserted\\\":{\\\"id\\\":7,\\\"name\\\":\\\"afadafdladf\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Zoom Lisans Kodu\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 09:37:28'),
(58, 0, 'user_auth_added', '\"{\\\"id\\\":40,\\\"inserted\\\":{\\\"id\\\":40,\\\"user_id\\\":3,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$5rixVLbDmN8eZcysPWewwetR6.2wdneFeIY3rsRsOtxZEzylNp\\\\/\\\\/y\\\",\\\"expires\\\":\\\"2024-04-19 09:57:12\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"uSILhd6EftsjGnAiuQ8pSlsik8Pe-Poj\\\"}}\"', '2024-04-19 09:54:12'),
(59, 0, 'user_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Burhan\\\",\\\"last_name\\\":\\\"\\\\u00c7alhan\\\",\\\"tcno\\\":\\\"13515135131\\\",\\\"gsm\\\":\\\"5057958150\\\",\\\"email\\\":\\\"calhan.bur@gmail.com\\\",\\\"dogum_yili\\\":1981,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Burhan \\\\u00c7alhan\\\"},\\\"deleted\\\":{\\\"gsmverified\\\":0}}\"', '2024-04-19 09:54:29'),
(60, 3, 'user_auth_added', '\"{\\\"id\\\":41,\\\"inserted\\\":{\\\"id\\\":41,\\\"user_id\\\":3,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$KKewOFXieeE3TUE9NPnt6uEYuNOejXrrBtHMM\\\\/Icj3OiUBdyJ.kgO\\\",\\\"expires\\\":\\\"2024-04-19 09:58:15\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"IygmtBFdS0SkECaxSQxScbCLRQE58bMB\\\"}}\"', '2024-04-19 09:55:15'),
(61, 3, 'user_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Burhan\\\",\\\"last_name\\\":\\\"\\\\u00c7alhan\\\",\\\"tcno\\\":\\\"13515135131\\\",\\\"gsm\\\":\\\"5057958150\\\",\\\"email\\\":\\\"calhan.bur@gmail.com\\\",\\\"dogum_yili\\\":1981,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Burhan \\\\u00c7alhan\\\"},\\\"deleted\\\":{\\\"gsmverified\\\":0}}\"', '2024-04-19 09:55:27'),
(62, 3, 'user_auth_added', '\"{\\\"id\\\":42,\\\"inserted\\\":{\\\"id\\\":42,\\\"user_id\\\":3,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$mmIuhvV4GUaeiPvQ6z50IeEclkvSOKn5KfoEZD2uSUMdNS22B6mzW\\\",\\\"expires\\\":\\\"2024-04-19 10:05:29\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"GPwCgNk9mvHeJ0FPkkSC9wPl_W640e4o\\\"}}\"', '2024-04-19 09:55:29'),
(63, 3, 'user_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Burhan\\\",\\\"last_name\\\":\\\"\\\\u00c7alhan\\\",\\\"tcno\\\":\\\"13515135131\\\",\\\"gsm\\\":\\\"5057958150\\\",\\\"email\\\":\\\"calhan.bur@gmail.com\\\",\\\"dogum_yili\\\":1981,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Burhan \\\\u00c7alhan\\\"},\\\"deleted\\\":{\\\"emailverified\\\":0}}\"', '2024-04-19 09:55:31'),
(64, 0, 'user_auth_added', '\"{\\\"id\\\":43,\\\"inserted\\\":{\\\"id\\\":43,\\\"user_id\\\":1,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$AZ\\\\/zH9sHVjBneR.IPIXypOk4kN3y8Q\\\\/XWSSgiV2UCiQul.qmgz.He\\\",\\\"expires\\\":\\\"2024-04-19 10:06:54\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"v0_p90LeytZx-gWMWD8IPj0ddz6kjZz4\\\"}}\"', '2024-04-19 09:56:54'),
(65, 1, 'user_business_updated', '\"{\\\"id\\\":27,\\\"updated\\\":{\\\"id\\\":27,\\\"user_id\\\":1,\\\"business_id\\\":10,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 18:19:38\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 09:57:14'),
(66, 1, 'user_business_updated', '\"{\\\"id\\\":27,\\\"updated\\\":{\\\"id\\\":27,\\\"user_id\\\":1,\\\"business_id\\\":10,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 18:19:38\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":\\\"2024-04-19 09:57:14\\\"}}\"', '2024-04-19 09:57:55'),
(67, 1, 'user_business_deleted', '\"{\\\"id\\\":27,\\\"deleted\\\":{\\\"id\\\":27,\\\"user_id\\\":1,\\\"business_id\\\":10,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 18:19:38\\\",\\\"deleted_at\\\":\\\"2024-04-19 09:57:55\\\"}}\"', '2024-04-19 09:58:50'),
(68, 0, 'user_auth_added', '\"{\\\"id\\\":44,\\\"inserted\\\":{\\\"id\\\":44,\\\"user_id\\\":1,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$8I09qkQ9Mw5mEptcxlYiruRwLX8K4SR\\\\/zUdAhrGk16fJz2hzXHz.i\\\",\\\"expires\\\":\\\"2024-04-19 10:10:39\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"hJCCqoNs2yTXhazf-XQQoQ5w1okuLZLO\\\"}}\"', '2024-04-19 10:07:39'),
(69, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 16:08:15'),
(70, 1, 'user_business_added', '\"{\\\"id\\\":35,\\\"inserted\\\":{\\\"id\\\":35,\\\"user_id\\\":3,\\\"business_id\\\":1,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 18:57:46'),
(71, 1, 'user_business_updated', '\"{\\\"id\\\":35,\\\"updated\\\":{\\\"id\\\":35,\\\"user_id\\\":3,\\\"business_id\\\":1,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-19 18:57:46\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 18:59:06'),
(72, 1, 'user_business_deleted', '\"{\\\"id\\\":35,\\\"deleted\\\":{\\\"id\\\":35,\\\"user_id\\\":3,\\\"business_id\\\":1,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-19 18:57:46\\\",\\\"deleted_at\\\":\\\"2024-04-19 18:59:06\\\"}}\"', '2024-04-19 19:15:29'),
(73, 1, 'user_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Umut\\\",\\\"last_name\\\":\\\"Demirhan\\\",\\\"tcno\\\":\\\"23416086000\\\",\\\"gsm\\\":\\\"5330338197\\\",\\\"email\\\":\\\"umut@kariyerfora.com\\\",\\\"dogum_yili\\\":\\\"1978\\\",\\\"tcnoverified\\\":1,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Umut Demirhan\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1977}}\"', '2024-04-19 19:17:29'),
(74, 1, 'user_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Umut\\\",\\\"last_name\\\":\\\"Demirhan\\\",\\\"tcno\\\":\\\"23416086000\\\",\\\"gsm\\\":\\\"5330338197\\\",\\\"email\\\":\\\"umut@kariyerfora.com\\\",\\\"dogum_yili\\\":\\\"1977\\\",\\\"tcnoverified\\\":1,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Umut Demirhan\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1978}}\"', '2024-04-19 19:17:34'),
(75, 1, 'user_business_added', '\"{\\\"id\\\":36,\\\"inserted\\\":{\\\"id\\\":36,\\\"user_id\\\":5,\\\"business_id\\\":1,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 19:21:08'),
(76, 1, 'user_business_added', '\"{\\\"id\\\":37,\\\"inserted\\\":{\\\"id\\\":37,\\\"user_id\\\":2,\\\"business_id\\\":1,\\\"role\\\":\\\"customer\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 19:21:21'),
(77, 1, 'user_business_added', '\"{\\\"id\\\":38,\\\"inserted\\\":{\\\"id\\\":38,\\\"user_id\\\":3,\\\"business_id\\\":1,\\\"role\\\":\\\"customer\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 19:21:25'),
(78, 1, 'user_business_deleted', '\"{\\\"id\\\":37,\\\"deleted\\\":{\\\"id\\\":37,\\\"user_id\\\":2,\\\"business_id\\\":1,\\\"role\\\":\\\"expert\\\",\\\"created_at\\\":\\\"2024-04-19 19:21:21\\\",\\\"deleted_at\\\":null}}\"', '2024-04-19 19:21:54'),
(79, 1, 'user_business_added', '\"{\\\"id\\\":39,\\\"inserted\\\":{\\\"id\\\":39,\\\"user_id\\\":2,\\\"business_id\\\":1,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 19:22:01'),
(80, 1, 'user_business_added', '\"{\\\"id\\\":40,\\\"inserted\\\":{\\\"id\\\":40,\\\"user_id\\\":4,\\\"business_id\\\":12,\\\"role\\\":\\\"expert\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 19:29:35'),
(81, 1, 'resource_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"name\\\":\\\"11 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 05:03:29\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"1 No\'lu Oda\\\"}}\"', '2024-04-19 20:47:15'),
(82, 1, 'resource_updated', '\"{\\\"id\\\":7,\\\"updated\\\":{\\\"id\\\":7,\\\"name\\\":\\\"afadafdladf\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Zoom Lisans Kodu\\\",\\\"created_at\\\":\\\"2024-04-19 09:37:28\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 20:47:24'),
(83, 1, 'resource_updated', '\"{\\\"id\\\":7,\\\"updated\\\":{\\\"id\\\":7,\\\"name\\\":\\\"afadafdladf\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Zoom Lisans Kodu\\\",\\\"created_at\\\":\\\"2024-04-19 09:37:28\\\",\\\"updated_at\\\":\\\"2024-04-19 20:47:24\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":\\\"2024-04-19 20:47:24\\\"}}\"', '2024-04-19 20:47:30'),
(84, 1, 'service_created', '\"{\\\"id\\\":1,\\\"inserted\\\":{\\\"id\\\":1,\\\"business_id\\\":1,\\\"name\\\":\\\"a\\\",\\\"resource_type\\\":\\\"adf\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":\\\"134\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 21:08:18'),
(85, 1, 'service_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"business_id\\\":1,\\\"name\\\":\\\"a\\\",\\\"resource_type\\\":\\\"adf\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":134,\\\"created_at\\\":\\\"2024-04-19 21:08:18\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 21:08:21'),
(86, 1, 'resource_updated', '\"{\\\"id\\\":7,\\\"updated\\\":{\\\"id\\\":7,\\\"name\\\":\\\"afadafdladf\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Zoom Lisans Kodu\\\",\\\"created_at\\\":\\\"2024-04-19 09:37:28\\\",\\\"updated_at\\\":\\\"2024-04-19 20:47:30\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":\\\"2024-04-19 20:47:30\\\"}}\"', '2024-04-19 21:14:34'),
(87, 1, 'rule_created', '\"{\\\"id\\\":2,\\\"inserted\\\":{\\\"id\\\":2,\\\"name\\\":\\\"Mesai Saatlerix\\\",\\\"ruleset\\\":\\\"<>\\\",\\\"business_id\\\":1,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 21:14:43'),
(88, 1, 'rule_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"name\\\":\\\"Mesai Saatlerix\\\",\\\"ruleset\\\":\\\"<>\\\",\\\"business_id\\\":1,\\\"created_at\\\":\\\"2024-04-19 21:14:43\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-19 21:14:46'),
(89, 1, 'resource_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"name\\\":\\\"1 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 05:03:29\\\",\\\"updated_at\\\":\\\"2024-04-19 20:47:15\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"11 No\'lu Oda\\\"}}\"', '2024-04-19 21:15:16'),
(90, 1, 'resource_created', '\"{\\\"id\\\":8,\\\"inserted\\\":{\\\"id\\\":8,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 21:16:15'),
(91, 1, 'resource_created', '\"{\\\"id\\\":9,\\\"inserted\\\":{\\\"id\\\":9,\\\"name\\\":\\\"\\\\u00c7ocuk Muayene\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Pediatrik Muayene Odas\\\\u0131\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 21:35:11'),
(92, 1, 'resource_created', '\"{\\\"id\\\":10,\\\"inserted\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Panaromik R\\\\u00f6ntgen\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Panaromik R\\\\u00f6ntgen\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 21:35:27'),
(93, 1, 'resource_created', '\"{\\\"id\\\":11,\\\"inserted\\\":{\\\"id\\\":11,\\\"name\\\":\\\"Implant Set\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Implant Set\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 21:35:54'),
(94, 1, 'user_business_added', '\"{\\\"id\\\":41,\\\"inserted\\\":{\\\"id\\\":41,\\\"user_id\\\":4,\\\"business_id\\\":1,\\\"role\\\":\\\"expert\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 21:36:22'),
(95, 1, 'user_business_deleted', '\"{\\\"id\\\":22,\\\"deleted\\\":{\\\"id\\\":22,\\\"user_id\\\":1,\\\"business_id\\\":6,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 04:29:29\\\",\\\"deleted_at\\\":null}}\"', '2024-04-19 21:49:07'),
(96, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-19 21:55:34'),
(97, 1, 'service_created', '\"{\\\"id\\\":2,\\\"inserted\\\":{\\\"id\\\":2,\\\"business_id\\\":1,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"Pediatrik Muayene Odas\\\\u0131\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":\\\"49\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-19 22:09:44'),
(98, 1, 'resource_created', '\"{\\\"id\\\":12,\\\"inserted\\\":{\\\"id\\\":12,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":10,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 03:34:22'),
(99, 1, 'service_created', '\"{\\\"id\\\":3,\\\"inserted\\\":{\\\"id\\\":3,\\\"business_id\\\":10,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"Muayene\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":\\\"187\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 03:40:30'),
(100, 1, 'rule_created', '\"{\\\"id\\\":3,\\\"inserted\\\":{\\\"id\\\":3,\\\"name\\\":\\\"Mesai Saatleri\\\",\\\"ruleset\\\":\\\"<>\\\",\\\"business_id\\\":10,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 03:40:57'),
(101, 1, 'user_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Umut\\\",\\\"last_name\\\":\\\"Demirhan\\\",\\\"tcno\\\":\\\"23416086000\\\",\\\"gsm\\\":\\\"5330338197\\\",\\\"email\\\":\\\"umut@kariyerfora.com\\\",\\\"dogum_yili\\\":\\\"1977\\\",\\\"tcnoverified\\\":1,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Umut Demirhan\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1977}}\"', '2024-04-20 03:45:50'),
(102, 1, 'service_created', '\"{\\\"id\\\":4,\\\"inserted\\\":{\\\"id\\\":4,\\\"business_id\\\":11,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"\\\",\\\"expert_type\\\":null,\\\"duration\\\":\\\"40\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 04:07:18'),
(103, 1, 'service_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"business_id\\\":1,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":\\\"49\\\",\\\"created_at\\\":\\\"2024-04-19 22:09:44\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type\\\":\\\"Pediatrik Muayene Odas\\\\u0131\\\",\\\"duration\\\":49}}\"', '2024-04-20 04:07:50'),
(104, 1, 'service_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"business_id\\\":1,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"Pediatrik Muayene Odas\\\\u0131\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":\\\"49\\\",\\\"created_at\\\":\\\"2024-04-19 22:09:44\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type\\\":\\\"\\\",\\\"duration\\\":49}}\"', '2024-04-20 04:08:44'),
(105, 1, 'service_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"business_id\\\":1,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":\\\"49\\\",\\\"created_at\\\":\\\"2024-04-19 22:09:44\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type\\\":\\\"Pediatrik Muayene Odas\\\\u0131\\\",\\\"duration\\\":49}}\"', '2024-04-20 04:08:53'),
(106, 1, 'user_created', '\"{\\\"id\\\":6,\\\"inserted\\\":{\\\"id\\\":6,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Ahmet\\\",\\\"last_name\\\":\\\"Mehmet\\\",\\\"tcno\\\":\\\"15145511545\\\",\\\"gsm\\\":\\\"1313155144\\\",\\\"email\\\":\\\"ahmet@gmail.com\\\",\\\"dogum_yili\\\":\\\"1990\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-20 04:37:54'),
(107, 1, 'user_business_added', '\"{\\\"id\\\":42,\\\"inserted\\\":{\\\"id\\\":42,\\\"user_id\\\":6,\\\"business_id\\\":12,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 04:37:54'),
(108, 1, 'user_created', '\"{\\\"id\\\":7,\\\"inserted\\\":{\\\"id\\\":7,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Mehmt\\\",\\\"last_name\\\":\\\"Aaad\\\",\\\"tcno\\\":\\\"13158878781\\\",\\\"gsm\\\":\\\"1341341333\\\",\\\"email\\\":\\\"hmet@gmail.com\\\",\\\"dogum_yili\\\":\\\"1991\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-20 04:39:39'),
(109, 1, 'user_business_added', '\"{\\\"id\\\":43,\\\"inserted\\\":{\\\"id\\\":43,\\\"user_id\\\":7,\\\"business_id\\\":12,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 04:39:39'),
(110, 0, 'user_created', '\"{\\\"id\\\":8,\\\"inserted\\\":{\\\"id\\\":8,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Etsy\\\",\\\"last_name\\\":\\\"Bitsy\\\",\\\"tcno\\\":\\\"00000000000\\\",\\\"gsm\\\":\\\"0000000000\\\",\\\"email\\\":\\\"etsy@gmail.com\\\",\\\"dogum_yili\\\":\\\"1990\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-20 04:52:02'),
(111, 0, 'user_auth_added', '\"{\\\"id\\\":45,\\\"inserted\\\":{\\\"id\\\":45,\\\"user_id\\\":8,\\\"type\\\":\\\"password\\\",\\\"secret\\\":\\\"$2y$13$q47YeeLZwC.fPdDymcr8PuObb82uemq5cQYNQvsYfDoMSdoEL8pvC\\\",\\\"expires\\\":null,\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":null}}\"', '2024-04-20 04:52:03'),
(112, 8, 'business_created', '\"{\\\"id\\\":16,\\\"inserted\\\":{\\\"id\\\":16,\\\"name\\\":\\\"Etsy Bitsy Corp\\\",\\\"slug\\\":\\\"etsy-bitsy-corp\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 06:08:48'),
(113, 8, 'user_business_added', '\"{\\\"id\\\":44,\\\"inserted\\\":{\\\"id\\\":44,\\\"user_id\\\":8,\\\"business_id\\\":16,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 06:08:48'),
(114, 8, 'user_updated', '\"{\\\"id\\\":8,\\\"updated\\\":{\\\"id\\\":8,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Etsy\\\",\\\"last_name\\\":\\\"Bitsy\\\",\\\"tcno\\\":\\\"00000000000\\\",\\\"gsm\\\":\\\"0000000000\\\",\\\"email\\\":\\\"etsy@gmail.com\\\",\\\"dogum_yili\\\":1990,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":0,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 04:52:02\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Etsy Bitsy\\\"},\\\"deleted\\\":{\\\"remainingBusinessCount\\\":1}}\"', '2024-04-20 06:08:48'),
(115, 8, 'user_updated', '\"{\\\"id\\\":8,\\\"updated\\\":{\\\"id\\\":8,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Etsy\\\",\\\"last_name\\\":\\\"Bitsy\\\",\\\"tcno\\\":\\\"00000000000\\\",\\\"gsm\\\":\\\"0000000000\\\",\\\"email\\\":\\\"etsy@gmail.com\\\",\\\"dogum_yili\\\":\\\"1990\\\",\\\"tcnoverified\\\":0,\\\"gsmverified\\\":0,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 04:52:02\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Etsy Bitsy\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1990}}\"', '2024-04-20 06:11:13'),
(116, 8, 'user_created', '\"{\\\"id\\\":9,\\\"inserted\\\":{\\\"id\\\":9,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Etsy\\\",\\\"last_name\\\":\\\"Secretary\\\",\\\"tcno\\\":\\\"41331413434\\\",\\\"gsm\\\":\\\"1231231231\\\",\\\"email\\\":\\\"secretary@gmail.com\\\",\\\"dogum_yili\\\":\\\"1900\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-20 06:11:44'),
(117, 8, 'user_business_added', '\"{\\\"id\\\":45,\\\"inserted\\\":{\\\"id\\\":45,\\\"user_id\\\":9,\\\"business_id\\\":16,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 06:11:44'),
(118, 8, 'user_business_deleted', '\"{\\\"id\\\":45,\\\"deleted\\\":{\\\"id\\\":45,\\\"user_id\\\":9,\\\"business_id\\\":16,\\\"role\\\":\\\"customer\\\",\\\"created_at\\\":\\\"2024-04-20 06:11:44\\\",\\\"deleted_at\\\":null}}\"', '2024-04-20 06:12:51'),
(119, 8, 'user_business_added', '\"{\\\"id\\\":46,\\\"inserted\\\":{\\\"id\\\":46,\\\"user_id\\\":9,\\\"business_id\\\":16,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 06:13:04'),
(120, 0, 'user_created', '\"{\\\"id\\\":10,\\\"inserted\\\":{\\\"id\\\":10,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Kariyer\\\",\\\"last_name\\\":\\\"Fora\\\",\\\"tcno\\\":\\\"99999999999\\\",\\\"gsm\\\":\\\"9999999999\\\",\\\"email\\\":\\\"kariyer@kariyerfora.com\\\",\\\"dogum_yili\\\":\\\"9999\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-20 06:16:14'),
(121, 0, 'user_auth_added', '\"{\\\"id\\\":46,\\\"inserted\\\":{\\\"id\\\":46,\\\"user_id\\\":10,\\\"type\\\":\\\"password\\\",\\\"secret\\\":\\\"$2y$13$GkKJEwz0UkU3jB6nsGvse.K9MeKiM3ThIs0Gii5rl9B.GIWX9P5v6\\\",\\\"expires\\\":null,\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":null}}\"', '2024-04-20 06:16:14'),
(122, 0, 'user_auth_added', '\"{\\\"id\\\":47,\\\"inserted\\\":{\\\"id\\\":47,\\\"user_id\\\":9,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$fzEb1FGWZ0Oz2dUsAK5jwOsayP7XkH04XX539hczwYnEuHqXa7VqC\\\",\\\"expires\\\":\\\"2024-04-20 06:20:10\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"DdBfFAg2JKUErHWTO3nFa58IkjkzKyqB\\\"}}\"', '2024-04-20 06:17:10'),
(123, 0, 'user_updated', '\"{\\\"id\\\":9,\\\"updated\\\":{\\\"id\\\":9,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Etsy\\\",\\\"last_name\\\":\\\"Secretary\\\",\\\"tcno\\\":\\\"41331413434\\\",\\\"gsm\\\":\\\"1231231231\\\",\\\"email\\\":\\\"secretary@gmail.com\\\",\\\"dogum_yili\\\":1900,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 06:11:44\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Etsy Secretary\\\"},\\\"deleted\\\":{\\\"gsmverified\\\":0}}\"', '2024-04-20 06:17:15'),
(124, 0, 'user_auth_added', '\"{\\\"id\\\":48,\\\"inserted\\\":{\\\"id\\\":48,\\\"user_id\\\":10,\\\"type\\\":\\\"email_token\\\",\\\"secret\\\":\\\"$2y$13$YTLbOLtHgUM4iB6ptrfw6.t10B7Lhr3pP7dig4E3rtWqw.zZ2Zq12\\\",\\\"expires\\\":\\\"2024-04-20 08:06:41\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"H50ijahUNKJ1WI1rmHihNHN7coOGCxGg\\\"}}\"', '2024-04-20 07:56:41'),
(125, 0, 'user_auth_added', '\"{\\\"id\\\":49,\\\"inserted\\\":{\\\"id\\\":49,\\\"user_id\\\":10,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$Sojx0WgTH95sbt7nJQ7oo.y6wL.j8IlKE7DTIjxOvj5uGwGsPSotm\\\",\\\"expires\\\":\\\"2024-04-20 11:16:59\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"t3EppslVgDmjvsl0r0-RjwVlk1UnqJmJ\\\"}}\"', '2024-04-20 11:13:59'),
(126, 0, 'user_updated', '\"{\\\"id\\\":10,\\\"updated\\\":{\\\"id\\\":10,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Kariyer\\\",\\\"last_name\\\":\\\"Fora\\\",\\\"tcno\\\":\\\"99999999999\\\",\\\"gsm\\\":\\\"9999999999\\\",\\\"email\\\":\\\"kariyer@kariyerfora.com\\\",\\\"dogum_yili\\\":9999,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 06:16:14\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Kariyer Fora\\\"},\\\"deleted\\\":{\\\"gsmverified\\\":0}}\"', '2024-04-20 11:14:04'),
(127, 0, 'user_auth_added', '\"{\\\"id\\\":50,\\\"inserted\\\":{\\\"id\\\":50,\\\"user_id\\\":10,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$DFIAdYE99NjB5QDUF7dau.baSeKRVAv9lkxU2vsDuiDodGmsvZRYy\\\",\\\"expires\\\":\\\"2024-04-20 11:17:42\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"0C98OIHp1XR1MV0p0pnoHtnqLifJNu6z\\\"}}\"', '2024-04-20 11:14:42'),
(128, 10, 'business_created', '\"{\\\"id\\\":17,\\\"inserted\\\":{\\\"id\\\":17,\\\"name\\\":\\\"Etsy Bitsy Corps\\\",\\\"slug\\\":\\\"etsy-bitsy-corps\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 11:15:02'),
(129, 10, 'user_business_added', '\"{\\\"id\\\":47,\\\"inserted\\\":{\\\"id\\\":47,\\\"user_id\\\":10,\\\"business_id\\\":17,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 11:15:02'),
(130, 10, 'user_updated', '\"{\\\"id\\\":10,\\\"updated\\\":{\\\"id\\\":10,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Kariyer\\\",\\\"last_name\\\":\\\"Fora\\\",\\\"tcno\\\":\\\"99999999999\\\",\\\"gsm\\\":\\\"9999999999\\\",\\\"email\\\":\\\"kariyer@kariyerfora.com\\\",\\\"dogum_yili\\\":9999,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 06:16:14\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Kariyer Fora\\\"},\\\"deleted\\\":{\\\"remainingBusinessCount\\\":1}}\"', '2024-04-20 11:15:02'),
(131, 10, 'user_business_added', '\"{\\\"id\\\":48,\\\"inserted\\\":{\\\"id\\\":48,\\\"user_id\\\":3,\\\"business_id\\\":17,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 11:15:08'),
(132, 10, 'user_business_added', '\"{\\\"id\\\":49,\\\"inserted\\\":{\\\"id\\\":49,\\\"user_id\\\":7,\\\"business_id\\\":17,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 11:15:13'),
(133, 10, 'user_updated', '\"{\\\"id\\\":7,\\\"updated\\\":{\\\"id\\\":7,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Mehmt\\\",\\\"last_name\\\":\\\"Aaada\\\",\\\"tcno\\\":\\\"13158878781\\\",\\\"gsm\\\":\\\"1341341333\\\",\\\"email\\\":\\\"hmet@gmail.com\\\",\\\"dogum_yili\\\":\\\"1991\\\",\\\"tcnoverified\\\":0,\\\"gsmverified\\\":0,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 04:39:39\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Mehmt Aaad\\\"},\\\"deleted\\\":{\\\"last_name\\\":\\\"Aaad\\\",\\\"dogum_yili\\\":1991}}\"', '2024-04-20 11:15:23'),
(134, 1, 'resource_updated', '\"{\\\"id\\\":8,\\\"updated\\\":{\\\"id\\\":8,\\\"name\\\":\\\"5 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-19 21:16:15\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"3 No\'lu Oda\\\"}}\"', '2024-04-20 14:03:26');
INSERT INTO `logs` (`id`, `user_id`, `event_type`, `event`, `created_at`) VALUES
(135, 1, 'resource_updated', '\"{\\\"id\\\":9,\\\"updated\\\":{\\\"id\\\":9,\\\"name\\\":\\\"\\\\u00c7ocuk Muayene Odas\\\\u0131\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Pediatrik Muayene Odas\\\\u0131\\\",\\\"created_at\\\":\\\"2024-04-19 21:35:11\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"\\\\u00c7ocuk Muayene\\\"}}\"', '2024-04-20 14:04:17'),
(136, 1, 'resource_updated', '\"{\\\"id\\\":11,\\\"updated\\\":{\\\"id\\\":11,\\\"name\\\":\\\"Implant Set\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"Implant Set\\\",\\\"created_at\\\":\\\"2024-04-19 21:35:54\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 14:04:20'),
(137, 0, 'user_created', '\"{\\\"id\\\":11,\\\"inserted\\\":{\\\"id\\\":11,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"berna\\\",\\\"last_name\\\":\\\"nh\\\",\\\"tcno\\\":\\\"\\\",\\\"gsm\\\":\\\"1111111111\\\",\\\"email\\\":\\\"ev@gmail.com\\\",\\\"dogum_yili\\\":\\\"\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-20 15:08:05'),
(138, 0, 'user_auth_added', '\"{\\\"id\\\":51,\\\"inserted\\\":{\\\"id\\\":51,\\\"user_id\\\":11,\\\"type\\\":\\\"password\\\",\\\"secret\\\":\\\"$2y$13$Vz5gTRYTFZEUBKUi0IivIOHlVhlwcNnjE\\\\/wExrNxkvehI9.bVAb3K\\\",\\\"expires\\\":null,\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":null}}\"', '2024-04-20 15:08:05'),
(139, 11, 'business_created', '\"{\\\"id\\\":18,\\\"inserted\\\":{\\\"id\\\":18,\\\"name\\\":\\\"berna\\\",\\\"slug\\\":\\\"berna\\\",\\\"timezone\\\":\\\"Pacific\\\\/Pohnpei\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 15:08:46'),
(140, 11, 'user_business_added', '\"{\\\"id\\\":50,\\\"inserted\\\":{\\\"id\\\":50,\\\"user_id\\\":11,\\\"business_id\\\":18,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 15:08:46'),
(141, 11, 'user_updated', '\"{\\\"id\\\":11,\\\"updated\\\":{\\\"id\\\":11,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"berna\\\",\\\"last_name\\\":\\\"nh\\\",\\\"tcno\\\":\\\"\\\",\\\"gsm\\\":\\\"1111111111\\\",\\\"email\\\":\\\"ev@gmail.com\\\",\\\"dogum_yili\\\":null,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":0,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 15:08:05\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"berna nh\\\"},\\\"deleted\\\":{\\\"remainingBusinessCount\\\":1}}\"', '2024-04-20 15:08:46'),
(142, 11, 'user_business_added', '\"{\\\"id\\\":51,\\\"inserted\\\":{\\\"id\\\":51,\\\"user_id\\\":6,\\\"business_id\\\":18,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 15:35:15'),
(143, 11, 'user_business_added', '\"{\\\"id\\\":52,\\\"inserted\\\":{\\\"id\\\":52,\\\"user_id\\\":1,\\\"business_id\\\":18,\\\"role\\\":\\\"expert\\\",\\\"created_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 15:35:22'),
(144, 11, 'business_updated', '\"{\\\"id\\\":18,\\\"updated\\\":{\\\"id\\\":18,\\\"name\\\":\\\"Berna So\\\\u011fuk Zincir Ta\\\\u015f\\\\u0131mac\\\\u0131l\\\\u0131k\\\",\\\"slug\\\":\\\"berna\\\",\\\"timezone\\\":\\\"Pacific\\\\/Pohnpei\\\",\\\"created_at\\\":\\\"2024-04-20 15:08:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"berna\\\"}}\"', '2024-04-20 15:35:51'),
(145, 11, 'resource_created', '\"{\\\"id\\\":13,\\\"inserted\\\":{\\\"id\\\":13,\\\"name\\\":\\\"06 AA 1001\\\",\\\"business_id\\\":18,\\\"resource_type\\\":\\\"Frigo T\\\\u0131r\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 15:36:15'),
(146, 1, 'service_created', '\"{\\\"id\\\":5,\\\"inserted\\\":{\\\"id\\\":5,\\\"business_id\\\":18,\\\"name\\\":\\\"Nakliye\\\",\\\"resource_type\\\":\\\"Frigo T\\\\u0131r\\\",\\\"expert_type\\\":null,\\\"duration\\\":\\\"60\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-20 15:39:43'),
(147, 1, 'business_updated', '\"{\\\"id\\\":18,\\\"updated\\\":{\\\"id\\\":18,\\\"name\\\":\\\"So\\\\u011fuk Zincir Ta\\\\u015f\\\\u0131mac\\\\u0131l\\\\u0131k\\\",\\\"slug\\\":\\\"berna\\\",\\\"timezone\\\":\\\"Pacific\\\\/Pohnpei\\\",\\\"created_at\\\":\\\"2024-04-20 15:08:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"Berna So\\\\u011fuk Zincir Ta\\\\u015f\\\\u0131mac\\\\u0131l\\\\u0131k\\\"}}\"', '2024-04-20 15:50:09'),
(148, 1, 'business_updated', '\"{\\\"id\\\":18,\\\"updated\\\":{\\\"id\\\":18,\\\"name\\\":\\\"Zincir Ta\\\\u015f\\\\u0131mac\\\\u0131l\\\\u0131k\\\",\\\"slug\\\":\\\"berna\\\",\\\"timezone\\\":\\\"Pacific\\\\/Pohnpei\\\",\\\"created_at\\\":\\\"2024-04-20 15:08:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"So\\\\u011fuk Zincir Ta\\\\u015f\\\\u0131mac\\\\u0131l\\\\u0131k\\\"}}\"', '2024-04-20 15:50:15'),
(149, 0, 'user_auth_added', '\"{\\\"id\\\":52,\\\"inserted\\\":{\\\"id\\\":52,\\\"user_id\\\":11,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$SV5s4fIEE7MVe1zy689h3eDvbNH4BPxWi.YTOkHMguLjjM4dLuslO\\\",\\\"expires\\\":\\\"2024-04-20 16:14:11\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"Ts071KdpGfcAWfm6fHMHEKPczB3jqryn\\\"}}\"', '2024-04-20 16:11:11'),
(150, 0, 'user_updated', '\"{\\\"id\\\":11,\\\"updated\\\":{\\\"id\\\":11,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"berna\\\",\\\"last_name\\\":\\\"nh\\\",\\\"tcno\\\":\\\"\\\",\\\"gsm\\\":\\\"1111111111\\\",\\\"email\\\":\\\"ev@gmail.com\\\",\\\"dogum_yili\\\":null,\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 15:08:05\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"berna nh\\\"},\\\"deleted\\\":{\\\"gsmverified\\\":0}}\"', '2024-04-20 16:11:15'),
(153, 11, 'resource_updated', '\"{\\\"id\\\":13,\\\"updated\\\":{\\\"id\\\":13,\\\"name\\\":\\\"06 AA 1001\\\",\\\"business_id\\\":18,\\\"resource_type\\\":\\\"Frigo T\\\\u0131r\\\",\\\"created_at\\\":\\\"2024-04-20 15:36:15\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 16:28:02'),
(154, 11, 'service_updated', '\"{\\\"id\\\":5,\\\"updated\\\":{\\\"id\\\":5,\\\"business_id\\\":18,\\\"name\\\":\\\"Nakliye\\\",\\\"resource_type\\\":\\\"Frigo T\\\\u0131r\\\",\\\"expert_type\\\":null,\\\"duration\\\":60,\\\"created_at\\\":\\\"2024-04-20 15:39:43\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 16:28:02'),
(155, 11, 'user_business_deleted', '\"{\\\"id\\\":50,\\\"deleted\\\":{\\\"id\\\":50,\\\"user_id\\\":11,\\\"business_id\\\":18,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-20 15:08:46\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 16:28:02'),
(156, 11, 'user_business_deleted', '\"{\\\"id\\\":51,\\\"deleted\\\":{\\\"id\\\":51,\\\"user_id\\\":6,\\\"business_id\\\":18,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-20 15:35:15\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 16:28:02'),
(157, 11, 'user_business_deleted', '\"{\\\"id\\\":52,\\\"deleted\\\":{\\\"id\\\":52,\\\"user_id\\\":1,\\\"business_id\\\":18,\\\"role\\\":\\\"expert\\\",\\\"created_at\\\":\\\"2024-04-20 15:35:22\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 16:28:02'),
(158, 11, 'business_updated', '\"{\\\"id\\\":18,\\\"updated\\\":{\\\"id\\\":18,\\\"name\\\":\\\"Zincir Ta\\\\u015f\\\\u0131mac\\\\u0131l\\\\u0131k\\\",\\\"slug\\\":\\\"berna\\\",\\\"timezone\\\":\\\"Pacific\\\\/Pohnpei\\\",\\\"created_at\\\":\\\"2024-04-20 15:08:46\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 16:28:02'),
(159, 1, 'user_business_deleted', '\"{\\\"id\\\":28,\\\"deleted\\\":{\\\"id\\\":28,\\\"user_id\\\":1,\\\"business_id\\\":12,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 18:26:14\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:34:56'),
(160, 1, 'user_business_deleted', '\"{\\\"id\\\":40,\\\"deleted\\\":{\\\"id\\\":40,\\\"user_id\\\":4,\\\"business_id\\\":12,\\\"role\\\":\\\"expert\\\",\\\"created_at\\\":\\\"2024-04-19 19:29:35\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:34:56'),
(161, 1, 'user_business_deleted', '\"{\\\"id\\\":42,\\\"deleted\\\":{\\\"id\\\":42,\\\"user_id\\\":6,\\\"business_id\\\":12,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-20 04:37:54\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:34:56'),
(162, 1, 'user_business_deleted', '\"{\\\"id\\\":43,\\\"deleted\\\":{\\\"id\\\":43,\\\"user_id\\\":7,\\\"business_id\\\":12,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-20 04:39:39\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:34:56'),
(163, 1, 'business_updated', '\"{\\\"id\\\":12,\\\"updated\\\":{\\\"id\\\":12,\\\"name\\\":\\\"G\\\\u00fcm\\\\u00fc\\\\u015f Oto Y\\\\u0131kama\\\",\\\"slug\\\":\\\"gumus-oto-yikama\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-18 18:26:14\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:34:56'),
(164, 1, 'resource_updated', '\"{\\\"id\\\":13,\\\"updated\\\":{\\\"id\\\":13,\\\"name\\\":\\\"06 AA 1001\\\",\\\"business_id\\\":18,\\\"resource_type\\\":\\\"Frigo T\\\\u0131r\\\",\\\"created_at\\\":\\\"2024-04-20 15:36:15\\\",\\\"updated_at\\\":\\\"2024-04-20 16:28:02\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":\\\"2024-04-20 16:28:02\\\"}}\"', '2024-04-20 17:35:01'),
(165, 1, 'service_updated', '\"{\\\"id\\\":5,\\\"updated\\\":{\\\"id\\\":5,\\\"business_id\\\":18,\\\"name\\\":\\\"Nakliye\\\",\\\"resource_type\\\":\\\"Frigo T\\\\u0131r\\\",\\\"expert_type\\\":null,\\\"duration\\\":60,\\\"created_at\\\":\\\"2024-04-20 15:39:43\\\",\\\"updated_at\\\":\\\"2024-04-20 16:28:02\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":\\\"2024-04-20 16:28:02\\\"}}\"', '2024-04-20 17:35:01'),
(166, 1, 'business_updated', '\"{\\\"id\\\":18,\\\"updated\\\":{\\\"id\\\":18,\\\"name\\\":\\\"Zincir Ta\\\\u015f\\\\u0131mac\\\\u0131l\\\\u0131k\\\",\\\"slug\\\":\\\"berna\\\",\\\"timezone\\\":\\\"Pacific\\\\/Pohnpei\\\",\\\"created_at\\\":\\\"2024-04-20 15:08:46\\\",\\\"updated_at\\\":\\\"2024-04-20 19:31:01\\\",\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:01'),
(167, 1, 'user_business_deleted', '\"{\\\"id\\\":33,\\\"deleted\\\":{\\\"id\\\":33,\\\"user_id\\\":1,\\\"business_id\\\":15,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-19 09:34:28\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:35:06'),
(168, 1, 'user_business_deleted', '\"{\\\"id\\\":34,\\\"deleted\\\":{\\\"id\\\":34,\\\"user_id\\\":5,\\\"business_id\\\":15,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-19 09:34:47\\\",\\\"updated_at\\\":\\\"2024-04-19 09:35:05\\\"}}\"', '2024-04-20 17:35:06'),
(169, 1, 'business_updated', '\"{\\\"id\\\":15,\\\"updated\\\":{\\\"id\\\":15,\\\"name\\\":\\\"Titiz Oto Y\\\\u0131kama\\\",\\\"slug\\\":\\\"titiz-oto-yikama\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-19 09:34:28\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:06'),
(170, 1, 'resource_updated', '\"{\\\"id\\\":12,\\\"updated\\\":{\\\"id\\\":12,\\\"name\\\":\\\"3 No\'lu Oda\\\",\\\"business_id\\\":10,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":\\\"2024-04-20 03:34:22\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:11'),
(171, 1, 'service_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"business_id\\\":10,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"Muayene\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":187,\\\"created_at\\\":\\\"2024-04-20 03:40:30\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:11'),
(172, 1, 'rule_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"name\\\":\\\"Mesai Saatleri\\\",\\\"ruleset\\\":\\\"<>\\\",\\\"business_id\\\":10,\\\"created_at\\\":\\\"2024-04-20 03:40:57\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:11'),
(173, 1, 'business_updated', '\"{\\\"id\\\":10,\\\"updated\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Pala Pilates\\\",\\\"slug\\\":\\\"pala-pilates\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-18 18:19:16\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:11'),
(174, 1, 'user_business_deleted', '\"{\\\"id\\\":29,\\\"deleted\\\":{\\\"id\\\":29,\\\"user_id\\\":1,\\\"business_id\\\":13,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 18:27:59\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:35:17'),
(175, 1, 'business_updated', '\"{\\\"id\\\":13,\\\"updated\\\":{\\\"id\\\":13,\\\"name\\\":\\\"G\\\\u00f6rg\\\\u00fcl\\\\u00fc Kuaf\\\\u00f6r\\\",\\\"slug\\\":\\\"gorgulu-kuafor\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-18 18:27:59\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:17'),
(176, 1, 'service_updated', '\"{\\\"id\\\":4,\\\"updated\\\":{\\\"id\\\":4,\\\"business_id\\\":11,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"\\\",\\\"expert_type\\\":null,\\\"duration\\\":40,\\\"created_at\\\":\\\"2024-04-20 04:07:18\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:24'),
(177, 1, 'user_business_deleted', '\"{\\\"id\\\":30,\\\"deleted\\\":{\\\"id\\\":30,\\\"user_id\\\":1,\\\"business_id\\\":11,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-18 18:31:43\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:35:24'),
(178, 1, 'business_updated', '\"{\\\"id\\\":11,\\\"updated\\\":{\\\"id\\\":11,\\\"name\\\":\\\"Gezegen \\\\u0130n\\\\u015faat\\\",\\\"slug\\\":\\\"gezegen-insaat\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-18 18:25:23\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:24'),
(179, 1, 'user_business_deleted', '\"{\\\"id\\\":47,\\\"deleted\\\":{\\\"id\\\":47,\\\"user_id\\\":10,\\\"business_id\\\":17,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":\\\"2024-04-20 11:15:02\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:35:32'),
(180, 1, 'user_business_deleted', '\"{\\\"id\\\":48,\\\"deleted\\\":{\\\"id\\\":48,\\\"user_id\\\":3,\\\"business_id\\\":17,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-20 11:15:08\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:35:32'),
(181, 1, 'user_business_deleted', '\"{\\\"id\\\":49,\\\"deleted\\\":{\\\"id\\\":49,\\\"user_id\\\":7,\\\"business_id\\\":17,\\\"role\\\":\\\"secretary\\\",\\\"created_at\\\":\\\"2024-04-20 11:15:13\\\",\\\"updated_at\\\":null}}\"', '2024-04-20 17:35:32'),
(182, 1, 'business_updated', '\"{\\\"id\\\":17,\\\"updated\\\":{\\\"id\\\":17,\\\"name\\\":\\\"Etsy Bitsy Corps\\\",\\\"slug\\\":\\\"etsy-bitsy-corps\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-20 11:15:02\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-20 17:35:32'),
(183, 1, 'business_updated', '\"{\\\"id\\\":6,\\\"updated\\\":{\\\"id\\\":6,\\\"name\\\":\\\"Appointment SAAS\\\",\\\"slug\\\":\\\"appointmentsaas\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"created_at\\\":\\\"2024-04-15 07:56:23\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"name\\\":\\\"Appointment SAAS Inc.\\\"}}\"', '2024-04-20 17:35:44'),
(184, 1, 'user_business_added', '\"{\\\"id\\\":53,\\\"inserted\\\":{\\\"id\\\":53,\\\"user_id\\\":1,\\\"business_id\\\":6,\\\"role\\\":\\\"admin\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\"', '2024-04-21 10:15:17'),
(185, 1, 'user_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Umut\\\",\\\"last_name\\\":\\\"Demirhan\\\",\\\"tcno\\\":\\\"23416086000\\\",\\\"gsm\\\":\\\"5330338197\\\",\\\"email\\\":\\\"umut@kariyerfora.com\\\",\\\"dogum_yili\\\":\\\"1977\\\",\\\"tcnoverified\\\":1,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Umut Demirhan\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1977}}\"', '2024-04-21 10:15:20'),
(186, 1, 'resource_created', '\"{\\\"id\\\":14,\\\"inserted\\\":{\\\"id\\\":14,\\\"name\\\":\\\"2 No\'lu Oda\\\",\\\"business_id\\\":6,\\\"resource_type\\\":\\\"Muayene\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-21 10:15:31'),
(187, 1, 'rule_created', '\"{\\\"id\\\":4,\\\"inserted\\\":{\\\"id\\\":4,\\\"name\\\":\\\"Mesai Saatleri\\\",\\\"ruleset\\\":\\\"<>\\\",\\\"business_id\\\":6,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-21 10:15:38'),
(188, 1, 'service_created', '\"{\\\"id\\\":6,\\\"inserted\\\":{\\\"id\\\":6,\\\"business_id\\\":6,\\\"name\\\":\\\"Di\\\\u015f Temizli\\\\u011fi\\\",\\\"resource_type\\\":\\\"Muayene\\\",\\\"expert_type\\\":null,\\\"duration\\\":\\\"15\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-21 10:16:07'),
(189, 1, 'user_created', '\"{\\\"id\\\":12,\\\"inserted\\\":{\\\"id\\\":12,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Ali\\\",\\\"last_name\\\":\\\"Veli\\\",\\\"tcno\\\":\\\"22222222222\\\",\\\"gsm\\\":\\\"2222222222\\\",\\\"email\\\":\\\"22@a.b\\\",\\\"dogum_yili\\\":\\\"1922\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-24 07:34:00'),
(190, 1, 'user_created', '\"{\\\"id\\\":13,\\\"inserted\\\":{\\\"id\\\":13,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Ali\\\",\\\"last_name\\\":\\\"Veli\\\",\\\"tcno\\\":\\\"22222222222\\\",\\\"gsm\\\":\\\"2222222222\\\",\\\"email\\\":\\\"22@il.c\\\",\\\"dogum_yili\\\":\\\"1922\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-24 07:35:46'),
(191, 1, 'user_created', '\"{\\\"id\\\":14,\\\"inserted\\\":{\\\"id\\\":14,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Ali\\\",\\\"last_name\\\":\\\"Veli\\\",\\\"tcno\\\":\\\"22222222222\\\",\\\"gsm\\\":\\\"2222222222\\\",\\\"email\\\":\\\"22@il.c\\\",\\\"dogum_yili\\\":\\\"1922\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-24 07:37:19'),
(192, 1, 'user_business_added', '\"{\\\"id\\\":54,\\\"inserted\\\":{\\\"id\\\":54,\\\"user_id\\\":14,\\\"business_id\\\":1,\\\"role\\\":\\\"expert\\\",\\\"expert_type\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null}}\"', '2024-04-24 07:37:19'),
(193, 1, 'user_created', '\"{\\\"id\\\":15,\\\"inserted\\\":{\\\"id\\\":15,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Uzman 3\\\",\\\"last_name\\\":\\\"Azman\\\",\\\"tcno\\\":\\\"33333333333\\\",\\\"gsm\\\":\\\"3333333333\\\",\\\"email\\\":\\\"333@33.33\\\",\\\"dogum_yili\\\":\\\"3333\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-24 07:39:03'),
(194, 1, 'user_business_added', '\"{\\\"id\\\":55,\\\"inserted\\\":{\\\"id\\\":55,\\\"user_id\\\":15,\\\"business_id\\\":1,\\\"role\\\":\\\"expert\\\",\\\"expert_type\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null}}\"', '2024-04-24 07:39:03'),
(195, 1, 'user_created', '\"{\\\"id\\\":16,\\\"inserted\\\":{\\\"id\\\":16,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"44444444\\\",\\\"last_name\\\":\\\"4444\\\",\\\"tcno\\\":\\\"44444444444\\\",\\\"gsm\\\":\\\"4444444444\\\",\\\"email\\\":\\\"44@44.44\\\",\\\"dogum_yili\\\":\\\"4444\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-24 07:43:24'),
(196, 1, 'user_business_added', '\"{\\\"id\\\":56,\\\"inserted\\\":{\\\"id\\\":56,\\\"user_id\\\":16,\\\"business_id\\\":1,\\\"role\\\":\\\"expert\\\",\\\"expert_type\\\":\\\"Doktor\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\"', '2024-04-24 07:43:24'),
(197, 1, 'business_updated', '\"{\\\"id\\\":6,\\\"updated\\\":{\\\"id\\\":6,\\\"name\\\":\\\"Appointment SAAS\\\",\\\"slug\\\":\\\"appointmentsaas\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"created_at\\\":\\\"2024-04-15 07:56:23\\\",\\\"updated_at\\\":\\\"2024-04-20 17:35:44\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":null}}\"', '2024-04-24 19:30:18'),
(198, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":null}}\"', '2024-04-24 19:36:54'),
(199, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 19:36:54\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\"]\\\"}}\"', '2024-04-24 19:37:50'),
(200, 1, 'user_business_added', '\"{\\\"id\\\":57,\\\"inserted\\\":{\\\"id\\\":57,\\\"user_id\\\":11,\\\"business_id\\\":1,\\\"role\\\":\\\"expert\\\",\\\"expert_type\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null}}\"', '2024-04-24 19:58:39'),
(201, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"1\\\\\\\",\\\\\\\"3\\\\\\\",\\\\\\\"4\\\\\\\",\\\\\\\"6\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 19:37:50\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\"}}\"', '2024-04-24 20:16:29'),
(202, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"1\\\\\\\",\\\\\\\"3\\\\\\\",\\\\\\\"4\\\\\\\",\\\\\\\"5\\\\\\\",\\\\\\\"6\\\\\\\",\\\\\\\"7\\\\\\\",\\\\\\\"9\\\\\\\",\\\\\\\"10\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:16:29\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"1\\\\\\\",\\\\\\\"3\\\\\\\",\\\\\\\"4\\\\\\\",\\\\\\\"6\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\"}}\"', '2024-04-24 20:17:41'),
(203, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"1\\\\\\\",\\\\\\\"3\\\\\\\",\\\\\\\"4\\\\\\\",\\\\\\\"5\\\\\\\",\\\\\\\"6\\\\\\\",\\\\\\\"7\\\\\\\",\\\\\\\"9\\\\\\\",\\\\\\\"10\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:17:41\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 20:17:40'),
(204, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"1\\\\\\\",\\\\\\\"3\\\\\\\",\\\\\\\"4\\\\\\\",\\\\\\\"5\\\\\\\",\\\\\\\"6\\\\\\\",\\\\\\\"7\\\\\\\",\\\\\\\"9\\\\\\\",\\\\\\\"10\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:17:41\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 20:17:41'),
(205, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"1\\\\\\\",\\\\\\\"3\\\\\\\",\\\\\\\"4\\\\\\\",\\\\\\\"5\\\\\\\",\\\\\\\"6\\\\\\\",\\\\\\\"7\\\\\\\",\\\\\\\"9\\\\\\\",\\\\\\\"10\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:17:41\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 20:17:43'),
(206, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:17:41\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"1\\\\\\\",\\\\\\\"3\\\\\\\",\\\\\\\"4\\\\\\\",\\\\\\\"5\\\\\\\",\\\\\\\"6\\\\\\\",\\\\\\\"7\\\\\\\",\\\\\\\"9\\\\\\\",\\\\\\\"10\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\"}}\"', '2024-04-24 20:20:28'),
(207, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:20:28\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 20:20:41'),
(208, 1, 'service_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"business_id\\\":1,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"Panaromik R\\\\u00f6ntgen\\\",\\\"expert_type\\\":\\\"Ortodonti\\\",\\\"expert_id\\\":null,\\\"duration\\\":\\\"49\\\",\\\"created_at\\\":\\\"2024-04-19 22:09:44\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type\\\":\\\"\\\",\\\"expert_type\\\":\\\"adf\\\",\\\"duration\\\":49}}\"', '2024-04-24 20:26:01'),
(209, 1, 'service_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"business_id\\\":1,\\\"name\\\":\\\"a34\\\",\\\"resource_type\\\":\\\"Panaromik R\\\\u00f6ntgen\\\",\\\"expert_type\\\":\\\"Ortodonti\\\",\\\"expert_id\\\":null,\\\"duration\\\":\\\"105\\\",\\\"created_at\\\":\\\"2024-04-19 22:09:44\\\",\\\"updated_at\\\":\\\"2024-04-24 20:26:01\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"duration\\\":49}}\"', '2024-04-24 20:27:10'),
(210, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"adf\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:20:28\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type_list\\\":null}}\"', '2024-04-24 20:47:57'),
(211, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"adf\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:47:57\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 20:51:55'),
(212, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\",\\\\\\\"adf\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:47:57\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"adf\\\\\\\"]\\\"}}\"', '2024-04-24 20:52:30'),
(213, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:52:30\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\",\\\\\\\"adf\\\\\\\"]\\\"}}\"', '2024-04-24 20:52:37'),
(214, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:52:37\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 20:52:41'),
(215, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:52:37\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\"}}\"', '2024-04-24 20:54:04'),
(216, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:54:04\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Di\\\\\\\\u015f Hekimi\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Ortodonti\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\"}}\"', '2024-04-24 20:54:08'),
(217, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:54:08\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 21:03:32'),
(218, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 20:54:08\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\"}}\"', '2024-04-24 21:04:50'),
(219, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 21:04:49\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 21:05:29'),
(220, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 21:04:49\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\"}}\"', '2024-04-24 21:06:12'),
(221, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 21:06:12\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-24 21:06:40'),
(222, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 21:06:12\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Implant Set\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\",\\\\\\\"Zoom Lisans Kodu\\\\\\\"]\\\"}}\"', '2024-04-24 21:06:44'),
(223, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 21:06:44\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-28 11:07:46'),
(224, 1, 'resource_created', '\"{\\\"id\\\":15,\\\"inserted\\\":{\\\"id\\\":15,\\\"name\\\":\\\"1 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-28 11:08:13'),
(225, 1, 'resource_updated', '\"{\\\"id\\\":15,\\\"updated\\\":{\\\"id\\\":15,\\\"name\\\":\\\"1 No\'lu Oda\\\",\\\"business_id\\\":1,\\\"resource_type\\\":\\\"\\\",\\\"created_at\\\":\\\"2024-04-28 11:08:13\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":{\\\"expression\\\":\\\"NOW()\\\",\\\"params\\\":[]}},\\\"deleted\\\":{\\\"deleted_at\\\":null}}\"', '2024-04-28 11:08:26'),
(226, 1, 'service_created', '\"{\\\"id\\\":7,\\\"inserted\\\":{\\\"id\\\":7,\\\"business_id\\\":1,\\\"name\\\":\\\"Nakliye\\\",\\\"resource_type\\\":\\\"Muayene\\\",\\\"expert_type\\\":\\\"\\\",\\\"expert_id\\\":null,\\\"duration\\\":\\\"50\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-28 11:09:56'),
(227, 1, 'business_updated', '\"{\\\"id\\\":16,\\\"updated\\\":{\\\"id\\\":16,\\\"name\\\":\\\"Etsy Bitsy Corp\\\",\\\"slug\\\":\\\"etsy-bitsy-corp\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"created_at\\\":\\\"2024-04-20 06:08:48\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":null,\\\"resource_type_list\\\":null}}\"', '2024-04-28 17:42:14'),
(228, 1, 'business_updated', '\"{\\\"id\\\":16,\\\"updated\\\":{\\\"id\\\":16,\\\"name\\\":\\\"Etsy Bitsy Corp\\\",\\\"slug\\\":\\\"etsy-bitsy-corp\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"created_at\\\":\\\"2024-04-20 06:08:48\\\",\\\"updated_at\\\":\\\"2024-04-28 17:42:14\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-28 17:42:30'),
(229, 1, 'business_updated', '\"{\\\"id\\\":16,\\\"updated\\\":{\\\"id\\\":16,\\\"name\\\":\\\"Etsy Bitsy Corp\\\",\\\"slug\\\":\\\"etsy-bitsy-corp\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"created_at\\\":\\\"2024-04-20 06:08:48\\\",\\\"updated_at\\\":\\\"2024-04-28 17:42:14\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-28 17:43:03'),
(230, 1, 'business_updated', '\"{\\\"id\\\":1,\\\"updated\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Dental Dentist\\\",\\\"slug\\\":\\\"dentaldent\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Doktor\\\\\\\",\\\\\\\"Estetisyen\\\\\\\",\\\\\\\"Pediatrik Ortodondi\\\\\\\",\\\\\\\"\\\\\\\\u00c7ene Cerrahisi\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Muayene\\\\\\\",\\\\\\\"Panaromik R\\\\\\\\u00f6ntgen\\\\\\\",\\\\\\\"Pediatrik Muayene Odas\\\\\\\\u0131\\\\\\\"]\\\",\\\"created_at\\\":null,\\\"updated_at\\\":\\\"2024-04-24 21:06:44\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-28 18:00:26'),
(231, 1, 'user_updated', '\"{\\\"id\\\":16,\\\"updated\\\":{\\\"id\\\":16,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"44444444\\\",\\\"last_name\\\":\\\"4444\\\",\\\"tcno\\\":\\\"44444444444\\\",\\\"gsm\\\":\\\"4444444444\\\",\\\"email\\\":\\\"44@44.44\\\",\\\"dogum_yili\\\":\\\"4444\\\",\\\"tcnoverified\\\":0,\\\"gsmverified\\\":0,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-24 07:43:24\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"44444444 4444\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":4444}}\"', '2024-04-28 20:01:23'),
(232, 1, 'user_updated', '\"{\\\"id\\\":11,\\\"updated\\\":{\\\"id\\\":11,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"berna\\\",\\\"last_name\\\":\\\"nh\\\",\\\"tcno\\\":\\\"\\\",\\\"gsm\\\":\\\"1111111111\\\",\\\"email\\\":\\\"ev@gmail.com\\\",\\\"dogum_yili\\\":\\\"\\\",\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-20 15:08:05\\\",\\\"updated_at\\\":\\\"2024-04-20 16:11:15\\\",\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"berna nh\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":null}}\"', '2024-04-28 20:01:52'),
(233, 1, 'user_updated', '\"{\\\"id\\\":2,\\\"updated\\\":{\\\"id\\\":2,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"H\\\\u00fcseyin\\\",\\\"last_name\\\":\\\"Mumay\\\",\\\"tcno\\\":\\\"53149018900\\\",\\\"gsm\\\":\\\"5445868624\\\",\\\"email\\\":\\\"ideametrik@gmail.com\\\",\\\"dogum_yili\\\":\\\"1981\\\",\\\"tcnoverified\\\":1,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"H\\\\u00fcseyin Mumay\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1981}}\"', '2024-04-28 20:01:59'),
(234, 1, 'user_updated', '\"{\\\"id\\\":16,\\\"updated\\\":{\\\"id\\\":16,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"44444444\\\",\\\"last_name\\\":\\\"4444\\\",\\\"tcno\\\":\\\"44444444444\\\",\\\"gsm\\\":\\\"4444444444\\\",\\\"email\\\":\\\"44@44.44\\\",\\\"dogum_yili\\\":\\\"4444\\\",\\\"tcnoverified\\\":0,\\\"gsmverified\\\":0,\\\"emailverified\\\":0,\\\"language\\\":\\\"tr\\\",\\\"superadmin\\\":0,\\\"remainingBusinessCount\\\":0,\\\"last_active\\\":null,\\\"created_at\\\":\\\"2024-04-24 07:43:24\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"44444444 4444\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":4444}}\"', '2024-04-28 20:04:21'),
(235, 1, 'user_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Burhan\\\",\\\"last_name\\\":\\\"\\\\u00c7alhan\\\",\\\"tcno\\\":\\\"13515135131\\\",\\\"gsm\\\":\\\"5057958150\\\",\\\"email\\\":\\\"calhan.bur@gmail.com\\\",\\\"dogum_yili\\\":\\\"1981\\\",\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Burhan \\\\u00c7alhan\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1981}}\"', '2024-04-28 20:04:39'),
(236, 1, 'user_updated', '\"{\\\"id\\\":3,\\\"updated\\\":{\\\"id\\\":3,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Burhan\\\",\\\"last_name\\\":\\\"\\\\u00c7alhan\\\",\\\"tcno\\\":\\\"13515135131\\\",\\\"gsm\\\":\\\"5057958150\\\",\\\"email\\\":\\\"calhan.bur@gmail.com\\\",\\\"dogum_yili\\\":\\\"1981\\\",\\\"tcnoverified\\\":0,\\\"gsmverified\\\":1,\\\"emailverified\\\":1,\\\"language\\\":null,\\\"superadmin\\\":1,\\\"remainingBusinessCount\\\":1,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":\\\"Burhan \\\\u00c7alhan\\\"},\\\"deleted\\\":{\\\"dogum_yili\\\":1981}}\"', '2024-04-28 20:16:34');
INSERT INTO `logs` (`id`, `user_id`, `event_type`, `event`, `created_at`) VALUES
(237, 0, 'user_auth_added', '\"{\\\"id\\\":53,\\\"inserted\\\":{\\\"id\\\":53,\\\"user_id\\\":1,\\\"type\\\":\\\"sms_otp\\\",\\\"secret\\\":\\\"$2y$13$e6EmXZfsYDmQq6JwKA6xCOJlK7MtWUeW63A0wkCW7bV\\\\/EMeaJDKC2\\\",\\\"expires\\\":\\\"2024-04-28 20:30:29\\\",\\\"extra\\\":null,\\\"force_reset\\\":null,\\\"last_used_at\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"authKey\\\":\\\"5fgRe9mfzvgpzSgmOHDmncQYP0Pdu3O0\\\"}}\"', '2024-04-28 20:27:29'),
(238, 1, 'user_created', '\"{\\\"id\\\":17,\\\"inserted\\\":{\\\"id\\\":17,\\\"status\\\":null,\\\"status_message\\\":null,\\\"first_name\\\":\\\"Ufuk\\\",\\\"last_name\\\":\\\"Ufff\\\",\\\"tcno\\\":\\\"77777777777\\\",\\\"gsm\\\":\\\"7777777777\\\",\\\"email\\\":\\\"y@y.y\\\",\\\"dogum_yili\\\":\\\"7777\\\",\\\"tcnoverified\\\":null,\\\"gsmverified\\\":null,\\\"emailverified\\\":null,\\\"language\\\":null,\\\"superadmin\\\":null,\\\"remainingBusinessCount\\\":null,\\\"last_active\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null,\\\"fullname\\\":null}}\"', '2024-04-29 16:25:10'),
(239, 1, 'user_business_added', '\"{\\\"id\\\":58,\\\"inserted\\\":{\\\"id\\\":58,\\\"user_id\\\":17,\\\"business_id\\\":14,\\\"role\\\":\\\"expert\\\",\\\"expert_type\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null}}\"', '2024-04-29 16:25:10'),
(240, 1, 'business_updated', '\"{\\\"id\\\":14,\\\"updated\\\":{\\\"id\\\":14,\\\"name\\\":\\\"ideametrik GM\\\",\\\"slug\\\":\\\"ideametrik-gm\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Bilgisayar\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\",\\\\\\\"Genel Muayene\\\\\\\"]\\\",\\\"created_at\\\":\\\"2024-04-19 08:32:11\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"expert_type_list\\\":null,\\\"resource_type_list\\\":null}}\"', '2024-04-29 16:25:29'),
(241, 1, 'rule_created', '\"{\\\"id\\\":5,\\\"inserted\\\":{\\\"id\\\":5,\\\"name\\\":\\\"Mesai Saatleri\\\",\\\"ruleset\\\":\\\"<>\\\",\\\"business_id\\\":16,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-29 16:26:25'),
(242, 1, 'rule_updated', '\"{\\\"id\\\":5,\\\"updated\\\":{\\\"id\\\":5,\\\"name\\\":\\\"Mesai Saatleri\\\",\\\"ruleset\\\":\\\"<xx>\\\",\\\"business_id\\\":16,\\\"created_at\\\":\\\"2024-04-29 16:26:25\\\",\\\"updated_at\\\":null,\\\"deleted_at\\\":null},\\\"deleted\\\":{\\\"ruleset\\\":\\\"<>\\\"}}\"', '2024-04-29 16:26:33'),
(243, 1, 'service_created', '\"{\\\"id\\\":8,\\\"inserted\\\":{\\\"id\\\":8,\\\"business_id\\\":16,\\\"name\\\":\\\"Nakliye\\\",\\\"resource_type\\\":\\\"\\\",\\\"expert_type\\\":\\\"\\\",\\\"expert_id\\\":null,\\\"duration\\\":\\\"73\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"deleted_at\\\":null}}\"', '2024-04-29 16:26:45'),
(244, 1, 'business_updated', '\"{\\\"id\\\":16,\\\"updated\\\":{\\\"id\\\":16,\\\"name\\\":\\\"Etsy Bitsy Corp\\\",\\\"slug\\\":\\\"etsy-bitsy-corp\\\",\\\"timezone\\\":\\\"Europe\\\\/Istanbul\\\",\\\"expert_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"resource_type_list\\\":\\\"[\\\\\\\"\\\\\\\"]\\\",\\\"created_at\\\":\\\"2024-04-20 06:08:48\\\",\\\"updated_at\\\":\\\"2024-04-28 17:42:14\\\",\\\"deleted_at\\\":null},\\\"deleted\\\":[]}\"', '2024-04-29 16:26:53');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `resource_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`, `business_id`, `resource_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2 No\'lu Oda', 1, 'Muayene', '2024-04-11 08:13:46', NULL, '2024-04-19 08:08:21'),
(2, '1 No\'lu Oda', 1, 'Muayene', '2024-04-19 05:03:29', '2024-04-19 21:15:16', NULL),
(3, '5 No\'lu Oda', 1, 'Muayene', '2024-04-19 06:31:20', NULL, '2024-04-19 07:56:28'),
(4, '2 No\'lu Oda', 1, 'Muayene', '2024-04-19 08:08:16', NULL, NULL),
(5, '1 No\'lu Oda', 14, 'Genel Muayene', '2024-04-19 08:34:24', NULL, NULL),
(6, '2 No\'lu Oda', 14, 'Genel Muayene', '2024-04-19 08:34:31', NULL, NULL),
(7, 'afadafdladf', 1, 'Zoom Lisans Kodu', '2024-04-19 09:37:28', '2024-04-19 21:14:34', '2024-04-19 21:14:34'),
(8, '5 No\'lu Oda', 1, 'Muayene', '2024-04-19 21:16:15', '2024-04-20 14:03:26', NULL),
(9, 'Çocuk Muayene Odası', 1, 'Pediatrik Muayene Odası', '2024-04-19 21:35:11', '2024-04-20 14:04:17', NULL),
(10, 'Panaromik Röntgen', 1, 'Panaromik Röntgen', '2024-04-19 21:35:27', NULL, NULL),
(11, 'Implant Set', 1, 'Implant Set', '2024-04-19 21:35:54', '2024-04-20 14:04:20', '2024-04-20 14:04:20'),
(12, '3 No\'lu Oda', 10, 'Muayene', '2024-04-20 03:34:22', '2024-04-20 17:35:11', '2024-04-20 17:35:11'),
(13, '06 AA 1001', 18, 'Frigo Tır', '2024-04-20 15:36:15', '2024-04-20 17:35:01', '2024-04-20 17:35:01'),
(14, '2 No\'lu Oda', 6, 'Muayene', '2024-04-21 10:15:31', NULL, NULL),
(15, '1 No\'lu Oda', 1, '', '2024-04-28 11:08:13', '2024-04-28 11:08:26', '2024-04-28 11:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruleset` json NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`id`, `name`, `ruleset`, `business_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mesai Saatleri', '\"<>\"', 1, '2024-04-19 07:39:38', NULL, NULL),
(2, 'Mesai Saatlerix', '\"<>\"', 1, '2024-04-19 21:14:43', NULL, '2024-04-19 21:14:46'),
(3, 'Mesai Saatleri', '\"<>\"', 10, '2024-04-20 03:40:57', '2024-04-20 17:35:11', '2024-04-20 17:35:11'),
(4, 'Mesai Saatleri', '\"<>\"', 6, '2024-04-21 10:15:38', NULL, NULL),
(5, 'Mesai Saatleri', '\"<xx>\"', 16, '2024-04-29 16:26:25', '2024-04-29 16:26:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expert_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expert_id` int UNSIGNED DEFAULT NULL,
  `duration` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `business_id`, `name`, `resource_type`, `expert_type`, `expert_id`, `duration`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'a', 'adf', 'adf', NULL, 134, '2024-04-19 21:08:18', NULL, '2024-04-19 21:08:21'),
(2, 1, 'a34', 'Panaromik Röntgen', 'Ortodonti', NULL, 105, '2024-04-19 22:09:44', '2024-04-24 20:27:10', NULL),
(3, 10, 'a34', 'Muayene', 'adf', NULL, 187, '2024-04-20 03:40:30', '2024-04-20 17:35:11', '2024-04-20 17:35:11'),
(4, 11, 'a34', '', NULL, NULL, 40, '2024-04-20 04:07:18', '2024-04-20 17:35:24', '2024-04-20 17:35:24'),
(5, 18, 'Nakliye', 'Frigo Tır', NULL, NULL, 60, '2024-04-20 15:39:43', '2024-04-20 17:35:01', '2024-04-20 17:35:01'),
(6, 6, 'Diş Temizliği', 'Muayene', NULL, NULL, 15, '2024-04-21 10:16:07', NULL, NULL),
(7, 1, 'Nakliye', 'Muayene', '', NULL, 50, '2024-04-28 11:09:56', NULL, NULL),
(8, 16, 'Nakliye', '', '', NULL, 73, '2024-04-29 16:26:45', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
  `remainingBusinessCount` tinyint NOT NULL DEFAULT '0',
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (concat(`first_name`,_utf8mb4' ',`last_name`)) VIRTUAL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `status`, `status_message`, `first_name`, `last_name`, `tcno`, `gsm`, `email`, `dogum_yili`, `tcnoverified`, `gsmverified`, `emailverified`, `language`, `superadmin`, `remainingBusinessCount`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, 'Umut', 'Demirhan', '23416086000', '5330338197', 'umut@kariyerfora.com', 1977, 1, 1, 1, NULL, 1, 1, NULL, NULL, NULL, NULL),
(2, NULL, NULL, 'Hüseyin', 'Mumay', '53149018900', '5445868624', 'ideametrik@gmail.com', 1981, 1, 1, 1, NULL, 1, 1, NULL, NULL, NULL, NULL),
(3, NULL, NULL, 'Burhan', 'Çalhan', '13515135131', '5057958150', 'calhan.bur@gmail.com', 1981, 0, 1, 1, NULL, 1, 1, NULL, NULL, NULL, NULL),
(4, NULL, NULL, 'Test', 'Customer', '12345678901', '1234567890', 'develop@kariyerfora.com', 1990, 0, 1, 1, 'tr', 0, 1, NULL, '2024-04-11 16:10:28', NULL, NULL),
(5, NULL, NULL, 'Test', 'aadf', '', '1351351351', 'testi@gmail.com', NULL, 0, 0, 1, 'tr', 0, 1, NULL, '2024-04-17 20:58:22', NULL, NULL),
(6, NULL, NULL, 'Ahmet', 'Mehmet', '15145511545', '1313155144', 'ahmet@gmail.com', 1990, 0, 0, 0, 'tr', 0, 1, NULL, '2024-04-20 04:37:54', NULL, NULL),
(7, NULL, NULL, 'Mehmt', 'Aaada', '13158878781', '1341341333', 'hmet@gmail.com', 1991, 0, 0, 0, 'tr', 0, 1, NULL, '2024-04-20 04:39:39', NULL, NULL),
(8, NULL, NULL, 'Etsy', 'Bitsy', '00000000000', '0000000000', 'etsy@gmail.com', 1990, 0, 0, 0, 'tr', 0, 0, NULL, '2024-04-20 04:52:02', NULL, NULL),
(9, NULL, NULL, 'Etsy', 'Secretary', '41331413434', '1231231231', 'secretary@gmail.com', 1900, 0, 1, 0, 'tr', 0, 0, NULL, '2024-04-20 06:11:44', NULL, NULL),
(10, NULL, NULL, 'Kariyer', 'Fora', '99999999999', '9999999999', 'kariyer@kariyerfora.com', 9999, 0, 1, 0, 'tr', 0, 0, NULL, '2024-04-20 06:16:14', NULL, NULL),
(11, NULL, NULL, 'berna', 'nh', '', '1111111111', 'ev@gmail.com', NULL, 0, 1, 0, 'tr', 0, 0, NULL, '2024-04-20 15:08:05', '2024-04-20 16:11:15', NULL),
(14, NULL, NULL, 'Ali', 'Veli', '22222222222', '2222222222', '22@il.c', 1922, 0, 0, 0, 'tr', 0, 0, NULL, '2024-04-24 07:37:19', NULL, NULL),
(15, NULL, NULL, 'Uzman 3', 'Azman', '33333333333', '3333333333', '333@33.33', 3333, 0, 0, 0, 'tr', 0, 0, NULL, '2024-04-24 07:39:03', NULL, NULL),
(16, NULL, NULL, '44444444', '4444', '44444444444', '4444444444', '44@44.44', 4444, 0, 0, 0, 'tr', 0, 0, NULL, '2024-04-24 07:43:24', NULL, NULL),
(17, NULL, NULL, 'Ufuk', 'Ufff', '77777777777', '7777777777', 'y@y.y', 7777, 0, 0, 0, 'tr', 0, 0, NULL, '2024-04-29 16:25:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_businesses`
--

CREATE TABLE `users_businesses` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `business_id` int UNSIGNED NOT NULL,
  `role` enum('admin','secretary','expert','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `expert_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_businesses`
--

INSERT INTO `users_businesses` (`id`, `user_id`, `business_id`, `role`, `expert_type`, `created_at`, `updated_at`) VALUES
(20, 3, 6, 'expert', NULL, '2024-04-17 22:36:44', '2024-04-17 22:36:55'),
(21, 4, 6, 'customer', NULL, '2024-04-17 22:36:52', NULL),
(23, 1, 1, 'expert', 'Doktor', '2024-04-18 06:47:03', NULL),
(24, 3, 2, 'secretary', NULL, '2024-04-18 15:35:51', NULL),
(25, 2, 2, 'secretary', NULL, '2024-04-18 15:35:55', NULL),
(26, 5, 2, 'secretary', NULL, '2024-04-18 15:35:58', NULL),
(31, 1, 14, 'admin', NULL, '2024-04-19 08:32:11', NULL),
(32, 2, 14, 'secretary', NULL, '2024-04-19 08:32:54', NULL),
(36, 5, 1, 'expert', 'Pediatrik Ortodondi', '2024-04-19 19:21:08', NULL),
(38, 3, 1, 'expert', '', '2024-04-19 19:21:25', NULL),
(39, 2, 1, 'expert', 'Pediatrik Ortodondi', '2024-04-19 19:22:01', NULL),
(41, 4, 1, 'admin', NULL, '2024-04-19 21:36:22', NULL),
(44, 8, 16, 'admin', NULL, '2024-04-20 06:08:48', NULL),
(46, 9, 16, 'secretary', NULL, '2024-04-20 06:13:04', NULL),
(53, 1, 6, 'admin', NULL, '2024-04-21 10:15:17', NULL),
(54, 14, 1, 'expert', 'Çene Cerrahisi', '2024-04-24 07:37:19', NULL),
(55, 15, 1, 'expert', 'Estetisyen', '2024-04-24 07:39:03', NULL),
(56, 16, 1, 'expert', 'Çene Cerrahisi', '2024-04-24 07:43:24', NULL),
(57, 11, 1, 'expert', 'Pediatrik Ortodondi', '2024-04-24 19:58:39', NULL),
(58, 17, 14, 'expert', 'Bilgisayar', '2024-04-29 16:25:10', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_businesses_users`
-- (See below for the actual view)
--
CREATE TABLE `vw_businesses_users` (
`id` int unsigned
,`name` varchar(100)
,`slug` varchar(45)
,`timezone` varchar(45)
,`created_at` datetime
,`updated_at` datetime
,`deleted_at` datetime
,`user_id` int unsigned
,`role` enum('admin','secretary','expert','customer')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_users_businesses`
-- (See below for the actual view)
--
CREATE TABLE `vw_users_businesses` (
`id` int unsigned
,`status` varchar(255)
,`status_message` varchar(255)
,`first_name` varchar(100)
,`last_name` varchar(100)
,`tcno` varchar(11)
,`gsm` varchar(30)
,`email` varchar(255)
,`dogum_yili` smallint unsigned
,`tcnoverified` tinyint(1)
,`gsmverified` tinyint(1)
,`emailverified` tinyint(1)
,`language` varchar(30)
,`superadmin` tinyint
,`remainingBusinessCount` tinyint
,`last_active` datetime
,`created_at` datetime
,`updated_at` datetime
,`deleted_at` datetime
,`fullname` varchar(255)
,`business_id` int unsigned
,`role` enum('admin','secretary','expert','customer')
,`expert_type` varchar(255)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_businesses_users`
--
DROP TABLE IF EXISTS `vw_businesses_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`etsy`@`localhost` SQL SECURITY DEFINER VIEW `vw_businesses_users`  AS SELECT `b`.`id` AS `id`, `b`.`name` AS `name`, `b`.`slug` AS `slug`, `b`.`timezone` AS `timezone`, `b`.`created_at` AS `created_at`, `b`.`updated_at` AS `updated_at`, `b`.`deleted_at` AS `deleted_at`, `ub`.`user_id` AS `user_id`, `ub`.`role` AS `role` FROM (`businesses` `b` join `users_businesses` `ub` on((`b`.`id` = `ub`.`user_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_users_businesses`
--
DROP TABLE IF EXISTS `vw_users_businesses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`etsy`@`localhost` SQL SECURITY DEFINER VIEW `vw_users_businesses`  AS SELECT `u`.`id` AS `id`, `u`.`status` AS `status`, `u`.`status_message` AS `status_message`, `u`.`first_name` AS `first_name`, `u`.`last_name` AS `last_name`, `u`.`tcno` AS `tcno`, `u`.`gsm` AS `gsm`, `u`.`email` AS `email`, `u`.`dogum_yili` AS `dogum_yili`, `u`.`tcnoverified` AS `tcnoverified`, `u`.`gsmverified` AS `gsmverified`, `u`.`emailverified` AS `emailverified`, `u`.`language` AS `language`, `u`.`superadmin` AS `superadmin`, `u`.`remainingBusinessCount` AS `remainingBusinessCount`, `u`.`last_active` AS `last_active`, `u`.`created_at` AS `created_at`, `u`.`updated_at` AS `updated_at`, `u`.`deleted_at` AS `deleted_at`, `u`.`fullname` AS `fullname`, `ub`.`business_id` AS `business_id`, `ub`.`role` AS `role`, `ub`.`expert_type` AS `expert_type` FROM (`users` `u` join `users_businesses` `ub` on((`u`.`id` = `ub`.`user_id`))) ;

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
  ADD KEY `user_id` (`user_id`) INVISIBLE,
  ADD KEY `type` (`type`),
  ADD KEY `secret` (`secret`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`deleted_at`),
  ADD UNIQUE KEY `slug` (`slug`,`deleted_at`);

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
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`business_id`,`deleted_at`),
  ADD UNIQUE KEY `business_id` (`business_id`,`resource_type`,`deleted_at`),
  ADD KEY `resources_businesses_business_id_fk_idx` (`business_id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`business_id`,`deleted_at`),
  ADD KEY `rules_businesses_business_id_fk_idx` (`business_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`business_id`,`deleted_at`) USING BTREE,
  ADD KEY `services_businesses_business_id_fk_idx` (`business_id`),
  ADD KEY `services_users_expert_id_fk` (`expert_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gsm_UNIQUE` (`gsm`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `fullname` (`fullname`);

--
-- Indexes for table `users_businesses`
--
ALTER TABLE `users_businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_businesses_unique` (`user_id`,`business_id`),
  ADD KEY `users_businesses_business_id_fk_idx` (`business_id`),
  ADD KEY `businesses_users_user_id_fk_idx` (`user_id`) INVISIBLE,
  ADD KEY `role` (`role`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users_businesses`
--
ALTER TABLE `users_businesses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

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
  ADD CONSTRAINT `services_businesses_business_id_fk` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`),
  ADD CONSTRAINT `services_users_expert_id_fk` FOREIGN KEY (`expert_id`) REFERENCES `users` (`id`);

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
