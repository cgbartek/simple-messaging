-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 17, 2020 at 01:31 AM
-- Server version: 5.6.13
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `simplemessaging`
--
CREATE DATABASE IF NOT EXISTS `simplemessaging` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `simplemessaging`;

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE IF NOT EXISTS `channels` (
  `cid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `channels`
--

INSERT INTO `channels` (`cid`, `channel_name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'wholeteam', 1, '2020-04-15 18:37:26', '2020-04-15 18:37:26'),
(2, 'random', 2, '2020-04-15 18:37:34', '2020-04-15 18:37:34');

-- --------------------------------------------------------

--
-- Table structure for table `channel_members`
--

CREATE TABLE IF NOT EXISTS `channel_members` (
  `mid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `channel_members`
--

INSERT INTO `channel_members` (`mid`, `cid`, `uid`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2020-04-15 18:40:07', '2020-04-15 18:40:07'),
(2, 1, 2, '2020-04-15 18:40:07', '2020-04-15 18:40:07'),
(3, 1, 3, '2020-04-15 18:40:34', '2020-04-15 18:40:34'),
(4, 2, 1, '2020-04-15 18:40:54', '2020-04-15 18:40:54'),
(5, 2, 3, '2020-04-15 18:40:46', '2020-04-15 18:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `channel` int(11) DEFAULT NULL,
  `user_to` int(11) DEFAULT NULL,
  `user_from` int(11) DEFAULT NULL,
  `user_to_ack` tinyint(1) NOT NULL DEFAULT '0',
  `user_from_ack` tinyint(1) NOT NULL DEFAULT '0',
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=49 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `channel`, `user_to`, `user_from`, `user_to_ack`, `user_from_ack`, `message`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 2, 1, 0, 'Hello Chris, this is Tom.', '2020-04-14 20:41:54', '2020-04-16 22:55:32'),
(3, NULL, 1, 3, 1, 0, 'Hello Chris, this is Sally.', '2020-04-15 14:34:15', '2020-04-16 21:55:07'),
(4, NULL, 3, 1, 1, 0, 'Hello Sally, this is Chris.', '2020-04-15 15:04:06', '2020-04-16 22:37:53'),
(11, 1, NULL, 1, 0, 0, 'Hello world!', '2020-04-15 20:34:59', '2020-04-15 20:34:59'),
(12, 2, NULL, 3, 0, 0, 'Hello random!', '2020-04-15 20:35:07', '2020-04-15 20:35:07'),
(13, 2, NULL, 1, 0, 0, 'I''m sooo random!', '2020-04-16 01:00:58', '2020-04-16 01:00:58'),
(20, NULL, 2, 1, 1, 0, 'Hello Tom, this is Chris.', '2020-04-16 22:59:06', '2020-04-17 05:07:57'),
(48, NULL, 3, 2, 1, 0, 'Hey Sally, have you had a chance to finish the TPS reports?', '2020-04-17 05:14:20', '2020-04-17 05:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `lat`, `lng`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Chris', 'chris@test.com', NULL, '$2y$10$jJylY6WUJiAC/YQbO4LJpOz7jolBuhMNp2oPyJNlngvnnNINv7ybW', '30.3425496', '-81.5687914', NULL, '2020-04-14 21:37:05', '2020-04-17 05:21:20'),
(2, 'Tom', 'tom@test.com', NULL, '$2y$10$QiZZidrVY8Jbgu/1.xmlIu1EmRVKVnuCE4i3Okzewxm.hkHR5hukC', '39.1031000', '-84.5120000', NULL, '2020-04-15 15:25:51', '2020-04-17 05:07:49'),
(3, 'Sally', 'sally@test.com', NULL, '$2y$10$jE8zncIfCxsRwynqWUt88uT3qEt5go8.am5rJwAzu.vAXU2dShjuC', '37.7749000', '-122.4194000', NULL, '2020-04-15 17:20:54', '2020-04-17 00:03:41');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
