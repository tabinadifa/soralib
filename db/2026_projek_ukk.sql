-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 24, 2026 at 12:01 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2026_projek_ukk`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` bigint UNSIGNED NOT NULL,
  `judul_buku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `jumlah_stok` int UNSIGNED NOT NULL DEFAULT '0',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `penulis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bahasa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isbn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_buku_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul_buku`, `kategori_id`, `jumlah_stok`, `deskripsi`, `penulis`, `penerbit`, `tahun_terbit`, `bahasa`, `isbn`, `gambar_buku_id`, `created_at`, `updated_at`) VALUES
(1, 'Hai, Miiko! (27)', 2, 9, 'Komik Hai, Miiko! 27 karya Eriko Ono melanjutkan kisah keseharian Miiko Yamada, siswi SD kelas 5/6 yang ceria dan energik. Volume ini menonjolkan cerita persahabatan, kekonyolan Miiko, dan momen manis seperti usaha Miiko membuat cake cokelat yang lezat.', 'Ono Eriko', 'Gramedia', '2017', 'Indonesia', '123-1212-2009', 1, '2026-04-22 04:11:08', '2026-04-23 07:45:11'),
(2, 'Laskar Pelangi', 1, 9, 'Cerita berpusat pada kehidupan 10 anak dari keluarga miskin di Belitong yang bersekolah di SD Muhammadiyah, sekolah yang penuh keterbatasan dan nyaris ditutup. Meski hidup dalam kekurangan, mereka memiliki semangat tinggi untuk menuntut ilmu. Kisah ini mengikuti petualangan mereka sehari-hari, perjuangan guru-gurunya (Bu Mus dan Pak Harfan), hingga karakter menonjol seperti Lintang si jenius dan Mahar yang kreatif.', 'Andrea Hirata', 'Bentang Pustaka', '2005', 'Indonesia', '230-232-2323', 2, '2026-04-22 04:16:29', '2026-04-23 00:28:13'),
(3, 'Blue Lock Vol. 2', 2, 6, 'Di tengah tekanan media dan tatapan sinis Sae Itoshi, babak penyisihan dimulai. Tim Z berjuang melawan tim lain yang berisi penyerang berbakat, memaksa mereka menemukan ego dan senjata rahasia masing-masing untuk lolos ke babak selanjutnya.', 'Yusuke Nomura', 'PT Elex Media Computindo', '2022', 'Indonesia', '300-123-9090', 3, '2026-04-22 04:19:00', '2026-04-23 01:10:45'),
(4, 'Negeri Para Bedebah', 1, 7, 'Thomas harus berhadapan dengan intrik, konspirasi, dan pengkhianatan dari para bedebah—penguasa dan pebisnis culas—saat berusaha menyelamatkan Bank Semesta.', 'Tere Liye', 'PT Gramedia Pustaka Utama', '2012', 'Indonesia', '310-123-2012', 5, '2026-04-22 13:39:32', '2026-04-23 00:36:04'),
(5, 'Uzumaki', 2, 1, 'Bercerita tentang kutukan spiral yang melanda kota fiksi Kurouzu-cho, di mana penduduknya terobsesi hingga mengalami kegilaan dan perubahan tubuh yang mengerikan.', 'Junji Ito', 'Akasha (PT Gramedia Pustaka Utama)', '2022', 'Indonesia', '450-123-4567', 11, '2026-04-23 01:29:03', '2026-04-23 01:29:57'),
(6, 'Ito Junji Compilation - Tomie Part 1 & Part 2', 2, 2, 'Tomie membuat pria terobsesi padanya hingga gila dan melakukan pembunuhan, seringkali berujung pada kematian Tomie sendiri sebelum ia muncul kembali.', 'Junji Ito', 'M&c! (Gramedia)', '2023', 'Indonesia', '467-234-3222', 12, '2026-04-23 01:36:04', '2026-04-23 01:36:04'),
(7, 'Mohammad Hatta: Biografi Singkat 1902-1980', 4, 4, 'Buku ini memotret perjalanan hidup Hatta sejak masa muda, pendidikan di ELS, perjuangannya di Belanda melalui Perhimpunan Indonesia, hingga menjadi Wakil Presiden pertama Indonesia dan Bapak Koperasi. Buku ini memberikan gambaran tentang sosok yang jujur, visioner, dan bersahaja', 'Salman Alfarizi', 'Garasi House', '2017', 'Indonesia', '520-123-9999', 13, '2026-04-23 01:38:46', '2026-04-23 01:38:46'),
(8, 'Robohnya Surau Kami', 3, 2, 'Kisah ini menyoroti kakek penjaga surau yang taat namun bunuh diri setelah terpengaruh cerita sindiran Ajo Sidi tentang Haji Saleh, seorang ahli ibadah yang dimasukkan ke neraka karena abai pada kehidupan dunia dan sesama.', 'Ali Akbar Navis', 'PT Gramedia Pustaka Utama', '1994', 'Indonesia', '9794030465', 14, '2026-04-23 01:43:29', '2026-04-23 01:43:29'),
(9, 'Tanah Para Bandit', 1, 2, 'Novel ini berpusat pada tokoh Padma, seorang gadis yang dilatih keras oleh kakeknya, Abu Syik, di kawasan Bukit Barisan. Padma terlibat dalam perjuangan melawan sindikat kejahatan yang dilindungi oleh pejabat korup. Cerita penuh dengan adegan aksi, misteri, dan perjuangan menegakkan kebenaran di wilayah yang penuh bandit.', 'Tere Liye', 'Sabak Grip Nusantara', '2023', 'Indonesia', '978-623-88296-7-5', 15, '2026-04-23 01:49:34', '2026-04-23 01:49:34'),
(10, '風都探偵 (1) (fūto Tantei 1 / Futo Detective Vol. 1)', 2, 1, 'Manga ini merupakan sekuel resmi dari seri tokusatsu Kamen Rider W (2009). Ceritanya mengikuti Shotaro Hidari dan Philip di Agensi Detektif Narumi, memecahkan kasus-kasus aneh yang melibatkan Gaia Memory di kota Futo.', 'Riku Sanjo', 'Shogakukan (Big Comics)', '2018', 'Jepang', '9784-09189-85-17', 16, '2026-04-23 01:57:57', '2026-04-23 07:05:54');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `file_managers`
--

CREATE TABLE `file_managers` (
  `id` bigint UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_managers`
