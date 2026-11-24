-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2026 at 06:18 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db-sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `siswa_id`, `tanggal`, `status`, `keterangan`, `created_at`) VALUES
(1, 5, '2026-10-31', 'Hadir', NULL, '2026-11-11 01:48:05'),
(2, 1, '2026-10-31', 'Hadir', NULL, '2026-11-11 01:48:05'),
(3, 3, '2026-10-31', 'Hadir', NULL, '2026-11-11 01:48:05'),
(4, 4, '2026-10-31', 'Alpa', NULL, '2026-11-11 01:48:05'),
(5, 6, '2026-10-31', 'Hadir', NULL, '2026-11-11 01:48:05'),
(6, 2, '2026-10-31', 'Hadir', NULL, '2026-11-11 01:48:05'),
(7, 5, '2026-11-11', 'Sakit', 'demam', '2026-11-11 01:59:36'),
(8, 1, '2026-11-11', 'Hadir', '', '2026-11-11 01:59:36'),
(9, 3, '2026-11-11', 'Hadir', '', '2026-11-11 01:59:36'),
(10, 4, '2026-11-11', 'Alpa', 'jaga bebek lahiran', '2026-11-11 01:59:36'),
(11, 6, '2026-11-11', 'Hadir', '', '2026-11-11 01:59:36'),
(12, 2, '2026-11-11', 'Hadir', '', '2026-11-11 01:59:36'),
(18, 7, '2026-11-11', 'Alpa', 'sakit kuning (sk)', '2026-11-11 02:01:12'),
(27, 1, '2026-11-12', 'Alpa', 'jaga ayam bertelur', '2026-11-12 01:54:08'),
(28, 2, '2026-11-12', 'Sakit', '', '2026-11-12 01:54:08'),
(29, 3, '2026-11-12', 'Izin', '', '2026-11-12 01:54:08'),
(30, 4, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(31, 5, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(32, 6, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(33, 7, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(34, 8, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(35, 9, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(36, 10, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(37, 11, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(38, 12, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(39, 13, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(40, 14, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(41, 15, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(42, 16, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(43, 17, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(44, 18, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(45, 19, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(46, 21, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(47, 20, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(48, 22, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(49, 23, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(50, 24, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(51, 25, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(52, 26, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(53, 27, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(54, 29, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(55, 30, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(56, 31, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(57, 28, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(58, 32, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(59, 33, '2026-11-12', 'Hadir', '', '2026-11-12 01:54:08'),
(126, 1, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(127, 2, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(128, 3, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(129, 4, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(130, 5, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(131, 6, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(132, 7, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(133, 8, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(134, 9, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(135, 10, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(136, 11, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(137, 12, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(138, 13, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(139, 14, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(140, 15, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(141, 16, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(142, 17, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(143, 18, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(144, 19, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(145, 21, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(146, 20, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(147, 22, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(148, 23, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(149, 24, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(150, 25, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(151, 26, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(152, 27, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(153, 29, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(154, 30, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(155, 31, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(156, 28, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(157, 32, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55'),
(158, 33, '2026-11-18', 'Hadir', '', '2026-11-18 08:47:55');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `nama`, `password`, `created_at`) VALUES
(1, 'admin', 'Amelia', '$2y$12$iWUHpB1y2/Wcy/tXOU8eZOzmbS82/hzhnsyY7cKzdB98ukN13hVCe', '2026-11-11 03:15:49');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `mata_pelajaran` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `nip`, `nama`, `jenis_kelamin`, `mata_pelajaran`, `no_hp`, `alamat`, `created_at`) VALUES
(1, '00099', 'TAIN SIDIK S.KOM', 'Laki-laki', 'INFORMATIKA', '0896473298', 'BINJAI SEKITAR', '2026-11-09 06:48:32'),
(2, '0097', 'NILA SARI', 'Perempuan', 'INFORMATIKA', '0896473298', 'TANJUNG SEKITAR', '2026-11-09 06:54:37');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `wali_kelas` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `mata_pelajaran` varchar(100) NOT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT 0.00,
  `nilai_uts` decimal(5,2) DEFAULT 0.00,
  `nilai_uas` decimal(5,2) DEFAULT 0.00,
  `nilai_akhir` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id`, `siswa_id`, `mata_pelajaran`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `created_at`) VALUES
(2, 1, 'INFORMATIKA', 70.00, 80.02, 76.00, 75.41, '2026-11-12 01:57:24'),
(3, 2, 'INFORMATIKA', 89.00, 86.00, 79.00, 84.10, '2026-11-12 01:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `kelas` varchar(30) NOT NULL,
  `alamat` text DEFAULT NULL,
  `jenis_kelamin` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nama`, `nis`, `kelas`, `alamat`, `jenis_kelamin`) VALUES
(1, 'Abdul Hafiz Karim', '1001', 'XII RPL', 'pulau merbo', 'Laki-laki'),
(2, 'Abdul Rahman', '1002', 'XII RPL', 'seilepan', 'Laki-laki'),
(3, 'Adea Friscilla', '1003', 'XII RPL', 'pondok bengkok', 'Perempuan'),
(4, 'Andika Pratama', '1004', 'XII RPL', 'palau merbo', 'Laki-laki'),
(5, 'Arjuanda', '1005', 'XII RPL', 'cempa', 'Laki-laki'),
(6, 'Astina Ramadhani', '1006', 'XII RPL', 'tanjung beringin', 'Perempuan'),
(7, 'Ata Kurniawan', '1007', 'XII RPL', 'besilam', 'Laki-laki'),
(8, 'Dava Nuggraha', '1008', 'XII RPL', 'tanjung beringin', 'Laki-laki'),
(9, 'Dhea Amanda', '1009', 'XII RPL', 'tanjung pura', 'Perempuan'),
(10, 'Dida Al Imran', '1010', 'XII RPL', 'lubuk jaya', 'Laki-laki'),
(11, 'Dimas Rio Hartanto', '1011', 'XII RPL', 'sentang', 'Laki-laki'),
(12, 'Dwi Maulinda', '1012', 'XII RPL', 'tanjung beringin', 'Laki-laki'),
(13, 'Elisa', '1013', 'XII RPL', 'pondok bengkok', 'Perempuan'),
(14, 'Fahri Akbar', '1014', 'XII RPL', 'cempa', 'Perempuan'),
(15, 'Fardhandi Akbar Faarif', '1015', 'XII RPL', 'sanga 5', 'Laki-laki'),
(16, 'Farthur Zuhri Albaqi', '1016', 'XII RPL', 'sanga 5', 'Laki-laki'),
(17, 'Harjun Handayani Hutabarat', '1017', 'XII RPL', 'air hitam', 'Laki-laki'),
(18, 'Kelvin Sianturi', '1018', 'XII RPL', 'gebang', 'Laki-laki'),
(19, 'Laila Nazua Keyla', '1019', 'XII RPL', 'pulau merbo', 'Perempuan'),
(20, 'M Rizqi Ansyah Ramadhan Nasution', '1020', 'XII RPL', 'tekongan', 'Laki-laki'),
(21, 'M Adityansyah Pratama', '1021', 'XII RPL', 'kampung lalang', 'Laki-laki'),
(22, 'Masrin', '1022', 'XII RPL', 'kedaimuk', 'Laki-laki'),
(23, 'Mhd Fadli', '1023', 'XII RPL', 'pematang cengal', 'Laki-laki'),
(24, 'Muhammad Aril', '1024', 'XII RPL', 'palau mesjid', 'Laki-laki'),
(25, 'Nur Maulida Amelia Br Kaban', '1025', 'XII RPL', 'langkat indah', 'Perempuan'),
(26, 'Nuraini', '1026', 'XII RPL', 'gebang', 'Perempuan'),
(27, 'Putra Binjai Nababan', '1027', 'XII RPL', 'besilam', 'Laki-laki'),
(28, 'Rsyadan Haqul Maladzi', '1028', 'XII RPL', 'cempa', 'Laki-laki'),
(29, 'Reyfan Armada Manulang', '1029', 'XII RPL', 'bukit mengkirai', 'Laki-laki'),
(30, 'Riky Hamdani', '1030', 'XII RPL', 'sanga 5', 'Laki-laki'),
(31, 'Risma Elfina', '1031', 'XII RPL', 'lubuk jaya', 'Perempuan'),
(32, 'Sandi Syahdewa', '1032', 'XII RPL', 'cempa', 'Laki-laki'),
(33, 'Yudy Sulistio', '1033', 'XII RPL', 'palau mesjid', 'Laki-laki');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unik_absensi` (`siswa_id`,`tanggal`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kelas` (`nama_kelas`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
