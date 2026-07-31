-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 27, 2026 at 05:56 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kursus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id_admin` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_cabang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_gmaps` text COLLATE utf8mb4_unicode_ci,
  `no_telp_admin` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_cabang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `nama_cabang`, `link_gmaps`, `no_telp_admin`, `lokasi`, `detail`, `foto`, `foto_cabang`, `created_at`, `updated_at`) VALUES
(1, 'SATRIA JAYANTI Jatikramat', 'https://maps.app.goo.gl/Md1FrMRAFoEGDYYKA', '087777325440', 'No Jl. Raya Jatikramat No.99, RT.004/RW.001, Jatikramat, Kec. Jatiasih, Kota Bks, Jawa Barat 17421', 'Bekasi', '1776871674_cabang.jpeg', '1776871674_cabang.jpeg', '2026-04-18 08:56:33', '2026-06-10 11:40:02'),
(3, 'SATRIA JAYANTI Jatibening', 'https://maps.app.goo.gl/cZk8AxTDFy3bs6MZ6', '081398783568', 'Jl. Caman Raya No.81, RT.002/RW.003, Jatibening, Kec. Pd. Gede, Kota Bks, Jawa Barat 17412', 'Bekasi', '1776871633_cabang.jpeg', '1776871633_cabang.jpeg', '2026-04-22 08:27:13', '2026-06-10 11:41:38'),
(4, 'SATRIA JAYANTI Pondok Bambu', 'https://maps.app.goo.gl/MoB1hdWqs49ZyNT7A', '081990087770', 'Jl. Pahlawan Revolusi, RT.1/RW.4, Pd. Bambu, Kec. Duren Sawit, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13430', 'Jakarta Timur', '1776871782_cabang.jpeg', '1776871782_cabang.jpeg', '2026-04-22 08:29:42', '2026-07-27 10:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `cabangs`
--

CREATE TABLE `cabangs` (
  `id_cabang` bigint UNSIGNED NOT NULL,
  `nama_cabang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_cabang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_admin` bigint UNSIGNED DEFAULT NULL,
  `id_unit` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cutis`
--

CREATE TABLE `cutis` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pending','Disetujui','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cutis`
--

INSERT INTO `cutis` (`id`, `user_id`, `tanggal_mulai`, `tanggal_selesai`, `alasan`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-04-20', '2026-04-22', 'Pulang kampung sodara sakit', 'Disetujui', '2026-04-17 11:18:49', '2026-04-18 09:51:47'),
(2, 7, '2026-04-21', '2026-04-23', 'Pulkam', 'Disetujui', '2026-04-18 03:11:39', '2026-04-18 09:51:31'),
(3, 1, '2026-04-18', '2026-04-22', 'acara keluarga', 'Disetujui', '2026-04-18 04:37:25', '2026-04-21 00:42:20'),
(6, 1, '2026-04-23', '2026-04-24', 'Sakit', 'Disetujui', '2026-04-21 20:27:41', '2026-04-22 08:54:06'),
(7, 8, '2026-05-01', '2026-05-02', 'Sakit', 'Disetujui', '2026-04-30 01:54:45', '2026-04-30 01:56:56'),
(9, 2, '2026-06-10', '2026-06-11', 'Sedang ada acara keluarga', 'Ditolak', '2026-06-09 00:14:41', '2026-07-09 20:11:05'),
(10, 7, '2026-07-28', '2026-07-30', 'sedang ada acara keluarga', 'Ditolak', '2026-07-27 16:37:08', '2026-07-27 16:54:45');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `judul`, `foto`, `created_at`, `updated_at`) VALUES
(6, 'Tim Satria Jayanti', '1776744851_galeri.jpeg', '2026-04-20 21:14:11', '2026-04-20 21:14:11'),
(7, 'Unit Kendaraan', '1776746604_galeri.jpeg', '2026-04-20 21:43:24', '2026-04-20 21:43:24'),
(11, 'Unit Kendaraan', '1776746804_galeri.jpeg', '2026-04-20 21:46:44', '2026-04-20 21:46:44'),
(13, 'Tim Satria Jayanti', '1776869388_galeri.jpeg', '2026-04-22 07:49:48', '2026-04-22 07:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `instrukturs`
--

CREATE TABLE `instrukturs` (
  `id_instruktur` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_instruktur` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tgl_lahir` date DEFAULT NULL,
  `no_telp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_unit` bigint UNSIGNED DEFAULT NULL,
  `id_cabang` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `instructor_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Disetujui','Selesai','Batal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `is_extra_charge` tinyint(1) NOT NULL DEFAULT '0',
  `status_pembayaran_extra` enum('Tidak Ada','Belum Lunas','Lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tidak Ada',
  `catatan_evaluasi` text COLLATE utf8mb4_unicode_ci,
  `rating` int DEFAULT NULL,
  `feedback_siswa` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `user_id`, `instructor_id`, `unit_id`, `tanggal`, `jam_mulai`, `status`, `is_extra_charge`, `status_pembayaran_extra`, `catatan_evaluasi`, `rating`, `feedback_siswa`, `created_at`, `updated_at`, `branch_id`) VALUES
(33, 21, 23, NULL, '2026-04-28', '09:00', 'Disetujui', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-04-26 21:01:18', '2026-04-26 21:03:29', 3),
(34, 21, 23, NULL, '2026-04-29', '09:00', 'Disetujui', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-04-26 21:01:28', '2026-04-26 21:03:20', 3),
(35, 21, 24, NULL, '2026-04-30', '11:00', 'Disetujui', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-04-26 21:02:01', '2026-04-26 21:35:53', 3),
(39, 35, 24, NULL, '2026-05-04', '10:00', 'Disetujui', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-04-30 01:59:23', '2026-04-30 02:00:26', 3),
(49, 44, 7, NULL, '2026-06-10', '10:00', 'Disetujui', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-06-07 18:45:09', '2026-06-07 19:30:31', 4),
(50, 44, NULL, NULL, '2026-06-09', '11:00', 'Pending', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-06-07 18:46:53', '2026-06-07 18:46:53', 4),
(51, 45, 46, NULL, '2026-06-08', '10:00', 'Selesai', 0, 'Tidak Ada', 'Kopling nya kurang feeling', NULL, NULL, '2026-06-07 18:50:09', '2026-06-07 18:59:27', 4),
(68, 59, 2, 7, '2026-07-28', '13:00', 'Selesai', 0, 'Tidak Ada', 'Sudah cukup baik untuk gas dan rem', 5, 'instruktur sangat ramah dan belajar sangat menyenangkan', '2026-07-27 11:10:11', '2026-07-27 11:12:40', 1),
(69, 59, 7, 8, '2026-07-29', '13:00', 'Selesai', 0, 'Tidak Ada', 'Belokan masih terlalu melebar', NULL, NULL, '2026-07-27 16:28:39', '2026-07-27 16:39:26', 1),
(70, 59, 7, 8, '2026-07-30', '16:00', 'Selesai', 1, 'Belum Lunas', 'Sudah Cukup baik untuk penguasaan gas dan rem', NULL, NULL, '2026-07-27 16:58:09', '2026-07-27 17:02:06', 1),
(71, 59, 8, 8, '2026-08-01', '10:00', 'Selesai', 0, 'Tidak Ada', 'Sudah cukup baik, lanjutkan belajar parkir di pertemuan selanjutnya', NULL, NULL, '2026-07-27 16:59:17', '2026-07-27 17:03:36', 1),
(72, 59, 2, 8, '2026-07-31', '11:00', 'Selesai', 0, 'Tidak Ada', 'Sudah baik', NULL, NULL, '2026-07-27 16:59:51', '2026-07-27 17:04:19', 1),
(73, 59, 8, 8, '2026-08-03', '10:00', 'Selesai', 0, 'Tidak Ada', 'Sangat bagus', NULL, NULL, '2026-07-27 17:00:41', '2026-07-27 17:03:46', 1),
(74, 59, 7, 8, '2026-08-07', '16:00', 'Selesai', 1, 'Belum Lunas', 'sudah lancar di jalan raya, selanjutnya adalah belajar di tanjakan', NULL, NULL, '2026-07-27 17:01:24', '2026-07-27 17:02:26', 1),
(75, 60, 7, 8, '2026-07-29', '11:00', 'Selesai', 0, 'Tidak Ada', 'baik', NULL, NULL, '2026-07-27 17:09:02', '2026-07-27 17:11:00', 1),
(76, 60, NULL, NULL, '2026-07-30', '11:00', 'Pending', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-07-27 17:13:20', '2026-07-27 17:13:20', 1),
(77, 60, 7, 3, '2026-08-01', '09:00', 'Disetujui', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-07-27 17:27:09', '2026-07-27 17:51:10', 1),
(78, 60, NULL, NULL, '2026-07-28', '19:00', 'Pending', 1, 'Belum Lunas', NULL, NULL, NULL, '2026-07-27 17:27:26', '2026-07-27 17:27:26', 1),
(79, 60, 48, 5, '2026-07-29', '15:00', 'Disetujui', 0, 'Tidak Ada', NULL, NULL, NULL, '2026-07-27 17:49:10', '2026-07-27 17:49:10', 1),
(80, 61, 53, 8, '2026-07-30', '17:00', 'Disetujui', 1, 'Belum Lunas', NULL, NULL, NULL, '2026-07-27 17:51:44', '2026-07-27 17:51:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kursus`
--

CREATE TABLE `jadwal_kursus` (
  `id_kursus` bigint UNSIGNED NOT NULL,
  `tanggal_kursus` datetime NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `id_unit` bigint UNSIGNED DEFAULT NULL,
  `id_murid` bigint UNSIGNED DEFAULT NULL,
  `id_instruktur` bigint UNSIGNED DEFAULT NULL,
  `id_package` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_units`
--

CREATE TABLE `laporan_units` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `instruktur_id` bigint UNSIGNED NOT NULL,
  `tingkat_kendala` enum('Ringan','Berat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ringan',
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_laporan` enum('Menunggu','Diproses','Selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_units`
--

INSERT INTO `laporan_units` (`id`, `unit_id`, `instruktur_id`, `tingkat_kendala`, `deskripsi`, `status_laporan`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'Ringan', 'ban mobil bocor', 'Selesai', '2026-07-25 08:27:29', '2026-07-25 08:29:05'),
(2, 1, 24, 'Berat', 'Kampas kopling habis', 'Selesai', '2026-07-25 15:39:31', '2026-07-25 15:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `managements`
--

CREATE TABLE `managements` (
  `id_management` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_direktur` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_08_19_000000_create_failed_jobs_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2026_04_16_08000_create_users_table', 1),
(4, '2026_04_16_080857_create_semua_tabel_kursus', 1),
(5, '2026_04_16_092052_create_settings_table', 2),
(6, '2026_04_16_113549_add_foto_hero_to_settings_table', 3),
(7, '2026_04_16_115715_create_units_table', 4),
(8, '2026_04_16_163938_create_branches_table', 5),
(9, '2026_04_16_163956_create_team_members_table', 5),
(10, '2026_04_16_164025_update_settings_table_full', 6),
(11, '2026_04_17_054110_add_student_fields_to_users_table', 7),
(12, '2026_04_17_055246_add_logo_to_settings_table', 7),
(13, '2026_04_17_063111_add_package_id_to_users_table', 8),
(14, '2026_04_17_065146_create_pembayarans_table', 8),
(15, '2026_04_17_073623_add_sessions_to_packages_table', 9),
(16, '2026_04_17_073653_create_jadwals_table', 9),
(17, '2026_04_17_075308_add_jumlah_pertemuan_to_packages_table', 10),
(18, '2026_04_17_105355_add_evaluation_to_jadwals_table', 11),
(19, '2026_04_17_105659_add_instructor_id_to_jadwals_table', 12),
(20, '2026_04_17_110920_add_feedback_to_jadwals_table', 13),
(21, '2026_04_17_174945_ubah_tipe_jam_mulai_di_jadwals', 14),
(22, '2026_04_17_180054_create_cutis_table', 15),
(23, '2026_04_17_183657_add_transmisi_to_packages_and_users', 16),
(24, '2026_04_18_101215_add_jenis_tagihan_to_pembayarans_table', 17),
(25, '2026_04_18_154146_add_branch_id_to_core_tables', 18),
(26, '2026_04_18_172700_add_approved_by_to_pembayarans_table', 19),
(27, '2026_04_19_175337_add_lokasi_detail_to_branches_table', 20),
(28, '2026_04_20_061056_add_status_and_package_to_users_table', 21),
(29, '2026_04_21_053846_create_bank_accounts_table', 22),
(30, '2026_04_21_053924_add_bank_account_id_to_pembayarans_table', 22),
(31, '2026_04_22_163131_add_kategori_to_packages_table', 23),
(32, '2026_04_22_163257_add_extra_charge_to_jadwals_table', 23),
(33, '2026_04_29_075204_add_pajak_kir_columns_to_units_table', 24),
(34, '2026_04_29_083202_add_nopol_to_units_table', 25),
(35, '2026_06_09_174138_add_penolakan_to_pembayarans_table', 26),
(36, '2026_06_10_080857_add_id_siswa_to_users_table', 27),
(37, '2026_06_10_180745_add_gmaps_telp_to_branches_table', 28),
(38, '2026_07_24_171403_add_operasional_fields_to_units_table', 29),
(39, '2026_07_24_171406_add_tipe_instruktur_to_users_table', 29),
(40, '2026_07_24_171408_add_unit_id_to_jadwals_table', 29),
(41, '2026_07_25_035809_create_laporan_units_table', 30),
(42, '2026_07_25_230650_add_transmisi_to_units_table', 31);

-- --------------------------------------------------------

--
-- Table structure for table `murids`
--

CREATE TABLE `murids` (
  `id_murid` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_murid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id_package` bigint UNSIGNED NOT NULL,
  `nama_package` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `kategori` enum('Reguler','Non-Reguler') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Reguler',
  `pertemuan` int DEFAULT '0',
  `jumlah_pertemuan` int NOT NULL DEFAULT '1',
  `transmisi` enum('Manual','Matic','Manual & Matic') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Manual',
  `jumlah_sesi` int NOT NULL DEFAULT '5',
  `detail` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id_package`, `nama_package`, `harga`, `kategori`, `pertemuan`, `jumlah_pertemuan`, `transmisi`, `jumlah_sesi`, `detail`, `created_at`, `updated_at`) VALUES
(7, 'Manual 7x Pertemuan', 820000, 'Reguler', 7, 7, 'Manual', 5, 'Jadwal Latihan Senin - Jumat Jam 08.00 s/d 16.00', '2026-04-17 03:07:23', '2026-06-07 09:04:29'),
(8, 'Matic 7x Pertemuan', 925000, 'Reguler', 7, 7, 'Matic', 5, 'Jadwal Latihan Senin - Jumat Jam 08.00 s/d 16.00', '2026-04-17 03:08:07', '2026-06-10 10:44:47'),
(9, 'Matic 7x pertemuan', 1030000, 'Non-Reguler', 7, 7, 'Matic', 5, 'Senin - Minggu 08.00 - 20.00', '2026-04-17 03:09:27', '2026-06-10 10:44:58'),
(10, 'Manual 7x Pertemuan', 925000, 'Non-Reguler', 7, 7, 'Manual', 5, 'Senin - Minggu 08.00 - 20.00', '2026-04-17 03:10:20', '2026-06-07 09:08:05'),
(11, 'Manual 8x Pertemuan', 930000, 'Reguler', 8, 8, 'Manual', 5, 'Jadwal Latihan Senin - Jumat Jam 08.00 s/d 16.00', '2026-04-21 11:46:46', '2026-06-07 09:04:01'),
(13, 'Matic 8x Pertemuan', 980000, 'Reguler', 8, 1, 'Matic', 5, 'Jadwal Latihan Senin - Jumat Jam 08.00 s/d 16.00', '2026-04-22 08:03:40', '2026-06-10 10:45:12'),
(16, 'Reguler Manual 15 Kali pertemuan', 1400000, 'Reguler', 15, 1, 'Manual', 5, 'Jam Latihan 08.00 - 16.00', '2026-06-09 01:06:26', '2026-06-09 01:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `id_package` bigint UNSIGNED DEFAULT NULL,
  `bukti_bayar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_tagihan` int NOT NULL,
  `status` enum('Pending','Lunas','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `jenis_tagihan` enum('Paket Utama','Tambahan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Paket Utama',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penolakan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `user_id`, `id_package`, `bukti_bayar`, `total_tagihan`, `status`, `jenis_tagihan`, `keterangan`, `penolakan`, `created_at`, `updated_at`, `branch_id`, `approved_by`) VALUES
(20, 21, 9, '1776924925_bukti_20_21.jpeg', 1030000, 'Lunas', 'Paket Utama', 'Pendaftaran kursus paket: Matic 7x pertemuan', NULL, '2026-04-22 23:14:11', '2026-04-26 21:00:49', 3, 10),
(26, 35, 9, '1777538824_bukti_26_35.jpeg', 1030000, 'Lunas', 'Paket Utama', 'Pendaftaran kursus paket: Matic 7x pertemuan', NULL, '2026-04-30 01:46:14', '2026-04-30 01:47:57', 3, 10),
(34, 44, 7, '1780882978_bukti_34_44.jpeg', 820000, 'Lunas', 'Paket Utama', 'Pendaftaran kursus paket: Manual 7x Pertemuan', NULL, '2026-06-07 18:41:58', '2026-06-07 18:44:26', 4, 38),
(35, 45, 7, '1780883339_bukti_35_45.jpeg', 820000, 'Lunas', 'Paket Utama', 'Pendaftaran kursus paket: Manual 7x Pertemuan', NULL, '2026-06-07 18:48:34', '2026-06-07 18:49:24', 4, 38),
(36, 44, NULL, '1780883625_bukti_36_44.jpeg', 6000000, 'Lunas', 'Tambahan', 'Biaya Bikin SIM', NULL, '2026-06-07 18:52:58', '2026-06-07 18:55:08', 4, 38),
(52, 59, 8, '1785150448_bukti_52_59.jpeg', 925000, 'Lunas', 'Paket Utama', 'Pendaftaran Offline via Admin. Menunggu upload bukti bayar. (Via Bank: BCA)', NULL, '2026-07-27 11:05:57', '2026-07-27 11:09:18', 1, NULL),
(53, 59, NULL, NULL, 20000, 'Pending', 'Tambahan', 'Biaya tambahan sesi jam non-reguler (Pukul 16:00 WIB) - Didaftarkan Admin', NULL, '2026-07-27 16:58:09', '2026-07-27 16:58:09', 1, NULL),
(54, 59, NULL, '1785172594_bukti_54_59.jpeg', 20000, 'Pending', 'Tambahan', 'Biaya tambahan sesi jam non-reguler (Pukul 16:00 WIB) - Didaftarkan Admin (Via Bank: BCA)', NULL, '2026-07-27 17:01:24', '2026-07-27 17:16:34', 1, NULL),
(55, 60, 8, '1785172069_admin_upload_WhatsApp_Image_2026-04-17_at_14.30.39.jpeg', 925000, 'Lunas', 'Paket Utama', 'Pendaftaran Offline via Admin. Menunggu upload bukti bayar. (Via: Tunai / Cash)', NULL, '2026-07-27 17:05:58', '2026-07-27 17:07:49', 1, NULL),
(56, 60, NULL, NULL, 20000, 'Pending', 'Tambahan', 'Biaya tambahan sesi jam non-reguler (Pukul 19:00 WIB)', NULL, '2026-07-27 17:27:26', '2026-07-27 17:27:26', 1, NULL),
(57, 61, 7, '1785174631_admin_upload_WhatsApp_Image_2026-04-17_at_14.30.39.jpeg', 820000, 'Lunas', 'Paket Utama', 'Pendaftaran Offline via Admin. Menunggu upload bukti bayar. (Via: Tunai / Cash)', NULL, '2026-07-27 17:50:08', '2026-07-27 17:50:31', 1, NULL),
(58, 61, NULL, NULL, 20000, 'Pending', 'Tambahan', 'Biaya tambahan sesi jam non-reguler (Pukul 17:00 WIB)', NULL, '2026-07-27 17:51:44', '2026-07-27 17:51:44', 1, NULL),
(59, 61, NULL, NULL, 20000, 'Pending', 'Tambahan', 'Biaya charge pindah transmisi Manual ke Matic (Jadwal: 2026-07-30)', NULL, '2026-07-27 17:51:44', '2026-07-27 17:51:44', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PT. Satria Jayanti',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_description` text COLLATE utf8mb4_unicode_ci,
  `foto_hero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `visi` text COLLATE utf8mb4_unicode_ci,
  `misi` text COLLATE utf8mb4_unicode_ci,
  `about_text` text COLLATE utf8mb4_unicode_ci,
  `cara_daftar` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `nama_website`, `logo`, `hero_title`, `hero_description`, `foto_hero`, `no_telp`, `email`, `alamat`, `instagram`, `created_at`, `updated_at`, `visi`, `misi`, `about_text`, `cara_daftar`) VALUES
(1, 'PT. Satria Jayanti', '1776405650_logo.jpeg', 'Wujudkan Mimpimu Mengemudi', 'Aman, nyaman, dan profesional bersama instruktur berpengalaman.', '1776341654_hero_C_TipsBelajarMengemudiMobilbagiPemula 1542098555.jpg', '087777325440', 'info@satriajayanti.com', 'Jl. Jatiwaringin Raya, Pondok Gede, Bekasi', '@satriajayanti_kursus', '2026-04-16 02:33:59', '2026-04-16 23:00:50', 'Menjadi lembaga pendidikan dan pelatihan sekolah mengemudi yang ungul dan mampu bersaing di dunia sekolah mengemudi', '1, Menyiapkan pengemudi yang mampu berkendara dengan aman, sopan, dan bertanggung jawab.\r\n2. Menjadikan SATRIA JAYANTI sebagai pusat kursus mengemudi unggulan dengan mengedepankan kepentingan peserta kursus untuk meningkatkan kinerja pengemudi\r\n3. Menjadikan SATRIA JAYANTI sebagai lembaga kursus mengemudi yang terpercaya dan responsif terhadap masyarakat', 'Satria Jayanti mengintegrasikan kurikulum Nasional (SKKNI) yang berorientasi pada pembentukan karakter didikannya.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksis`
--

CREATE TABLE `transaksis` (
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `jenis_transaksi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_bayar` int NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `id_murid` bigint UNSIGNED DEFAULT NULL,
  `id_admin` bigint UNSIGNED DEFAULT NULL,
  `id_package` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `instruktur_id` bigint UNSIGNED DEFAULT NULL,
  `status_kepemilikan` enum('Tetap','Rolling') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tetap',
  `status_operasional` enum('Aktif','Maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `nama_mobil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nopol` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transmisi` enum('Manual','Matic','Manual & Matic') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Manual',
  `foto_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_jatuh_tempo_pajak` date DEFAULT NULL,
  `tgl_terakhir_bayar_pajak` date DEFAULT NULL,
  `tgl_jatuh_tempo_kir` date DEFAULT NULL,
  `tgl_terakhir_bayar_kir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `branch_id`, `instruktur_id`, `status_kepemilikan`, `status_operasional`, `nama_mobil`, `nopol`, `transmisi`, `foto_unit`, `tgl_jatuh_tempo_pajak`, `tgl_terakhir_bayar_pajak`, `tgl_jatuh_tempo_kir`, `tgl_terakhir_bayar_kir`, `created_at`, `updated_at`) VALUES
(1, 1, 48, 'Tetap', 'Aktif', 'XL7', 'B 1998 JKW', 'Manual', '1777451835_xl7.jpeg', '2027-04-29', '2026-04-29', '2026-10-29', '2026-04-29', '2026-04-16 05:11:27', '2026-07-26 16:18:02'),
(3, 1, 2, 'Tetap', 'Aktif', 'Avanza', 'B 2234 PAS', 'Manual', '1778952788_kendaraan.jpeg', '2027-06-25', '2026-06-25', '2027-01-10', '2026-07-10', '2026-05-16 10:33:08', '2026-07-25 08:29:45'),
(5, 1, 7, 'Tetap', 'Aktif', 'Avanza', 'B 1273 RTO', 'Manual', '1784994670_kendaraan.jpeg', '2027-07-25', '2026-07-25', '2027-01-25', '2026-07-25', '2026-07-25 15:51:10', '2026-07-25 18:33:16'),
(6, 3, 24, 'Tetap', 'Aktif', 'Avanza', 'B 1223 RSW', 'Manual', '1784994966_kendaraan.jpeg', '2027-07-27', '2026-07-27', '2027-01-27', '2026-07-27', '2026-07-25 15:56:06', '2026-07-27 07:29:26'),
(7, 1, 53, 'Tetap', 'Aktif', 'Toyota Russh', 'B 1252 TLG', 'Matic', '1785136864_toyota_rush.jpeg', '2027-07-27', '2026-07-27', '2027-01-27', '2026-07-27', '2026-07-27 07:20:45', '2026-07-27 07:29:16'),
(8, 1, 24, 'Tetap', 'Aktif', 'Suzuki XL7', 'B 1263 TLG', 'Matic', '1785137209_xl7.jpeg', '2027-07-27', '2026-07-27', '2027-01-27', '2026-07-27', '2026-07-27 07:26:49', '2026-07-27 08:41:28');

-- --------------------------------------------------------

--
-- Table structure for table `unit_kendaraans`
--

CREATE TABLE `unit_kendaraans` (
  `id_unit` bigint UNSIGNED NOT NULL,
  `merk_mobil` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nopol` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transmisi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kir` date DEFAULT NULL,
  `pajak` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `id_siswa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kategori_transmisi` enum('Manual','Matic','Manual & Matic') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','siswa','instruktur','management') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `tipe_instruktur` enum('Tetap','Backup') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Non-Aktif',
  `id_package` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `package_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_siswa`, `username`, `email`, `email_verified_at`, `password`, `nama_lengkap`, `no_telp`, `alamat`, `kategori_transmisi`, `role`, `tipe_instruktur`, `status`, `id_package`, `remember_token`, `created_at`, `updated_at`, `branch_id`, `package_id`) VALUES
(1, NULL, 'admin_kodau', 'admin@satriajayanti.com', NULL, '$2y$10$N0vhbI3Yd9He8kzojczvGeNFfBmgtrteQtPLaKDZOb9wkS5yqMr.G', 'Rikardo Satrio', '0878 - 7011 - 4108', NULL, NULL, 'admin', NULL, 'Non-Aktif', NULL, NULL, '2026-04-16 02:39:22', '2026-04-18 10:03:17', 1, NULL),
(2, NULL, 'adesjn', 'adesjn@satriajayanti.com', NULL, '$2y$10$uVQwHeM7nAKPm5CILUOKQut.6SixYcH7msEmhoy/C0Z7JoWOjndZ6', 'Ade', '08823232445', 'jalan raya kodau', 'Matic', 'instruktur', NULL, 'Non-Aktif', NULL, NULL, '2026-04-16 23:22:42', '2026-05-15 08:25:35', 1, NULL),
(7, NULL, 'bayusjn', 'bayusjn@satriajayanti.com', NULL, '$2y$10$G803MJUm/4Hz.4OE3/wKaurYzdb2ZUqLdpl7qIE2IX5RWvsCEcTWC', 'Bayu', '08772188282', 'Kebon Jeruk', 'Manual & Matic', 'instruktur', NULL, 'Non-Aktif', NULL, NULL, '2026-04-17 11:22:38', '2026-07-09 19:42:36', 1, NULL),
(8, NULL, 'imamsjn', 'imam@gmail.com', NULL, '$2y$10$SxwudUSIGZ8HCPCUjtkUmuJV2krh5K/TU6cP4qG.l0l23TEU2L/Aa', 'Imam', '08123465738', NULL, 'Manual & Matic', 'instruktur', NULL, 'Non-Aktif', NULL, NULL, '2026-04-17 12:16:52', '2026-07-27 07:28:31', 1, NULL),
(9, NULL, 'management', 'management@satriajayanti.com', NULL, '$2y$10$dRgkL.nOtMpcyQhu9Tg14eHyJ7Z4nCbuR5v/OS9xRbxb3t2wQyyNW', 'Direktur Utama', '081234567890', NULL, NULL, 'management', NULL, 'Non-Aktif', NULL, NULL, '2026-04-18 09:08:48', '2026-04-18 09:08:48', NULL, NULL),
(10, NULL, 'admin_jatibening', 'rakan@gmail.com', NULL, '$2y$10$6hI3YqnX6Mgj74qYF850FuP.gBWqTTefVwi.6i9fyfofnIHioxD1q', 'Rakan', '087777325440', NULL, NULL, 'admin', NULL, 'Non-Aktif', NULL, NULL, '2026-04-18 10:04:49', '2026-07-25 15:42:00', 3, NULL),
(21, NULL, 'nmsyrkn', 'nmsyrkn@gmail.com', NULL, '$2y$10$WIVYoKK70kw0E6vx6t.N5e0kQrupeg3cdLMaYbnOvu3Tm6bzTSWni', 'rakan', '0812345678', NULL, NULL, 'siswa', NULL, 'Aktif', 9, NULL, '2026-04-22 23:14:11', '2026-04-26 21:00:49', 3, NULL),
(24, NULL, 'asepsjn', 'asep123@gmail.com', NULL, '$2y$10$QTNhM9MxLQMD6MT4.4tKbOC2G0WJAYfGH3v9wzBg2suxNxjPxnqi.', 'Asep', '087882712771', NULL, 'Manual & Matic', 'instruktur', NULL, 'Non-Aktif', NULL, NULL, '2026-04-26 21:34:52', '2026-04-26 21:38:17', 3, NULL),
(35, NULL, 'rikardo', 'rikardosatrio1@gmail.com', NULL, '$2y$10$0bCy2QQ4wHEFF5JLt9uFfuWZ9AlvcViisD6RwuHarxRk32LzF5I1O', 'Rikardo', '0878782728', NULL, NULL, 'siswa', NULL, 'Aktif', 9, NULL, '2026-04-30 01:46:14', '2026-04-30 01:47:57', 3, NULL),
(38, NULL, 'admin_galaxy', 'miko@gmail.com', NULL, '$2y$10$kwXnWpn8eVjzthDYhyxmA.knnxAtU1SIMCiU72t0BFlfjMqDQ204S', 'Miko', '08978272821', NULL, NULL, 'admin', NULL, 'Non-Aktif', NULL, NULL, '2026-06-07 07:54:09', '2026-07-26 16:03:56', 4, NULL),
(44, NULL, 'ariw', 'ariwinata@gmail.com', NULL, '$2y$10$ILyoKfyKvZI/GQUlKDhmN.T1pza1uviULC0QoeQl0ym8eUQps0Yoe', 'Ari Winata', '08678271282', NULL, NULL, 'siswa', NULL, 'Aktif', 7, NULL, '2026-06-07 18:41:58', '2026-06-07 18:44:26', 4, NULL),
(45, NULL, 'dian', 'dian@gmail.com', NULL, '$2y$10$/SakEtrW5w83lOTYFmZDn.tOW7lmBbN5svN9uXS4X9VYgVN8P.tmC', 'Dian Sifa', '0828727182', NULL, NULL, 'siswa', NULL, 'Aktif', 7, NULL, '2026-06-07 18:48:34', '2026-06-07 18:49:24', 4, NULL),
(46, NULL, 'jokotingkir', 'joko@gmail.com', NULL, '$2y$10$uRhWAP0jlsf5pHzIMTpaPurvEoWMaE6cJ6UG5wtmLnJKBkLkNRmqW', 'Joko Tingkir', '0882727122', NULL, 'Manual & Matic', 'instruktur', NULL, 'Non-Aktif', NULL, NULL, '2026-06-07 18:52:07', '2026-06-07 18:52:07', 4, NULL),
(48, NULL, 'ajidsjn', 'ajid@gmail.com', NULL, '$2y$10$z1nxbtDgmCqbKlqjrEBTt.2rkoXIU.A34pAjVuxwP08DMJdmfTKX.', 'Ajid', '0897282728', NULL, 'Manual', 'instruktur', NULL, 'Non-Aktif', NULL, NULL, '2026-06-09 00:13:10', '2026-07-09 00:42:53', 1, NULL),
(53, NULL, 'dadangsjn', 'dadang@gmail.com', NULL, '$2y$10$wX/ldwBTmH2h2Sr1bb2we.7ghY2TWNIHjgEtO508Ug.tw97ETX6z.', 'dadang', '087870114108', NULL, 'Matic', 'instruktur', NULL, 'Non-Aktif', NULL, NULL, '2026-07-25 18:21:08', '2026-07-27 07:21:58', 1, NULL),
(59, 'SJN072601', 'mikosjn', 'miko123@gmail.com', NULL, '$2y$10$43ZeN7N7xmKBtM6JcIEfLusZUN6WBa05JsPFivRhBI/lBLyKkwm2u', 'Jatmiko', '08278271282', 'Bojongkulur', NULL, 'siswa', NULL, 'Aktif', 8, NULL, '2026-07-27 11:05:57', '2026-07-27 11:09:18', 1, NULL),
(60, 'SJN072602', 'susilo', 'susilo@gmail.com', NULL, '$2y$10$9rCB1k89W8ydG5PVlhmvn.6cZSHLTUiR2jJs0/a1HcFAiQIAzF/E6', 'Susilo', '08127821727', 'Bekasi raya', NULL, 'siswa', NULL, 'Aktif', 8, NULL, '2026-07-27 17:05:58', '2026-07-27 17:07:49', 1, NULL),
(61, 'SJN072603', 'satrio', 'satrio@gmail.com', NULL, '$2y$10$3JoG7V.DhGol9Ha5SiMn7.xj616gZU.mBUyHNYNCEOfwckZlgBJ/e', 'aldosatrio', '0982818281', 'usuashudh', NULL, 'siswa', NULL, 'Aktif', 7, NULL, '2026-07-27 17:50:08', '2026-07-27 17:50:31', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `admins_user_id_foreign` (`user_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cabangs`
--
ALTER TABLE `cabangs`
  ADD PRIMARY KEY (`id_cabang`),
  ADD KEY `cabangs_id_admin_foreign` (`id_admin`),
  ADD KEY `cabangs_id_unit_foreign` (`id_unit`);

--
-- Indexes for table `cutis`
--
ALTER TABLE `cutis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cutis_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instrukturs`
--
ALTER TABLE `instrukturs`
  ADD PRIMARY KEY (`id_instruktur`),
  ADD KEY `instrukturs_user_id_foreign` (`user_id`),
  ADD KEY `instrukturs_id_unit_foreign` (`id_unit`),
  ADD KEY `instrukturs_id_cabang_foreign` (`id_cabang`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_user_id_foreign` (`user_id`),
  ADD KEY `jadwals_branch_id_foreign` (`branch_id`),
  ADD KEY `jadwals_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `jadwal_kursus`
--
ALTER TABLE `jadwal_kursus`
  ADD PRIMARY KEY (`id_kursus`),
  ADD KEY `jadwal_kursus_id_unit_foreign` (`id_unit`),
  ADD KEY `jadwal_kursus_id_murid_foreign` (`id_murid`),
  ADD KEY `jadwal_kursus_id_instruktur_foreign` (`id_instruktur`),
  ADD KEY `jadwal_kursus_id_package_foreign` (`id_package`);

--
-- Indexes for table `laporan_units`
--
ALTER TABLE `laporan_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_units_unit_id_foreign` (`unit_id`),
  ADD KEY `laporan_units_instruktur_id_foreign` (`instruktur_id`);

--
-- Indexes for table `managements`
--
ALTER TABLE `managements`
  ADD PRIMARY KEY (`id_management`),
  ADD KEY `managements_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `murids`
--
ALTER TABLE `murids`
  ADD PRIMARY KEY (`id_murid`),
  ADD KEY `murids_user_id_foreign` (`user_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id_package`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayarans_user_id_foreign` (`user_id`),
  ADD KEY `pembayarans_branch_id_foreign` (`branch_id`),
  ADD KEY `pembayarans_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksis`
--
ALTER TABLE `transaksis`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksis_id_murid_foreign` (`id_murid`),
  ADD KEY `transaksis_id_admin_foreign` (`id_admin`),
  ADD KEY `transaksis_id_package_foreign` (`id_package`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `units_branch_id_foreign` (`branch_id`),
  ADD KEY `units_instruktur_id_foreign` (`instruktur_id`);

--
-- Indexes for table `unit_kendaraans`
--
ALTER TABLE `unit_kendaraans`
  ADD PRIMARY KEY (`id_unit`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_id_siswa_unique` (`id_siswa`),
  ADD KEY `users_branch_id_foreign` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cabangs`
--
ALTER TABLE `cabangs`
  MODIFY `id_cabang` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cutis`
--
ALTER TABLE `cutis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `instrukturs`
--
ALTER TABLE `instrukturs`
  MODIFY `id_instruktur` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `jadwal_kursus`
--
ALTER TABLE `jadwal_kursus`
  MODIFY `id_kursus` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_units`
--
ALTER TABLE `laporan_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `managements`
--
ALTER TABLE `managements`
  MODIFY `id_management` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `murids`
--
ALTER TABLE `murids`
  MODIFY `id_murid` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id_package` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksis`
--
ALTER TABLE `transaksis`
  MODIFY `id_transaksi` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `unit_kendaraans`
--
ALTER TABLE `unit_kendaraans`
  MODIFY `id_unit` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cabangs`
--
ALTER TABLE `cabangs`
  ADD CONSTRAINT `cabangs_id_admin_foreign` FOREIGN KEY (`id_admin`) REFERENCES `admins` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `cabangs_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `unit_kendaraans` (`id_unit`) ON DELETE SET NULL;

--
-- Constraints for table `cutis`
--
ALTER TABLE `cutis`
  ADD CONSTRAINT `cutis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instrukturs`
--
ALTER TABLE `instrukturs`
  ADD CONSTRAINT `instrukturs_id_cabang_foreign` FOREIGN KEY (`id_cabang`) REFERENCES `cabangs` (`id_cabang`) ON DELETE SET NULL,
  ADD CONSTRAINT `instrukturs_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `unit_kendaraans` (`id_unit`) ON DELETE SET NULL,
  ADD CONSTRAINT `instrukturs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwals_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jadwals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_kursus`
--
ALTER TABLE `jadwal_kursus`
  ADD CONSTRAINT `jadwal_kursus_id_instruktur_foreign` FOREIGN KEY (`id_instruktur`) REFERENCES `instrukturs` (`id_instruktur`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_kursus_id_murid_foreign` FOREIGN KEY (`id_murid`) REFERENCES `murids` (`id_murid`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_kursus_id_package_foreign` FOREIGN KEY (`id_package`) REFERENCES `packages` (`id_package`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_kursus_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `unit_kendaraans` (`id_unit`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_units`
--
ALTER TABLE `laporan_units`
  ADD CONSTRAINT `laporan_units_instruktur_id_foreign` FOREIGN KEY (`instruktur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_units_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `managements`
--
ALTER TABLE `managements`
  ADD CONSTRAINT `managements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `murids`
--
ALTER TABLE `murids`
  ADD CONSTRAINT `murids_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pembayarans_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayarans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksis`
--
ALTER TABLE `transaksis`
  ADD CONSTRAINT `transaksis_id_admin_foreign` FOREIGN KEY (`id_admin`) REFERENCES `admins` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksis_id_murid_foreign` FOREIGN KEY (`id_murid`) REFERENCES `murids` (`id_murid`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksis_id_package_foreign` FOREIGN KEY (`id_package`) REFERENCES `packages` (`id_package`) ON DELETE SET NULL;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `units_instruktur_id_foreign` FOREIGN KEY (`instruktur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
