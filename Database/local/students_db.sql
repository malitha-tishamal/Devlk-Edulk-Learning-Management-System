-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2025 at 07:55 AM
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
-- Database: `students_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `regno` varchar(20) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `profile_picture` varchar(200) NOT NULL DEFAULT 'uploads/profile_pictures/default.jpg',
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `regno`, `nic`, `email`, `gender`, `mobile`, `password`, `status`, `profile_picture`, `created_at`, `last_login`) VALUES
(3, 'Mithun Chameera', 'gal/it/2324/f/0043', '200400211211', 'mithunchameera7@gmail.com', 'Male', '760099206', '$2y$10$U6E9uqdhneVtHvcy1eyTdeSabXSrpZAA/Gglu0wtFtiyU28SsjpZ2', 'approved', 'uploads/profile_pictures/default.jpg', '2025-06-15 07:59:36', '2025-08-25 08:30:51'),
(4, 'H.P.Bhashitha sandaruwan', 'GAL/IT/2324/F/0048', '200111900818', 'admin.hpbashitha@gmail.com', 'Male', '766251286', '$2y$10$6U/KHjL5pMEON5Yqkz8oa.1klAuI8ibREakAtECPVCCOgg0TmQGcK', 'approved', 'uploads/profile_pictures/default.jpg', '2025-06-29 06:47:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admins_reviews`
--

CREATE TABLE `admins_reviews` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `review` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` varchar(100) NOT NULL DEFAULT current_timestamp(),
  `admin_reply` varchar(100) NOT NULL,
  `admin_reply_read` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `regno` text NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `device_vendor` varchar(50) DEFAULT NULL,
  `device_model` varchar(50) DEFAULT NULL,
  `orientation` varchar(50) DEFAULT NULL,
  `touch_support` varchar(10) DEFAULT NULL,
  `pixel_ratio` float DEFAULT NULL,
  `connection_type` varchar(50) DEFAULT NULL,
  `viewport_size` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `browser_version` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `screen_resolution` varchar(50) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `battery_level` varchar(10) DEFAULT NULL,
  `online_status` varchar(10) DEFAULT NULL,
  `referrer_url` text DEFAULT NULL,
  `current_url` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `admin_name`, `regno`, `ip_address`, `user_agent`, `device_type`, `device_vendor`, `device_model`, `orientation`, `touch_support`, `pixel_ratio`, `connection_type`, `viewport_size`, `latitude`, `longitude`, `os`, `browser`, `browser_version`, `language`, `screen_resolution`, `timezone`, `battery_level`, `online_status`, `referrer_url`, `current_url`, `session_id`, `login_time`, `logout_time`) VALUES
(1, 3, 'Mithun Chameera', 'gal/it/2324/f/0043', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', 6.0719104, 80.5470208, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'http://localhost/Devlk-Edulk-Student-Management-System/index.php', '/Devlk-Edulk-Student-Management-System/login-process.php', 'fb9hjue3vrj3t49rt92u8hkcec', '2025-08-23 11:23:58', '2025-08-23 07:54:09'),
(2, 3, 'Mithun Chameera', 'gal/it/2324/f/0043', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 08:30:51', '2025-08-24 23:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('approved','pending','disabled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'uploads/profile_pictures/default.png',
  `blog` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`id`, `name`, `nic`, `email`, `mobile`, `password`, `status`, `created_at`, `last_login`, `profile_picture`, `blog`, `facebook`, `linkedin`, `github`) VALUES
(1, 'testlecture', '200202202615', 'testlecture@email.com', '7855309992', '$2y$10$ZWHow5Gxl3stcip2u4B/1u5gaeCKKIPxJ0MpmKT2fpIErGwRgTwnO', 'approved', '2025-07-30 06:01:10', '2025-08-25 08:30:38', 'uploads/profile_pictures/default.png', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lectures_assignment`
--

