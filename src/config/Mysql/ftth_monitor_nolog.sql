-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: mysql
-- Thời gian đã tạo: Th4 05, 2026 lúc 11:18 PM
-- Phiên bản máy phục vụ: 8.0.45
-- Phiên bản PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ftth_monitor`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `alert_log`
--

CREATE TABLE `alert_log` (
  `id` bigint NOT NULL,
  `line_id` int NOT NULL,
  `alert_type` enum('down','up','warning','recovery') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notified` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `alert_log`
--

INSERT INTO `alert_log` (`id`, `line_id`, `alert_type`, `message`, `notified`, `created_at`) VALUES
(1, 2, 'recovery', 'Line \'VNPT Van Phuc Tang 1\' (192.168.10.30) recovered', 0, '2026-03-27 11:52:32'),
(2, 3, 'recovery', 'Line \'Hà Nội Centre \' (113.190.242.107) recovered', 0, '2026-03-27 12:52:30'),
(3, 6, 'down', 'Line \'Vũng Tàu Square\' (10.182.58.213) is DOWN', 0, '2026-03-27 13:01:09'),
(4, 7, 'down', 'Line \'Rox Center\' (222.252.98.117) is DOWN', 0, '2026-03-27 17:40:28'),
(5, 7, 'recovery', 'Line \'Rox Center\' (222.252.98.117) recovered', 0, '2026-03-27 17:40:43'),
(6, 2, 'down', 'Line \'Văn phòng \' (27.74.251.232) is DOWN', 0, '2026-03-27 18:06:06'),
(7, 2, 'recovery', 'Line \'Văn phòng \' (27.74.251.232) recovered', 0, '2026-03-27 18:06:21'),
(8, 2, 'down', 'Line \'Văn phòng \' (27.74.251.232) is DOWN', 0, '2026-03-27 18:35:38'),
(9, 2, 'recovery', 'Line \'Văn phòng \' (27.74.251.232) recovered', 0, '2026-03-27 18:35:54'),
(10, 2, 'down', 'Line \'Văn phòng \' (27.74.251.232) is DOWN', 0, '2026-03-27 19:41:37'),
(11, 2, 'recovery', 'Line \'Văn phòng \' (27.74.251.232) recovered', 0, '2026-03-27 19:41:54'),
(12, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-03-28 07:57:33'),
(13, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-03-28 08:01:01'),
(14, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-03-28 15:13:04'),
(15, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-03-28 15:13:20'),
(16, 22, 'down', 'Line \'Vincom Star city\' (222.252.16.32) is DOWN', 0, '2026-03-28 23:55:10'),
(17, 24, 'down', 'Line \'Landmark 72\' (113.190.253.248) is DOWN', 0, '2026-03-29 00:02:10'),
(18, 22, 'recovery', 'Line \'Vincom Star city\' (222.252.16.32) recovered', 0, '2026-03-29 09:30:20'),
(19, 22, 'down', 'Line \'Vincom Star city\' (222.252.16.32) is DOWN', 0, '2026-03-29 09:31:01'),
(20, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-03-29 10:59:20'),
(21, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-03-29 11:02:55'),
(22, 5, 'down', 'Line \'Văn phòng HCM\' (115.79.142.51) is DOWN', 0, '2026-03-29 14:26:18'),
(23, 5, 'recovery', 'Line \'Văn phòng HCM\' (115.79.142.51) recovered', 0, '2026-03-29 14:26:59'),
(24, 30, 'down', 'Line \'Phan Xích Long\' (14.224.167.134) is DOWN', 0, '2026-03-30 07:59:28'),
(25, 30, 'recovery', 'Line \'Phan Xích Long\' (14.224.167.134) recovered', 0, '2026-03-30 08:01:33'),
(26, 24, 'recovery', 'Line \'Landmark 72\' (113.190.253.248) recovered', 0, '2026-03-30 08:07:19'),
(27, 24, 'down', 'Line \'Landmark 72\' (113.190.253.248) is DOWN', 0, '2026-03-30 08:08:01'),
(28, 24, 'recovery', 'Line \'Landmark 72\' (113.190.253.248) recovered', 0, '2026-03-30 08:12:45'),
(29, 24, 'down', 'Line \'Landmark 72\' (113.190.253.248) is DOWN', 0, '2026-03-30 08:13:30'),
(30, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-03-30 14:00:29'),
(31, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-03-30 14:04:10'),
(32, 2, 'down', 'Line \'Văn phòng HCM\' (27.74.251.232) is DOWN', 0, '2026-03-30 14:50:59'),
(33, 2, 'recovery', 'Line \'Văn phòng HCM\' (27.74.251.232) recovered', 0, '2026-03-30 14:51:40'),
(34, 9, 'down', 'Line \'Vincom Plaza Vinh\' (14.241.69.164) is DOWN', 0, '2026-03-30 15:32:59'),
(35, 9, 'recovery', 'Line \'Vincom Plaza Vinh\' (14.241.69.164) recovered', 0, '2026-03-30 15:33:43'),
(36, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-03-30 20:35:29'),
(37, 7, 'down', 'Line \'Rox Center\' (222.252.98.117) is DOWN', 0, '2026-03-31 06:18:52'),
(38, 7, 'recovery', 'Line \'Rox Center\' (222.252.98.117) recovered', 0, '2026-03-31 06:32:52'),
(39, 30, 'down', 'Line \'Phan Xích Long\' (14.224.167.134) is DOWN', 0, '2026-03-31 08:58:36'),
(40, 30, 'recovery', 'Line \'Phan Xích Long\' (14.224.167.134) recovered', 0, '2026-03-31 09:00:55'),
(41, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-03-31 09:45:41'),
(42, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-03-31 13:42:06'),
(43, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-03-31 13:42:48'),
(44, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-03-31 13:52:11'),
(45, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-03-31 13:52:53'),
(46, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-03-31 15:11:26'),
(47, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-03-31 15:12:08'),
(48, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-03-31 17:01:46'),
(49, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-03-31 17:06:15'),
(50, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-03-31 20:29:54'),
(51, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-03-31 20:30:39'),
(52, 13, 'down', 'Line \'Vinwonder Phú Quốc\' (14.241.196.254) is DOWN', 0, '2026-03-31 21:07:27'),
(53, 13, 'recovery', 'Line \'Vinwonder Phú Quốc\' (14.241.196.254) recovered', 0, '2026-03-31 21:13:18'),
(54, 5, 'down', 'Line \'Văn phòng HCM\' (115.79.142.51) is DOWN', 0, '2026-03-31 22:36:18'),
(55, 5, 'recovery', 'Line \'Văn phòng HCM\' (115.79.142.51) recovered', 0, '2026-03-31 22:37:00'),
(56, 16, 'down', 'Line \'Saigon Mia\' (113.161.32.168) is DOWN', 0, '2026-04-01 04:00:09'),
(57, 19, 'down', 'Line \'Lotte Nam Saigon\' (113.161.78.249) is DOWN', 0, '2026-04-01 04:00:13'),
(58, 10, 'down', 'Line \'Văn Lang Uni\' (113.161.43.186) is DOWN', 0, '2026-04-01 04:00:32'),
(59, 13, 'down', 'Line \'Vinwonder Phú Quốc\' (14.241.196.254) is DOWN', 0, '2026-04-01 04:00:35'),
(60, 26, 'down', 'Line \'Glory Heights \' (113.176.64.203) is DOWN', 0, '2026-04-01 04:00:41'),
(61, 25, 'down', 'Line \'Genki Sushi Crescent Mall\' (222.255.192.3) is DOWN', 0, '2026-04-01 04:00:47'),
(62, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 04:01:02'),
(63, 18, 'down', 'Line \'Vạn Hạnh Mall\' (14.161.23.80) is DOWN', 0, '2026-04-01 04:01:11'),
(64, 18, 'recovery', 'Line \'Vạn Hạnh Mall\' (14.161.23.80) recovered', 0, '2026-04-01 04:02:06'),
(65, 16, 'recovery', 'Line \'Saigon Mia\' (113.161.32.168) recovered', 0, '2026-04-01 04:02:08'),
(66, 19, 'recovery', 'Line \'Lotte Nam Saigon\' (113.161.78.249) recovered', 0, '2026-04-01 04:02:09'),
(67, 10, 'recovery', 'Line \'Văn Lang Uni\' (113.161.43.186) recovered', 0, '2026-04-01 04:02:25'),
(68, 13, 'recovery', 'Line \'Vinwonder Phú Quốc\' (14.241.196.254) recovered', 0, '2026-04-01 04:02:26'),
(69, 26, 'recovery', 'Line \'Glory Heights \' (113.176.64.203) recovered', 0, '2026-04-01 04:02:30'),
(70, 25, 'recovery', 'Line \'Genki Sushi Crescent Mall\' (222.255.192.3) recovered', 0, '2026-04-01 04:02:35'),
(71, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 04:02:48'),
(72, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 08:04:59'),
(73, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 08:05:41'),
(74, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 08:44:46'),
(75, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 08:45:29'),
(76, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 09:31:23'),
(77, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 09:32:07'),
(78, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 09:44:22'),
(79, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 09:45:04'),
(80, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 09:48:44'),
(81, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 09:49:26'),
(82, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 10:01:28'),
(83, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 10:02:10'),
(84, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 10:04:19'),
(85, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 10:05:05'),
(86, 15, 'down', 'Line \'Ocean Park S2\' (222.252.0.7) is DOWN', 0, '2026-04-01 10:27:03'),
(87, 15, 'recovery', 'Line \'Ocean Park S2\' (222.252.0.7) recovered', 0, '2026-04-01 10:27:48'),
(88, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 10:42:29'),
(89, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 10:47:12'),
(90, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 10:49:29'),
(91, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 10:53:16'),
(92, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 10:59:13'),
(93, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 11:03:03'),
(94, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 11:45:11'),
(95, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 11:45:53'),
(96, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 12:29:01'),
(97, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 13:06:52'),
(98, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 13:07:43'),
(99, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 13:29:21'),
(100, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 13:39:24'),
(101, 23, 'down', 'Line \'Bắc Ninh\' (14.252.29.17) is DOWN', 0, '2026-04-01 14:08:54'),
(102, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 14:16:04'),
(103, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 14:24:21'),
(104, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 14:25:05'),
(105, 23, 'recovery', 'Line \'Bắc Ninh\' (14.252.29.17) recovered', 0, '2026-04-01 14:27:06'),
(106, 20, 'down', 'Line \'Phan Xích Long\' (113.161.77.248) is DOWN', 0, '2026-04-01 14:31:37'),
(107, 20, 'recovery', 'Line \'Phan Xích Long\' (113.161.77.248) recovered', 0, '2026-04-01 14:38:15'),
(108, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 14:53:15'),
(109, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 14:53:57'),
(110, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-01 15:08:52'),
(111, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-01 15:09:36'),
(112, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-04-01 20:02:50'),
(113, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-04-01 20:07:15'),
(114, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-04-02 04:00:10'),
(115, 27, 'down', 'Line \'Fansipan San May\' (14.241.67.179) is DOWN', 0, '2026-04-02 04:00:13'),
(116, 23, 'down', 'Line \'Bắc Ninh\' (14.252.29.17) is DOWN', 0, '2026-04-02 04:00:17'),
(117, 3, 'down', 'Line \'Hà Nội Centre \' (113.190.242.107) is DOWN', 0, '2026-04-02 04:00:20'),
(118, 11, 'down', 'Line \'Vincom Hạ Long \' (113.160.112.88) is DOWN', 0, '2026-04-02 04:00:23'),
(119, 14, 'down', 'Line \'Golden Field\' (222.252.20.211) is DOWN', 0, '2026-04-02 04:00:52'),
(120, 17, 'down', 'Line \'Lotte WestLake\' (14.248.83.156) is DOWN', 0, '2026-04-02 04:00:55'),
(121, 21, 'down', 'Line \'Nguyễn Hữu Huân\' (14.160.32.198) is DOWN', 0, '2026-04-02 04:00:58'),
(122, 12, 'down', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) is DOWN', 0, '2026-04-02 04:01:01'),
(123, 15, 'down', 'Line \'Ocean Park S2\' (222.252.0.7) is DOWN', 0, '2026-04-02 04:01:04'),
(124, 9, 'down', 'Line \'Vincom Plaza Vinh\' (14.241.69.164) is DOWN', 0, '2026-04-02 04:01:07'),
(125, 7, 'down', 'Line \'Rox Center\' (222.252.98.117) is DOWN', 0, '2026-04-02 04:01:12'),
(126, 27, 'recovery', 'Line \'Fansipan San May\' (14.241.67.179) recovered', 0, '2026-04-02 04:02:22'),
(127, 23, 'recovery', 'Line \'Bắc Ninh\' (14.252.29.17) recovered', 0, '2026-04-02 04:02:24'),
(128, 3, 'recovery', 'Line \'Hà Nội Centre \' (113.190.242.107) recovered', 0, '2026-04-02 04:02:25'),
(129, 11, 'recovery', 'Line \'Vincom Hạ Long \' (113.160.112.88) recovered', 0, '2026-04-02 04:02:26'),
(130, 14, 'recovery', 'Line \'Golden Field\' (222.252.20.211) recovered', 0, '2026-04-02 04:02:54'),
(131, 17, 'recovery', 'Line \'Lotte WestLake\' (14.248.83.156) recovered', 0, '2026-04-02 04:02:55'),
(132, 21, 'recovery', 'Line \'Nguyễn Hữu Huân\' (14.160.32.198) recovered', 0, '2026-04-02 04:02:56'),
(133, 12, 'recovery', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) recovered', 0, '2026-04-02 04:02:57'),
(134, 15, 'recovery', 'Line \'Ocean Park S2\' (222.252.0.7) recovered', 0, '2026-04-02 04:02:58'),
(135, 9, 'recovery', 'Line \'Vincom Plaza Vinh\' (14.241.69.164) recovered', 0, '2026-04-02 04:02:59'),
(136, 7, 'recovery', 'Line \'Rox Center\' (222.252.98.117) recovered', 0, '2026-04-02 04:03:03'),
(137, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-04-02 04:03:04'),
(138, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-02 08:38:36'),
(139, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-02 08:39:18'),
(140, 34, 'down', 'Line \'Sensota Hải Phòng\' (14.241.117.14) is DOWN', 0, '2026-04-02 09:07:51'),
(141, 2, 'down', 'Line \'Văn phòng HCM\' (27.74.251.232) is DOWN', 0, '2026-04-02 10:59:51'),
(142, 2, 'recovery', 'Line \'Văn phòng HCM\' (27.74.251.232) recovered', 0, '2026-04-02 11:00:44'),
(143, 24, 'recovery', 'Line \'Landmark 72\' (113.190.253.248) recovered', 0, '2026-04-02 11:34:14'),
(144, 22, 'recovery', 'Line \'Vincom Star city\' (222.252.16.32) recovered', 0, '2026-04-02 11:40:01'),
(145, 37, 'down', 'Line \'Aeon Ha Dong\' (123.24.132.230) is DOWN', 0, '2026-04-02 11:40:08'),
(146, 37, 'recovery', 'Line \'Aeon Ha Dong\' (123.24.132.230) recovered', 0, '2026-04-02 11:43:25'),
(147, 37, 'down', 'Line \'Aeon Ha Dong\' (123.24.132.230) is DOWN', 0, '2026-04-02 12:16:52'),
(148, 37, 'recovery', 'Line \'Aeon Ha Dong\' (123.24.132.230) recovered', 0, '2026-04-02 12:20:59'),
(149, 34, 'recovery', 'Line \'Sensota Hải Phòng\' (14.241.117.14) recovered', 0, '2026-04-02 13:30:59'),
(150, 19, 'down', 'Line \'Lotte Nam Saigon\' (113.161.78.249) is DOWN', 0, '2026-04-02 14:11:42'),
(151, 19, 'recovery', 'Line \'Lotte Nam Saigon\' (113.161.78.249) recovered', 0, '2026-04-02 14:22:47'),
(152, 19, 'down', 'Line \'Lotte Nam Saigon\' (113.161.78.249) is DOWN', 0, '2026-04-02 14:23:34'),
(153, 19, 'recovery', 'Line \'Lotte Nam Saigon\' (113.161.78.249) recovered', 0, '2026-04-02 14:26:41'),
(154, 19, 'down', 'Line \'Lotte Nam Saigon\' (113.161.78.249) is DOWN', 0, '2026-04-02 14:27:28'),
(155, 37, 'down', 'Line \'Aeon Ha Dong\' (123.24.132.230) is DOWN', 0, '2026-04-02 14:37:03'),
(156, 37, 'recovery', 'Line \'Aeon Ha Dong\' (123.24.132.230) recovered', 0, '2026-04-02 14:38:42'),
(157, 34, 'down', 'Line \'Sensota Hải Phòng\' (14.241.117.14) is DOWN', 0, '2026-04-02 14:41:19'),
(158, 19, 'recovery', 'Line \'Lotte Nam Saigon\' (113.161.78.249) recovered', 0, '2026-04-02 14:46:40'),
(159, 34, 'recovery', 'Line \'Sensota Hải Phòng\' (14.241.117.14) recovered', 0, '2026-04-02 18:13:55'),
(160, 2, 'down', 'Line \'Văn phòng HCM\' (27.74.251.232) is DOWN', 0, '2026-04-02 19:32:58'),
(161, 2, 'recovery', 'Line \'Văn phòng HCM\' (27.74.251.232) recovered', 0, '2026-04-02 19:33:40'),
(162, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-04-02 23:04:13'),
(163, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-04-02 23:08:42'),
(164, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-03 13:35:33'),
(165, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-03 13:36:15'),
(166, 28, 'down', 'Line \'FTTH Viettel\' (115.77.189.31) is DOWN', 0, '2026-04-03 14:35:03'),
(167, 28, 'recovery', 'Line \'FTTH Viettel\' (115.77.189.31) recovered', 0, '2026-04-03 14:35:45'),
(168, 12, 'down', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) is DOWN', 0, '2026-04-03 21:42:42'),
(169, 12, 'recovery', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) recovered', 0, '2026-04-03 21:49:18'),
(170, 12, 'down', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) is DOWN', 0, '2026-04-03 22:14:20'),
(171, 12, 'recovery', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) recovered', 0, '2026-04-03 22:30:48'),
(172, 12, 'down', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) is DOWN', 0, '2026-04-03 22:38:43'),
(173, 12, 'recovery', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) recovered', 0, '2026-04-03 22:43:09'),
(174, 12, 'down', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) is DOWN', 0, '2026-04-04 01:34:24'),
(175, 18, 'down', 'Line \'Vạn Hạnh Mall\' (14.161.23.80) is DOWN', 0, '2026-04-04 01:46:53'),
(176, 16, 'down', 'Line \'Saigon Mia\' (113.161.32.168) is DOWN', 0, '2026-04-04 01:46:56'),
(177, 18, 'recovery', 'Line \'Vạn Hạnh Mall\' (14.161.23.80) recovered', 0, '2026-04-04 01:48:31'),
(178, 16, 'recovery', 'Line \'Saigon Mia\' (113.161.32.168) recovered', 0, '2026-04-04 01:48:33'),
(179, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-04-04 02:05:26'),
(180, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-04-04 02:10:03'),
(181, 12, 'recovery', 'Line \'Noi Bai Domestic Gate 12\' (123.27.3.165) recovered', 0, '2026-04-04 04:26:41'),
(182, 1, 'down', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) is DOWN', 0, '2026-04-05 05:07:18'),
(183, 1, 'recovery', 'Line \'Lotte Đà Nẵng \' (14.176.232.129) recovered', 0, '2026-04-05 05:10:58'),
(184, 2, 'down', 'Line \'Văn phòng HCM\' (27.74.251.232) is DOWN', 0, '2026-04-05 21:22:49'),
(185, 2, 'recovery', 'Line \'Văn phòng HCM\' (27.74.251.232) recovered', 0, '2026-04-05 21:23:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`id`, `name`, `contact_person`, `phone`, `email`, `notes`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Cong ty VNPT', 'Nguyen Van A', '0901234567', 'contact@vnpt.vn', '', 0, '2026-03-27 09:41:40', '2026-03-27 13:09:14'),
(2, 'STARBUCKS', 'Bùi Minh Nhật', '', 'nhat.bm@coffee-concepts.com.vn', '', 1, '2026-03-27 12:13:14', '2026-03-29 00:25:29'),
(3, 'Genki Sushi', 'Bùi Minh Nhật', '0907543135', 'nhat.bm@coffee-concepts.com.vn', 'IT', 1, '2026-03-29 00:02:37', '2026-03-31 09:44:27'),
(4, 'Việt Phú (Movi)', 'Trần Minh Thoại', '0961796791', 'thoai.tm@movi.vn', '', 1, '2026-03-29 00:13:47', '2026-03-29 00:17:04'),
(5, 'Pizza Hut', 'Ong Đức Nghĩa', '0908979491', 'nghia.ong@jrgvn.com', 'IT Manager', 1, '2026-03-29 00:26:06', '2026-03-31 09:39:04'),
(6, 'TSARSI', 'Nguyễn Thanh Hiền (Ms)', '0908633774', 'hien.nguyen@tsarsi.com', 'HR & OFFICE MANAGER', 1, '2026-03-29 00:26:24', '2026-03-31 09:28:08'),
(7, '4PS', 'Vo Ngoc Tuyet Hong (Ms)', '0934818605', 'hong.vo@pizza4ps.com', 'Senior Admin Executive | GA Team', 1, '2026-03-29 00:27:18', '2026-03-31 09:31:43'),
(8, 'Tửu Lầu', 'Huỳnh Thùy (Ms)', '0915998955', 'nhahangtuulau@gmail.com', 'Admin', 1, '2026-03-29 00:27:29', '2026-03-31 09:41:02'),
(9, 'Bộ tư lênh 86', '', '', '', '', 1, '2026-03-30 09:05:40', '2026-03-30 09:05:40'),
(10, 'Kiến tạo niềm vui (CF LỀ)', 'Nguyễn Thị Hằng (Ms)', '0938672233', '', 'Kế toán', 1, '2026-03-31 09:52:30', '2026-03-31 09:53:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_branches`
--

CREATE TABLE `customer_branches` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `branch_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_branches`
--

INSERT INTO `customer_branches` (`id`, `customer_id`, `branch_name`, `address`, `contact_person`, `phone`, `active`, `created_at`) VALUES
(1, 1, 'Chi nhanh Quan 1', '123 Nguyen Hue, Q1, HCM', '', NULL, 1, '2026-03-27 09:43:15'),
(2, 2, 'Lotte Đà Nẵng ', '6 Nại Nam, Hoà Cường Bắc, Hải Châu, Đà Nẵng', NULL, '02363227878 (VNPT)', 1, '2026-03-27 13:08:38'),
(3, 2, 'Văn phòng ', 'Tầng 15, Cao ốc Văn phòng Phượng Long 2, Số 16, Đường Nguyễn Trường Tộ, Phường Xóm Chiếu, TP Hồ Chí Minh', NULL, NULL, 1, '2026-03-27 13:08:38'),
(4, 2, 'Hà Nội Centre ', 'Gian hàng Số L1 A09 - 175 Nguyễn Thái Học, Cát Linh, Ba Đình, Hà Nội, Việt Nam', NULL, '02432000582 (VNPT)', 1, '2026-03-27 13:08:38'),
(5, 2, 'Vũng Tàu Square', 'Quảng trường Tam Thắng, Đường Thùy Vân, Phường Vũng Tàu , TP. Hồ Chí Minh', NULL, '02844550051 (VTC)', 1, '2026-03-27 13:08:38'),
(6, 2, 'Rox Center', 'T1-10, Số 136 Hồ Tùng Mậu, Phường Phú Diễn, Thành phố Hà Nội', NULL, '02432013855 (VNPT)', 1, '2026-03-27 13:08:38'),
(7, 2, 'Vincom Plaza Vinh', 'Lô L1-09, L109A tầng L1 của TTTM Vincom, tòa nhà Vincom Plaza Vinh, Chung cư Quang Trung, Đường Quang Trung(Tây), Phường Thành Vinh, Tỉnh Nghệ An', NULL, '02388769001 (VNPT)', 1, '2026-03-27 13:08:38'),
(9, 2, 'Văn Lang Uni', '69/68 Đường Đặng Thuỳ Trâm, Phường 13, Bình Thạnh, Thành phố Hồ Chí Minh (Địa chỉ sau sáp nhập: Trường Đại học Văn Lang, 80/68, Đường Dương Quảng Hàm, Phường An Nhơn, Thành phố Hồ Chí Minh', NULL, '02836221535 (VNPT)', 1, '2026-03-28 23:21:04'),
(10, 2, 'Vincom Hạ Long', 'Lô L1-17 và Lô L1-17A, Tầng L1, Trung tâm thương mại Vincom Plaza Hạ Long, Khu cột Đồng Hồ, Tòa Nhà Hạ Long Center, Phường Hồng Gai, Tỉnh Quảng Ninh', NULL, '02033511152 (VNPT)', 1, '2026-03-28 23:23:19'),
(11, 2, 'Noi Bai Domestic Gate 12', '2-A4A5-ADAE, Tầng 2, Cánh A, Khu vực cách ly Nhà ga T1, Sân bay Nội Bài, Ga T1, Xã Nội Bài, Thành phố Hà Nội', NULL, NULL, 1, '2026-03-28 23:25:13'),
(12, 2, 'Vinwonder Phú Quốc', 'CTXD số QT-VW6-01 và QT-VW6-02 thuộc Tổ hợp dự án đảo Phú Quốc, Khu Bãi Dài, Đặc khu Phú Quốc, Tỉnh An Giang', NULL, '02973510017 (VNPT)', 1, '2026-03-28 23:27:09'),
(13, 2, 'Golden Field', 'Tầng 01+02, tòa nhà Golden Field, Khu ĐTM Mỹ Đình I, Phường Từ Liêm, Thành phố Hà Nội', NULL, '02432020317 (VNPT)', 1, '2026-03-28 23:29:11'),
(14, 2, 'Ocean Park S2', 'Gian hàng 01S05 và 01S5A, Tầng 1, Ô đất B2-CT01 Tòa nhà U26 (S2.03) Dự án khu đô thị Gia Lâm – Vinhomes Ocean Park, Xã Gia Lâm, Thành phố Hà Nội', NULL, '02432020273 (VNPT)', 1, '2026-03-28 23:31:49'),
(15, 2, 'Saigon Mia', 'L1-04, Tầng 1, Đường số 9A, Chung cư Cụm III, IV - Khu dân cư Trung Sơn, Xã Bình Hưng, Thành phố Hồ Chí Minh', NULL, '02835101019 (VNPT)', 1, '2026-03-28 23:33:56'),
(16, 2, 'Lotte WestLake', 'Lô 167, tầng 1, TTTM Lotte Mall Hà Nội, Số 272 đường Võ Chí Công, phường Tây Hồ, Thành phố Hà Nội, Việt Nam', NULL, '02432020253 (VNPT)', 1, '2026-03-28 23:36:30'),
(17, 2, 'Vạn Hạnh Mall', 'Phòng 1-09A, Tầng 01, Tòa nhà Trung tâm thương mại Vạn Hạnh, Số 11 Đường Sư Vạn Hạnh, Phường Hòa Hưng, Thành phố Hồ Chí Minh, Việt Nam', NULL, '02836220328 (VNPT)', 1, '2026-03-28 23:38:22'),
(18, 2, 'Lotte Nam Saigon', '1F76 +1F77, Tầng 1, Lotte Mart Nam Sài Gòn, 469 Nguyễn Hữu Thọ, Phường Tân Hưng, Thành phố Hồ Chí Minh', NULL, '02837331128 (VNPT)', 1, '2026-03-28 23:39:24'),
(19, 2, 'Văn phòng HCM', 'Tầng 15, Cao ốc Văn phòng Phượng Long 2, Số 16, Đường Nguyễn Trường Tộ, Phường Xóm Chiếu, TP Hồ Chí Minh', NULL, NULL, 1, '2026-03-28 23:40:43'),
(20, 2, 'Phan Xích Long', '214-216 Phan Xích Long, Phường Cầu Kiệu, Thành phố Hồ Chí Minh, Việt Nam', NULL, NULL, 1, '2026-03-28 23:42:19'),
(21, 2, 'Nguyễn Hữu Huân', 'Số 32 – 34 phố Nguyễn Hữu Huân, Phường Hoàn Kiếm, Thành phố Hà Nội', NULL, '02432013862 (VNPT)', 1, '2026-03-28 23:43:34'),
(22, 2, 'Vincom Star city', 'Lô L1- 08B, tầng L1, Trung tâm thương mại Vincom Center Trần Duy Hưng, Khu đô thị Đông Nam, Phố Trần Duy Hưng, Phường Thanh Xuân, Thành phố Hà Nội', NULL, '02432020337 (VNPT)', 1, '2026-03-28 23:52:53'),
(23, 2, 'Bắc Ninh', 'L1-12A và L1-12C, Ngã 6 mặt đường Lý Thái Tổ và đường Trần Hưng Đạo, phường Kinh Bắc, Tỉnh Bắc Ninh', NULL, '02223555035 (VNPT)', 1, '2026-03-29 00:00:01'),
(24, 2, 'Landmark 72', 'Diện tích số T0101-T0103 Tầng 1, Keangnam Hanoi Landmark Tower, Khu E6, Khu Đô thị mới Cầu Giấy, Phường Yên Hòa, Thành phố Hà Nội', NULL, '02432020397 (VNPT)', 1, '2026-03-29 00:01:58'),
(25, 3, 'Genki Sushi Crescent Mall', 'Unit 4F-22 4th Floor, TTTM Crescent Mall, 101 Tôn Dật Tiên, P. Tân Mỹ, TP. HCM', NULL, '02838721105 (VNPT)', 1, '2026-03-29 00:03:43'),
(26, 2, 'Glory Heights', 'GH6-01S03-S04, Vinhome Grand Park - S1.S2.S3.S4.S5, Đường Nguyễn Xiễn, Phường Long Bình, Thành phố Hồ Chí Minh', NULL, '02836209063 (VNPT)', 1, '2026-03-29 00:05:32'),
(27, 2, 'Fansipan San May', 'Ga đến Cáp treo Khu du lịch Sun World Fansipan Legend tại địa chỉ: Số nhà 089B, đường Nguyễn Chí Thanh, phường Sa Pa, tỉnh Lào Cai, Việt Nam', NULL, NULL, 1, '2026-03-29 00:06:34'),
(28, 4, 'FTTH Viettel', 'Tầng 4, Toà nhà K&M 33 Ung Văn Khiêm, Phường 25, Quận Bình Thạnh, Tp.Hồ Chí Minh', NULL, NULL, 1, '2026-03-29 00:18:43'),
(29, 7, 'Lý Chính Thắng', '127 Lý Chính Thắng, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh', NULL, NULL, 1, '2026-03-29 00:29:45'),
(30, 5, 'Phan Xích Long', 'Số 93 đường Phan Xích Long, Phường 2, Quận Phú Nhuận, TP. HCM', NULL, NULL, 1, '2026-03-29 00:33:02'),
(31, 6, 'Văn Phòng', 'Tầng 20, toà nhà vietinbank - 93-95 Hàm Nghi, Nguyễn Thái Bình, Q1, TP. Hồ Chí Minh', NULL, NULL, 1, '2026-03-29 00:34:16'),
(32, 8, 'Himisub Bar', '154 Bis Trần Quang Khải, Phường Tân Định, TP.HCM', NULL, NULL, 1, '2026-03-29 00:36:11'),
(33, 9, 'VNPT 01', 'Số 4, Phố Tôn Thất Thiệp, Phường Ba Đình, Thành phố Hà Nội, Việt Nam', NULL, NULL, 1, '2026-03-30 09:09:24'),
(34, 2, 'Sensota Hải Phòng', 'Sensota Sky Park, số TMDV 1.5 thuộc Dự án Chung cư 28 tầng tại Lô CT4, Khu đô thị ven sông Lạch Tray, Đường Võ Nguyên Giáp, Phường Lê Chân, Tp Hải Phòng', NULL, '02253260035 (VNPT)', 1, '2026-04-02 09:06:46'),
(35, 2, 'VNG Campus', 'Z06 đường số 13, KCX Tân Thuận, phường Tân Thuận, TP.HCM', NULL, '02839212112 (VNPT)', 1, '2026-04-02 09:10:43'),
(36, 2, 'Quang Trung', '647 Quang trung, phường Thông Tây Hội, TP. Hồ Chí Minh', NULL, '02836362607 (VNPT)', 1, '2026-04-02 09:12:09'),
(37, 2, 'Aeon Ha Dong', 'T160-161, Tầng 1, Trung tâm thương mại Aeon Mall Hà Đông, Phường Dương Nội, Thành phố Hà Nội', NULL, '02432013805 (VNPT)', 1, '2026-04-02 09:13:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ftth_lines`
--

CREATE TABLE `ftth_lines` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isp_account` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_method` enum('ping','http','tcp','snmp') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ping',
  `customer_id` int DEFAULT NULL,
  `branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regional_contact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `on_net` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `contract_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `olt_info` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'OLT/PON port info',
  `status` enum('up','down','warning','paused','unknown') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `last_check` datetime DEFAULT NULL,
  `last_up` datetime DEFAULT NULL,
  `last_down` datetime DEFAULT NULL,
  `uptime_percent` decimal(5,2) DEFAULT '100.00',
  `avg_response_time` decimal(10,2) DEFAULT NULL COMMENT 'ms',
  `notify_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ftth_lines`
--

INSERT INTO `ftth_lines` (`id`, `name`, `store_code`, `ip_address`, `provider`, `isp_account`, `check_method`, `customer_id`, `branch_name`, `branch_address`, `phone`, `regional_contact`, `on_net`, `expiry_date`, `notes`, `contract_id`, `olt_info`, `status`, `last_check`, `last_up`, `last_down`, `uptime_percent`, `avg_response_time`, `notify_enabled`, `active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Lotte Đà Nẵng ', '16482', '14.176.232.129', 'VNPT', 'dng0058000016074', 'ping', 2, 'Lotte Đà Nẵng ', '6 Nại Nam, Hoà Cường Bắc, Hải Châu, Đà Nẵng', '02363227878 (VNPT)', 'A Bình - 0914158979', '2025-11-10', '2027-01-09', '', '', '', 'up', '2026-04-05 23:17:41', '2026-04-05 23:17:41', '2026-04-05 05:10:15', 100.00, 26.03, 1, 1, 1, '2026-03-27 09:06:13', '2026-04-05 23:17:41'),
(2, 'Văn phòng HCM', '4292', '27.74.251.232', 'Viettel', 'hcm_gftth_vtccnpnttmtvvts1', 'ping', 2, 'Văn phòng HCM', 'Tầng 15, Cao ốc Văn phòng Phượng Long 2, Số 16, Đường Nguyễn Trường Tộ, Phường Xóm Chiếu, TP Hồ Chí Minh', '', 'Anh Qui - 0868834270', '2025-11-01', '2026-12-31', '', '', '', 'up', '2026-04-05 23:17:27', '2026-04-05 23:17:27', '2026-04-05 21:22:49', 100.00, 11.70, 1, 1, 1, '2026-03-27 09:06:13', '2026-04-05 23:17:27'),
(3, 'Hà Nội Centre ', '17084', '113.190.242.107', 'VNPT', 'hni0058000033447', 'ping', 2, 'Hà Nội Centre', 'Gian hàng Số L1 A09 - 175 Nguyễn Thái Học, Cát Linh, Ba Đình, Hà Nội, Việt Nam', '02432000582 (VNPT)', '0947536555 - A Toản ', '2025-10-20', '2026-12-19', '', '', '', 'up', '2026-04-05 23:17:48', '2026-04-05 23:17:48', '2026-04-02 04:01:26', 100.00, 37.07, 1, 1, 1, '2026-03-27 09:06:13', '2026-04-05 23:17:48'),
(4, 'Văn phòng HCM', '4292', '113.161.34.209', 'VNPT', 'hcm0058000076572', 'ping', 2, 'Văn phòng HCM', 'Tầng 15, Cao ốc Văn phòng Phượng Long 2, Số 16, Đường Nguyễn Trường Tộ, Phường Xóm Chiếu, TP Hồ Chí Minh', '', '0886748595-A Tài - ', '2025-01-01', '2026-12-31', '', '', '', 'up', '2026-04-05 23:17:28', '2026-04-05 23:17:28', '2026-03-27 03:21:26', 100.00, 8.58, 1, 1, 1, '2026-03-27 09:44:18', '2026-04-05 23:17:28'),
(5, 'Văn phòng HCM', '4292', '115.79.142.51', 'Viettel', 'hcm_gftth_vtccnpnttmtvvts2', 'ping', 2, 'Văn phòng HCM', 'Tầng 15, Cao ốc Văn phòng Phượng Long 2, Số 16, Đường Nguyễn Trường Tộ, Phường Xóm Chiếu, TP Hồ Chí Minh', '', 'Anh Qui - 0868834270', '2025-01-01', '2026-12-31', '', '', '', 'up', '2026-04-05 23:17:29', '2026-04-05 23:17:29', '2026-03-31 22:36:18', 100.00, 11.56, 1, 1, 1, '2026-03-27 12:56:48', '2026-04-05 23:17:29'),
(6, 'Vũng Tàu Square', '17051', '10.182.58.213', 'Viettel', 'hcm_gftth_ vtccnpnttmtvvts5', 'ping', 2, 'Vũng Tàu Square', 'Quảng trường Tam Thắng, Đường Thùy Vân, Phường Vũng Tàu , TP. Hồ Chí Minh', '02844550051 (VTC)', 'A Phát - 0396863635', '2025-11-24', '2027-01-23', '', '', '', 'down', '2026-03-29 00:28:06', NULL, '2026-03-29 00:28:06', 100.00, 3017.99, 1, 0, 1, '2026-03-27 13:00:56', '2026-03-29 00:28:06'),
(7, 'Rox Center', '16474', '222.252.98.117', 'VNPT', 'hni0058000107036', 'ping', 2, 'Rox Center', 'T1-10, Số 136 Hồ Tùng Mậu, Phường Phú Diễn, Thành phố Hà Nội', '02432013855 (VNPT)', 'A Điệp - 0913224172', '2025-11-28', '2027-01-27', '', '', '', 'up', '2026-04-05 23:17:40', '2026-04-05 23:17:40', '2026-04-02 04:02:18', 100.00, 30.84, 1, 1, 1, '2026-03-27 13:02:19', '2026-04-05 23:17:40'),
(8, 'Vincom Plaza Vinh', '16441', '14.241.69.164', 'VNPT', 'nan0058000041375', 'ping', 2, 'Vincom Plaza Vinh', 'Lô L1-09, L109A tầng L1 của TTTM Vincom, tòa nhà Vincom Plaza Vinh, Chung cư Quang Trung, Đường Quang Trung(Tây), Phường Thành Vinh, Tỉnh Nghệ An', '02388769001 (VNPT)', 'A Thành - 0912077119', '2025-12-01', '2027-01-31', '', '', '', 'up', '2026-03-28 23:19:21', '2026-03-28 23:19:21', NULL, 100.00, 34.93, 1, 0, 1, '2026-03-27 13:04:57', '2026-03-28 23:19:24'),
(9, 'Vincom Plaza Vinh', '16441', '14.241.69.164', 'VNPT', 'nan0058000041375', 'ping', 2, 'Vincom Plaza Vinh', 'Lô L1-09, L109A tầng L1 của TTTM Vincom, tòa nhà Vincom Plaza Vinh, Chung cư Quang Trung, Đường Quang Trung(Tây), Phường Thành Vinh, Tỉnh Nghệ An', '02388769001 (VNPT)', 'A Thành - 0912077119', '2025-12-01', '2027-01-31', '', '', '', 'up', '2026-04-05 23:17:37', '2026-04-05 23:17:37', '2026-04-02 04:02:13', 100.00, 41.45, 1, 1, 1, '2026-03-28 23:16:57', '2026-04-05 23:17:37'),
(10, 'Văn Lang Uni', '16445', '113.161.43.186', 'VNPT', 'hcm0058000228599', 'ping', 2, 'Văn Lang Uni', '69/68 Đường Đặng Thuỳ Trâm, Phường 13, Bình Thạnh, Thành phố Hồ Chí Minh (Địa chỉ sau sáp nhập: Trường Đại học Văn Lang, 80/68, Đường Dương Quảng Hàm, Phường An Nhơn, Thành phố Hồ Chí Minh', '02836221535 (VNPT)', 'A Phương 0915113532', '2025-12-21', '2027-02-20', '', '', '', 'up', '2026-04-05 23:17:38', '2026-04-05 23:17:38', '2026-04-01 04:01:35', 100.00, 8.07, 1, 1, 1, '2026-03-28 23:21:04', '2026-04-05 23:17:38'),
(11, 'Vincom Hạ Long ', '17117', '113.160.112.88', 'VNPT', 'qnh0058000020275', 'ping', 2, 'Vincom Hạ Long', 'Lô L1-17 và Lô L1-17A, Tầng L1, Trung tâm thương mại Vincom Plaza Hạ Long, Khu cột Đồng Hồ, Tòa Nhà Hạ Long Center, Phường Hồng Gai, Tỉnh Quảng Ninh', '02033511152 (VNPT)', 'A Đông - 0918961685', '2025-12-01', '2027-01-31', '', '', '', 'up', '2026-04-05 23:17:50', '2026-04-05 23:17:50', '2026-04-02 04:01:29', 100.00, 34.70, 1, 1, 1, '2026-03-28 23:23:19', '2026-04-05 23:17:50'),
(12, 'Noi Bai Domestic Gate 12', '16433', '123.27.3.165', 'VNPT', 'hni0058000110783', 'ping', 2, 'Noi Bai Domestic Gate 12', '2-A4A5-ADAE, Tầng 2, Cánh A, Khu vực cách ly Nhà ga T1, Sân bay Nội Bài, Ga T1, Xã Nội Bài, Thành phố Hà Nội', '', 'A Tiến - 0913522520', '2025-12-01', '2027-01-31', '', '', '', 'up', '2026-04-05 23:17:34', '2026-04-05 23:17:34', '2026-04-04 04:25:59', 100.00, 29.36, 1, 1, 1, '2026-03-28 23:25:13', '2026-04-05 23:17:34'),
(13, 'Vinwonder Phú Quốc', '16471', '14.241.196.254', 'VNPT', 'agg0058000068137', 'ping', 2, 'Vinwonder Phú Quốc', 'CTXD số QT-VW6-01 và QT-VW6-02 thuộc Tổ hợp dự án đảo Phú Quốc, Khu Bãi Dài, Đặc khu Phú Quốc, Tỉnh An Giang', '02973510017 (VNPT)', 'A Vụ - 0815534222', '2025-12-10', '2027-02-09', '', '', '', 'up', '2026-04-05 23:17:39', '2026-04-05 23:17:39', '2026-04-01 04:01:38', 100.00, 14.71, 1, 1, 1, '2026-03-28 23:27:09', '2026-04-05 23:17:39'),
(14, 'Golden Field', '16412', '222.252.20.211', 'VNPT', 'fbr004dxr', 'ping', 2, 'Golden Field', 'Tầng 01+02, tòa nhà Golden Field, Khu ĐTM Mỹ Đình I, Phường Từ Liêm, Thành phố Hà Nội', '02432020317 (VNPT)', 'A Toàn: 0946942299', '2006-02-04', '2027-04-03', '', '', '', 'up', '2026-04-05 23:17:31', '2026-04-05 23:17:31', '2026-04-02 04:01:57', 100.00, 32.43, 1, 1, 1, '2026-03-28 23:29:11', '2026-04-05 23:17:31'),
(15, 'Ocean Park S2', '16435', '222.252.0.7', 'VNPT', 'fbr00486o', 'ping', 2, 'Ocean Park S2', 'Gian hàng 01S05 và 01S5A, Tầng 1, Ô đất B2-CT01 Tòa nhà U26 (S2.03) Dự án khu đô thị Gia Lâm – Vinhomes Ocean Park, Xã Gia Lâm, Thành phố Hà Nội ', '02432020273 (VNPT)', 'A Tài: 0949486886', '2026-02-03', '2027-04-02', '', '', '', 'up', '2026-04-05 23:17:36', '2026-04-05 23:17:36', '2026-04-02 04:02:10', 100.00, 33.91, 1, 1, 1, '2026-03-28 23:31:50', '2026-04-05 23:17:36'),
(16, 'Saigon Mia', '4240', '113.161.32.168', 'VNPT', 'hcm0058000236040', 'ping', 2, 'Saigon Mia', 'L1-04, Tầng 1, Đường số 9A, Chung cư Cụm III, IV - Khu dân cư Trung Sơn, Xã Bình Hưng, Thành phố Hồ Chí Minh', '02835101019 (VNPT)', 'A Tuấn - 0914232905', '2025-12-24', '2027-02-23', '', '', '', 'up', '2026-04-05 23:17:23', '2026-04-05 23:17:23', '2026-04-04 01:47:48', 100.00, 10.65, 1, 1, 1, '2026-03-28 23:33:56', '2026-04-05 23:17:23'),
(17, 'Lotte WestLake', '16413', '14.248.83.156', 'VNPT', 'fbr002s3l', 'ping', 2, 'Lotte WestLake', 'Lô 167, tầng 1, TTTM Lotte Mall Hà Nội, Số 272 đường Võ Chí Công, phường Tây Hồ, Thành phố Hà Nội, Việt Nam', '02432020253 (VNPT)', 'A Tùng 0917701057 ', '2026-01-20', '2027-03-19', '', '', '', 'up', '2026-04-05 23:17:32', '2026-04-05 23:17:32', '2026-04-02 04:02:00', 100.00, 32.94, 1, 1, 1, '2026-03-28 23:36:30', '2026-04-05 23:17:32'),
(18, 'Vạn Hạnh Mall', '4223', '14.161.23.80', 'VNPT', 'fbr001wxc', 'ping', 2, 'Vạn Hạnh Mall', 'Phòng 1-09A, Tầng 01, Tòa nhà Trung tâm thương mại Vạn Hạnh, Số 11 Đường Sư Vạn Hạnh, Phường Hòa Hưng, Thành phố Hồ Chí Minh, Việt Nam', '02836220328 (VNPT)', 'A Thành : 0941172000', '2026-01-15', '2027-03-14', '', '', '', 'up', '2026-04-05 23:17:22', '2026-04-05 23:17:22', '2026-04-04 01:47:45', 100.00, 9.84, 1, 1, 1, '2026-03-28 23:38:22', '2026-04-05 23:17:22'),
(19, 'Lotte Nam Saigon', '4242', '113.161.78.249', 'VNPT', 'fbr001wwh', 'ping', 2, 'Lotte Nam Saigon', '1F76 +1F77, Tầng 1, Lotte Mart Nam Sài Gòn, 469 Nguyễn Hữu Thọ, Phường Tân Hưng, Thành phố Hồ Chí Minh', '02837331128 (VNPT)', 'A Hán Trung 0835335592', '2026-01-15', '2027-03-14', '', '', '', 'up', '2026-04-05 23:17:24', '2026-04-05 23:17:24', '2026-04-02 14:45:49', 100.00, 11.24, 1, 1, 1, '2026-03-28 23:39:25', '2026-04-05 23:17:24'),
(20, 'Phan Xích Long', '4206', '113.161.77.248', 'VNPT', 'fbr002bjv', 'ping', 2, 'Phan Xích Long', '214-216 Phan Xích Long, Phường Cầu Kiệu, Thành phố Hồ Chí Minh, Việt Nam', '', 'A PHÚ: 0911749229', '2026-01-17', '2027-03-16', '', '', '', 'up', '2026-04-05 23:18:03', '2026-04-05 23:18:03', '2026-04-01 14:37:33', 100.00, 8.10, 1, 1, 1, '2026-03-28 23:42:19', '2026-04-05 23:18:03'),
(21, 'Nguyễn Hữu Huân', '16414', '14.160.32.198', 'VNPT', 'fbr002l4z', 'ping', 2, 'Nguyễn Hữu Huân', 'Số 32 – 34 phố Nguyễn Hữu Huân, Phường Hoàn Kiếm, Thành phố Hà Nội ', '02432013862 (VNPT)', 'A Trường 0914661972', '2026-01-19', '2027-03-18', '', '', '', 'up', '2026-04-05 23:17:33', '2026-04-05 23:17:33', '2026-04-02 04:02:04', 100.00, 30.78, 1, 1, 1, '2026-03-28 23:43:34', '2026-04-05 23:17:33'),
(22, 'Vincom Star city', '4288', '222.252.16.32', 'VNPT', 'fbr003383', 'ping', 2, 'Vincom Star city', 'Lô L1- 08B, tầng L1, Trung tâm thương mại Vincom Center Trần Duy Hưng, Khu đô thị Đông Nam, Phố Trần Duy Hưng, Phường Thanh Xuân, Thành phố Hà Nội', '02432020337 (VNPT)', 'A Thuận 0889456363', '2026-01-22', '2027-03-21', '', '', '', 'up', '2026-04-05 23:17:26', '2026-04-05 23:17:26', '2026-04-02 11:39:14', 100.00, 32.16, 1, 1, 1, '2026-03-28 23:52:53', '2026-04-05 23:17:26'),
(23, 'Bắc Ninh', '17076', '14.252.29.17', 'VNPT', 'fbr002wcb', 'ping', 2, 'Bắc Ninh', 'L1-12A và L1-12C, Ngã 6 mặt đường Lý Thái Tổ và đường Trần Hưng Đạo, phường Kinh Bắc, Tỉnh Bắc Ninh', '02223555035 (VNPT)', 'A Thuận 0889456363', '2026-01-23', '2027-03-22', '', '', '', 'up', '2026-04-05 23:17:47', '2026-04-05 23:17:47', '2026-04-02 04:01:23', 100.00, 35.04, 1, 1, 1, '2026-03-29 00:00:01', '2026-04-05 23:17:47'),
(24, 'Landmark 72', '4281', '113.190.253.248', 'VNPT', 'fbr003qe8', 'ping', 2, 'Landmark 72', 'Diện tích số T0101-T0103 Tầng 1, Keangnam Hanoi Landmark Tower, Khu E6, Khu Đô thị mới Cầu Giấy, Phường Yên Hòa, Thành phố Hà Nội', '02432020397 (VNPT)', 'A Tân 0915085858', '2026-01-29', '2027-03-28', '', '', '', 'up', '2026-04-05 23:17:25', '2026-04-05 23:17:25', '2026-04-02 11:33:24', 100.00, 31.34, 1, 1, 1, '2026-03-29 00:01:58', '2026-04-05 23:17:25'),
(25, 'Genki Sushi Crescent Mall', '17251', '222.255.192.3', 'VNPT', 'fbr002ykd', 'ping', 3, 'Genki Sushi Crescent Mall', 'Unit 4F-22 4th Floor, TTTM Crescent Mall, 101 Tôn Dật Tiên, P. Tân Mỹ, TP. HCM', '02838721105 (VNPT)', '', '2026-01-23', '2027-03-22', '', '', '', 'up', '2026-04-05 23:17:51', '2026-04-05 23:17:51', '2026-04-01 04:01:51', 100.00, 11.77, 1, 1, 1, '2026-03-29 00:03:43', '2026-04-05 23:17:51'),
(26, 'Glory Heights ', '17010', '113.176.64.203', 'VNPT', 'fbr006hsf', 'ping', 2, 'Glory Heights', 'GH6-01S03-S04, Vinhome Grand Park - S1.S2.S3.S4.S5, Đường Nguyễn Xiễn, Phường Long Bình, Thành phố Hồ Chí Minh', '02836209063 (VNPT)', 'A Hoài - 0824417531', '2026-03-04', '2027-05-03', '', '', '', 'up', '2026-04-05 23:17:46', '2026-04-05 23:17:46', '2026-04-01 04:01:45', 100.00, 9.33, 1, 1, 1, '2026-03-29 00:05:32', '2026-04-05 23:17:46'),
(27, 'Fansipan San May', '16490', '14.241.67.179', 'VNPT', 'fbr0044yo', 'ping', 2, 'Fansipan San May', 'Ga đến Cáp treo Khu du lịch Sun World Fansipan Legend tại địa chỉ: Số nhà 089B, đường Nguyễn Chí Thanh, phường Sa Pa, tỉnh Lào Cai, Việt Nam', '', 'A Lộc - 0835882003', '2026-03-04', NULL, '', '', '', 'up', '2026-04-05 23:17:42', '2026-04-05 23:17:42', '2026-04-02 04:01:18', 100.00, 34.92, 1, 1, 1, '2026-03-29 00:06:34', '2026-04-05 23:17:42'),
(28, 'FTTH Viettel', 'FTTH01', '115.77.189.31', 'Viettel', 't008_ftth_telecomctcpvtdd', 'ping', 4, 'FTTH Viettel', 'Tầng 4, Toà nhà K&M 33 Ung Văn Khiêm, Phường 25, Quận Bình Thạnh, Tp.Hồ Chí Minh', '', '', '2026-02-24', '2026-08-23', '', '', '', 'up', '2026-04-05 23:18:01', '2026-04-05 23:18:01', '2026-04-03 14:35:03', 100.00, 8.28, 1, 1, 1, '2026-03-29 00:18:43', '2026-04-05 23:18:01'),
(29, 'Lý Chính Thắng', '', '203.210.144.132', 'VNPT', 'dd2022_127', 'ping', 7, 'Lý Chính Thắng', '127 Lý Chính Thắng, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh', '', '', '2025-04-15', '2026-06-14', '', '', '', 'up', '2026-04-05 23:17:57', '2026-04-05 23:17:57', NULL, 100.00, 9.51, 1, 1, 1, '2026-03-29 00:29:45', '2026-04-05 23:17:57'),
(30, 'Phan Xích Long', '', '14.224.167.134', 'VNPT', 'dd2023_93pxl', 'ping', 5, 'Phan Xích Long', 'Số 93 đường Phan Xích Long, Phường 2, Quận Phú Nhuận, TP. HCM', '', '', '2025-01-09', '2026-10-31', '', '', '', 'up', '2026-04-05 23:17:58', '2026-04-05 23:17:58', '2026-03-31 09:00:08', 100.00, 10.41, 1, 1, 1, '2026-03-29 00:33:02', '2026-04-05 23:17:58'),
(31, 'Văn Phòng', '', '203.210.219.95', 'VNPT', 'hcm0058000004493', 'ping', 6, 'Văn Phòng', 'Tầng 20, toà nhà vietinbank - 93-95 Hàm Nghi, Nguyễn Thái Bình, Q1, TP. Hồ Chí Minh', '', '', '2025-10-05', '2026-12-04', '', '', '', 'up', '2026-04-05 23:17:59', '2026-04-05 23:17:59', NULL, 100.00, 11.22, 1, 1, 1, '2026-03-29 00:34:16', '2026-04-05 23:17:59'),
(32, 'Himisub Bar', '', '14.241.237.123', 'VNPT', 'hcm0058000052133', 'ping', 8, 'Himisub Bar', '154 Bis Trần Quang Khải, Phường Tân Định, TP.HCM', '', '', NULL, NULL, '', '', '', 'up', '2026-04-05 23:18:00', '2026-04-05 23:18:00', NULL, 100.00, 9.72, 1, 1, 1, '2026-03-29 00:36:11', '2026-04-05 23:18:00'),
(33, 'VNPT 01', 'VNPT 01', '222.252.9.201', 'VNPT', '', 'ping', 9, 'VNPT 01', 'Số 4, Phố Tôn Thất Thiệp, Phường Ba Đình, Thành phố Hà Nội, Việt Nam', '', '', '2025-12-31', '2027-01-01', '', '', '', 'up', '2026-04-05 23:18:02', '2026-04-05 23:18:02', NULL, 100.00, 33.45, 1, 1, 1, '2026-03-30 09:09:24', '2026-04-05 23:18:02'),
(34, 'Sensota Hải Phòng', '17005', '14.241.117.14', 'VNPT', 'hpg0058000063352', 'ping', 2, 'Sensota Hải Phòng', 'Sensota Sky Park, số TMDV 1.5 thuộc Dự án Chung cư 28 tầng tại Lô CT4, Khu đô thị ven sông Lạch Tray, Đường Võ Nguyên Giáp, Phường Lê Chân, Tp Hải Phòng', '02253260035 (VNPT)', 'Anh Long - 0948463899', '2025-12-16', '2027-02-15', '', '', '', 'up', '2026-04-05 23:17:44', '2026-04-05 23:17:44', '2026-04-02 18:13:08', 100.00, 38.16, 1, 1, 1, '2026-04-02 09:06:46', '2026-04-05 23:17:44'),
(35, 'VNG Campus', '17006', '14.161.37.93', 'VNPT', 'hcm0058000182087', 'ping', 2, 'VNG Campus', 'Z06 đường số 13, KCX Tân Thuận, phường Tân Thuận, TP.HCM', '02839212112 (VNPT)', 'Anh Tùng - 0913772468', '2025-12-20', '2027-02-19', '', '', '', 'up', '2026-04-05 23:17:45', '2026-04-05 23:17:45', NULL, 100.00, 9.36, 1, 1, 1, '2026-04-02 09:10:43', '2026-04-05 23:17:45'),
(36, 'Quang Trung', '17001', '113.161.85.104', 'VNPT', 'hcm0058000214651', 'ping', 2, 'Quang Trung', '647 Quang trung, phường Thông Tây Hội, TP. Hồ Chí Minh', '02836362607 (VNPT)', 'Anh Ngọc - 0886921841', '2025-12-20', '2027-02-19', '', '', '', 'up', '2026-04-05 23:17:43', '2026-04-05 23:17:43', NULL, 100.00, 10.39, 1, 1, 1, '2026-04-02 09:12:09', '2026-04-05 23:17:43'),
(37, 'Aeon Ha Dong', '16401', '123.24.132.230', 'VNPT', 'fbr000740', 'ping', 2, 'Aeon Ha Dong', ' T160-161, Tầng 1, Trung tâm thương mại Aeon Mall Hà Đông, Phường Dương Nội, Thành phố Hà Nội', '02432013805 (VNPT)', 'A LÂN : 0916063399', '2025-12-26', '2027-02-20', '', '', '', 'up', '2026-04-05 23:17:30', '2026-04-05 23:17:30', '2026-04-02 14:37:55', 100.00, 33.36, 1, 1, 1, '2026-04-02 09:13:58', '2026-04-05 23:17:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'login', 'Can login to the system'),
(2, 'view_dashboard', 'Can view monitoring dashboard'),
(3, 'manage_lines', 'Can add/edit/delete FTTH lines'),
(4, 'view_lines', 'Can view FTTH line details'),
(5, 'manage_users', 'Can manage user accounts'),
(6, 'manage_alerts', 'Can configure alert settings'),
(7, 'view_reports', 'Can view reports and statistics'),
(8, 'system_settings', 'Can change system settings');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`, `updated_at`) VALUES
('ping_interval', '5', 'Khoáº£ng thá»i gian giá»¯a má»—i láº§n ping (giÃ¢y)', '2026-03-27 16:41:24'),
('telegram_bot_token', '', 'Bot Token tá»« BotFather', '2026-03-27 16:41:24'),
('telegram_chat_id', '', 'Chat ID nháº­n thÃ´ng bÃ¡o', '2026-03-27 16:41:24'),
('telegram_enabled', '0', 'Báº­t/táº¯t thÃ´ng bÃ¡o Telegram', '2026-03-27 16:41:24'),
('timezone', 'Asia/Ho_Chi_Minh', 'MÃºi giá» há»‡ thá»‘ng', '2026-03-27 16:41:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user','viewer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role`, `active`, `avatar`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', 'vietdq246@gmail.com', '$2y$10$2GXn4ogZq0MK.dO24X4msuK9POgqXWpZrZHutaXSOePz20JfDS4wK', 'admin', 1, NULL, '2026-04-03 00:09:08', '2026-03-27 09:06:13', '2026-04-03 00:09:08'),
(2, 'sangdc', 'Dương Công Sáng', 'sangdc134@gmail.com', '$2y$10$QVTPeCfDetHYkkRb1FoExeEv0t1j8sNSXnnkeQwnyZ0Y1gSokMpzm', 'admin', 1, NULL, '2026-03-29 23:52:26', '2026-03-27 16:01:47', '2026-03-29 23:52:26'),
(3, 'anhlt', 'Lê Thị Kim Anh', 'anhlt@vtc.vn', '$2y$10$zjGI7QVR1r6AZceuKhvMwewGpD5PFG5T9j..qMQYRMCzTaUOcIPwm', 'viewer', 1, NULL, NULL, '2026-03-28 23:07:39', '2026-03-28 23:07:39'),
(4, 'nocvtc', 'NOC VTC CNPN', 'noc.kv2@vtc.vn', '$2y$10$Dclwo1/HpSyX1/MFv8PNEeuzPk4dZGoOe9iVsajgVxYOV4M2ub.zu', 'viewer', 1, NULL, '2026-04-02 14:55:20', '2026-03-31 12:02:43', '2026-04-02 14:55:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_id`) VALUES
(1, 1, 1),
(6, 1, 2),
(3, 1, 3),
(7, 1, 4),
(4, 1, 5),
(2, 1, 6),
(8, 1, 7),
(5, 1, 8),
(9, 2, 1),
(14, 2, 2),
(11, 2, 3),
(15, 2, 4),
(12, 2, 5),
(10, 2, 6),
(16, 2, 7),
(13, 2, 8),
(18, 3, 2),
(17, 3, 3),
(19, 3, 4),
(20, 3, 7);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_action` (`user_id`,`action`),
  ADD KEY `idx_created` (`created_at`);

--
-- Chỉ mục cho bảng `alert_log`
--
ALTER TABLE `alert_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_line_alert` (`line_id`,`created_at`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customer_branches`
--
ALTER TABLE `customer_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer` (`customer_id`);

--
-- Chỉ mục cho bảng `ftth_lines`
--
ALTER TABLE `ftth_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_ip` (`ip_address`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_user_perm` (`user_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `alert_log`
--
ALTER TABLE `alert_log`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `customer_branches`
--
ALTER TABLE `customer_branches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `ftth_lines`
--
ALTER TABLE `ftth_lines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `alert_log`
--
ALTER TABLE `alert_log`
  ADD CONSTRAINT `alert_log_ibfk_1` FOREIGN KEY (`line_id`) REFERENCES `ftth_lines` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `customer_branches`
--
ALTER TABLE `customer_branches`
  ADD CONSTRAINT `customer_branches_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `ftth_lines`
--
ALTER TABLE `ftth_lines`
  ADD CONSTRAINT `ftth_lines_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ftth_lines_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
