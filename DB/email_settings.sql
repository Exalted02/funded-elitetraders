-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 17, 2025 at 01:59 PM
-- Server version: 10.11.11-MariaDB-cll-lve
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srv80615_expert_funded_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

CREATE TABLE `email_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_from_address` varchar(255) NOT NULL,
  `emails_from_name` varchar(255) NOT NULL,
  `smtp_host` varchar(255) NOT NULL,
  `smtp_user` varchar(255) NOT NULL,
  `smtp_password` varchar(255) NOT NULL,
  `smpt_port` int(10) NOT NULL,
  `smtp_authentication_domain` varchar(255) NOT NULL,
  `php_mail_smtp` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=php_mail,1=smtp',
  `smtp_security` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=none,1=tlc,2=ssl,',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`id`, `email_from_address`, `emails_from_name`, `smtp_host`, `smtp_user`, `smtp_password`, `smpt_port`, `smtp_authentication_domain`, `php_mail_smtp`, `smtp_security`, `created_at`, `updated_at`) VALUES
(1, 'noreply@expertelitefunds.com', 'Expert Funded', 'h52.seohost.pl', 'noreply@expertelitefunds.com', 'ef#$2025', 465, '', 0, 0, NULL, '2025-04-17 09:05:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_settings`
--
ALTER TABLE `email_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_from_address` (`email_from_address`),
  ADD KEY `emails_from_name` (`emails_from_name`),
  ADD KEY `smtp_host` (`smtp_host`),
  ADD KEY `smtp_user` (`smtp_user`),
  ADD KEY `smtp_password` (`smtp_password`),
  ADD KEY `smtp_authentication_domain` (`smtp_authentication_domain`),
  ADD KEY `email_from_address_2` (`email_from_address`),
  ADD KEY `emails_from_name_2` (`emails_from_name`),
  ADD KEY `smtp_host_2` (`smtp_host`),
  ADD KEY `smtp_user_2` (`smtp_user`),
  ADD KEY `smtp_password_2` (`smtp_password`),
  ADD KEY `smtp_authentication_domain_2` (`smtp_authentication_domain`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_settings`
--
ALTER TABLE `email_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