CREATE TABLE `lectures_assignment` (
  `id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lectures_logs`
--

CREATE TABLE `lectures_logs` (
  `id` int(11) NOT NULL,
  `lecture_id` int(11) NOT NULL,
  `lecture_name` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `device_vendor` varchar(50) DEFAULT NULL,
  `device_model` varchar(50) DEFAULT NULL,
  `orientation` varchar(50) DEFAULT NULL,
  `touch_support` varchar(10) DEFAULT NULL,
  `pixel_ratio` float DEFAULT NULL,
  `connection_type` varchar(50) DEFAULT NULL,
  `viewport_size` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `browser_version` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `screen_resolution` varchar(50) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `battery_level` varchar(10) DEFAULT NULL,
  `online_status` varchar(10) DEFAULT NULL,
  `referrer_url` text DEFAULT NULL,
  `current_url` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lectures_logs`
--

INSERT INTO `lectures_logs` (`id`, `lecture_id`, `lecture_name`, `ip_address`, `user_agent`, `device_type`, `device_vendor`, `device_model`, `orientation`, `touch_support`, `pixel_ratio`, `connection_type`, `viewport_size`, `latitude`, `longitude`, `os`, `browser`, `browser_version`, `language`, `screen_resolution`, `timezone`, `battery_level`, `online_status`, `referrer_url`, `current_url`, `session_id`, `login_time`, `logout_time`) VALUES
(2, 1, 'test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '3g', '2304x1042', 6.0719104, 80.5470208, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'http://localhost/Devlk-Edulk-Student-Management-System/index.php', '/Devlk-Edulk-Student-Management-System/login-process.php', 'fb9hjue3vrj3t49rt92u8hkcec', '2025-08-23 11:20:25', '2025-08-23 07:50:33'),
(3, 1, 'testlecture', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 08:30:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lectures_reviews`
--

CREATE TABLE `lectures_reviews` (
  `id` int(11) NOT NULL,
  `lecture_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `review` text NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` varchar(100) NOT NULL DEFAULT current_timestamp(),
  `admin_reply` varchar(100) NOT NULL,
  `admin_reply_read` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `zoom_link` text NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'permanent',
  `subject` varchar(255) NOT NULL,
  `link_expiry_status` varchar(20) DEFAULT 'permanent',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_live` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `title`, `date`, `start_time`, `zoom_link`, `created_by`, `role`, `status`, `subject`, `link_expiry_status`, `created_at`, `is_live`) VALUES
(13, 'Malitha Tishamal\'s Zoom Meeting', '2025-08-26', '10:00:00', 'https://us05web.zoom.us/j/84660830530?pwd=XlLOW6mnHjdNPTzCxYENIPaEi1Qq9w.1', 'Malitha Tishamal', 'admin', 'disabled', 'HNDIT2062 - ICT Project (Group)', '4d', '2025-08-24 20:07:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `meeting_attendance`
--

CREATE TABLE `meeting_attendance` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `regno` varchar(100) NOT NULL,
  `logtime` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Present','Absent') DEFAULT 'Present',
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_chat`
--

CREATE TABLE `meeting_chat` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_role` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_chat`
--

INSERT INTO `meeting_chat` (`id`, `meeting_id`, `user_id`, `user_name`, `user_role`, `message`, `created_at`) VALUES
(1, 2, 1, 'Malitha Tishamal', 'sadmin', 'test', '2025-08-03 22:58:09'),
(2, 2, 1, 'Malitha Tishamal', 'sadmin', 'test', '2025-08-03 22:58:16'),
(3, 2, 1, 'Malitha Tishamal', 'sadmin', 'test', '2025-08-04 01:26:58'),
(4, 2, 1, 'Malitha Tishamal', 'sadmin', 'test', '2025-08-04 01:40:36'),
(5, 2, 1, 'Malitha Tishamal', 'sadmin', 'test', '2025-08-04 04:03:40'),
(6, 3, 1, 'Malitha Tishamal', 'sadmin', 'test', '2025-08-04 04:04:30'),
(7, 2, 1, 'Malitha Tishamal', 'sadmin', 'tet', '2025-08-04 04:22:59');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_resources`
--

CREATE TABLE `meeting_resources` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `resource_type` varchar(50) NOT NULL,
  `resource_data` text NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `uploaded_by` int(11) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_resources`
--

INSERT INTO `meeting_resources` (`id`, `meeting_id`, `resource_type`, `resource_data`, `uploaded_at`, `uploaded_by`, `status`) VALUES
(1, 2, 'file', 'uploads/meeting_resources/687bbf091d066_687b1c3587aa0_Screenshot_2025-07-19_085029__1_.png', '2025-07-19 08:51:37', 1, 'active'),
(2, 2, 'link', 'www.facebook.com', '2025-07-19 08:51:50', 1, 'active'),
(6, 3, 'link', 'www.facebook,.com', '2025-08-04 04:05:56', 1, 'active'),
(7, 3, 'file', 'uploads/meeting_resources/689098f4b0a7f_WhatsApp_Image_2025-08-03_at_20.49.19_c6a54429.jpg', '2025-08-04 04:26:44', 1, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `recordings`
--

CREATE TABLE `recordings` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `lecture_type` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `access_level` enum('public','batch','private') DEFAULT 'batch',
  `status` enum('active','disabled') DEFAULT 'active',
  `release_time` datetime DEFAULT current_timestamp(),
  `video_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `view_limit_minutes` int(11) DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `role` varchar(50) DEFAULT 'superadmin',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `download_count` int(11) DEFAULT 0,
  `play_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recording_resources`
--

CREATE TABLE `recording_resources` (
  `id` int(11) NOT NULL,
  `recording_id` int(11) NOT NULL,
  `type` enum('file','link') NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','disabled') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recording_resources`
--

INSERT INTO `recording_resources` (`id`, `recording_id`, `type`, `title`, `file_path`, `link_url`, `uploaded_at`, `status`) VALUES
(17, 5, 'file', 'test', 'uploads/resources/1754162633_ER.pdf', NULL, '2025-08-02 19:23:53', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `recording_student_plays`
--

CREATE TABLE `recording_student_plays` (
  `id` int(11) NOT NULL,
  `recording_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `remaining_play_count` int(11) DEFAULT 0,
  `last_played` timestamp NOT NULL DEFAULT current_timestamp(),
  `plays_left` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recording_student_plays_logs`
--

CREATE TABLE `recording_student_plays_logs` (
  `id` int(11) NOT NULL,
  `recording_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `regno` varchar(100) NOT NULL,
  `played_at` timestamp NULL DEFAULT current_timestamp(),
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sadmins`
--

CREATE TABLE `sadmins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `profile_picture` varchar(200) NOT NULL DEFAULT 'uploads/profile_pictures/default.jpg',
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sadmins`
--

INSERT INTO `sadmins` (`id`, `name`, `nic`, `email`, `mobile`, `password`, `status`, `profile_picture`, `created_at`, `last_login`) VALUES
(1, 'Malitha Tishamal', '20002202615', 'malithatishamal@gmail.com', '771000001', '$2y$10$wc.2njjqODl2guzQnvGmieCLFsJnHV/8x.zF90ONUQKxKWBrDEPHy', 'approved', 'uploads/profile_pictures/6818d0f1cf442-411001152_1557287805017611_3900716309730349802_n1.jpg', '2025-05-05 20:12:59', '2025-09-05 11:03:52'),
(6, 'Bashitha Sandaruwan', '200111900818', 'admin.Hpbashitha@gmail.com', '766251286', '$2y$10$wcapNVVcaCo0zVw65ZiL.O491axxSq9KccAMtZBXQZ5jsRPZZt5R2', 'approved', 'uploads/profile_pictures/68b1b223e4a54-IMG-20241005-WA0017_1.jpg', '2025-08-29 06:32:36', '2025-08-29 19:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `sadmin_logs`
--

CREATE TABLE `sadmin_logs` (
  `id` int(11) NOT NULL,
  `sadmin_id` int(11) NOT NULL,
  `sadmin_name` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `device_vendor` varchar(50) DEFAULT NULL,
  `device_model` varchar(50) DEFAULT NULL,
  `orientation` varchar(50) DEFAULT NULL,
  `touch_support` varchar(10) DEFAULT NULL,
  `pixel_ratio` float DEFAULT NULL,
  `connection_type` varchar(50) DEFAULT NULL,
  `viewport_size` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `browser_version` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `screen_resolution` varchar(50) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `battery_level` varchar(10) DEFAULT NULL,
  `online_status` varchar(10) DEFAULT NULL,
  `referrer_url` text DEFAULT NULL,
  `current_url` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sadmin_logs`
--

INSERT INTO `sadmin_logs` (`id`, `sadmin_id`, `sadmin_name`, `ip_address`, `user_agent`, `device_type`, `device_vendor`, `device_model`, `orientation`, `touch_support`, `pixel_ratio`, `connection_type`, `viewport_size`, `latitude`, `longitude`, `os`, `browser`, `browser_version`, `language`, `screen_resolution`, `timezone`, `battery_level`, `online_status`, `referrer_url`, `current_url`, `session_id`, `login_time`, `logout_time`) VALUES
(0, 1, 'Malitha Tishamal', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'http://localhost/Devlk-Edulk-Student-Management-System/index.php', '/Devlk-Edulk-Student-Management-System/login-process.php', 'c770jivlv1b1p3truoivf1bpj8', '2025-09-05 11:03:52', NULL),
(7, 1, 'Malitha Tishamal', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '3g', '2304x1042', 6.0719104, 80.5470208, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'http://localhost/Devlk-Edulk-Student-Management-System/index.php', '/Devlk-Edulk-Student-Management-System/login-process.php', 'fb9hjue3vrj3t49rt92u8hkcec', '2025-08-23 11:19:44', '2025-08-23 07:51:02'),
(8, 1, 'Malitha Tishamal', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '3g', '2304x1042', 6.0719104, 80.5470208, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'http://localhost/Devlk-Edulk-Student-Management-System/index.php', '/Devlk-Edulk-Student-Management-System/login-process.php', 'fb9hjue3vrj3t49rt92u8hkcec', '2025-08-23 11:20:39', '2025-08-23 07:51:02'),
(9, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/?i=1', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-24 16:03:21', '2025-08-25 00:32:13'),
(10, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-24 21:01:56', '2025-08-25 00:32:13'),
(11, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/?i=1', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 08:29:31', '2025-08-25 00:32:13'),
(12, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 08:31:18', '2025-08-25 00:32:13'),
(13, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 08:35:54', '2025-08-25 00:32:13'),
(14, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 08:45:12', '2025-08-25 00:32:13'),
(15, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 09:00:46', '2025-08-25 00:32:13'),
(16, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 09:52:35', '2025-08-25 00:32:13'),
(17, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 09:58:14', '2025-08-25 00:32:13'),
(18, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '60%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9b1bb633b2c71e5e877dd83058b207d8', '2025-08-25 16:06:32', '2025-08-26 01:06:00'),
(19, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'mobile', 'unknown', 'K', 'portrait-primary', 'yes', 2.75, '4g', '392x750', 5.9871910, 80.4769314, 'Android', 'Chrome', '139.0.0.0', 'en-US', '393x851', 'Asia/Colombo', '32%', 'online', 'https://edulk.42web.io/?i=1', '/login-process.php', 'dd6b115184a3b02ca032e4ca5bad1d2d', '2025-08-26 07:35:54', NULL),
(20, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '60%', 'online', 'https://edulk.42web.io/?i=1', '/login-process.php', '9b1bb633b2c71e5e877dd83058b207d8', '2025-08-26 07:59:03', '2025-08-26 01:06:00'),
(21, 1, 'Malitha Tishamal', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '60%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9b1bb633b2c71e5e877dd83058b207d8', '2025-08-26 11:23:30', NULL),
(22, 1, 'Malitha Tishamal', '112.134.104.29', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', 0.833333, '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '60%', 'online', 'https://edulk.42web.io/?i=1', '/login-process.php', '9820d307575bcf5d0b463dbf862b4eb6', '2025-08-27 08:09:02', NULL),
(23, 1, 'Malitha Tishamal', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/?i=1', '/login-process.php', 'c3407214ed45d8b63b76c241def6845d', '2025-08-29 08:24:52', NULL),
(24, 1, 'Malitha Tishamal', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', 'c3407214ed45d8b63b76c241def6845d', '2025-08-29 14:03:28', NULL),
(25, 1, 'Malitha Tishamal', '108.181.10.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/?i=1', '/login-process.php', '72bcbfc6c5eb49ffd0d93c5570deaa41', '2025-08-29 16:31:28', NULL),
(26, 1, 'Malitha Tishamal', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/', '/login-process.php', 'c3407214ed45d8b63b76c241def6845d', '2025-08-29 17:38:12', NULL),
(27, 1, 'Malitha Tishamal', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/?i=1', '/login-process.php', 'd3fcb4cad7fcb61fc16d265f22739a3b', '2025-08-29 19:01:11', NULL),
(28, 1, 'Malitha Tishamal', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', 'c3407214ed45d8b63b76c241def6845d', '2025-08-29 19:05:55', NULL),
(29, 6, 'Bashitha Sandaruwan', '43.250.243.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/', '/login-process.php', '8493078714c7c78a18acdb02507166fb', '2025-08-29 19:12:16', NULL),
(30, 6, 'Bashitha Sandaruwan', '43.250.243.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/', '/login-process.php', '8493078714c7c78a18acdb02507166fb', '2025-08-29 19:27:02', NULL),
(31, 1, 'Malitha Tishamal', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', 'f6a8c985c1327610254b8fc8115c64e1', '2025-08-29 21:59:11', NULL),
(32, 1, 'Malitha Tishamal', '112.134.107.171', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/?i=1', '/login-process.php', '651ae32de2ada29aa9f9a6a074ead233', '2025-09-01 11:08:09', NULL),
(33, 1, 'Malitha Tishamal', '112.134.107.171', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/', '/login-process.php', '651ae32de2ada29aa9f9a6a074ead233', '2025-09-01 14:15:34', NULL),
(34, 1, 'Malitha Tishamal', '112.134.107.171', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', '76b238608e400fab6e362ca30849e83c', '2025-09-01 14:17:41', NULL),
(35, 1, 'Malitha Tishamal', '112.134.107.171', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/', '/login-process.php', '651ae32de2ada29aa9f9a6a074ead233', '2025-09-01 15:32:06', NULL),
(36, 1, 'Malitha Tishamal', '112.134.107.171', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/', '/login-process.php', '651ae32de2ada29aa9f9a6a074ead233', '2025-09-01 15:40:41', NULL),
(37, 1, 'Malitha Tishamal', '112.134.107.171', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', '5494f181149a5e2effdaa66d1bf6fe22', '2025-09-01 15:41:09', NULL),
(38, 1, 'Malitha Tishamal', '112.134.106.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/?i=1', '/login-process.php', '651ae32de2ada29aa9f9a6a074ead233', '2025-09-01 19:20:02', NULL),
(39, 1, 'Malitha Tishamal', '112.134.107.163', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/?i=1', '/login-process.php', '0fe854998cd0a5ed52359163792d2499', '2025-09-03 10:10:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `regno` varchar(20) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `nowstatus` varchar(50) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `batch_year` varchar(10) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `mobile2` varchar(10) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `profile_picture` varchar(255) NOT NULL DEFAULT 'uploads/profile_pictures/default.jpg',
  `blog` varchar(200) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `linkedin` varchar(200) NOT NULL,
  `github` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `regno`, `nic`, `email`, `gender`, `address`, `nowstatus`, `mobile`, `batch_year`, `birthday`, `mobile2`, `password`, `status`, `created_at`, `last_login`, `profile_picture`, `blog`, `facebook`, `linkedin`, `github`) VALUES
(4, 'H.P.Bhashitha sandaruwan', 'GAL/IT/2324/F/0048', '200111900818', 'hpbashitha@gmail.com', 'Male', 'PITAWATHTHA KANATHTHAHENA  RANSEGODA WEST RANSEGODA', 'Bord', '766251286', NULL, NULL, '778351286', '$2y$10$1PL4L.AkzlR5mP3.Qgu4XevUxwSyWEVQ1rvgmzm/ecd7KjXSHcxqG', 'approved', '2025-06-29 03:34:36', '2025-08-29 19:23:41', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(5, 'test', 'TEST', '200202202615', 'user@gmail.com', 'Male', 'test', 'Home', '763279285', NULL, NULL, '771295976', '$2y$10$2ajMdZ6zXKtD5k5zqFh3Muv5ghHXpJyhKueQE.Xt1wyDzLzDX3L4q', 'approved', '2025-06-29 20:47:34', '2025-09-01 14:16:52', 'uploads/profile_pictures/6890d3c0e96d9-506902894_1921641775248877_5518975110052725666_n.jpg', '', '', '', ''),
(7, 'S.S.Godakanda', 'GAL/IT2324/F/219', '200364412130', 'gsandanila@gmail.com', 'Female', 'Sarasavi Kurunduwatta Wanduramba', 'Home', '781852852', NULL, NULL, '703444188', '$2y$10$73Oi6UAeWg41BYUdfw9d7u609Xr639eHLG3nnc0YxbOdlIJKJhu72', 'approved', '2025-07-02 08:07:08', NULL, 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(8, 'Thimira Savinda', 'GAL/IT/2324/F/216', '200412701219', 'thimirapost116@gmail.com', 'Male', 'Malwachchigoda, Meegahatenna', 'Bord', '787842415', NULL, NULL, '712118983', '$2y$10$oUdPcfgn/3vqp2YxZUQFXe9x//OTm/GOG9uhsa.pjNTf4.WT641/e', 'approved', '2025-07-02 18:23:13', '2025-07-03 06:53:51', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(9, 'I.G.N.Sewwandi', 'GAL/IT/2324/F/0018', '200350302032', 'nadeeshasewwandi859@gmail.com', 'Female', 'Iluppellagewaththa,Kirimetimulla,Thelijjawila', 'Bord', '752462899', NULL, NULL, '704944170', '$2y$10$t3TlglzJ6T4h3XXrlLXLQu3L5foKoaU8lDwln0LU/CK0FHX8JrsWO', 'approved', '2025-07-04 06:49:32', '2025-07-04 19:20:20', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(10, 'Dumindu Damsara', 'GAL/IT/2324/F/0050', '200105602878', 'dumindudamsara60@gmail.com', 'Male', '61 Rathna Gangodahena Bopagoda Akuressa', 'Bord', '721712468', NULL, NULL, '703146434', '$2y$10$rx.sg5KOpXUjdQNykmu.D.c2n9/OmcJXoosd2kgWbT7j1krDoc17.', 'approved', '2025-07-07 00:42:59', '2025-08-25 12:46:00', 'uploads/profile_pictures/686b7df08467c-WhatsApp Image 2025-07-07 at 13.23.53.jpeg', '', 'https://web.facebook.com/dumindu.sirijayalathjothirathna.9', 'https://www.linkedin.com/in/dumindu-damsara-0049ab246/', 'https://github.com/dumindu2041329'),
(11, 'Subhashi Jayawardhana', 'GAL/IT/2324/F/0109', '200380813870', 'jayawardhanasubhashi@gmail.com', 'Female', 'Kapila Iron Works,Udugama Road, Ginigalgodalla,Thalgampala', 'Home', '753240927', NULL, NULL, '787854336', '$2y$10$Um1dprESlrTtGfn8KEF2RulnGRHN5ONDI.JK9DFA71aVmKEFeaTOW', 'approved', '2025-07-11 03:12:28', NULL, 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(14, 'V. G. Tharindu Sampath', 'GAL/IT/2324/F/0007', '200334400893', 'vgtharindu165@gmail.com', 'Male', '227/1 ginigalahena,dewalegama,deiyandara,matara,sri lanka', 'Bord', '0772010733', NULL, NULL, '788123275', '$2y$10$NJ2NvXxkodg.zXP323HqHuBe1Ys.41ffT2ZrmQATIBcHcXIk8t132', 'approved', '2025-08-23 02:40:14', '2025-08-23 15:11:10', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(15, 'Ewni Akithma', 'GAL/IT/2324/F/217', '200354711748', 'ewniakithma@gmail.com', 'Female', '\"Wedagedara\", Thalawa, Wanduramba, Galle', 'Home', '0772072026', NULL, NULL, '0712284562', '$2y$10$a17Zc2PnhoOCLC44ID3Td.99on.JxTMTx5.Rn6F3Cp1WfdP2EMqIO', 'approved', '2025-08-23 14:47:51', '2025-08-24 03:20:35', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(16, 'Pathumi Roshani', 'GAL/IT/2324/F/0075', '200273301411', 'pathumiroshanikumarage@gmail.com', 'Male', 'Kumara koraburuwana ransegoda', 'Bord', '718869468', NULL, NULL, '713961479', '$2y$10$ebYJ9wEWPrmI5cpzpD4xAuVQvx5u9akHtNWRDPD6ZVL2jK1afrUkK', 'approved', '2025-08-25 00:40:49', '2025-08-26 08:14:31', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(17, 'Lakmi Kavindya Kumanayaka', 'GAL/IT/2324/F/0079', '200356611890', 'lakmikumanayaka@gmail.com', 'Female', 'Arachchigehena,Nadugala,Matara', 'Bord', '0743579591', NULL, NULL, '412234758', '$2y$10$rPppXdJF7YYxZF.P.Q9eXu0Ivb7PAuLfwvvYAlrALwjgTMrgQl.FS', 'approved', '2025-08-25 01:36:51', '2025-08-25 14:07:28', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(18, 'K. Nethmi Suriyabandara', 'GAL/IT/2324/F/0024', '200351401583', 'kaveenethmi1256@gmail.com', 'Female', 'No 259/1, Ruhunagewaththa, Diddenipotha East, Makandura, Matara.', 'Home', '769987533', NULL, NULL, '412268687', '$2y$10$2DmNaDNDbsfbleI5eIsbnei7.sGfJLxgQ1nKVDZ95hJVLFi7G0VvC', 'approved', '2025-08-25 06:08:36', '2025-08-25 18:39:30', 'uploads/profile_pictures/default.jpg', '', '', '', ''),
(19, 'Nishara De Silva', 'GAL/IT/2324/F/0023', '200378711255', 'tharushinishara2003@gmail.com', 'Female', 'No.252,Mawadawila,Rathgama.', 'Home', '743397871', NULL, NULL, '766801989', '$2y$10$K/RsBanxIa2IjcsP0DyG9u5/FV76J3tQwTCRAHlB57EzNBe7QTWvG', 'approved', '2025-08-25 19:35:09', '2025-08-26 08:06:14', 'uploads/profile_pictures/default.jpg', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `students_logs`
--

CREATE TABLE `students_logs` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `regno` varchar(50) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `device_vendor` varchar(100) DEFAULT NULL,
  `device_model` varchar(100) DEFAULT NULL,
  `orientation` varchar(20) DEFAULT NULL,
  `touch_support` varchar(10) DEFAULT NULL,
  `pixel_ratio` varchar(10) DEFAULT NULL,
  `connection_type` varchar(10) DEFAULT NULL,
  `viewport_size` varchar(20) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `browser_version` varchar(20) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `screen_resolution` varchar(20) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `battery_level` varchar(10) DEFAULT NULL,
  `online_status` varchar(10) DEFAULT NULL,
  `referrer_url` text DEFAULT NULL,
  `current_url` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_logs`
--

INSERT INTO `students_logs` (`id`, `student_id`, `student_name`, `regno`, `ip_address`, `user_agent`, `device_type`, `device_vendor`, `device_model`, `orientation`, `touch_support`, `pixel_ratio`, `connection_type`, `viewport_size`, `latitude`, `longitude`, `os`, `browser`, `browser_version`, `language`, `screen_resolution`, `timezone`, `battery_level`, `online_status`, `referrer_url`, `current_url`, `session_id`, `login_time`, `logout_time`) VALUES
(11, 5, 'test', 'TEST', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '3g', '2304x1042', '6.0719104', '80.5470208', 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'http://localhost/Devlk-Edulk-Student-Management-System/index.php', '/Devlk-Edulk-Student-Management-System/login-process.php', 'fb9hjue3vrj3t49rt92u8hkcec', '2025-08-23 11:02:20', '2025-08-23 07:32:36'),
(12, 5, 'test', 'TEST', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'http://localhost/Devlk-Edulk-Student-Management-System/index.php', '/Devlk-Edulk-Student-Management-System/login-process.php', 'm2qg759k0d4h53gkhmcbsstv2k', '2025-08-25 08:30:06', '2025-08-25 05:17:17'),
(13, 5, 'test', 'TEST', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 09:51:49', '2025-08-25 00:21:53'),
(14, 5, 'test', 'TEST', '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 10:02:28', NULL),
(15, 10, 'Dumindu Damsara', 'GAL/IT/2324/F/0050', '175.157.8.209', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'mobile', 'unknown', 'K', 'portrait-primary', 'yes', '1.875', '3g', '384x726', NULL, NULL, 'Android', 'Chrome', '139.0.0.0', 'en-US', '385x854', 'Asia/Colombo', '63%', 'online', 'https://edulk.42web.io/?i=1', '/login-process.php', 'cdb5e1794176a193fa460497b7b79189', '2025-08-25 12:46:00', NULL),
(16, 16, 'Pathumi', 'GAL/IT/2324/F/0075', '175.157.17.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '1.25', '4g', '1528x834', NULL, NULL, 'Windows', 'Edge', '139.0.0.0', 'en-US', '1536x960', 'Asia/Colombo', '40%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'cdb7644fb1fcbbf4ae4cd320aed1ba42', '2025-08-25 13:11:16', NULL),
(17, 16, 'Pathumi', 'GAL/IT/2324/F/0075', '175.157.17.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '1.25', '4g', '1528x834', NULL, NULL, 'Windows', 'Edge', '139.0.0.0', 'en-US', '1536x960', 'Asia/Colombo', '40%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'cdb7644fb1fcbbf4ae4cd320aed1ba42', '2025-08-25 13:14:26', NULL),
(18, 16, 'Pathumi', 'GAL/IT/2324/F/0075', '175.157.17.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '1.25', '4g', '1528x834', NULL, NULL, 'Windows', 'Edge', '139.0.0.0', 'en-US', '1536x960', 'Asia/Colombo', '40%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'cdb7644fb1fcbbf4ae4cd320aed1ba42', '2025-08-25 13:15:59', NULL),
(19, 17, 'Lakmi Kavindya Kumanayaka', 'GAL/IT/2324/F/0079', '112.134.236.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'mobile', 'unknown', 'K', 'portrait-primary', 'yes', '2.8125', '4g', '384x728', '5.982486', '80.548327', 'Android', 'Chrome', '139.0.0.0', 'en-IN', '384x857', 'Asia/Colombo', '88%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'e77c587b5b135e6b3a55cfaad0583da7', '2025-08-25 14:07:28', NULL),
(20, 18, 'K. Nethmi Suriyabandara', 'GAL/IT/2324/F/0024', '212.104.231.118', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'mobile', 'unknown', 'K', 'portrait-primary', 'yes', '1.70000004', '3g', '423x799', '6.2017153', '80.0902648', 'Android', 'Chrome', '139.0.0.0', 'en-US', '424x949', 'Asia/Colombo', '83%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'ca987d402048655ff4a254d80e362a7a', '2025-08-25 18:39:01', NULL),
(21, 18, 'K. Nethmi Suriyabandara', 'GAL/IT/2324/F/0024', '212.104.231.118', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', 'ca987d402048655ff4a254d80e362a7a', '2025-08-25 18:39:29', NULL),
(22, 18, 'K. Nethmi Suriyabandara', 'GAL/IT/2324/F/0024', '212.104.231.118', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://edulk.42web.io/index.php', '/login-process.php', 'ca987d402048655ff4a254d80e362a7a', '2025-08-25 18:39:30', NULL),
(23, 19, 'Nishara De Silva', 'GAL/IT/2324/F/0023', '175.157.27.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Mobile/15E148 Safari/604.1', 'mobile', 'Apple', 'iPhone', 'landscape', 'yes', '2', 'unknown', '375x548', '6.12511631102', '80.1579966211', 'iOS', 'Mobile Safari', '15.6.7', 'en-GB', '375x667', 'Asia/Colombo', 'unknown', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '2b84e7839b2f25caae58e6d83a73b702', '2025-08-26 08:06:14', '2025-08-25 22:37:46'),
(24, 16, 'Pathumi', 'GAL/IT/2324/F/0075', '175.157.127.130', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'mobile', 'unknown', 'K', 'portrait-primary', 'yes', '2', '4g', '360x724', '6.0992959', '80.5626889', 'Android', 'Chrome', '139.0.0.0', 'en-GB', '360x806', 'Asia/Colombo', '69%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'c22b2b74d3097499e8780a2a877bfbbd', '2025-08-26 08:14:31', NULL),
(25, 5, 'test', 'TEST', '112.134.107.150', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'https://edulk.42web.io/?i=1', '/login-process.php', 'c3407214ed45d8b63b76c241def6845d', '2025-08-28 20:14:18', '2025-08-29 19:05:48'),
(26, 5, 'test', 'TEST', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'c3407214ed45d8b63b76c241def6845d', '2025-08-29 17:39:03', '2025-08-29 19:05:48'),
(27, 5, 'test', 'TEST', '112.134.109.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '52%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', 'd3fcb4cad7fcb61fc16d265f22739a3b', '2025-08-29 19:04:24', NULL),
(28, 4, 'H.P.Bhashitha sandaruwan', 'GAL/IT/2324/F/0048', '43.250.243.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'mobile', 'unknown', 'K', 'portrait-primary', 'yes', '2', '4g', '360x680', NULL, NULL, 'Android', 'Chrome', '139.0.0.0', 'en-GB', '360x820', 'Asia/Colombo', '49%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '8493078714c7c78a18acdb02507166fb', '2025-08-29 19:23:41', NULL),
(29, 5, 'test', 'TEST', '112.134.107.171', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', NULL, NULL, 'Windows', 'Chrome', '139.0.0.0', 'en-US', '1536x864', 'Asia/Colombo', '59%', 'online', 'https://edulk.42web.io/index.php', '/login-process.php', '651ae32de2ada29aa9f9a6a074ead233', '2025-09-01 14:16:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_reviews`
--

CREATE TABLE `student_reviews` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT 'Anonymous',
  `regno` varchar(50) DEFAULT 'Anonymous',
  `batch_year` varchar(10) DEFAULT 'Anonymous',
  `review` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` varchar(100) NOT NULL DEFAULT current_timestamp(),
  `admin_reply` varchar(100) NOT NULL,
  `admin_reply_read` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `semester`, `code`, `name`, `description`) VALUES
(1, 'Semester I', 'HNDIT1012', 'Visual Application Programming', 'Core | GPA'),
(2, 'Semester I', 'HNDIT1022', 'Web Design', 'Core | GPA'),
(3, 'Semester I', 'HNDIT1032', 'Computer and Network Systems', 'Core | GPA'),
(4, 'Semester I', 'HNDIT1042', 'Information Management and Information Systems', 'Core | GPA'),
(5, 'Semester I', 'HNDIT1052', 'ICT Project (Individual)', 'Core | GPA'),
(6, 'Semester I', 'HNDIT1062', 'Communication Skills', 'Core | GPA'),
(7, 'Semester II', 'HNDIT2012', 'Fundamentals of Programming', 'Core | GPA'),
(8, 'Semester II', 'HNDIT2022', 'Software Development', 'Core | GPA'),
(9, 'Semester II', 'HNDIT2032', 'System Analysis and Design', 'Core | GPA'),
(10, 'Semester II', 'HNDIT2042', 'Data communication and Computer Networks', 'Core | GPA'),
(11, 'Semester II', 'HNDIT2052', 'Principles of User Interface Design', 'Core | GPA'),
(12, 'Semester II', 'HNDIT2062', 'ICT Project (Group)', 'Core | GPA'),
(13, 'Semester II', 'HNDIT2072', 'Technical Writing', 'Core | GPA'),
(14, 'Semester II', 'HNDIT2082', 'Human Value & Professional Ethics', 'Core | NGPA'),
(15, 'Semester III', 'HNDIT3012', 'Object Oriented Programming', 'Core | GPA'),
(16, 'Semester III', 'HNDIT3022', 'Web Programming', 'Core | GPA'),
(17, 'Semester III', 'HNDIT3032', 'Data Structures and Algorithms', 'Core | GPA'),
(18, 'Semester III', 'HNDIT3042', 'Database Management Systems', 'Core | GPA'),
(19, 'Semester III', 'HNDIT3052', 'Operating Systems', 'Core | GPA'),
(20, 'Semester III', 'HNDIT3062', 'Information and Computer Security', 'Core | GPA'),
(21, 'Semester III', 'HNDIT3072', 'Statistics for IT', 'Core | GPA'),
(22, 'Semester IV', 'HNDIT4012', 'Software Engineering', 'Core | GPA'),
(23, 'Semester IV', 'HNDIT4022', 'Software Quality Assurance', 'Core | GPA'),
(24, 'Semester IV', 'HNDIT4032', 'IT Project Management', 'Core | GPA'),
(25, 'Semester IV', 'HNDIT4042', 'Professional World', 'Core | GPA'),
(26, 'Semester IV', 'HNDIT4052', 'Programming Individual Project', 'Core | GPA'),
(27, 'Semester IV', 'HNDIT4212', 'Machine Learning', 'Elective | GPA'),
(28, 'Semester IV', 'HNDIT4222', 'Business Analysis Practice', 'Elective | GPA'),
(29, 'Semester IV', 'HNDIT4232', 'Enterprise Architecture', 'Elective | GPA'),
(30, 'Semester IV', 'HNDIT4242', 'Computer Services Management', 'Elective | GPA');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_files`
--

CREATE TABLE `tuition_files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `status` enum('active','disabled','inactive') DEFAULT 'active',
  `uploaded_at` timestamp NULL DEFAULT current_timestamp(),
  `uploaded_by_name` varchar(100) NOT NULL,
  `uploaded_by_role` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tuition_files`
--

INSERT INTO `tuition_files` (`id`, `user_id`, `title`, `subject_id`, `category`, `filename`, `status`, `uploaded_at`, `uploaded_by_name`, `uploaded_by_role`) VALUES
(2, 0, 'Communication Skills Paper 2021', 6, 'Pass Papers', '1751207044_68614c84f41d6_CommunicationSkills-JUMAIL-0039.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(3, 0, 'Computer and Network System Paper 2021', 3, 'Pass Papers', '1751207045_68614c8500718_ComputerandNetworkSystem-JUMAIL-0039.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(4, 0, 'Computer and Network System Marking 2021', 3, 'Pass Papers', '1751207045_68614c8501063_HNDIT1032ComputerNetworkScheme.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(5, 0, 'Information Management and Information System Paper 2021', 4, 'Pass Papers', '1751207045_68614c850175d_InformationManagementandInformationSystem-JUMAIL-0039.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(6, 0, 'Information Management and Information System Marking 2021', 4, 'Pass Papers', '1751207045_68614c850226b_HNDIT1042InformationManagementandInformationSystems-Answer-1styear2021.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(7, 0, 'Visual Application Programming Paper 2021', 1, 'Pass Papers', '1751207045_68614c8502ad7_VisualApplicationProgramming-JUMAIL-0039.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(8, 0, 'Visual Application Programming Marking 2021', 1, 'Pass Papers', '1751207045_68614c850319f_HNDIT1012VisualScheme.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(9, 0, 'Web Design Paper 2021', 2, 'Pass Papers', '1751207045_68614c85037e3_WebDesign-JUMAIL-0039.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(10, 0, 'Web Design Marking 2021', 2, 'Pass Papers', '1751207045_68614c8503d25_HNDIT1022WebDesignScheme-new.pdf', 'active', '2025-06-29 14:24:05', 'Malitha Tishamal', 'admin'),
(11, 0, 'Paper 2022', 6, 'Pass Papers', '1751208012_6861504c10451_2022-communicationskills.pdf', 'active', '2025-06-29 14:40:12', 'Malitha Tishamal', 'admin'),
(12, 0, 'Paper 2022', 3, 'Pass Papers', '1751208012_6861504c532b1_2022-CNS.pdf', 'active', '2025-06-29 14:40:12', 'Malitha Tishamal', 'admin'),
(13, 0, 'Paper 2022', 4, 'Pass Papers', '1751208012_6861504c743df_2022-MIS.pdf', 'active', '2025-06-29 14:40:12', 'Malitha Tishamal', 'admin'),
(14, 0, 'Paper 2022', 1, 'Pass Papers', '1751208012_6861504ca11e9_2022-visual.pdf', 'active', '2025-06-29 14:40:12', 'Malitha Tishamal', 'admin'),
(15, 0, 'Paper 2022', 2, 'Pass Papers', '1751208012_6861504cdfc07_2022-webdesign.pdf', 'active', '2025-06-29 14:40:13', 'Malitha Tishamal', 'admin'),
(16, 0, 'Marking 2022', 6, 'Pass Papers', '1751208154_686150dade23f_2022-HNDIT1062-Marking-CommunicationSkill.pdf', 'active', '2025-06-29 14:42:34', 'Malitha Tishamal', 'admin'),
(17, 0, 'Marking 2022', 3, 'Pass Papers', '1751208154_686150dade935_IT1032MarkingScheme2022-ComputerNetwork.pdf', 'active', '2025-06-29 14:42:34', 'Malitha Tishamal', 'admin'),
(18, 0, 'Marking 2022', 4, 'Pass Papers', '1751208154_686150dadf020_.2024.01.10E-HNDIT1042_1styear_2022_InformationManagementandInformationSystems_MarkingScheme.pdf', 'active', '2025-06-29 14:42:34', 'Malitha Tishamal', 'admin'),
(19, 0, 'Marking 2022', 1, 'Pass Papers', '1751208154_686150dadf577_HNDIT1012-Visual_2022_MarkingScheme.pdf', 'active', '2025-06-29 14:42:34', 'Malitha Tishamal', 'admin'),
(20, 0, 'Marking 2022', 2, 'Pass Papers', '1751208154_686150dadfa3b_WebDesignMarkingScheme-2022.pdf', 'active', '2025-06-29 14:42:34', 'Malitha Tishamal', 'admin'),
(21, 0, 'Paper 2021', 10, 'Pass Papers', '1751208299_6861516b111be_DataCommunicationandComputerNetworks-JUMAIL-00391.pdf', 'active', '2025-06-29 14:44:59', 'Malitha Tishamal', 'admin'),
(22, 0, 'Paper 2021', 7, 'Pass Papers', '1751208299_6861516b118ba_FundamentalsofProgramming-JUMAIL-0039.pdf', 'active', '2025-06-29 14:44:59', 'Malitha Tishamal', 'admin'),
(23, 0, 'Paper 2021', 11, 'Pass Papers', '1751208299_6861516b11ef9_PrincipelsofUserInterfaceDesign-JUMAIL-0039.pdf', 'active', '2025-06-29 14:44:59', 'Malitha Tishamal', 'admin'),
(24, 0, 'Paper 2021', 8, 'Pass Papers', '1751208299_6861516b12595_Softwaredevelopment-JUMAIL-0039.pdf', 'active', '2025-06-29 14:44:59', 'Malitha Tishamal', 'admin'),
(25, 0, 'Paper 2021', 9, 'Pass Papers', '1751208299_6861516b12cfa_SystemAnalysisandDesign-JUMAIL-0039.pdf', 'active', '2025-06-29 14:44:59', 'Malitha Tishamal', 'admin'),
(26, 0, 'Paper 2021', 13, 'Pass Papers', '1751208299_6861516b13372_TechnicalWriting-JUMAIL-0039.pdf', 'active', '2025-06-29 14:44:59', 'Malitha Tishamal', 'admin'),
(27, 0, 'Marking 2021', 10, 'Pass Papers', '1751208520_68615248d6dda_HNDIT2042-2021DataCommunicationandNetwork.pdf', 'active', '2025-06-29 14:48:40', 'Malitha Tishamal', 'admin'),
(28, 0, 'Marking 2021', 7, 'Pass Papers', '1751208520_68615248d74fb_HNDIT2012fundamentalsofprogrammingMarkingScheme.pdf', 'active', '2025-06-29 14:48:40', 'Malitha Tishamal', 'admin'),
(29, 0, 'Marking 2021', 11, 'Pass Papers', '1751208520_68615248d7b7f_HNDIT2052PrinciplesofUID_2021-Answer.pdf', 'active', '2025-06-29 14:48:40', 'Malitha Tishamal', 'admin'),
(30, 0, 'Marking 2021', 8, 'Pass Papers', '1751208520_68615248d80a7_HNDIT2022-SoftwareDevelopmentMarkingScheme2021.pdf', 'active', '2025-06-29 14:48:40', 'Malitha Tishamal', 'admin'),
(31, 0, 'Marking 2021', 9, 'Pass Papers', '1751208520_68615248d8972_HNDIT2032-SADMarkingScheme.pdf', 'active', '2025-06-29 14:48:40', 'Malitha Tishamal', 'admin'),
(32, 0, 'Marking 2021', 13, 'Pass Papers', '1751208520_68615248ed527_HNDIT2072-TechnicalWriting.pdf', 'active', '2025-06-29 14:48:40', 'Malitha Tishamal', 'admin'),
(33, 0, 'Paper 2022', 10, 'Pass Papers', '1751208886_686153b6c12d6_2022-DCN.pdf', 'active', '2025-06-29 14:54:46', 'Malitha Tishamal', 'admin'),
(34, 0, 'Paper 2022', 7, 'Pass Papers', '1751208886_686153b6e77bf_2022-FoP.pdf', 'active', '2025-06-29 14:54:47', 'Malitha Tishamal', 'admin'),
(35, 0, 'Paper 2022', 11, 'Pass Papers', '1751208887_686153b71a6fe_2022-UI.pdf', 'active', '2025-06-29 14:54:47', 'Malitha Tishamal', 'admin'),
(36, 0, 'Paper 2022', 8, 'Pass Papers', '1751208887_686153b74f6f9_2022-SD.pdf', 'active', '2025-06-29 14:54:47', 'Malitha Tishamal', 'admin'),
(37, 0, 'Paper 2022', 9, 'Pass Papers', '1751208887_686153b785d1c_2022-SAD.pdf', 'active', '2025-06-29 14:54:47', 'Malitha Tishamal', 'admin'),
(38, 0, 'Paper 2022', 13, 'Pass Papers', '1751208887_686153b7d20d4_2022-technicalwriting.pdf', 'active', '2025-06-29 14:54:48', 'Malitha Tishamal', 'admin'),
(39, 0, 'Marking 2022', 10, 'Pass Papers', '1751209056_68615460a54e7_HNDIT2042-2022-DCN.pdf', 'active', '2025-06-29 14:57:36', 'Malitha Tishamal', 'admin'),
(40, 0, 'Marking 2022', 7, 'Pass Papers', '1751209056_68615460a5c6f_HNDIT2012MarkingSchemeFundamentalofProgramming.pdf', 'active', '2025-06-29 14:57:36', 'Malitha Tishamal', 'admin'),
(41, 0, 'Marking 2022', 11, 'Pass Papers', '1751209056_68615460a6159_HNDIT2052_PUIDanswerscript2022sem2.pdf', 'active', '2025-06-29 14:57:36', 'Malitha Tishamal', 'admin'),
(42, 0, 'Marking 2022', 8, 'Pass Papers', '1751209056_68615460a67fd_HNDIT2022-SoftwareDevelopment-2022-MarkingScheme.pdf', 'active', '2025-06-29 14:57:36', 'Malitha Tishamal', 'admin'),
(43, 0, 'Marking 2022', 9, 'Pass Papers', '1751209056_68615460a6d3f_HNDIT2032SAD-Answer.pdf', 'active', '2025-06-29 14:57:36', 'Malitha Tishamal', 'admin'),
(44, 0, 'Week 1', 1, 'Notes', '1751251447_6861f9f7edb42_W1.pptx', 'active', '2025-06-30 02:44:07', 'Malitha Tishamal', 'admin'),
(45, 0, 'Week 2', 1, 'Notes', '1751251447_6861f9f7ef16b_W2.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(46, 0, 'Week 3', 1, 'Notes', '1751251448_6861f9f80d372_W3.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(47, 0, 'Week 4', 1, 'Notes', '1751251448_6861f9f82cac9_W4.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(48, 0, 'Week 5', 1, 'Notes', '1751251448_6861f9f830077_W5.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(49, 0, 'Week 6', 1, 'Notes', '1751251448_6861f9f83520e_W6.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(50, 0, 'Week 7', 1, 'Notes', '1751251448_6861f9f83c75d_W7.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(51, 0, 'Week 8', 1, 'Notes', '1751251448_6861f9f847b45_W8.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(52, 0, 'Week 9', 1, 'Notes', '1751251448_6861f9f84d080_W9.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(53, 0, 'Week 10', 1, 'Notes', '1751251448_6861f9f852267_W10.pptx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(54, 0, 'Lab sheet 1', 1, 'Notes', '1751251448_6861f9f857c67_Labsheet1.docx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(55, 0, 'Lab sheet 1', 1, 'Notes', '1751251448_6861f9f894344_LabSheet2.docx', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(56, 0, 'BMI Calculator', 1, 'Notes', '1751251448_6861f9f89eb6e_BMICalculatorApplication.pdf', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(57, 0, 'VAP Pos System', 1, 'Notes', '1751251448_6861f9f8a3530_VAPPOSTutorial.pdf', 'active', '2025-06-30 02:44:08', 'Malitha Tishamal', 'admin'),
(58, 0, 'Week 1', 2, 'Notes', '1751253097_68620069b8764_HNDIT1022Week01Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(59, 0, 'Week 2', 2, 'Notes', '1751253098_6862006a24c94_HNDIT1022Week02Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(60, 0, 'Week 3-1', 2, 'Notes', '1751253098_6862006a3e29f_HNDIT1022Week03Part1Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(61, 0, 'Week 3-2', 2, 'Notes', '1751253098_6862006a5b610_HNDIT1022Week03Part2Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(62, 0, 'Week 4', 2, 'Notes', '1751253098_6862006a6876c_HNDIT1022Week04Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(63, 0, 'Week 5', 2, 'Notes', '1751253098_6862006a900b9_HNDIT1022Week05Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(64, 0, 'Week 6', 2, 'Notes', '1751253098_6862006aa387e_HNDIT1022Week06Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(65, 0, 'Week 7', 2, 'Notes', '1751253098_6862006ab1052_HNDIT1022Week07Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(66, 0, 'Week 8.9.10', 2, 'Notes', '1751253098_6862006abca86_HNDIT1022Week080910Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(67, 0, 'Week 13', 2, 'Notes', '1751253098_6862006ad6563_HNDIT1022Week13Theory.pptx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(68, 0, 'labsheet1', 2, 'Notes', '1751253098_6862006ade2be_HNDIT1022labsheet01.docx', 'active', '2025-06-30 03:11:38', 'Malitha Tishamal', 'admin'),
(69, 0, 'labsheet2', 2, 'Notes', '1751253098_6862006ae5f1d_HNDIT1022labsheet02.docx', 'active', '2025-06-30 03:11:39', 'Malitha Tishamal', 'admin'),
(70, 0, 'labsheet4', 2, 'Notes', '1751253099_6862006b3a2a9_HNDIT1022labsheet04.docx', 'active', '2025-06-30 03:11:39', 'Malitha Tishamal', 'admin'),
(71, 0, 'labsheet5', 2, 'Notes', '1751253099_6862006b41e11_HNDIT1022labsheet05.docx', 'active', '2025-06-30 03:11:39', 'Malitha Tishamal', 'admin'),
(72, 0, 'labsheet7', 2, 'Notes', '1751253099_6862006b49a90_HNDIT1022labsheet07.docx', 'active', '2025-06-30 03:11:39', 'Malitha Tishamal', 'admin'),
(73, 0, 'labsheet13', 2, 'Notes', '1751253099_6862006b51902_HNDIT1022labsheet13.docx', 'active', '2025-06-30 03:11:39', 'Malitha Tishamal', 'admin'),
(74, 0, 'practicle quick rev', 2, 'Notes', '1751253099_6862006b59953_HNDIT1022PracticalQuickRevision.docx', 'active', '2025-06-30 03:11:39', 'Malitha Tishamal', 'admin'),
(75, 0, 'Week 1', 3, 'Notes', '1751254259_686204f3cb1c5_IT1032-Week01.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(76, 0, 'Week 2', 3, 'Notes', '1751254260_686204f4017b5_IT1032-Week02.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(77, 0, 'Week 3', 3, 'Notes', '1751254260_686204f41eb3a_IT1032-Week03.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(78, 0, 'Week 4', 3, 'Notes', '1751254260_686204f4323d3_IT1032-Week04.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(79, 0, 'Week 5', 3, 'Notes', '1751254260_686204f43ff38_IT1032-Week05.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(80, 0, 'Week 6', 3, 'Notes', '1751254260_686204f4632a9_IT1032-Week06.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(81, 0, 'Week 7', 3, 'Notes', '1751254260_686204f4ad822_IT1032-Week07.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(82, 0, 'Week 8', 3, 'Notes', '1751254260_686204f4bd5f3_IT1032-Week08.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(83, 0, 'Week 9', 3, 'Notes', '1751254260_686204f4d4594_IT1032-Week09.pptx', 'active', '2025-06-30 03:31:00', 'Malitha Tishamal', 'admin'),
(84, 0, 'Week 10.11', 3, 'Notes', '1751254260_686204f4f39f1_IT1032-Week10_11.pptx', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(85, 0, 'Week 12', 3, 'Notes', '1751254261_686204f5343a9_IT1032-Week12.pptx', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(86, 0, 'Week 13', 3, 'Notes', '1751254261_686204f553793_IT1032-Week13.pptx', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(87, 0, 'Week 14', 3, 'Notes', '1751254261_686204f5a9806_IT1032-Week14.pptx', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(88, 0, 'Tutorial 01', 3, 'Notes', '1751254261_686204f5de28e_IT1032-Tutorial01.pdf', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(89, 0, 'Tutorial 02', 3, 'Notes', '1751254261_686204f5dffcc_IT1032-Tutorial02.pdf', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(90, 0, 'Tutorial 03', 3, 'Notes', '1751254261_686204f5e3a9b_IT1032-Tutorial03.pdf', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(91, 0, 'Tutorial 09', 3, 'Notes', '1751254261_686204f5e711a_Tutorial09.pdf', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(92, 0, 'Tutorial 12', 3, 'Notes', '1751254261_686204f5eb36d_IT1032-Tutorial12.pdf', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(93, 0, 'Model Paper', 3, 'Notes', '1751254261_686204f5ef9e8_IT1032-ModelPaper.pdf', 'active', '2025-06-30 03:31:01', 'Malitha Tishamal', 'admin'),
(94, 0, 'Week 1', 4, 'Notes', '1751255293_686208fd01c4d_MISweek1.pptx', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(95, 0, 'Week 2', 4, 'Notes', '1751255293_686208fd3a356_MISweek2.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(96, 0, 'Week 3', 4, 'Notes', '1751255293_686208fd4d954_week3.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(97, 0, 'Week 4-1', 4, 'Notes', '1751255293_686208fd5f567_MISweek4.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(98, 0, 'Week 4-2', 4, 'Notes', '1751255293_686208fd64fb3_MISweek4-1.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(99, 0, 'Week 5', 4, 'Notes', '1751255293_686208fd6d124_MISweek5.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(100, 0, 'Week 6', 4, 'Notes', '1751255293_686208fd8e23c_MISweek6.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(101, 0, 'Week 7', 4, 'Notes', '1751255293_686208fda393d_MISweek7.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(102, 0, 'Week 8', 4, 'Notes', '1751255293_686208fdb5307_MIS8-newAutosaved.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(103, 0, 'Week 9', 4, 'Notes', '1751255293_686208fdc8b70_MIS9.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(104, 0, 'Week 10', 4, 'Notes', '1751255293_686208fdd2a96_MIS10.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(105, 0, 'week 1 activity 1', 4, 'Notes', '1751255293_686208fddf659_week1activity.docx', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(106, 0, 'week 1 activity 2', 4, 'Notes', '1751255293_686208fddfb9a_week1activity2.docx', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(107, 0, 'activity 3', 4, 'Notes', '1751255293_686208fdf3a70_Activity3.pdf', 'active', '2025-06-30 03:48:13', 'Malitha Tishamal', 'admin'),
(108, 0, 'Paper 2023', 6, 'Pass Papers', '1752077219_686e93a3cc9be_edulk.hndit.1062.com.skills.2023.pdf', 'active', '2025-07-09 16:06:59', 'Malitha Tishamal', 'admin'),
(109, 0, 'Paper 2023', 3, 'Pass Papers', '1752077219_686e93a3ce391_edulk.hndit.1042.cns.2023.pdf', 'active', '2025-07-09 16:06:59', 'Malitha Tishamal', 'admin'),
(110, 0, 'Paper 2023', 1, 'Pass Papers', '1752077219_686e93a3e7376_edulk.hndit.1012.vap.2023.pdf', 'active', '2025-07-09 16:07:00', 'Malitha Tishamal', 'admin'),
(111, 0, 'Paper 2023', 2, 'Pass Papers', '1752077220_686e93a416476_edulk.hndit.1022.web.design.2023.pdf', 'active', '2025-07-09 16:07:00', 'Malitha Tishamal', 'admin'),
(112, 0, 'Paper 2023', 4, 'Pass Papers', '1752077220_686e93a43f485_edulk.hndit.1042.ims.2023.pdf', 'active', '2025-07-09 16:07:00', 'Malitha Tishamal', 'admin'),
(113, 0, 'Paper 2023', 11, 'Pass Papers', '1752086273_686eb70174f72_Edulk.hndit2052.ui.2023.pdf', 'active', '2025-07-09 18:37:53', 'Malitha Tishamal', 'admin'),
(114, 0, 'Paper 2023', 9, 'Pass Papers', '1752086273_686eb70176b12_Edulk-hndit2052.SAD.2023.pdf', 'active', '2025-07-09 18:37:53', 'Malitha Tishamal', 'admin'),
(115, 0, 'Assignment', 10, 'Pass Papers', '1752086273_686eb701776a3_ASS-DCCN.docx', 'active', '2025-07-09 18:37:53', 'Malitha Tishamal', 'admin'),
(116, 0, 'Assignment', 9, 'Pass Papers', '1752086273_686eb70177ab2_IMG_20250709_173106.jpg', 'active', '2025-07-09 18:37:53', 'Malitha Tishamal', 'admin'),
(117, 0, 'Assignment', 3, 'Pass Papers', '1752086377_686eb7690d161_SLIATE-Copy.docx', 'active', '2025-07-09 18:39:37', 'Malitha Tishamal', 'admin'),
(118, 0, 'Assignment', 2, 'Pass Papers', '1752086377_686eb7690d734_edulk.web.design.assignment.2023.pdf', 'active', '2025-07-09 18:39:37', 'Malitha Tishamal', 'admin'),
(119, 0, 'Assignment 2023', 1, 'Pass Papers', '1752141169_686f8d71a890b_1752141098669.jpg', 'active', '2025-07-10 09:52:49', 'Malitha Tishamal', 'admin'),
(122, 0, 'Week 1', 7, 'Notes', '1755275393_689f60813873c_HNDIT2012-W1Introductiontocomputerprograms.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(123, 0, 'Week 2', 7, 'Notes', '1755275393_689f60813eed5_HNDIT2012-W2NetbeansIDE.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(124, 0, 'Week 3', 7, 'Notes', '1755275393_689f6081513ac_HNDIT2012-W3VariablesandDataTypes.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(125, 0, 'Week 4', 7, 'Notes', '1755275393_689f60815690d_HNDIT2012-W4ExpressionsandOperators.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(126, 0, 'Week 5', 7, 'Notes', '1755275393_689f608164155_HNDIT2012-W5JavaControlStructuresPartI.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(127, 0, 'Week 6', 7, 'Notes', '1755275393_689f60819204b_HNDIT2012-W6JavaControlStructuresPartII.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(128, 0, 'Week 7', 7, 'Notes', '1755275393_689f6081a76cd_HNDIT2012-W7-Arrays.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(129, 0, 'Week 8', 7, 'Notes', '1755275393_689f6081ac7ea_HNDIT2012-W8-Methoddeclarationandparameterpassing.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(130, 0, 'Week 9', 7, 'Notes', '1755275393_689f6081e3cf4_HNDIT2012-W9ExceptionsHandling.pptx', 'active', '2025-08-15 16:29:53', 'Malitha Tishamal', 'admin'),
(131, 0, 'Week 10', 7, 'Notes', '1755275393_689f6081e899f_HNDIT2012-W10-AccessModifiersObjectsandClasses.ppt', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(132, 0, 'Week 11', 7, 'Notes', '1755275394_689f6082050ab_HNDIT2012-W11-OOP.pptx', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(133, 0, 'Week 12 GUI Designing', 7, 'Notes', '1755275394_689f6082226c6_HNDIT2012-GUIDesigning.pptx', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(134, 0, 'Week 13 Event Handling', 7, 'Notes', '1755275394_689f60822aa43_HNDIT2012-EventHandling.pptx', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(135, 0, 'Java Programming Assignment', 7, 'Other', '1755275394_689f60823dbc3_JavaProgrammingAssignment.pdf', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(136, 0, 'Lab Sheet Conditional Statements Part I', 7, 'Other', '1755275394_689f60824357c_HNDIT2012-LabSheetConditionalStatementsPartI.pdf', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(137, 0, 'Lab Sheet Conditional Statements Part II', 7, 'Other', '1755275394_689f608244803_HNDIT2012-LabSheetConditionalStatementsPartII.pdf', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(138, 0, 'Lab Sheet Conditional Statements Part III', 7, 'Other', '1755275394_689f608247474_HNDIT2012-LabSheetConditionalStatementsPartIII.pdf', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(139, 0, 'Lab Sheet Conditional Statements Part IV', 7, 'Other', '1755275394_689f60824892a_HNDIT2012-LabSheetConditionalStatementsPartIV.pdf', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(140, 0, 'Lab sheet - OOP Concepts Part II', 7, 'Other', '1755275394_689f60824ad82_HNDIT2012-Labsheet-OOPConceptsPartII.pdf', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(141, 0, 'Lab Sheet - Java Variables and Data Types', 7, 'Other', '1755275394_689f608253ba8_HNDIT2012-LabSheet-JavaVariablesandDataTypes.pdf', 'active', '2025-08-15 16:29:54', 'Malitha Tishamal', 'admin'),
(142, 0, 'Paper 2023', 10, 'Pass Papers', '1755276869_689f6645194fa_Edulk.hndit.2042.dccn.2023.pdf', 'active', '2025-08-15 16:54:29', 'Malitha Tishamal', 'admin'),
(143, 0, 'Paper 2023', 7, 'Pass Papers', '1755276953_689f6699b8ed2_Edulk.hndit.2012.fundamentals.of.programming.2023.pdf', 'active', '2025-08-15 16:55:53', 'Malitha Tishamal', 'admin'),
(144, 0, 'Paper 2023', 8, 'Pass Papers', '1755277042_689f66f24f0e0_Edulk.hndit2.2022.software.developement.2023.pdf', 'active', '2025-08-15 16:57:22', 'Malitha Tishamal', 'admin'),
(145, 0, 'Paper 2023', 13, 'Pass Papers', '1755277114_689f673a30b77_Edulk.hndit.2072.technicle.writing.2023.pdf', 'active', '2025-08-15 16:58:34', 'Malitha Tishamal', 'admin'),
(146, 0, 'Week 1', 17, 'Notes', '1755309221_689fe4a5d3b18_HNDIT3032Lecturer01.ppt', 'active', '2025-08-16 01:53:41', 'Malitha Tishamal', 'admin'),
(147, 0, 'Week 2', 17, 'Notes', '1755309221_689fe4a5d4f61_HNDIT3032Lecturer2.ppt', 'active', '2025-08-16 01:53:41', 'Malitha Tishamal', 'admin'),
(148, 0, 'Week 3', 17, 'Notes', '1755309221_689fe4a5d5488_HNDIT3032Lecturer3.ppt', 'active', '2025-08-16 01:53:41', 'Malitha Tishamal', 'admin'),
(149, 0, 'Week 4', 17, 'Notes', '1755309221_689fe4a5d59a7_HNDIT3032Lecturer4.pptx', 'active', '2025-08-16 01:53:41', 'Malitha Tishamal', 'admin'),
(150, 0, 'Week 5-6', 17, 'Notes', '1755309221_689fe4a5d5e75_HNDIT3032Lecturer5.pdf', 'active', '2025-08-16 01:53:41', 'Malitha Tishamal', 'admin'),
(151, 0, 'Practical sheet 1', 17, 'Other', '1755309807_689fe6efadb0b_Practicalsheet01-Loop.pdf', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(152, 0, 'Practical sheet 2', 17, 'Other', '1755309807_689fe6efae0e2_PracticalSheets2.pdf', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(153, 0, 'Practical sheet 3 ArrayList', 17, 'Other', '1755309807_689fe6efae730_PracticalSheets3ArrayList.pdf', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(154, 0, 'Practical sheet 4', 17, 'Other', '1755309807_689fe6efaed54_Practicalsheet4.pdf', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(155, 0, 'Week 7', 17, 'Notes', '1755309807_689fe6efaf268_HNDIT3032Lecturer7.ppt', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(156, 0, 'Practical sheet 5', 17, 'Other', '1755309807_689fe6efaf89d_PracticalSheets5Stack.pdf', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(157, 0, 'Week 8', 17, 'Notes', '1755309807_689fe6efafddf_Week8.ppt', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(158, 0, 'Practical Week 8', 17, 'Other', '1755309807_689fe6efb13b5_Week08Practicalsheet.pdf', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(159, 0, 'Week 9', 17, 'Notes', '1755309807_689fe6efb5e61_HNDIT3032Lecturer9.ppt', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(160, 0, 'Week 10', 17, 'Notes', '1755309807_689fe6efd12b0_HNDIT3032-Lecture3_2.ppt', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(161, 0, 'Assignment 01-DSA-2021File', 17, 'Other', '1755309807_689fe6efe69dc_Assignment01-DSA-2021.pdf', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(162, 0, 'Week 11', 17, 'Notes', '1755309807_689fe6efebea5_HNDIT3032Lecturer11_2.ppt', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(163, 0, 'Week 12-13', 17, 'Notes', '1755309808_689fe6f029305_HNDIT3032Lecturer12and13.ppt', 'active', '2025-08-16 02:03:27', 'Malitha Tishamal', 'admin'),
(164, 0, 'Week 14-15', 17, 'Notes', '1755309808_689fe6f037507_HNDIT3032Lecturer14and15.ppt', 'active', '2025-08-16 02:03:28', 'Malitha Tishamal', 'admin'),
(165, 0, 'Week 1', 15, 'Notes', '1755310631_689fea279e4e5_Week1-OOP.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(166, 0, 'Week 2', 15, 'Notes', '1755310631_689fea279ec8e_Week2-OOP.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(167, 0, 'Week 3', 15, 'Notes', '1755310631_689fea27a1334_week3-oop.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(168, 0, 'Week 4', 15, 'Notes', '1755310631_689fea27a1791_Week4-OOP.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(169, 0, 'Week 5', 15, 'Notes', '1755310631_689fea27a1bf7_Week5-OOPCON.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(170, 0, 'Week 6', 15, 'Notes', '1755310631_689fea27a1fc1_Week6-OOPENC.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(171, 0, 'Week 7', 15, 'Notes', '1755310631_689fea27a23d0_Week7-OOPINH.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(172, 0, 'Week 8', 15, 'Notes', '1755310631_689fea27a27ff_Week8-OOP.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(173, 0, 'Week 9', 15, 'Notes', '1755310631_689fea27a2b9a_Week9-OOP1.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(174, 0, 'Week 10', 15, 'Notes', '1755310631_689fea27a2f2f_Week10-OOP.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(175, 0, 'Week 11', 15, 'Notes', '1755310631_689fea27a5447_Week11-OOP.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(176, 0, 'Week 12', 15, 'Notes', '1755310631_689fea27abde7_Week12-IOstream.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(177, 0, 'Week 13', 15, 'Notes', '1755310631_689fea27b1fdc_week13-thread.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(178, 0, 'Week 14', 15, 'Notes', '1755310631_689fea27bbbe7_Week14-JDBC.pptx', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(179, 0, 'Labsheet 1', 15, 'Other', '1755310631_689fea27c777c_HNDIT3012-JavafundermentalLabSheet-1.pdf', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(180, 0, 'Labsheet 2', 15, 'Other', '1755310631_689fea27cf084_HNDIT3012-AccessModifiersLabSheet-2.pdf', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(181, 0, 'Labsheet 3', 15, 'Other', '1755310631_689fea27d9c5f_HNDIT3012-Inheritance.pdf', 'active', '2025-08-16 02:17:11', 'Malitha Tishamal', 'admin'),
(182, 0, 'Lesson 1', 8, 'Notes', '1755337432_68a052d819a2c_Lesson01-FundamentalConceptsoftheProgrammingProcess.pptx', 'active', '2025-08-16 09:43:53', 'Malitha Tishamal', 'admin'),
(183, 0, 'Lesson 2', 8, 'Notes', '1755339036_68a0591c6fc50_Lesson02-FundamentalConceptsoftheProgrammingProcess-Part2.pptx', 'active', '2025-08-16 10:10:37', 'Malitha Tishamal', 'admin'),
(184, 0, 'Lesson 3', 8, 'Notes', '1755339037_68a0591d79027_Lesson03-Phase-specificissuesofsoftwaredevelopment.pptx', 'active', '2025-08-16 10:10:37', 'Malitha Tishamal', 'admin'),
(185, 0, 'Lesson 4', 8, 'Notes', '1755339037_68a0591dad22b_Lesson04-Phase-specificissuesofsoftwaredevelopment-Part2.pptx', 'active', '2025-08-16 10:10:37', 'Malitha Tishamal', 'admin'),
(186, 0, 'Lesson 5', 8, 'Notes', '1755339037_68a0591de9aea_Lesson05-IntorductiontoProgrammingconcepts-Part1.pptx', 'active', '2025-08-16 10:10:38', 'Malitha Tishamal', 'admin'),
(187, 0, 'Lesson 6', 8, 'Notes', '1755339408_68a05a902bacc_Lesson06-IntroductiontoProgrammingconcepts-Part2.pptx', 'active', '2025-08-16 10:16:48', 'Malitha Tishamal', 'admin'),
(188, 0, 'Lesson 7', 8, 'Notes', '1755339408_68a05a902c322_Lesson07-SortingSearchingAlgorithms.pptx', 'active', '2025-08-16 10:16:48', 'Malitha Tishamal', 'admin'),
(189, 0, 'Lesson 8', 8, 'Notes', '1755339408_68a05a902c95f_Lesson08-FileHandlinginProgramming.pptx', 'active', '2025-08-16 10:16:48', 'Malitha Tishamal', 'admin'),
(190, 0, 'Lesson 9', 8, 'Notes', '1755339408_68a05a902cf9d_Lesson09-Introductiontoconceptofuserinterfacedesign.pptx', 'active', '2025-08-16 10:16:48', 'Malitha Tishamal', 'admin'),
(191, 0, 'Selection sort', 8, 'Notes', '1755341000_68a060c8743e7_1.Selectionsort.pdf', 'active', '2025-08-16 10:43:20', 'Malitha Tishamal', 'admin'),
(192, 0, 'bubble sort', 8, 'Notes', '1755341000_68a060c874b1b_2bubblesort.pdf', 'active', '2025-08-16 10:43:20', 'Malitha Tishamal', 'admin'),
(193, 0, 'Insertion sort', 8, 'Notes', '1755341000_68a060c874eeb_3Insertionsort.pdf', 'active', '2025-08-16 10:43:20', 'Malitha Tishamal', 'admin'),
(194, 0, 'Merge sort', 8, 'Notes', '1755341000_68a060c87528f_4Mergesort.pdf', 'active', '2025-08-16 10:43:20', 'Malitha Tishamal', 'admin'),
(195, 0, 'Binary search', 8, 'Notes', '1755341000_68a060c87568c_Binarysearch.pdf', 'active', '2025-08-16 10:43:20', 'Malitha Tishamal', 'admin'),
(196, 0, 'Linear search', 8, 'Notes', '1755341000_68a060c875a9d_Linearsearch.pdf', 'active', '2025-08-16 10:43:20', 'Malitha Tishamal', 'admin'),
(197, 0, 'Quick sort', 8, 'Notes', '1755341000_68a060c875e4b_5Quicksort.pdf', 'active', '2025-08-16 10:43:20', 'Malitha Tishamal', 'admin'),
(198, 0, 'Answers - Quiz 01', 8, 'Other', '1755341614_68a0632edaed9_Answers-Quiz01.pdf', 'active', '2025-08-16 10:53:34', 'Malitha Tishamal', 'admin'),
(199, 0, 'fill_in_the_blanks_questions', 8, 'Other', '1755341614_68a0632edb33d_fill_in_the_blanks_questions.pdf', 'active', '2025-08-16 10:53:34', 'Malitha Tishamal', 'admin'),
(200, 0, 'Lesson 02 -Tutorial', 8, 'Other', '1755341614_68a0632edb69e_Lesson02-Tutorial.pdf', 'active', '2025-08-16 10:53:34', 'Malitha Tishamal', 'admin'),
(201, 0, 'Tutorial - Lesson 04', 8, 'Other', '1755341614_68a0632edbab1_Tutorial-Lesson04.pdf', 'active', '2025-08-16 10:53:34', 'Malitha Tishamal', 'admin'),
(202, 0, 'Lesson08_FileHandling_MCQ_No_Answers', 8, 'Other', '1755341614_68a0632edbed1_Lesson08_FileHandling_MCQ_No_Answers.pdf', 'active', '2025-08-16 10:53:34', 'Malitha Tishamal', 'admin'),
(203, 0, 'MCQ', 8, 'Other', '1755341614_68a0632edc2ae_MCQ.pdf', 'active', '2025-08-16 10:53:34', 'Malitha Tishamal', 'admin'),
(204, 0, 'Quiz -01', 8, 'Other', '1755341614_68a0632edc64b_Quiz-01.pdf', 'active', '2025-08-16 10:53:34', 'Malitha Tishamal', 'admin'),
(205, 0, 'Week 4', 13, 'Notes', '1755344483_68a06e631288f_Week4.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(206, 0, 'Week 5', 13, 'Notes', '1755344483_68a06e6313329_Week5.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(207, 0, 'Week 6', 13, 'Notes', '1755344483_68a06e6313998_Week6.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(208, 0, 'Week 7', 13, 'Notes', '1755344483_68a06e6313f2e_Week7.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(209, 0, 'Week 8', 13, 'Notes', '1755344483_68a06e6314410_Week8.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(210, 0, 'Week 9', 13, 'Notes', '1755344483_68a06e6314863_Week9.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(211, 0, 'Week 10', 13, 'Notes', '1755344483_68a06e6314c88_Week10.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(212, 0, 'Week 11', 13, 'Notes', '1755344483_68a06e631501b_Week11.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(213, 0, 'Week 12', 13, 'Notes', '1755344483_68a06e6315424_Week12.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(214, 0, 'Week 13', 13, 'Notes', '1755344483_68a06e63157d7_Week13.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(215, 0, 'Week 14', 13, 'Notes', '1755344483_68a06e6315b96_Week-14.pdf', 'active', '2025-08-16 11:41:23', 'Malitha Tishamal', 'admin'),
(216, 0, 'Lab sheet - Classes and Objects', 13, 'Other', '1755344598_68a06ed68b156_HNDIT2012-Labsheet-ClassesandObjects.pdf', 'active', '2025-08-16 11:43:18', 'Malitha Tishamal', 'admin'),
(217, 0, 'Lesson 2', 13, 'Notes', '1755344598_68a06ed68bcc1_Lesson2.pdf', 'active', '2025-08-16 11:43:18', 'Malitha Tishamal', 'admin'),
(218, 0, 'Lesson 3', 13, 'Notes', '1755344598_68a06ed68c253_Lesson3.pdf', 'active', '2025-08-16 11:43:18', 'Malitha Tishamal', 'admin'),
(219, 0, 'Practicle 1', 11, 'Other', '1755344844_68a06fcc71262_Practical01.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(220, 0, 'Practicle 2', 11, 'Other', '1755344844_68a06fcc718b5_practical02.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(221, 0, 'Practicle 3', 11, 'Other', '1755344844_68a06fcc71e25_practicalweek03.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(222, 0, 'Practicle 4', 11, 'Other', '1755344844_68a06fcc7240d_practicalweek04.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(223, 0, 'Practicle 5', 11, 'Other', '1755344844_68a06fcc729ba_practicalweek05.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(224, 0, 'Practicle 6', 11, 'Other', '1755344844_68a06fcc73e11_practicalweek06.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(225, 0, 'Practicle 7', 11, 'Other', '1755344844_68a06fcc743da_practicalweek07.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(226, 0, 'Practicle 8', 11, 'Other', '1755344844_68a06fcc7495c_practicalweek08.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(227, 0, 'Practicle 9', 11, 'Other', '1755344844_68a06fcc74e54_practicalweek09.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(228, 0, 'Practicle 10', 11, 'Other', '1755344844_68a06fcc753a1_practicalweek10.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(229, 0, 'Practicle 11', 11, 'Other', '1755344844_68a06fcc7588a_practicalweek11.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(230, 0, 'Practicle 12', 11, 'Other', '1755344844_68a06fcc75d9f_practicalweek12.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(231, 0, 'Practicle 13', 11, 'Other', '1755344844_68a06fcc76294_practicalweek13.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(232, 0, 'Practicle 14', 11, 'Other', '1755344844_68a06fcc76815_practicalweek14.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(233, 0, 'Practicle 15', 11, 'Other', '1755344844_68a06fcc76cd4_practicalweek15.pdf', 'active', '2025-08-16 11:47:24', 'Malitha Tishamal', 'admin'),
(234, 0, 'PUID Module Details', 11, 'Notes', '1755345213_68a0713d5b942_HNDIT2052PUIDModuleDetails.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(235, 0, 'Lesson 1', 11, 'Notes', '1755345213_68a0713d5be34_Lecture1_Introduction.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(236, 0, 'Lesson 3', 11, 'Notes', '1755345213_68a0713d5c34c_Lecture3_EvolvingTechnologiesforRichInteraction.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(237, 0, 'Lesson 4', 11, 'Notes', '1755345213_68a0713d5c73d_Lecture4_Interactionmodellinganddesign.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(238, 0, 'Lesson 5', 11, 'Notes', '1755345213_68a0713d61c57_Lecture5_DesignPrinciples-Typography.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(239, 0, 'Lesson 6', 11, 'Notes', '1755345213_68a0713d87422_Lecture6_DesignPrinciples-Colortheory.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(240, 0, 'Lesson 7', 11, 'Notes', '1755345213_68a0713da4895_Lecture7_PACTAnalysis.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(241, 0, 'Lesson 8', 11, 'Notes', '1755345213_68a0713da9130_Lecture8_Theprocessofhumancentredinteractivesystemsdesign.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(242, 0, 'Lesson 9', 11, 'Notes', '1755345213_68a0713db3b4a_Lecture9_UsabilityAccessibility.pdf', 'active', '2025-08-16 11:53:33', 'Malitha Tishamal', 'admin'),
(243, 0, 'Lesson 10', 11, 'Notes', '1755345213_68a0713db96aa_Lecture10_ProcessofGUIdesign.pdf', 'active', '2025-08-16 11:53:34', 'Malitha Tishamal', 'admin'),
(244, 0, 'Lesson 11', 11, 'Notes', '1755345214_68a0713e18035_Lecture11_TaskAnalysis.pdf', 'active', '2025-08-16 11:53:34', 'Malitha Tishamal', 'admin'),
(245, 0, 'Lesson 12', 11, 'Notes', '1755345214_68a0713e1d283_Lecture12_DevelopingEffectivePrototypeInterfaces.pdf', 'active', '2025-08-16 11:53:34', 'Malitha Tishamal', 'admin'),
(246, 0, 'Lesson 13', 11, 'Notes', '1755345214_68a0713e2349b_Lecture13_Toolsforprototyping.pdf', 'active', '2025-08-16 11:53:34', 'Malitha Tishamal', 'admin'),
(247, 0, 'Lesson 14', 11, 'Notes', '1755345214_68a0713e2bd7a_Lecture14_Developingaworkingprototype.pdf', 'active', '2025-08-16 11:53:34', 'Malitha Tishamal', 'admin'),
(248, 0, 'Lesson 15', 11, 'Notes', '1755345214_68a0713e30ef4_Lecture15_GeneralissuesinUserinterfacedesigningandnewtrendsinUID.pdf', 'active', '2025-08-16 11:53:34', 'Malitha Tishamal', 'admin'),
(249, 0, 'Week 1', 16, 'Notes', '1755571997_68a3e71df3f91_Week1.pptx', 'active', '2025-08-19 02:53:18', 'Malitha Tishamal', 'admin'),
(250, 0, 'Week 2', 16, 'Notes', '1755679841_68a58c61d7a8b_Week2.pptx', 'active', '2025-08-20 08:50:41', 'Malitha Tishamal', 'admin'),
(251, 0, 'Week 3', 16, 'Notes', '1755679841_68a58c61e43eb_Week3.pptx', 'active', '2025-08-20 08:50:41', 'Malitha Tishamal', 'admin'),
(252, 0, 'Week 4', 16, 'Notes', '1755679841_68a58c61ebf2b_Week4.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(253, 0, 'Week 5', 16, 'Notes', '1755679842_68a58c6203484_Week5.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(254, 0, 'Week 6', 16, 'Notes', '1755679842_68a58c620ccc1_Week6.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(255, 0, 'Week 7', 16, 'Notes', '1755679842_68a58c621ba22_Week7.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(256, 0, 'Week 7 Extra', 16, 'Notes', '1755679842_68a58c6226777_Week7-Extra.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(257, 0, 'Week 8', 16, 'Notes', '1755679842_68a58c6231398_Week8.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(258, 0, 'Week 9', 16, 'Notes', '1755679842_68a58c6242977_Week9.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(259, 0, 'Week 10', 16, 'Notes', '1755679842_68a58c624a192_Week10.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(260, 0, 'Week 11', 16, 'Notes', '1755679842_68a58c6269a7d_Week11.pptx', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(261, 0, 'Week1 Introduction.pdf', 16, 'Notes', '1755679842_68a58c627af06_Week1-Introduction.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(262, 0, 'Operator PRecedence', 16, 'Notes', '1755679842_68a58c628c799_OperatorPRecedence.png', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(263, 0, 'Week 2-Practical', 16, 'Other', '1755679842_68a58c629196a_Week2-Practical.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(264, 0, 'Week 3-Practical', 16, 'Other', '1755679842_68a58c629a304_Week3-Practical.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(265, 0, 'Week 4-Practical', 16, 'Other', '1755679842_68a58c62a2aac_Week4-Practical.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(266, 0, 'Week 5-Practical', 16, 'Other', '1755679842_68a58c62ab3d8_Week5-Practical.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(267, 0, 'Week 5.2-Practical', 16, 'Other', '1755679842_68a58c62b5851_Week5.2-Practical.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(268, 0, 'Week 10-Practical', 16, 'Other', '1755679842_68a58c62bf49b_Week10-Practical.pdf', 'active', '2025-08-20 08:50:42', 'Malitha Tishamal', 'admin'),
(269, 0, 'PHP Video', 16, 'Notes', '1755679842_68a58c62c758d_PHP-Video_.mp4', 'active', '2025-08-20 08:50:43', 'Malitha Tishamal', 'admin'),
(270, 0, 'Week 1 Intro', 9, 'Notes', '1755681564_68a5931c74cef_intro-week12023.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(271, 0, 'Week 1.2', 9, 'Notes', '1755681564_68a5931c7522a_week1-2-2023.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(272, 0, 'Week 2    software development lifecycle', 9, 'Notes', '1755681564_68a5931c75808_softwaredevelopmentlifecycle-week2.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(273, 0, 'Week 3 methodology', 9, 'Notes', '1755681564_68a5931c75e1e_methodology-week3.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(274, 0, 'Week 4  feasibility', 9, 'Notes', '1755681564_68a5931c7634c_feasibility-week4.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(275, 0, 'Week 5 requerment identification', 9, 'Notes', '1755681564_68a5931c83f76_requermentidentification-week5.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(276, 0, 'Week 6   Stakeholders', 9, 'Notes', '1755681564_68a5931c9a4f6_Stakeholders.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(277, 0, 'Week 7  Analysis Activities', 9, 'Notes', '1755681564_68a5931c9f147_AnalysisActivities.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(278, 0, 'Week 9  Requirement modeling', 9, 'Notes', '1755681564_68a5931cb39c3_Requirementmodeling-week9.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(279, 0, 'Week 10  data modeling', 9, 'Notes', '1755681564_68a5931cc53ab_datamodeling.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(280, 0, 'Week 11  database', 9, 'Notes', '1755681564_68a5931cd8f04_database-week11.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(281, 0, 'Case studies', 9, 'Other', '1755681564_68a5931ce45c3_Casestudies.docx', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(282, 0, 'Case studies 2', 9, 'Other', '1755681564_68a5931ce49e3_casestudies.docx', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(283, 0, 'Week 12 Activity Diagrams', 9, 'Notes', '1755681564_68a5931ce4da9_Activitydiagram.pdf', 'active', '2025-08-20 09:19:24', 'Malitha Tishamal', 'admin'),
(284, 0, 'Week 13 System Implementation', 9, 'Notes', '1755681564_68a5931cf1d67_implementation.pdf', 'active', '2025-08-20 09:19:25', 'Malitha Tishamal', 'admin'),
(285, 0, 'Week 13.2  maintenance', 9, 'Notes', '1755681565_68a5931d08419_maintenance.pdf', 'active', '2025-08-20 09:19:25', 'Malitha Tishamal', 'admin'),
(286, 0, 'System Vision Document', 9, 'Other', '1755681565_68a5931d0fc38_SystemVisionDocument.pdf', 'active', '2025-08-20 09:19:25', 'Malitha Tishamal', 'admin'),
(306, 0, 'Week 1 - 1.1 History of Operating Systems', 19, 'Notes', '1755800047_68a761ef3470b_1.1HistoryofOperatingSystems.pdf', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(288, 0, 'Week 01 Overview of Database System', 18, 'Notes', '1755798534_68a75c069b787_DBMS_W1.pptx', 'active', '2025-08-21 17:48:54', 'Malitha Tishamal', 'admin'),
(289, 0, 'Week 2,3 Data Model', 18, 'Notes', '1755798744_68a75cd81a266_TheRelationalDataModel.pptx', 'active', '2025-08-21 17:52:24', 'Malitha Tishamal', 'admin'),
(290, 0, 'Week 2,3 Lab 1', 18, 'Other', '1755798744_68a75cd81a84f_SQL-MySQLlab1.pptx', 'active', '2025-08-21 17:52:24', 'Malitha Tishamal', 'admin'),
(291, 0, 'Week 2,3 data model 2', 18, 'Notes', '1755798744_68a75cd81abe5_datamodels.pptx', 'active', '2025-08-21 17:52:24', 'Malitha Tishamal', 'admin'),
(292, 0, 'Week 2,3 relational model', 18, 'Notes', '1755798744_68a75cd81b27b_relationalModel.pptx', 'active', '2025-08-21 17:52:24', 'Malitha Tishamal', 'admin'),
(293, 0, 'Week 4 L3- ERD', 18, 'Notes', '1755799038_68a75dfe455ce_erd1.ppt', 'active', '2025-08-21 17:57:18', 'Malitha Tishamal', 'admin'),
(294, 0, 'Week 4 ERD Tutorial 1', 18, 'Other', '1755799038_68a75dfe45c85_T1-DatabaseManagementSystem.docx', 'active', '2025-08-21 17:57:18', 'Malitha Tishamal', 'admin'),
(295, 0, 'Week 4 database design', 18, 'Notes', '1755799038_68a75dfe46083_databsedesign.pptx', 'active', '2025-08-21 17:57:18', 'Malitha Tishamal', 'admin'),
(296, 0, 'Week 5 L4- ERD part 2', 18, 'Notes', '1755799038_68a75dfe4670b_erd2.PPT', 'active', '2025-08-21 17:57:18', 'Malitha Tishamal', 'admin'),
(297, 0, 'Week 5 Tutorial 02 -ERD', 18, 'Other', '1755799038_68a75dfe4709a_DatabaseManagementSystem-T2.docx', 'active', '2025-08-21 17:57:18', 'Malitha Tishamal', 'admin'),
(298, 0, 'Week 6 Relational Mapping: ERD to Relational Schema', 18, 'Notes', '1755799038_68a75dfe474da_RelationalDatabaseDesignbyER-andEERR-to-RelationalMapping.ppt', 'active', '2025-08-21 17:57:18', 'Malitha Tishamal', 'admin'),
(299, 0, 'Week 8,9 Normalization 1', 18, 'Notes', '1755799270_68a75ee60d38d_Normalization0.pptx', 'active', '2025-08-21 18:01:09', 'Malitha Tishamal', 'admin'),
(300, 0, 'Week 8,9  Normalization 2', 18, 'Notes', '1755799270_68a75ee60dad1_Normalization1.pptx', 'active', '2025-08-21 18:01:09', 'Malitha Tishamal', 'admin'),
(301, 0, 'Week 10 SQL 1', 18, 'Notes', '1755799270_68a75ee60e158_SQLI.ppt', 'active', '2025-08-21 18:01:09', 'Malitha Tishamal', 'admin'),
(302, 0, 'Week 10 SQL 2', 18, 'Notes', '1755799270_68a75ee60e6ad_SQLII.ppt', 'active', '2025-08-21 18:01:09', 'Malitha Tishamal', 'admin'),
(303, 0, 'Week 11 SQL 3', 18, 'Notes', '1755799270_68a75ee60efaa_SQLIII.ppt', 'active', '2025-08-21 18:01:09', 'Malitha Tishamal', 'admin'),
(304, 0, 'Week 12 Security', 18, 'Notes', '1755799270_68a75ee60f51d_DataBaseSecurity.pptx', 'active', '2025-08-21 18:01:09', 'Malitha Tishamal', 'admin'),
(305, 0, 'Week 13', 18, 'Other', '1755799270_68a75ee60fb69_revisiontobatabase.docx', 'active', '2025-08-21 18:01:09', 'Malitha Tishamal', 'admin'),
(307, 0, 'Week 1 - 1.2 Types of Operating Systems', 19, 'Notes', '1755800047_68a761ef35298_1.2TypesofOperatingSystems.pdf', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(308, 0, 'Week 2 - Processes', 19, 'Notes', '1755800047_68a761ef3a952_SLIATELMSOSPROCESSES-Week02.pdf', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(309, 0, 'Week 3 - Threads', 19, 'Notes', '1755800047_68a761ef4f275_Threads.pdf', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(310, 0, 'Week 4 - OS Process Synchronization -newFile', 19, 'Notes', '1755800047_68a761ef87d53_4.Week4-OSProcessSynchronization-new.pptx', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(311, 0, 'Week 5 - OS Process Scheduling (new)', 19, 'Notes', '1755800047_68a761ef93fb7_5.Week5-OSProcessSchedulingmodified-v2new.pptx', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(312, 0, 'Week 6.1 - Deadlocks', 19, 'Notes', '1755800047_68a761efacf0c_Week6-OSDeadlocks-v1.pptx', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(313, 0, 'Week 6.2 - Detection and Prevention (extra reference)', 19, 'Notes', '1755800047_68a761efb8d46_DeadlockDetectionPrevention.pdf', 'active', '2025-08-21 18:14:07', 'Malitha Tishamal', 'admin'),
(314, 0, 'Week 7.1 Memory Management', 19, 'Notes', '1755800994_68a765a28cdd1_Week07-MemeryManagement.pptx', 'active', '2025-08-21 18:29:54', 'Malitha Tishamal', 'admin'),
(315, 0, 'Week 7.2 Memory Management (Q&A)', 19, 'Notes', '1755800994_68a765a29f3b9_MemoryManagementQA_2.pdf', 'active', '2025-08-21 18:29:54', 'Malitha Tishamal', 'admin'),
(316, 0, 'Week 8. Virtual Memory', 19, 'Notes', '1755800994_68a765a2a15ab_Week8-VirtualMemory.pptx', 'active', '2025-08-21 18:29:54', 'Malitha Tishamal', 'admin'),
(317, 0, 'Week 9 File Systems', 19, 'Notes', '1755800994_68a765a2bc83b_Week9-FileSystems.pptx', 'active', '2025-08-21 18:29:54', 'Malitha Tishamal', 'admin'),
(318, 0, 'Week 10.1 Input - Output management and Disk scheduling', 19, 'Notes', '1755800994_68a765a2d2053_10.Input-OutputmanagementandDiskscheduling.pptx', 'active', '2025-08-21 18:29:55', 'Malitha Tishamal', 'admin'),
(319, 0, 'Week 10. 2 RAID (additional reading)', 19, 'Notes', '1755800995_68a765a30eb2a_10.2RAIDadditionalreading.pptx', 'active', '2025-08-21 18:29:55', 'Malitha Tishamal', 'admin'),
(320, 0, 'Week 11. Unix, Linux and Android', 19, 'Notes', '1755800995_68a765a31b5eb_11.UnixLinuxandAndroid.pptx', 'active', '2025-08-21 18:29:55', 'Malitha Tishamal', 'admin'),
(321, 0, 'Week 11 Linux Security', 19, 'Notes', '1755800995_68a765a3456fb_3.LinuxSecurity.docx', 'active', '2025-08-21 18:29:55', 'Malitha Tishamal', 'admin'),
(322, 0, 'Week 11 Linux File Security', 19, 'Notes', '1755800995_68a765a34a3e9_LinuxFileSecurity.docx', 'active', '2025-08-21 18:29:55', 'Malitha Tishamal', 'admin'),
(323, 0, 'Week 11 Basic File Management Commands in Linx', 19, 'Notes', '1755800995_68a765a34da52_1.BasicFileManagementCommandsinLinux.docx', 'active', '2025-08-21 18:29:55', 'Malitha Tishamal', 'admin'),
(324, 0, 'Week 1 Introduction to Computer Security', 20, 'Notes', '1755802124_68a76a0cd8ff3_L1-Cs-Intro.pptx', 'active', '2025-08-21 18:48:44', 'Malitha Tishamal', 'admin'),
(325, 0, 'Week 1 OSI Security Architecture', 20, 'Notes', '1755802124_68a76a0ce441c_CS-L1-OSIArc.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin');
INSERT INTO `tuition_files` (`id`, `user_id`, `title`, `subject_id`, `category`, `filename`, `status`, `uploaded_at`, `uploaded_by_name`, `uploaded_by_role`) VALUES
(326, 0, 'Week 2 Introduction to Cryptography', 20, 'Notes', '1755802125_68a76a0d0ac94_L2-CS.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(327, 0, 'Week 3 Authentication', 20, 'Notes', '1755802125_68a76a0d11253_CS-L3_2.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(328, 0, 'Week 4 Principals of Cryptography', 20, 'Notes', '1755802125_68a76a0d1ad81_week4.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(329, 0, 'Week 4 Public Key Infrastructure and Digital Signatures', 20, 'Notes', '1755802125_68a76a0d31e6f_L4-A.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(330, 0, 'Week 5 Authentication', 20, 'Notes', '1755802125_68a76a0d3d4ff_L5.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(331, 0, 'Week 6  Access Control', 20, 'Notes', '1755802125_68a76a0d76571_CS-L6.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(332, 0, 'Week 7 Malicious Software', 20, 'Notes', '1755802125_68a76a0d938db_CS-L7malacious.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(333, 0, 'Week 8 Network Security', 20, 'Notes', '1755802125_68a76a0d94650_CS10NETWOKSecurity.ppt', 'active', '2025-08-21 18:48:45', 'Malitha Tishamal', 'admin'),
(334, 0, 'Week 9 IT Security Management and Risk Assessment', 20, 'Notes', '1755802125_68a76a0da71bd_CS-L1112.ppt', 'active', '2025-08-21 18:48:46', 'Malitha Tishamal', 'admin'),
(335, 0, 'Week 10 Web Security', 20, 'Notes', '1755802126_68a76a0e88279_CS-13Webse.pptx', 'active', '2025-08-21 18:48:46', 'Malitha Tishamal', 'admin'),
(336, 0, 'Week 11 Electronic Mail Security', 20, 'Notes', '1755802126_68a76a0e88802_CS-14Email.ppt', 'active', '2025-08-21 18:48:46', 'Malitha Tishamal', 'admin'),
(383, 0, 'Week 1', 21, 'Notes', '1755842427_68a8077b49d78_week1.pptx', 'active', '2025-08-22 06:00:27', 'Malitha Tishamal', 'admin'),
(384, 0, 'Week 2', 21, 'Notes', '1755842662_68a8086648eff_week2.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(385, 0, 'Week 3', 21, 'Notes', '1755842662_68a8086649332_week3.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(386, 0, 'Week 4', 21, 'Notes', '1755842662_68a80866496e2_week4.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(387, 0, 'Week 5', 21, 'Notes', '1755842662_68a8086649b28_week5.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(388, 0, 'Week 6', 21, 'Notes', '1755842662_68a8086649fbd_week6.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(389, 0, 'Week 7', 21, 'Notes', '1755842662_68a808664a396_week7.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(390, 0, 'Week 8', 21, 'Notes', '1755842662_68a808664a7a1_week8.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(391, 0, 'Week 9', 21, 'Notes', '1755842662_68a808664ab32_week9.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(392, 0, 'Week 10', 21, 'Notes', '1755842662_68a808664af59_week10.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(393, 0, 'Week 12', 21, 'Notes', '1755842662_68a808664b33e_week12.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(394, 0, 'Week 13', 21, 'Notes', '1755842662_68a808664b6d8_week13.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(395, 0, 'Week 14', 21, 'Notes', '1755842662_68a808664baae_week14.pptx', 'active', '2025-08-22 06:04:21', 'Malitha Tishamal', 'admin'),
(396, 0, 'Week 1', 21, 'Notes', '1755843024_68a809d05b606_week1.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(397, 0, 'Week 2', 21, 'Notes', '1755843024_68a809d05bb33_week2.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(398, 0, 'Week 3', 21, 'Notes', '1755843024_68a809d05c02c_week3.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(399, 0, 'Week 4', 21, 'Notes', '1755843024_68a809d05c581_week4.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(400, 0, 'Week 5', 21, 'Notes', '1755843024_68a809d05cafb_week5.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(401, 0, 'Week 6', 21, 'Notes', '1755843024_68a809d05d302_week6.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(402, 0, 'Week 7', 21, 'Notes', '1755843024_68a809d05d720_week7.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(403, 0, 'Week 8', 21, 'Notes', '1755843024_68a809d05db85_week8.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(404, 0, 'Week 9', 21, 'Lecturer Notes', '1755843024_68a809d05e1f0_week9.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(405, 0, 'Week 10', 21, 'Notes', '1755843024_68a809d05e8b6_week10.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(406, 0, 'Week 11', 21, 'Notes', '1755843024_68a809d05ee66_week11.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(407, 0, 'Week 12', 21, 'Notes', '1755843024_68a809d05f244_week12.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(408, 0, 'Week 13', 21, 'Notes', '1755843024_68a809d05f660_week13.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(409, 0, 'Week 14', 21, 'Notes', '1755843024_68a809d05fb26_week14_2.pdf', 'active', '2025-08-22 06:10:23', 'Malitha Tishamal', 'admin'),
(410, 0, 'Tutorial 1', 21, 'Other', '1755843378_68a80b32bd981_TUTORIAL1.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(411, 0, 'Tutorial 2', 21, 'Other', '1755843378_68a80b32bdd78_Tutorial2.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(412, 0, 'Tutorial 3', 21, 'Other', '1755843378_68a80b32be138_Tutorial3.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(413, 0, 'Tutorial 4', 21, 'Other', '1755843378_68a80b32be4a4_TUTORIAL4.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(414, 0, 'Tutorial 5', 21, 'Other', '1755843378_68a80b32be802_TUTORIAL5.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(415, 0, 'Tutorial 6', 21, 'Other', '1755843378_68a80b32beb6b_TUTORIAL6.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(416, 0, 'Tutorial 7', 21, 'Other', '1755843378_68a80b32beece_TUTORIAL7.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(417, 0, 'Tutorial 8', 21, 'Other', '1755843378_68a80b32bf235_TUTORIAL8.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(418, 0, 'Tutorial 9', 21, 'Other', '1755843378_68a80b32bf5b5_TUTORIAL9.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(419, 0, 'Tutorial 10', 21, 'Other', '1755843378_68a80b32bf8fb_TUTORIAL10.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(420, 0, 'Tutorial 11', 21, 'Other', '1755843378_68a80b32bfc9a_TUTORIAL11.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(421, 0, 'Tutorial 12', 21, 'Other', '1755843378_68a80b32c0029_TUTORIAL12.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(422, 0, 'Tutorial 13', 21, 'Other', '1755843378_68a80b32c037b_TUTORIAL13.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(423, 0, 'Tutorial 14', 21, 'Other', '1755843378_68a80b32c06ec_TUTORIAL14.docx', 'active', '2025-08-22 06:16:18', 'Malitha Tishamal', 'admin'),
(424, 0, 'Spearman Rank Correlation [Simply explained]', 21, 'Notes', '1755844106_68a80e0ac1b94_SpearmanRankCorrelationSimplyexplained_2.mp4', 'active', '2025-08-22 06:28:27', 'Malitha Tishamal', 'admin'),
(378, 0, 'proposal method', 5, 'Notes', '1755835583_68a7ecbfc55ae_proposalmethod.pdf', 'active', '2025-08-22 04:06:23', 'Malitha Tishamal', 'admin'),
(379, 0, 'proposal method', 5, 'Notes', '1755835583_68a7ecbfc6487_proposalmethod.docx', 'active', '2025-08-22 04:06:23', 'Malitha Tishamal', 'admin'),
(380, 0, 'Final Project Report (Individual)', 12, 'Notes', '1755836383_68a7efdf6f7ee_FinalProjectReportIndividual.pdf', 'active', '2025-08-22 04:19:43', 'Malitha Tishamal', 'admin'),
(381, 0, 'ICT Project (Group) - JUMAIL', 12, 'Notes', '1755836383_68a7efdf6fe54_ICTProjectGroup-JUMAIL.pdf', 'active', '2025-08-22 04:19:43', 'Malitha Tishamal', 'admin'),
(382, 0, 'IT project Supervisor sign sheet', 12, 'Notes', '1755836383_68a7efdf7037c_ITprojectSupervisorsignsheet.pdf', 'active', '2025-08-22 04:19:43', 'Malitha Tishamal', 'admin'),
(425, 0, 'Binomial Distribution EXPLAINED with Examples', 21, 'Notes', '1755844107_68a80e0bc48d3_BinomialDistributionEXPLAINEDwithExamples.mp4', 'active', '2025-08-22 06:28:29', 'Malitha Tishamal', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `referrer` text DEFAULT NULL,
  `current_url` text DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `accessed_at` datetime DEFAULT NULL,
  `device_vendor` varchar(100) DEFAULT NULL,
  `device_model` varchar(100) DEFAULT NULL,
  `orientation` varchar(50) DEFAULT NULL,
  `touch_support` varchar(10) DEFAULT NULL,
  `pixel_ratio` varchar(10) DEFAULT NULL,
  `connection_type` varchar(50) DEFAULT NULL,
  `viewport_size` varchar(50) DEFAULT NULL,
  `browser_version` varchar(50) DEFAULT NULL,
  `screen_resolution` varchar(50) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `battery_level` varchar(20) DEFAULT NULL,
  `online_status` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `ip_address`, `user_agent`, `device_type`, `os`, `browser`, `language`, `referrer`, `current_url`, `session_id`, `accessed_at`, `device_vendor`, `device_model`, `orientation`, `touch_support`, `pixel_ratio`, `connection_type`, `viewport_size`, `browser_version`, `screen_resolution`, `timezone`, `battery_level`, `online_status`, `latitude`, `longitude`) VALUES
(1, NULL, '101.2.191.75', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/index.php?i=1', '/guest_view.php?semester=Semester+I&subject=', '18f70c31a2217392645558d01ba6c5f7', '2025-07-10 22:46:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, '101.2.191.75', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+I&subject=6', '18f70c31a2217392645558d01ba6c5f7', '2025-07-10 22:47:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, '101.2.191.75', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=6', '/guest_view.php?semester=Semester+I&subject=', '18f70c31a2217392645558d01ba6c5f7', '2025-07-10 22:47:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, NULL, '101.2.191.75', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+I&subject=5', '18f70c31a2217392645558d01ba6c5f7', '2025-07-10 22:47:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, NULL, '64.233.173.35', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'Mobile', 'Linux', 'Chrome', 'Unknown', 'Direct', '/guest_view.php?semester=Semester+I&subject=6', 'e203a4dc3c4704d1e7d3d19b92908106', '2025-07-10 22:47:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, NULL, '66.102.6.129', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'Mobile', 'Linux', 'Chrome', 'en-US', 'Direct', '/guest_view.php?semester=Semester+I&subject=6', '0f627ce8f0708c70d1c824261fafd3e9', '2025-07-10 22:47:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, NULL, '66.102.6.128', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'Mobile', 'Linux', 'Chrome', 'en-US', 'Direct', '/guest_view.php?semester=Semester+I&subject=6', 'c8be0b581dedf3280b2f94e7e5e5e897', '2025-07-10 22:47:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, NULL, '101.2.191.75', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=5', '/guest_view.php?semester=Semester+I&subject=', '18f70c31a2217392645558d01ba6c5f7', '2025-07-10 22:47:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, NULL, '101.2.191.75', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+I&subject=1', '18f70c31a2217392645558d01ba6c5f7', '2025-07-10 22:47:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, NULL, '112.134.104.213', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php?msg=Guest%20session%20expired.%20Please%20login.', '/guest_view.php?semester=Semester+I&subject=', '84ceea5f56d9e4bb18c36045a4829a68', '2025-07-10 22:58:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, NULL, '175.157.191.233', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9,si;q=0.8', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'efb2beee9d83dc5c9748ed32d10f9c5a', '2025-07-11 02:02:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, NULL, '175.157.103.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php?i=1', '/guest_view.php?semester=Semester+I&subject=', '63573889306569adf8c1409ab09c2255', '2025-07-11 05:48:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, NULL, '45.121.90.253', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.6312.118 Mobile Safari/537.36 XiaoMi/MiuiBrowser/14.37.0-gn', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', '64bbad861e5de5ad2fa88609e380b706', '2025-07-11 05:49:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, NULL, '175.157.103.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', '63573889306569adf8c1409ab09c2255', '2025-07-11 05:49:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, NULL, '45.121.90.253', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.6312.118 Mobile Safari/537.36 XiaoMi/MiuiBrowser/14.37.0-gn', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', '64bbad861e5de5ad2fa88609e380b706', '2025-07-11 05:50:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, NULL, '45.121.90.253', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.6312.118 Mobile Safari/537.36 XiaoMi/MiuiBrowser/14.37.0-gn', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+II&subject=', '/guest_view.php?semester=Semester+III&subject=', '64bbad861e5de5ad2fa88609e380b706', '2025-07-11 05:50:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, NULL, '223.224.31.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.4 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'ca0a342468a71195ce0d8baedd273423', '2025-07-11 06:12:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, NULL, '223.224.31.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.4 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', 'ca0a342468a71195ce0d8baedd273423', '2025-07-11 06:13:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, NULL, '223.224.31.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.4 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+II&subject=', '/guest_view.php?semester=Semester+II&subject=8', 'ca0a342468a71195ce0d8baedd273423', '2025-07-11 06:13:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, NULL, '223.224.31.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.4 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', 'ca0a342468a71195ce0d8baedd273423', '2025-07-11 06:14:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, NULL, '223.224.31.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.4 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'ca0a342468a71195ce0d8baedd273423', '2025-07-11 06:14:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, NULL, '111.223.189.119', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/25.0 Chrome/121.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/index.php?i=1', '/guest_view.php?semester=Semester+I&subject=', 'ac003daf29382c090776ddd8c2d28ddb', '2025-07-11 15:40:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, NULL, '111.223.189.119', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/25.0 Chrome/121.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-GB,en-US;q=0.9,en;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', 'ac003daf29382c090776ddd8c2d28ddb', '2025-07-11 15:41:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, NULL, '43.250.243.254', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/index.php?i=1', '/guest_view.php?semester=Semester+I&subject=', 'a8134de541fc73ed7d27f31cba3ea3d0', '2025-07-12 03:23:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, NULL, '43.250.243.254', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', 'a8134de541fc73ed7d27f31cba3ea3d0', '2025-07-12 03:24:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, NULL, '43.250.243.254', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+II&subject=', '/guest_view.php?semester=Semester+II&subject=7', 'a8134de541fc73ed7d27f31cba3ea3d0', '2025-07-12 03:24:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, NULL, '43.250.243.254', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', 'a8134de541fc73ed7d27f31cba3ea3d0', '2025-07-12 03:24:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, NULL, '203.189.185.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php?i=1', '/guest_view.php?semester=Semester+I&subject=', '4abd6191c44c925fbc262fb432dd45cf', '2025-07-15 00:15:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, NULL, '203.189.185.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', '4abd6191c44c925fbc262fb432dd45cf', '2025-07-15 00:15:27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, NULL, '203.189.185.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+II&subject=', '/guest_view.php?semester=Semester+II&subject=7', '4abd6191c44c925fbc262fb432dd45cf', '2025-07-15 00:15:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, NULL, '203.189.185.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+II&subject=7', '/guest_view.php?semester=Semester+II&subject=', '4abd6191c44c925fbc262fb432dd45cf', '2025-07-15 00:15:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, NULL, '203.189.189.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/index.php?i=1', '/guest_view.php?semester=Semester+I&subject=', '2ff887dde0ebaac1c19906a41189f3ca', '2025-07-17 04:09:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, NULL, '203.189.189.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', '2ff887dde0ebaac1c19906a41189f3ca', '2025-07-17 04:09:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 12, '203.189.189.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', '2ff887dde0ebaac1c19906a41189f3ca', '2025-07-17 04:15:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 12, '203.189.189.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', '2ff887dde0ebaac1c19906a41189f3ca', '2025-07-17 04:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 12, '203.189.189.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.6 Mobile/15E148 Safari/604.1', 'Mobile', 'Mac OS X', 'Safari', 'en-GB,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+II&subject=', '2ff887dde0ebaac1c19906a41189f3ca', '2025-07-17 04:15:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, NULL, '45.121.88.39', 'Mozilla/5.0 (Linux; Android 12; V2061 Build/SP1A.210812.003; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.146 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/521.0.0.42.97;IABMV/1;]', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/?i=1', '/guest_view.php?semester=Semester+I&subject=', '708ace384ea3f07cd4a8e6bc84121a06', '2025-07-18 10:54:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, NULL, '45.121.88.39', 'Mozilla/5.0 (Linux; Android 12; V2061 Build/SP1A.210812.003; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.146 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/521.0.0.42.97;IABMV/1;]', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+I&subject=1', '708ace384ea3f07cd4a8e6bc84121a06', '2025-07-18 10:54:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, NULL, '112.134.110.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/?i=1', '/guest_view.php?semester=Semester+I&subject=', '18cd3921ac95c7183eddf77131eb2203', '2025-07-26 10:27:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, NULL, '112.134.110.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', '8df71a8ffd018fbbc6daa70050b1120c', '2025-08-16 00:24:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, NULL, '112.134.110.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php?msg=Guest%20session%20expired.%20Please%20login.', '/guest_view.php?semester=Semester+I&subject=', '8df71a8ffd018fbbc6daa70050b1120c', '2025-08-16 01:13:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, NULL, '112.134.110.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', '8df71a8ffd018fbbc6daa70050b1120c', '2025-08-16 01:13:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, NULL, '112.134.110.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'Direct', '/guest_view.php?semester=Semester+III&subject=', '8df71a8ffd018fbbc6daa70050b1120c', '2025-08-16 01:16:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, NULL, '112.134.110.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php?msg=Guest%20session%20expired.%20Please%20login.', '/guest_view.php?semester=Semester+I&subject=', '8df71a8ffd018fbbc6daa70050b1120c', '2025-08-16 03:24:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, NULL, '112.134.110.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php?msg=Guest%20session%20expired.%20Please%20login.', '/guest_view.php?semester=Semester+I&subject=', '8df71a8ffd018fbbc6daa70050b1120c', '2025-08-16 03:24:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, NULL, '112.134.110.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+I&subject=&i=1', 'acd5f4337f03813c6327453197828e81', '2025-08-16 03:28:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'cffe91843d0bce15915467892ecc61ab', '2025-08-24 00:44:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+I&subject=', 'cffe91843d0bce15915467892ecc61ab', '2025-08-24 00:45:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 10, '123.231.86.40', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9,si;q=0.8', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'b396cf808813482005357f06df1cdc10', '2025-08-24 12:59:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 10, '123.231.86.40', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9,si;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+IV&subject=', 'b396cf808813482005357f06df1cdc10', '2025-08-24 13:00:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 10, '123.231.86.40', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9,si;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+IV&subject=', '/guest_view.php?semester=Semester+III&subject=', 'b396cf808813482005357f06df1cdc10', '2025-08-24 13:00:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 10, '123.231.86.40', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9,si;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+III&subject=', '/guest_view.php?semester=Semester+III&subject=16', 'b396cf808813482005357f06df1cdc10', '2025-08-24 13:00:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, NULL, '123.231.86.40', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9,si;q=0.8', 'https://edulk.42web.io/index.php?msg=Session%20expired.%20Please%20login%20again.', '/guest_view.php?semester=Semester+I&subject=', 'b396cf808813482005357f06df1cdc10', '2025-08-24 13:52:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, NULL, '123.231.86.40', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', 'Mobile', 'Linux', 'Chrome', 'en-US,en;q=0.9,si;q=0.8', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+I&subject=1', 'b396cf808813482005357f06df1cdc10', '2025-08-24 13:54:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'Windows', 'Chrome', 'en-US', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 00:21:55', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', '139.0.0.0', '1536x864', 'Asia/Colombo', '59%', 'online', NULL, NULL),
(56, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'Windows', 'Chrome', 'en-US', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'f1e12d909b507565a7bb919563b0a0a5', '2025-08-25 00:24:42', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', '139.0.0.0', '1536x864', 'Asia/Colombo', '59%', 'online', 5.9768832, 80.5502976),
(57, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'Windows', 'Chrome', 'en-US', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'f1e12d909b507565a7bb919563b0a0a5', '2025-08-25 00:26:38', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', '139.0.0.0', '1536x864', 'Asia/Colombo', '59%', 'online', 5.9768832, 80.5502976),
(58, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'Windows', 'Chrome', 'en-US', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'f1e12d909b507565a7bb919563b0a0a5', '2025-08-25 00:27:04', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', '139.0.0.0', '1536x864', 'Asia/Colombo', '59%', 'online', 5.9768832, 80.5502976),
(59, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'Windows', 'Chrome', 'en-US', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', '9629884ca3066ec6b085b29f1424dd0a', '2025-08-25 00:27:57', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', '139.0.0.0', '1536x864', 'Asia/Colombo', '59%', 'online', NULL, NULL),
(60, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'Windows', 'Chrome', 'en-US', 'https://edulk.42web.io/index.php?msg=Guest%20session%20expired.%20Please%20login.', '/guest_view.php?semester=Semester+I&subject=', 'f1e12d909b507565a7bb919563b0a0a5', '2025-08-25 00:31:12', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', '139.0.0.0', '1536x864', 'Asia/Colombo', '59%', 'online', 5.9768832, 80.5502976),
(61, NULL, '112.134.105.31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'desktop', 'Windows', 'Chrome', 'en-US', 'https://edulk.42web.io/index.php?msg=Guest%20session%20expired.%20Please%20login.', '/guest_view.php?semester=Semester+I&subject=', 'f1e12d909b507565a7bb919563b0a0a5', '2025-08-25 00:31:43', 'unknown', 'unknown', 'landscape-primary', 'no', '0.83333337', '4g', '2304x1042', '139.0.0.0', '1536x864', 'Asia/Colombo', '59%', 'online', 5.9768832, 80.5502976),
(62, NULL, '175.157.17.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'desktop', 'Windows', 'Edge', 'en-US', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'cdb7644fb1fcbbf4ae4cd320aed1ba42', '2025-08-25 03:36:43', 'unknown', 'unknown', 'landscape-primary', 'no', '1.25', '4g', '1528x834', '139.0.0.0', '1536x960', 'Asia/Colombo', '42%', 'online', NULL, NULL),
(63, NULL, '175.157.17.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'cdb7644fb1fcbbf4ae4cd320aed1ba42', '2025-08-25 03:37:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, NULL, '175.157.17.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/guest_view.php?semester=Semester+I&subject=', '/guest_view.php?semester=Semester+III&subject=', 'cdb7644fb1fcbbf4ae4cd320aed1ba42', '2025-08-25 03:38:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, NULL, '175.157.17.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'Desktop', 'Windows 10', 'Chrome', 'en-US,en;q=0.9', 'https://edulk.42web.io/index.php', '/guest_view.php?semester=Semester+I&subject=', 'cdb7644fb1fcbbf4ae4cd320aed1ba42', '2025-08-25 03:38:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, NULL, '207.46.13.160', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'Unknown', 'Unknown OS', 'Chrome', 'Unknown', 'Direct', '/guest_view.php?subject=', '3c74e6b9a953f44251961bbfa565858a', '2025-08-29 20:55:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regno` (`regno`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `lectures_assignment`
--
ALTER TABLE `lectures_assignment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lecturer_id` (`lecturer_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `lectures_logs`
--
ALTER TABLE `lectures_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_attendance`
--
ALTER TABLE `meeting_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `meeting_chat`
--
ALTER TABLE `meeting_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_resources`
--
ALTER TABLE `meeting_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`);

--
-- Indexes for table `recordings`
--
ALTER TABLE `recordings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `recording_resources`
--
ALTER TABLE `recording_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recording_id` (`recording_id`);

--
-- Indexes for table `recording_student_plays`
--
ALTER TABLE `recording_student_plays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_play` (`recording_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `recording_student_plays_logs`
--
ALTER TABLE `recording_student_plays_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recording_id` (`recording_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `sadmins`
--
ALTER TABLE `sadmins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sadmin_logs`
--
ALTER TABLE `sadmin_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regno` (`regno`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `students_logs`
--
ALTER TABLE `students_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tuition_files`
--
ALTER TABLE `tuition_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subject` (`subject_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
