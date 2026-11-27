-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql212.infinityfree.com
-- Generation Time: Nov 27, 2026 at 12:55 AM
-- Server version: 11.4.14-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_43528429_yara_laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `password_hash`, `address`, `created_at`) VALUES
(1, 'Siti Rahma', '+62 813-9821-4401', 'siti@mail.com', NULL, 'Jl. Melati No. 12, Blok A', '2026-11-24 09:37:00'),
(2, 'Budi Santoso', '+62 812-4412-9902', 'budi@mail.com', NULL, 'Perum Griya Indah Blok C-4', '2026-11-24 09:37:00'),
(3, 'Dimas Prayogo', '+62 878-1100-3221', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(4, 'Maya Indah', '+62 857-3312-9011', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(5, 'Hendra Wijaya', '+62 811-2099-8120', 'hendra@mail.com', NULL, 'Jl. Kenanga Raya No. 88', '2026-11-24 09:37:00'),
(6, 'Rina Marlina', '+62 819-7721-3044', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(7, 'Fajar Nugroho', '+62 821-5540-1189', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(8, 'Nadia Putri', '+62 812-8831-2091', 'nadia@mail.com', NULL, 'Komplek Permata Hijau No. 5', '2026-11-24 09:37:00'),
(9, 'Arif Rahman', '+62 856-9012-7743', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(10, 'Dewi Lestari', '+62 813-4420-6612', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(11, 'Gilang Ramadhan', '+62 877-2190-5510', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(12, 'Siska Amelia', '+62 812-3390-1288', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(13, 'Taufik Hidayat', '+62 815-6671-8890', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(14, 'Lina Kartika', '+62 818-9920-4411', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(15, 'Bagus Setiawan', '+62 822-1190-7765', 'bagus@mail.com', NULL, 'Jl. Sudirman Gg. Mawar 3', '2026-11-24 09:37:00'),
(16, 'Fitri Handayani', '+62 858-4412-9087', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(17, 'Rizky Pratama', '+62 813-7780-1123', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(18, 'Yuni Shara', '+62 812-6654-3321', NULL, NULL, NULL, '2026-11-24 09:37:00'),
(19, 'asep', '+62 812-3453-1922', NULL, '$2y$10$3EplJp7mqa57c8NRg7PFyuRJZQ1T.TTu16BSP6oELqu0EiV77stBa', NULL, '2026-11-24 10:41:00'),
(20, 'yunyun', '+62 812-3456-7890', NULL, '$2y$10$m6I91X9s/mv1X06ae6Y.eOd3JCjZpiwnp3ZFPubUDvVEBPwxdAdnS', NULL, '2026-11-24 10:44:35'),
(21, 'yuri', '+62 888-7777-6666', NULL, '$2y$10$E22abDUTYeDMi36dIx/UbeIp8YiV8TobInVvZZmUxMGWPq.AN70fi', 'karawang', '2026-11-24 14:52:13'),
(22, 'aleh sunandar', '+62 888-8888-7777', NULL, '$2y$10$yzkNSCtiNe96K62pPt7e1.q14AbkVfHeeEsvJw6aWoqpj.JCYp2Ki', 'blok a56', '2026-11-24 17:19:05'),
(23, 'sunaryo', '+62 857-1122-3344', NULL, '$2y$10$QgCrMLmxnVNK4Xemx/ZcG.VPIA/gxSG4BdRkcpdQ5Lz7x6yp51k.K', NULL, '2026-11-25 03:01:53'),
(24, 'Ridho Dwi Saputra', '+62 851-7999-8522', NULL, '$2y$12$s8nTuCe1sKiSUXJKJIu7fukzsocSbJ21.6AZqKXbx.WybjXvEYQ6C', 'blok c', '2026-11-25 03:13:50'),
(25, 'test user', '+62 851-7346-8794', NULL, '$2y$10$kzSemNbZeZF.ecQdoYwZ6u5irL0z0Svyj/UCfldZXFsQIOtCBxzJS', NULL, '2026-11-25 03:24:47'),
(26, 'Fauzi Ismail', '+62 895-0714-6734', NULL, '$2y$12$MYWO9axBp/6jFaJez/PDEu6hawGpaG8MeK13EQXejV47ygN0Pky7.', 'Jl. Sasak Dempul RT 001/RW 006', '2026-11-25 04:05:23'),
(27, 'pansaa', '+62 857-1942-0746', NULL, '$2y$12$jWGl0NjbZ4saBmQVlJtCJO3nWiaHAcYQIcSeh.Pg3wWmZ8Ka6JhrS', 'jalan yang lururs', '2026-11-25 04:10:33'),
(28, 'Andika Bagas Febrian', '+62 856-0331-7686', NULL, '$2y$12$OZlOJRNYKNGZIbfpeRYQe.5gKK5z4V.kI9uoLNT9/J1YRAeqD5kl6', 'gedung opon', '2026-11-26 07:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `receipt_no` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `delivery_type` enum('DROP_OFF','ANTAR_JEMPUT') NOT NULL DEFAULT 'DROP_OFF',
  `pickup_address` text DEFAULT NULL,
  `delivery_fee` int(11) NOT NULL DEFAULT 0,
  `weight` decimal(6,2) NOT NULL DEFAULT 0.00,
  `is_estimated_weight` tinyint(1) NOT NULL DEFAULT 1,
  `unit_label` varchar(50) DEFAULT NULL,
  `status_step` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=Menunggu Jemput, 1=Timbang, 2=Antre, 3=Cuci, 4=Kering, 5=Setrika, 6=Siap, 7=Selesai',
  `status_note` varchar(120) NOT NULL,
  `payment_status` enum('BELUM','LUNAS') NOT NULL DEFAULT 'BELUM',
  `payment_method` varchar(30) DEFAULT NULL,
  `total_price` int(11) NOT NULL DEFAULT 0,
  `is_overdue` tinyint(1) NOT NULL DEFAULT 0,
  `overdue_minutes` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `time_label` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `receipt_no`, `customer_id`, `service_id`, `delivery_type`, `pickup_address`, `delivery_fee`, `weight`, `is_estimated_weight`, `unit_label`, `status_step`, `status_note`, `payment_status`, `payment_method`, `total_price`, `is_overdue`, `overdue_minutes`, `notes`, `time_label`, `created_at`, `updated_at`) VALUES
(1, '#YYL-8925', 1, 1, 'ANTAR_JEMPUT', 'Jl. Melati No. 12, Blok A', 5000, '3.50', 0, NULL, 3, 'Drum-A1 (ETA 17:00)', 'BELUM', NULL, 33000, 0, 0, 'Jemput depan pagar hitam', 'Hari ini, 11:15', '2026-11-24 09:37:00', '2026-11-26 04:19:25'),
(2, '#YYL-8921', 2, 2, 'DROP_OFF', NULL, 0, '4.80', 0, NULL, 3, 'Drum-A3 (ETA 17:00)', 'LUNAS', 'QRIS', 38400, 0, 0, 'Parfum Lavender', 'Hari ini, 09:15', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(3, '#YYL-8918', 3, 3, 'DROP_OFF', NULL, 0, '5.00', 0, NULL, 7, 'Sudah Diambil Pelanggan', 'LUNAS', 'Tunai', 30000, 0, 0, 'Hanger kemeja kerja', 'Kmrn, 16:00', '2026-11-24 09:37:00', '2026-11-26 06:49:08'),
(4, '#YYL-8915', 4, 4, 'DROP_OFF', NULL, 0, '3.00', 0, NULL, 4, 'Pengering Tmb-01', 'LUNAS', 'Transfer', 18000, 0, 0, '', 'Kmrn, 14:20', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(5, '#YYL-8912', 5, 5, 'ANTAR_JEMPUT', 'Jl. Kenanga Raya No. 88', 5000, '1.00', 0, '1 Pcs (King)', 5, 'Meja Setrika-02', 'LUNAS', 'QRIS', 40000, 0, 0, 'Plastik vakum', 'Kmrn, 10:00', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(6, '#YYL-8910', 6, 6, 'DROP_OFF', NULL, 0, '4.00', 0, NULL, 7, 'Pengering Tmb-02 (Prioritas)', 'LUNAS', 'QRIS', 56000, 1, 20, 'Ditunggu pelanggan jam 11:00', 'Hari ini, 05:00', '2026-11-24 09:37:00', '2026-11-26 05:12:52'),
(7, '#YYL-8905', 7, 1, 'DROP_OFF', NULL, 0, '6.00', 0, NULL, 7, 'Meja Setrika-01 (Prioritas)', 'BELUM', NULL, 48000, 1, 10, 'Ambil siang', 'Kmrn, 09:30', '2026-11-24 09:37:00', '2026-11-26 05:13:35'),
(8, '#YYL-8924', 8, 1, 'ANTAR_JEMPUT', 'Komplek Permata Hijau No. 5', 5000, '2.50', 0, NULL, 3, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 25000, 0, 0, 'Hubungi WA sebelum tiba', 'Hari ini, 11:05', '2026-11-24 09:37:00', '2026-11-26 06:49:17'),
(9, '#YYL-8923', 9, 4, 'DROP_OFF', NULL, 0, '4.20', 1, NULL, 1, 'Menunggu Timbang Ulang', 'BELUM', NULL, 25200, 0, 0, '', 'Hari ini, 10:50', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(10, '#YYL-8922', 10, 3, 'DROP_OFF', NULL, 0, '3.00', 1, NULL, 1, 'Menunggu Timbang Ulang', 'BELUM', NULL, 18000, 0, 0, '', 'Hari ini, 10:30', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(11, '#YYL-8920', 11, 1, 'DROP_OFF', NULL, 0, '5.50', 0, NULL, 3, 'Drum-A1 (ETA 16:30)', 'LUNAS', 'Tunai', 44000, 0, 0, '', 'Hari ini, 09:00', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(12, '#YYL-8919', 12, 2, 'DROP_OFF', NULL, 0, '4.00', 0, NULL, 3, 'Drum-A2 (ETA 16:45)', 'BELUM', NULL, 32000, 0, 0, '', 'Hari ini, 08:45', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(13, '#YYL-8917', 13, 4, 'DROP_OFF', NULL, 0, '6.50', 0, NULL, 3, 'Drum-B1 (ETA 17:15)', 'LUNAS', 'QRIS', 39000, 0, 0, '', 'Hari ini, 08:20', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(14, '#YYL-8916', 14, 1, 'DROP_OFF', NULL, 0, '3.20', 0, NULL, 3, 'Drum-B2 (ETA 17:30)', 'LUNAS', 'Transfer', 25600, 0, 0, '', 'Hari ini, 08:00', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(15, '#YYL-8914', 15, 1, 'ANTAR_JEMPUT', 'Jl. Sudirman Gg. Mawar 3', 5000, '4.50', 0, NULL, 6, 'Siap Antar Kurir (Rak A-01)', 'LUNAS', 'QRIS', 41000, 0, 0, 'Antar sore jam 16:00', 'Kmrn, 13:15', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(16, '#YYL-8913', 16, 3, 'DROP_OFF', NULL, 0, '4.00', 0, NULL, 6, 'Siap Ambil (Rak A-03)', 'BELUM', NULL, 24000, 0, 0, 'Bayar saat ambil', 'Kmrn, 12:10', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(17, '#YYL-8911', 17, 2, 'DROP_OFF', NULL, 0, '7.00', 0, NULL, 6, 'Siap Ambil (Rak B-01)', 'LUNAS', 'Tunai', 56000, 0, 0, '', 'Kmrn, 09:45', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(18, '#YYL-8909', 18, 5, 'DROP_OFF', NULL, 0, '2.00', 0, '2 Pcs (Queen)', 6, 'Siap Ambil (Rak C-02)', 'LUNAS', 'QRIS', 70000, 0, 0, '', 'Kmrn, 08:30', '2026-11-24 09:37:00', '2026-11-24 09:37:00'),
(19, '#YYL-8926', 22, 5, 'ANTAR_JEMPUT', 'blok C 1', 5000, '3.00', 0, NULL, 7, 'Sudah Diantar ke Pelanggan', 'LUNAS', 'Tunai', 110000, 0, 0, 'atas', 'Hari ini, 00:47', '2026-11-24 17:47:48', '2026-11-24 18:06:35'),
(20, '#YYL-8927', 21, 1, 'ANTAR_JEMPUT', 'karawang', 5000, '6.00', 1, NULL, 0, 'Menunggu Kurir Jemput', 'BELUM', NULL, 53000, 0, 0, 'yg bersih ya', 'Hari ini, 00:51', '2026-11-24 17:51:25', '2026-11-24 17:51:25'),
(21, '#YYL-8928', 22, 5, 'ANTAR_JEMPUT', 'blok a56', 5000, '1.30', 0, NULL, 3, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 50500, 0, 0, 'bed cover', 'Hari ini, 09:23', '2026-11-25 02:23:16', '2026-11-25 02:47:31'),
(22, '#YYL-8929', 24, 1, 'DROP_OFF', NULL, 0, '4.30', 0, NULL, 7, 'Sudah Diambil Pelanggan', 'LUNAS', 'Tunai', 34400, 0, 0, 'Yg bersih yaa', 'Hari ini, 10:48', '2026-11-25 03:48:36', '2026-11-26 04:34:42'),
(23, '#YYL-8930', 27, 4, 'DROP_OFF', NULL, 0, '3.00', 0, NULL, 7, 'Sudah Diambil Pelanggan', 'LUNAS', 'Tunai', 18000, 0, 0, '', 'Hari ini, 11:11', '2026-11-25 04:11:15', '2026-11-25 15:16:46'),
(24, '#YYL-8931', 26, 6, 'DROP_OFF', NULL, 0, '1.00', 0, NULL, 7, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 14000, 0, 0, '', 'Hari ini, 11:15', '2026-11-25 04:15:14', '2026-11-25 15:17:24'),
(25, '#YYL-8932', 27, 4, 'ANTAR_JEMPUT', 'jalan yang lururs', 5000, '2.50', 0, NULL, 7, 'Sudah Diantar ke Pelanggan', 'LUNAS', 'QRIS', 20000, 0, 0, '', 'Hari ini, 22:18', '2026-11-25 15:18:33', '2026-11-26 04:40:03'),
(26, '#YYL-8933', 24, 1, 'DROP_OFF', NULL, 0, '4.00', 0, NULL, 7, 'Sudah Diambil Pelanggan', 'LUNAS', 'Tunai', 32000, 0, 0, 'aaaa', 'Hari ini, 11:36', '2026-11-26 04:36:35', '2026-11-26 07:01:54'),
(27, '#YYL-8934', 27, 6, 'ANTAR_JEMPUT', 'jalan yang lururs', 5002, '2.50', 0, NULL, 5, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 40000, 0, 0, '', 'Hari ini, 11:45', '2026-11-26 04:45:01', '2026-11-27 05:39:20'),
(28, '#YYL-8935', 24, 7, 'DROP_OFF', NULL, 0, '5.10', 0, NULL, 7, 'Sudah Diambil Pelanggan', 'LUNAS', 'Tunai', 38250, 0, 0, 'Y9oo', 'Hari ini, 13:59', '2026-11-26 06:59:11', '2026-11-26 07:01:04'),
(29, '#YYL-8936', 28, 2, 'ANTAR_JEMPUT', 'gedung opon', 5000, '7.00', 1, NULL, 0, 'Menunggu Kurir Jemput', 'BELUM', NULL, 61000, 0, 0, '', 'Hari ini, 14:48', '2026-11-26 07:48:32', '2026-11-26 07:48:32'),
(30, '#YYL-8937', 28, 6, 'ANTAR_JEMPUT', 'gedung opon', 5000, '4.20', 0, NULL, 4, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'Tunai', 63800, 0, 0, '', 'Hari ini, 14:49', '2026-11-26 07:49:34', '2026-11-26 07:53:09'),
(31, '#YYL-8938', 28, 3, 'DROP_OFF', NULL, 0, '2.10', 0, NULL, 3, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'Tunai', 12600, 0, 0, '', 'Hari ini, 14:54', '2026-11-26 07:54:16', '2026-11-26 08:05:13'),
(33, '#YYL-8939', 24, 9, 'ANTAR_JEMPUT', 'blok c', 5000, '2.00', 0, NULL, 3, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 19000, 0, 0, 'selimut', 'Hari ini, 15:16', '2026-11-26 08:16:03', '2026-11-26 08:41:50'),
(34, '#YYL-8940', 24, 1, 'DROP_OFF', NULL, 0, '3.00', 0, NULL, 3, 'Drum-A1 (ETA 17:00)', 'BELUM', NULL, 24000, 0, 0, 'aads', 'Hari ini, 15:18', '2026-11-26 08:18:02', '2026-11-26 08:20:26'),
(35, '#YYL-8941', 24, 9, 'DROP_OFF', NULL, 0, '4.00', 0, NULL, 3, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 23000, 0, 0, 'aaa', 'Hari ini, 15:42', '2026-11-26 08:42:55', '2026-11-26 08:55:47'),
(36, '#YYL-8942', 24, 3, 'ANTAR_JEMPUT', 'blok c', 5000, '3.00', 0, NULL, 7, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 23000, 0, 0, '', 'Hari ini, 15:45', '2026-11-26 08:45:16', '2026-11-26 08:52:52'),
(37, '#YYL-8943', 24, 3, 'ANTAR_JEMPUT', 'blok c', 5000, '3.00', 0, NULL, 7, 'Meja Setrika-1 (ETA 17:00)', 'LUNAS', 'QRIS', 23000, 0, 0, 'a', 'Hari ini, 15:53', '2026-11-26 08:53:27', '2026-11-26 08:54:17'),
(38, '#YYL-8944', 24, 1, 'DROP_OFF', NULL, 0, '4.00', 0, NULL, 7, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'QRIS', 32000, 0, 0, 'a', 'Hari ini, 15:54', '2026-11-26 08:54:45', '2026-11-27 02:34:54'),
(39, '#YYL-8945', 24, 5, 'ANTAR_JEMPUT', 'blok c', 5000, '1.00', 0, NULL, 7, 'Drum-A1 (ETA 17:00)', 'LUNAS', 'Tunai', 40000, 0, 0, '', 'Hari ini, 09:32', '2026-11-27 02:33:00', '2026-11-27 02:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `order_logs`
--

CREATE TABLE `order_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `actor` varchar(100) NOT NULL DEFAULT 'KASIR',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_logs`
--

INSERT INTO `order_logs` (`id`, `order_id`, `action`, `actor`, `created_at`) VALUES
(1, 1, 'Pesanan Antar-Jemput #YYL-8925 masuk dari aplikasi pelanggan (Estimasi 3.5 Kg)', 'PELANGGAN (Siti Rahma)', '2026-11-24 09:37:00'),
(2, 2, 'Tiket #YYL-8921 ditimbang & masuk mesin Drum-A3', 'KASIR-02 (RIAN HIDAYAT)', '2026-11-24 09:37:00'),
(3, 3, 'Tiket #YYL-8918 selesai setrika, ditempatkan di Rak B-04', 'KASIR', '2026-11-24 09:37:00'),
(4, 15, 'Tiket #YYL-8914 siap diantar ke Jl. Sudirman Gg. Mawar 3', 'KASIR', '2026-11-24 09:37:00'),
(5, 19, 'Pesanan Antar-Jemput #YYL-8926 masuk dari aplikasi pelanggan (Estimasi 2 Pcs)', 'PELANGGAN (aleh)', '2026-11-24 17:47:48'),
(6, 20, 'Pesanan Antar-Jemput #YYL-8927 masuk dari aplikasi pelanggan (Estimasi 6 Kg)', 'PELANGGAN (yuri)', '2026-11-24 17:51:25'),
(7, 19, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-24 18:01:39'),
(8, 19, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-24 18:02:08'),
(9, 19, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-24 18:05:53'),
(10, 19, 'Update -> Tahap [5/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-24 18:06:05'),
(11, 19, 'Update -> Tahap [6/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-24 18:06:27'),
(12, 19, 'Update -> Tahap [7/7] (Sudah Diantar ke Pelanggan) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-24 18:06:35'),
(13, 21, 'Pesanan Antar-Jemput #YYL-8928 masuk dari aplikasi pelanggan (Estimasi 1 Pcs)', 'PELANGGAN (aleh)', '2026-11-25 02:23:16'),
(14, 21, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-25 02:47:31'),
(15, 22, 'Pesanan Drop-Off #YYL-8929 masuk dari aplikasi pelanggan (Estimasi 4.5 Kg)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-25 03:48:36'),
(16, 22, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-25 03:49:31'),
(17, 23, 'Pesanan Drop-Off #YYL-8930 masuk dari aplikasi pelanggan (Estimasi 4 Kg)', 'PELANGGAN (pansaa)', '2026-11-25 04:11:15'),
(18, 24, 'Pesanan Drop-Off #YYL-8931 masuk dari aplikasi pelanggan (Estimasi 1 Kg)', 'PELANGGAN (Fauzi Ismail)', '2026-11-25 04:15:14'),
(19, 23, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-25 15:14:04'),
(20, 24, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-25 15:14:11'),
(21, 23, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-25 15:14:42'),
(22, 23, 'Update -> Tahap [6/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-25 15:14:54'),
(23, 23, 'Update -> Tahap [7/7] (Sudah Diambil Pelanggan) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-25 15:16:46'),
(24, 24, 'Update -> Tahap [7/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'KASIR (KASIR)', '2026-11-25 15:17:24'),
(25, 25, 'Pesanan Antar-Jemput #YYL-8932 masuk dari aplikasi pelanggan (Estimasi 2.5 Kg)', 'PELANGGAN (pansaa)', '2026-11-25 15:18:33'),
(26, 25, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: BELUM', 'KASIR (KASIR)', '2026-11-25 15:23:37'),
(27, 25, 'Update -> Tahap [6/7] (Drum-A1 (ETA 17:00)) | Bayar: BELUM', 'KASIR (KASIR)', '2026-11-25 15:23:45'),
(28, 1, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: BELUM', 'KASIR (KASIR)', '2026-11-26 04:19:25'),
(29, 22, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 04:33:55'),
(30, 22, 'Update -> Tahap [5/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 04:34:09'),
(31, 22, 'Update -> Tahap [6/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 04:34:24'),
(32, 22, 'Update -> Tahap [7/7] (Sudah Diambil Pelanggan) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 04:34:42'),
(33, 26, 'Pesanan Drop-Off #YYL-8933 masuk dari aplikasi pelanggan (Estimasi 4 Kg)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 04:36:35'),
(34, 25, 'Update -> Tahap [7/7] (Sudah Diantar ke Pelanggan) | Bayar: LUNAS', 'KASIR (KASIR)', '2026-11-26 04:40:03'),
(35, 27, 'Pesanan Antar-Jemput #YYL-8934 masuk dari aplikasi pelanggan (Estimasi 2.5 Kg)', 'PELANGGAN (pansaa)', '2026-11-26 04:45:01'),
(36, 27, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'KASIR (KASIR)', '2026-11-26 04:45:19'),
(37, 6, 'Update -> Tahap [4/7] (Pengering Tmb-02 (Prioritas)) | Bayar: LUNAS', 'KASIR (KASIR)', '2026-11-26 05:12:44'),
(38, 6, 'Update -> Tahap [7/7] (Pengering Tmb-02 (Prioritas)) | Bayar: LUNAS', 'KASIR (KASIR)', '2026-11-26 05:12:52'),
(39, 7, 'Update -> Tahap [7/7] (Meja Setrika-01 (Prioritas)) | Bayar: BELUM', 'KASIR (KASIR)', '2026-11-26 05:13:35'),
(40, 3, 'Update -> Tahap [7/7] (Sudah Diambil Pelanggan) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 06:49:08'),
(41, 8, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 06:49:17'),
(42, 26, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 06:50:31'),
(43, 26, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 06:56:03'),
(44, 28, 'Pesanan Drop-Off #YYL-8935 masuk dari aplikasi pelanggan (Estimasi 5.5 Kg)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 06:59:11'),
(45, 28, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: BELUM', 'OWNER (YARA OWNER)', '2026-11-26 06:59:43'),
(46, 28, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 06:59:58'),
(47, 28, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:00:07'),
(48, 28, 'Update -> Tahap [5/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:00:39'),
(49, 28, 'Update -> Tahap [6/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:00:55'),
(50, 28, 'Update -> Tahap [7/7] (Sudah Diambil Pelanggan) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:01:04'),
(51, 26, 'Update -> Tahap [6/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:01:46'),
(52, 26, 'Update -> Tahap [7/7] (Sudah Diambil Pelanggan) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:01:54'),
(53, 29, 'Pesanan Antar-Jemput #YYL-8936 masuk dari aplikasi pelanggan (Estimasi 7 Kg)', 'PELANGGAN (Andika Bagas Febrian)', '2026-11-26 07:48:32'),
(54, 30, 'Pesanan Antar-Jemput #YYL-8937 masuk dari aplikasi pelanggan (Estimasi 4 Kg)', 'PELANGGAN (Andika Bagas Febrian)', '2026-11-26 07:49:34'),
(55, 30, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:52:27'),
(56, 30, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 07:53:09'),
(57, 31, 'Pesanan Drop-Off #YYL-8938 masuk dari aplikasi pelanggan (Estimasi 2.5 Kg)', 'PELANGGAN (Andika Bagas Febrian)', '2026-11-26 07:54:16'),
(58, 31, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:05:13'),
(63, 33, 'Pesanan Antar-Jemput #YYL-8939 masuk dari aplikasi pelanggan (Estimasi 1 Pcs)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 08:16:03'),
(64, 34, 'Pesanan Drop-Off #YYL-8940 masuk dari aplikasi pelanggan (Estimasi 4 Kg)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 08:18:02'),
(65, 34, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: BELUM', 'OWNER (YARA OWNER)', '2026-11-26 08:20:26'),
(66, 33, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:41:50'),
(67, 35, 'Pesanan Drop-Off #YYL-8941 masuk dari aplikasi pelanggan (Estimasi 4 Pcs)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 08:42:55'),
(68, 36, 'Pesanan Antar-Jemput #YYL-8942 masuk dari aplikasi pelanggan (Estimasi 3 Kg)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 08:45:16'),
(69, 36, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:46:10'),
(70, 36, 'Update -> Tahap [7/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:52:52'),
(71, 37, 'Pesanan Antar-Jemput #YYL-8943 masuk dari aplikasi pelanggan (Estimasi 3 Kg)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 08:53:27'),
(72, 37, 'Update -> Tahap [5/7] (Meja Setrika-1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:53:52'),
(73, 37, 'Update -> Tahap [7/7] (Meja Setrika-1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:54:17'),
(74, 38, 'Pesanan Drop-Off #YYL-8944 masuk dari aplikasi pelanggan (Estimasi 4 Kg)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-26 08:54:45'),
(75, 38, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:55:22'),
(76, 35, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-26 08:55:47'),
(77, 39, 'Pesanan Antar-Jemput #YYL-8945 masuk dari aplikasi pelanggan (Estimasi 1 Pcs)', 'PELANGGAN (Ridho Dwi Saputra)', '2026-11-27 02:33:00'),
(78, 39, 'Update -> Tahap [3/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-27 02:33:43'),
(79, 39, 'Update -> Tahap [7/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-27 02:34:04'),
(80, 38, 'Update -> Tahap [7/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-27 02:34:54'),
(81, 27, 'Update -> Tahap [4/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-27 02:38:40'),
(82, 27, 'Update -> Tahap [5/7] (Drum-A1 (ETA 17:00)) | Bayar: LUNAS', 'OWNER (YARA OWNER)', '2026-11-27 05:39:20');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `purpose` enum('REGISTRASI','RESET_PASSWORD') NOT NULL,
  `code_hash` varchar(255) NOT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `phone`, `purpose`, `code_hash`, `attempts`, `is_used`, `expires_at`, `created_at`) VALUES
(1, '+62 812-3453-1922', 'REGISTRASI', '$2y$10$bmxQuH7qVsQQ/ljoFw/mEefX1t6MepXfvWE6w/MjgFiFaswgzTMga', 0, 1, '2026-11-24 17:42:36', '2026-11-24 10:40:36'),
(2, '+62 812-3456-7890', 'REGISTRASI', '$2y$10$tU.SOulFOyjLeq8cLOlxqObPdc3UprxbXdcBp6etnQKxM0ZRMX3OW', 0, 1, '2026-11-24 17:46:25', '2026-11-24 10:44:25'),
(3, '+62 813-1111-2222', 'REGISTRASI', '$2y$10$gmsjOl8sN6tF8Q2DJHJzheSUTkk7Mh7hEnCa3Dzukb.AkE/AB4DTC', 1, 0, '2026-11-24 17:47:50', '2026-11-24 10:45:50'),
(4, '+62 888-7777-6666', 'REGISTRASI', '$2y$10$jQCohcZVCm473TkeHKqjw.ZRB2QL6cT0Gc7c1JQdHBTp2cwU6hsBm', 0, 1, '2026-11-24 21:53:41', '2026-11-24 14:51:41'),
(5, '+62 888-9999-9999', 'REGISTRASI', '$2y$10$3H6UM5UBZRiy6xNUlRZdc.EjC19o0iptR8j4sMhdL1HefwwgI9Q9u', 0, 1, '2026-11-25 00:23:31', '2026-11-24 17:18:31'),
(6, '+62 857-1122-3344', 'REGISTRASI', '$2y$10$Na/2K/6XJx.jhyyEjCwvQuSTbqpNVH3zbWDLB/gpCnMjZOu4pOxMu', 0, 0, '2026-11-25 10:06:21', '2026-11-25 03:01:22'),
(7, '+62 857-1122-3344', 'REGISTRASI', '$2y$10$/at5a509YVr8B0p.vBa2X.IWo8Wdm95460okwipimeD2nrYpHid7W', 0, 0, '2026-11-25 10:06:33', '2026-11-25 03:01:34'),
(8, '+62 857-1122-3344', 'REGISTRASI', '$2y$10$stVaPzTeyp4ZS5KSC1WqZupf.GzPb2KBffNM3MSE6OrGxOvnHFghG', 0, 1, '2026-11-25 10:06:42', '2026-11-25 03:01:42'),
(9, '+62 851-7999-8522', 'REGISTRASI', '$2y$10$.Xy.bcRhff0mmwfEycZrPeYAW3kELw.dOZvQQVcEqejZteu7zr7xy', 0, 1, '2026-11-25 10:18:17', '2026-11-25 03:13:17'),
(10, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$10$kokQfNCZ9VZmRXqP8PiSUeR3OJsnhzVNPBL0lgUYBrEhz4KwanX16', 0, 1, '2026-11-25 10:22:35', '2026-11-25 03:17:35'),
(11, '+62 851-7346-8794', 'REGISTRASI', '$2y$10$B5WrPqhC548BP5VvBVoJ6.NEXvzRNRF9VsWkre11NLJ9lRy32tX7K', 0, 1, '2026-11-25 10:29:17', '2026-11-25 03:24:17'),
(12, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$i9gGg76O1Fvv/sm1BvYsOu/SE2.fBHnk845bymU13/kVnRh0crMKm', 0, 1, '2026-11-25 10:52:12', '2026-11-25 03:47:13'),
(13, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$59zQjKdSImlMa8t/QFiBQevZPpI/7HwhNzpEXNE68W.cmDpS/nwbi', 0, 1, '2026-11-25 11:01:11', '2026-11-25 03:56:12'),
(14, '+62 895-0714-6734', 'REGISTRASI', '$2y$12$6yajxQZxhDcr0nHa5LC/wuU40LVhSTV4L/8UcWf1B3qGtF0GlJm3i', 0, 1, '2026-11-25 11:09:57', '2026-11-25 04:04:58'),
(15, '+62 857-1942-0746', 'REGISTRASI', '$2y$12$GAohp6yObZHo87SFc.59deICPaKHWb0cTdULTi8bYtR90W.ZfaTEi', 0, 1, '2026-11-25 11:15:10', '2026-11-25 04:10:11'),
(16, '+62 857-1942-0746', 'RESET_PASSWORD', '$2y$12$WAviFTgJzzGEskJCqrjT.OIUYELWyU7TRxDXQQ6TJ5rmJKLOPL0NO', 0, 1, '2026-11-25 22:40:26', '2026-11-25 15:35:27'),
(17, '+62 857-1942-0746', 'RESET_PASSWORD', '$2y$12$zS0TbCJ3C8EpLVqRrfPEceN2xcZ5F6dpIG5rWegHhY/31rItH7v1O', 0, 0, '2026-11-25 22:47:07', '2026-11-25 15:42:07'),
(18, '+62 857-1942-0746', 'RESET_PASSWORD', '$2y$12$NyHbHxMvGrC7dndzmi41r.Zi5pzfrqHjqEVKqdOWeio6SVEr7u2sW', 0, 0, '2026-11-25 22:47:12', '2026-11-25 15:42:12'),
(19, '+62 857-1942-0746', 'RESET_PASSWORD', '$2y$12$HDlCytmM6xwjgOHQDTzfmeGO4bm7QCQoFSGWwYdWYJB/6gwk783wq', 0, 0, '2026-11-25 22:47:16', '2026-11-25 15:42:16'),
(20, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$KQvuUWvGG0BTgclfPhBVAeIQfvUeTNpmana6KbRfcNlCP7jpXuITy', 0, 1, '2026-11-26 11:20:19', '2026-11-26 04:15:20'),
(21, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$nGiGo1ARpCFlPi5di06h2eg6HlrG8RURoH2UrPh79dVrBI9KJtEJG', 0, 0, '2026-11-26 12:23:04', '2026-11-26 05:18:04'),
(22, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$IifE9jZnbIaRUr2faF4Qzem3ndwbZjjQe1xOGebhM1BThyPqm6OJC', 0, 0, '2026-11-26 12:23:14', '2026-11-26 05:18:15'),
(23, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$fVrWr7sYmUcdnR8d5UarSeLPucVO1.BIuOrorrAEtc4TlpOaQ9cye', 0, 0, '2026-11-26 12:23:23', '2026-11-26 05:18:24'),
(24, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$yAKrezU0ov3c9fXp3bYt5OFwGOl.kKhP9CAE4FRi/BdEdXSxVzdIS', 0, 0, '2026-11-26 12:30:15', '2026-11-26 05:25:15'),
(25, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$n82cCAQioS9HHzvGpOI43uDvfAxr9howy3oKgT4Zf2TKNjBQRqseS', 0, 0, '2026-11-26 12:30:21', '2026-11-26 05:25:21'),
(26, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$VpsKUbmlOzI/UfuYU7ebX./ShZHwyHEsxpNQOXvdtwOodRo.HXkf2', 0, 0, '2026-11-26 12:39:44', '2026-11-26 05:34:45'),
(27, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$utisoKpHyiEtTlTB7LLYIOypEod2rA4a9x1h4IKN46zeyJXVeQfrC', 0, 0, '2026-11-26 12:39:50', '2026-11-26 05:34:50'),
(28, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$P/C/6ZLPIhDjICCD2hSteO6NbxFIpc5gsK/yN0leVYcjYaz6RpHiy', 0, 0, '2026-11-26 12:42:05', '2026-11-26 05:37:05'),
(29, '+62 851-7999-8522', 'RESET_PASSWORD', '$2y$12$7sWvWhKHOwtH5.U4eMp2OORUWnZCq9GBYqt5qL.QVVJTfImByXC8y', 1, 1, '2026-11-26 12:43:11', '2026-11-26 05:38:11'),
(30, '+62 856-0331-7686', 'REGISTRASI', '$2y$12$YArMA1IeC5Bi5/YMle1W.ejWOd0mcqH0yZwFlz1idjjEYcez9h5Ki', 0, 1, '2026-11-26 14:51:34', '2026-11-26 07:46:34');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit` varchar(20) NOT NULL DEFAULT 'Kg',
  `price_per_unit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `code`, `name`, `unit`, `price_per_unit`) VALUES
(1, 'CK-01', 'Cuci Komplit', 'Kg', 8000),
(2, 'CKR-02', 'Cuci Komplit Reguler', 'Kg', 8000),
(3, 'ST-03', 'Setrika Saja', 'Kg', 6000),
(4, 'CKL-04', 'Cuci Kering Lipat', 'Kg', 6000),
(5, 'BC-05', 'Bedcover Besar', 'Pcs', 35000),
(6, 'EXP-06', 'Cuci Komplit Express 6 Jam', 'Kg', 14000),
(7, 'DCL-07', 'Dry Clean', 'Kg', 7500),
(9, 'CCS-08', 'Cuci Satuan (harga minimal)', 'Pcs', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('delivery_fee', '5000', '2026-11-24 09:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(120) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('kasir','owner') NOT NULL DEFAULT 'kasir',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `code`, `name`, `short_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'KASIR', 'KASIR', 'KASIR/ADMIN', 'kasir@yaralaundry.com', '$2y$10$v4e490/CfC0xiJy1AzcrpuTw04vw.IFjfmQ1fL7k8qfvM.bKJHC1m', 'kasir', '2026-11-24 09:40:47'),
(2, 'OWNER', 'YARA OWNER', 'OWNER', 'owner@yaralaundry.com', '$2y$10$4GMirw1Aq6veoMPSPcZ0xO5731lvXruuYZ.QP1XD1cBAu8Y3dZbMG', 'owner', '2026-11-24 09:40:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_no` (`receipt_no`),
  ADD KEY `fk_orders_customer` (`customer_id`),
  ADD KEY `fk_orders_service` (`service_id`),
  ADD KEY `idx_orders_status` (`status_step`),
  ADD KEY `idx_orders_delivery` (`delivery_type`),
  ADD KEY `idx_orders_overdue` (`is_overdue`);

--
-- Indexes for table `order_logs`
--
ALTER TABLE `order_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_logs_order` (`order_id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_otp_phone` (`phone`,`purpose`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_logs`
--
ALTER TABLE `order_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `fk_orders_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `order_logs`
--
ALTER TABLE `order_logs`
  ADD CONSTRAINT `fk_logs_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