--

INSERT INTO `file_managers` (`id`, `file_name`, `file_path`, `mime_type`, `size`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, '1776831062_images-2.jpg', 'storage/uploads/gambar-buku/1776831062_images-2.jpg', 'image/jpeg', 19157, 1, '2026-04-22 04:11:03', '2026-04-22 04:11:03'),
(2, '1776831381_laskar-pelangi-sampul.jpg', 'storage/uploads/gambar-buku/1776831381_laskar-pelangi-sampul.jpg', 'image/jpeg', 23879, 1, '2026-04-22 04:16:22', '2026-04-22 04:16:22'),
(3, '1776831537_images-3.jpg', 'storage/uploads/gambar-buku/1776831537_images-3.jpg', 'image/jpeg', 14062, 1, '2026-04-22 04:18:57', '2026-04-22 04:18:57'),
(5, '1776865166_images-4.jpg', 'storage/uploads/gambar-buku/1776865166_images-4.jpg', 'image/jpeg', 13511, 1, '2026-04-22 13:39:26', '2026-04-22 13:39:26'),
(6, '1776902292_download-2.jpg', 'storage/uploads/profile/1776902292_download-2.jpg', 'image/jpeg', 77765, 1, '2026-04-22 23:58:13', '2026-04-22 23:58:13'),
(7, '1776902519_download-3.jpg', 'storage/uploads/profile/1776902519_download-3.jpg', 'image/jpeg', 20244, 4, '2026-04-23 00:01:59', '2026-04-23 00:01:59'),
(8, '1776903600_ultraman-in-2022-foto-lucu-wallpaper-kartun.jpg', 'storage/uploads/bukti-pembayaran/1776903600_ultraman-in-2022-foto-lucu-wallpaper-kartun.jpg', 'image/jpeg', 12008, 4, '2026-04-23 00:20:00', '2026-04-23 00:20:00'),
(9, '1776904796_seijuro-akashi.jpg', 'storage/uploads/profile/1776904796_seijuro-akashi.jpg', 'image/jpeg', 68478, 6, '2026-04-23 00:39:56', '2026-04-23 00:39:56'),
(10, '1776906805_.jpg', 'storage/uploads/bukti-pembayaran/1776906805_.jpg', 'image/jpeg', 71025, 6, '2026-04-23 01:13:25', '2026-04-23 01:13:25'),
(11, '1776907731_images-5.jpg', 'storage/uploads/gambar-buku/1776907731_images-5.jpg', 'image/jpeg', 16365, 1, '2026-04-23 01:28:51', '2026-04-23 01:28:51'),
(12, '1776908159_images-6.jpg', 'storage/uploads/gambar-buku/1776908159_images-6.jpg', 'image/jpeg', 10035, 1, '2026-04-23 01:35:59', '2026-04-23 01:35:59'),
(13, '1776908322_128785.jpg', 'storage/uploads/gambar-buku/1776908322_128785.jpg', 'image/jpeg', 44722, 1, '2026-04-23 01:38:42', '2026-04-23 01:38:42'),
(14, '1776908602_1455480-651x1024.webp', 'storage/uploads/gambar-buku/1776908602_1455480-651x1024.webp', 'image/webp', 24552, 1, '2026-04-23 01:43:22', '2026-04-23 01:43:22'),
(15, '1776908965_2op5kuxbxmq8zvhdu8sqr8.jpg', 'storage/uploads/gambar-buku/1776908965_2op5kuxbxmq8zvhdu8sqr8.jpg', 'image/jpeg', 512477, 1, '2026-04-23 01:49:25', '2026-04-23 01:49:25'),
(16, '1776909470_images-7.jpg', 'storage/uploads/gambar-buku/1776909470_images-7.jpg', 'image/jpeg', 10230, 1, '2026-04-23 01:57:50', '2026-04-23 01:57:50');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_buku`
--

INSERT INTO `kategori_buku` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Novel', '2026-04-21 19:31:22', '2026-04-21 19:31:22'),
(2, 'Komik', '2026-04-21 19:40:18', '2026-04-21 19:40:18'),
(3, 'Cerpen', '2026-04-21 19:40:27', '2026-04-21 19:40:27'),
(4, 'Biografi', '2026-04-21 19:40:34', '2026-04-22 13:42:59');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_22_011022_add_fields_to_users_table', 2),
(5, '2026_04_22_012021_create_kategoris_table', 3),
(6, '2025_04_22_012743_create_file_managers_table', 4),
(7, '2026_04_22_012406_create_bukus_table', 5),
(8, '2026_04_22_105217_create_peminjamen_table', 6),
(9, '2026_04_22_110344_create_pengembalians_table', 7),
(10, '2026_04_23_065024_add_profile_id_to_users', 8);

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
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` bigint UNSIGNED NOT NULL,
  `buku_id` bigint UNSIGNED NOT NULL,
  `peminjam_id` bigint UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `total_buku` int NOT NULL,
  `status` enum('rejected','pending','approve','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `alasan_ditolak` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `buku_id`, `peminjam_id`, `tanggal_pinjam`, `tanggal_kembali`, `total_buku`, `status`, `alasan_ditolak`, `created_at`, `updated_at`) VALUES
(1, 3, 4, '2026-04-22', '2026-04-23', 1, 'returned', NULL, '2026-04-22 07:05:46', '2026-04-22 12:42:52'),
(2, 3, 4, '2026-04-22', '2026-04-23', 1, 'returned', NULL, '2026-04-22 07:05:52', '2026-04-23 00:33:30'),
(3, 3, 4, '2026-04-22', '2026-04-23', 1, 'returned', NULL, '2026-04-22 07:07:45', '2026-04-22 13:07:24'),
(4, 2, 4, '2026-04-22', '2026-04-23', 1, 'returned', NULL, '2026-04-22 07:11:18', '2026-04-22 11:51:42'),
(5, 1, 4, '2026-04-22', '2026-04-23', 2, 'returned', NULL, '2026-04-22 07:12:18', '2026-04-23 00:31:09'),
(6, 4, 4, '2026-04-23', '2026-04-24', 1, 'returned', NULL, '2026-04-23 00:18:45', '2026-04-23 00:36:04'),
(7, 2, 4, '2026-04-23', '2026-04-24', 1, 'returned', NULL, '2026-04-23 00:27:38', '2026-04-23 00:30:16'),
(8, 3, 6, '2026-04-23', '2026-04-24', 1, 'returned', NULL, '2026-04-23 00:40:14', '2026-04-23 01:10:45'),
(9, 5, 6, '2026-04-23', '2026-04-24', 1, 'returned', NULL, '2026-04-23 01:29:20', '2026-04-23 01:29:57'),
(10, 10, 4, '2026-04-23', '2026-04-24', 1, 'returned', NULL, '2026-04-23 02:56:16', '2026-04-23 04:44:58'),
(11, 1, 6, '2026-04-23', '2026-04-24', 1, 'approve', NULL, '2026-04-23 07:04:59', '2026-04-23 07:05:13'),
(12, 10, 6, '2026-04-23', '2026-04-24', 1, 'approve', NULL, '2026-04-23 07:05:32', '2026-04-23 07:05:54'),
(13, 1, 6, '2026-04-23', '2026-04-24', 1, 'returned', NULL, '2026-04-23 07:19:40', '2026-04-23 07:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id` bigint UNSIGNED NOT NULL,
  `peminjaman_id` bigint UNSIGNED NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `kondisi_buku` enum('baik','rusak_ringan','rusak_berat','hilang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `metode_pembayaran` enum('QRIS','tunai','tidak_denda','belum_ditentukan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('lunas','belum_lunas') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `denda` int NOT NULL DEFAULT '0',
  `file_bukti_pembayaran_id` bigint UNSIGNED DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`id`, `peminjaman_id`, `tanggal_pengembalian`, `kondisi_buku`, `metode_pembayaran`, `status`, `denda`, `file_bukti_pembayaran_id`, `catatan`, `created_at`, `updated_at`) VALUES
(3, 4, '2026-04-22', 'baik', 'tidak_denda', 'lunas', 0, NULL, NULL, '2026-04-22 11:51:42', '2026-04-22 11:51:42'),
(4, 1, '2026-04-22', 'baik', 'tidak_denda', 'lunas', 0, NULL, NULL, '2026-04-22 12:42:52', '2026-04-22 12:42:52'),
(5, 3, '2026-04-22', 'rusak_ringan', 'tunai', 'lunas', 20000, 8, NULL, '2026-04-22 13:07:24', '2026-04-23 00:20:00'),
(6, 7, '2026-04-23', 'hilang', 'belum_ditentukan', 'belum_lunas', 100000, NULL, NULL, '2026-04-23 00:30:16', '2026-04-23 00:30:16'),
(7, 5, '2026-04-23', 'baik', 'tidak_denda', 'lunas', 0, NULL, NULL, '2026-04-23 00:31:09', '2026-04-23 00:31:09'),
(9, 6, '2026-04-23', 'baik', 'tidak_denda', 'lunas', 0, NULL, NULL, '2026-04-23 00:36:04', '2026-04-23 00:36:04'),
(10, 8, '2026-04-23', 'rusak_ringan', 'QRIS', 'lunas', 4000, 10, NULL, '2026-04-23 01:10:45', '2026-04-23 01:14:02'),
(11, 9, '2026-04-23', 'baik', 'tidak_denda', 'lunas', 0, NULL, NULL, '2026-04-23 01:29:57', '2026-04-23 01:29:57'),
(12, 10, '2026-04-23', 'baik', 'tidak_denda', 'lunas', 0, NULL, NULL, '2026-04-23 04:44:58', '2026-04-23 04:44:58'),
(13, 13, '2026-04-23', 'baik', 'tidak_denda', 'lunas', 0, NULL, NULL, '2026-04-23 07:45:11', '2026-04-23 07:45:11');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4xBDCeerWqi9JWQ2BmaMGvAhArURO3GhumUhHlsP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ5c0dlTWp5R2F6VnBIQ01kWDlhNXZQUVhJMzZrT3RGWGxPQmNCbjVYIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3NvcmFsaWJcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1776930568),
('tyclRiNR9XLrBjHuGml2edHltnoUqed8bxiOuySI', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJSSHI1cWFHMHlJOWVjRllOdGE4VXRxRmp2MmY3ckNFNFhUWGlRMDZNIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3NvcmFsaWJcL3Npc3dhXC9waW5qYW0tYnVrdSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3NvcmFsaWJcL3Npc3dhXC9waW5qYW0tYnVrdSIsInJvdXRlIjoic2lzd2EucGVtaW5qYW1hbi5saXN0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjZ9', 1776930998);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','siswa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_active_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `role`, `nisn`, `kelas`, `email`, `phone`, `address`, `email_verified_at`, `password`, `last_active_at`, `remember_token`, `created_at`, `updated_at`, `profile_id`) VALUES
(1, 'Etmint Zero', 'admin', 'admin', NULL, NULL, 'admin@gmail.com', NULL, NULL, NULL, '$2y$12$qQQ.Ntn/G/CrTWYN4.L3POuTonvCwszZBJcYDKmLSlZOfSB1mM7KO', '2026-04-23 07:44:19', NULL, NULL, '2026-04-23 07:44:19', 6),
(2, 'Sakura Miyawaki', 'sakura', 'admin', NULL, NULL, 'sakura@gmail.com', '081282834389', 'Jalan Jalan', NULL, '$2y$12$zxV94v/i9Sk3GvfDycAegOs5a4BUD0E/ZJxivwJ7/3lqF.0kxhPHe', '2026-04-23 07:03:40', NULL, '2026-04-21 18:00:16', '2026-04-23 07:03:40', NULL),
(4, 'Naruto Uzumaki', 'naruto', 'siswa', '0087181423', '12 PPLG 2', 'naruto@gmail.com', '081282834389', 'Konohagakure', NULL, '$2y$12$8ap2162ogyq21Ydt8Z1sSuF4Kh7ZPhXeN7PGRiPsUtQGbSlJ.9bOi', '2026-04-23 02:40:32', NULL, '2026-04-21 20:21:20', '2026-04-23 02:40:32', 7),
(5, 'Ultraman', 'ultraman1', 'siswa', '0087181420', '12 PPLG 2', 'ultraman@gmail.com', '081282834389', 'Planet M78', NULL, '$2y$12$LcGtDz/kBhvkbJlrHF4gXu3KvaqANq3BYzmNHGchFjflpjof9e1uG', '2026-04-22 03:41:30', NULL, '2026-04-22 03:40:47', '2026-04-22 03:41:30', NULL),
(6, 'Akashi Seijuro', 'seijuro', 'siswa', '008245672', '12 ANM 1', 'akashi@gmail.com', '081282834389', 'Jalan Mana Weh', NULL, '$2y$12$NXJc2F8ma38uNhpMcOLef.lHdi1Bq10C6s8r3vw4WLCD37d9OmJG6', '2026-04-23 07:04:20', NULL, '2026-04-23 00:38:17', '2026-04-23 07:04:20', 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buku_isbn_unique` (`isbn`),
  ADD KEY `buku_kategori_id_foreign` (`kategori_id`),
  ADD KEY `buku_gambar_buku_id_foreign` (`gambar_buku_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `file_managers`
--
ALTER TABLE `file_managers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_managers_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_buku_id_foreign` (`buku_id`),
  ADD KEY `peminjaman_peminjam_id_foreign` (`peminjam_id`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengembalian_peminjaman_id_foreign` (`peminjaman_id`),
  ADD KEY `pengembalian_file_bukti_pembayaran_id_foreign` (`file_bukti_pembayaran_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_profile_id_foreign` (`profile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_managers`
--
ALTER TABLE `file_managers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_gambar_buku_id_foreign` FOREIGN KEY (`gambar_buku_id`) REFERENCES `file_managers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `buku_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_buku` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `file_managers`
--
ALTER TABLE `file_managers`
  ADD CONSTRAINT `file_managers_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_peminjam_id_foreign` FOREIGN KEY (`peminjam_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_file_bukti_pembayaran_id_foreign` FOREIGN KEY (`file_bukti_pembayaran_id`) REFERENCES `file_managers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pengembalian_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `file_managers` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
