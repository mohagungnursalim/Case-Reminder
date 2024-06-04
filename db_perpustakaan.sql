-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 17, 2024 at 01:22 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id`, `nama_lengkap`, `kelas`, `jurusan`, `alamat`, `telepon`, `email`, `created_at`, `updated_at`) VALUES
(9, 'Stiven Cahyati', 'XII', 'Teknik Elektronika', 'Jl. Miangas', '085757063914', 'stiven@gmail.com', '2024-05-06 02:24:15', '2024-05-06 02:24:15'),
(11, 'Gideon Namlea Lesnusa', 'XII', 'Teknik Elektronika', 'Jl. Rusak', '085757063913', 'gideon@gmail.com', '2024-05-06 02:52:55', '2024-05-06 02:52:55'),
(12, 'Wahyu Dwe', 'XI', 'Teknik Geospasial', 'Jl.Jati', '085757063976', 'wahyudwe@gmail.com', '2024-05-08 18:24:51', '2024-05-08 18:24:51'),
(13, 'Ridwan', 'XII', 'Teknik Ketenagalistrikan', 'Jl.Mamboro', '085757063933', 'ridwan@gmail.com', '2024-05-10 03:09:08', '2024-05-10 03:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_buku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_buku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengarang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_terbit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah` float NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `kode_buku`, `judul_buku`, `pengarang`, `penerbit`, `tahun_terbit`, `jumlah`, `created_at`, `updated_at`) VALUES
(13, '978-979-22-5235-2', 'Sapiens: Riwayat Singkat Umat Manusia', 'Yuval Noah Harari', '2011', '2011', 2, '2024-05-10 06:11:26', '2024-05-11 05:44:35'),
(14, '978-0-312-42109-4', 'The Sixth Extinction: An Unnatural History', 'Elizabeth Kolbert', '2014', '2014', 4, '2024-05-10 06:13:19', '2024-05-10 06:13:19'),
(15, '978-0-393-33771-7', 'Guns, Germs, and Steel: The Fates of Human Societies', 'Jared Diamond', '1997', '1997', 2, '2024-05-10 06:14:05', '2024-05-10 06:14:05'),
(16, '978-0-202-30922-2', 'Indonesia: A Modern History', 'George McTurnan Kahin', '1962', '1962', 1, '2024-05-10 06:15:03', '2024-05-10 06:15:03'),
(17, '978-0-670-06163-5', 'Capital in the Twenty-First Century', 'Thomas Piketty', '2014', '2014', 1, '2024-05-10 06:15:45', '2024-05-10 06:15:45'),
(18, '978-0-06-432262-4', 'Story of Art', 'E.H. Gombrich', '1950', '1950', 1, '2024-05-10 06:17:14', '2024-05-10 06:17:57');

-- --------------------------------------------------------

--
-- Table structure for table `buku_kategori`
--

CREATE TABLE `buku_kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `buku_id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku_kategori`
--

INSERT INTO `buku_kategori` (`id`, `buku_id`, `kategori_id`, `created_at`, `updated_at`) VALUES
(39, 7, 14, NULL, NULL),
(40, 7, 15, NULL, NULL),
(42, 9, 14, NULL, NULL),
(43, 10, 14, NULL, NULL),
(44, 11, 14, NULL, NULL),
(45, 12, 14, NULL, NULL),
(46, 8, 14, NULL, NULL),
(47, 8, 15, NULL, NULL),
(49, 14, 30, NULL, NULL),
(50, 14, 31, NULL, NULL),
(51, 15, 22, NULL, NULL),
(52, 16, 22, NULL, NULL),
(53, 17, 23, NULL, NULL),
(56, 18, 22, NULL, NULL),
(57, 18, 32, NULL, NULL),
(58, 19, 15, NULL, NULL),
(59, 19, 22, NULL, NULL),
(61, 13, 22, NULL, NULL);

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
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(14, 'Novel', '2024-05-06 02:22:38', '2024-05-06 02:22:38'),
(15, 'Komik', '2024-05-06 02:22:45', '2024-05-06 02:22:45'),
(21, 'Sains dan Teknologi', '2024-05-10 05:59:27', '2024-05-10 05:59:27'),
(22, 'Sejarah', '2024-05-10 05:59:35', '2024-05-10 05:59:35'),
(23, 'Politik dan Ekonomi', '2024-05-10 05:59:45', '2024-05-10 05:59:45'),
(24, 'Filsafat dan Agama', '2024-05-10 05:59:56', '2024-05-10 05:59:56'),
(25, 'Psikologi dan Kehidupan Pribadi', '2024-05-10 06:02:43', '2024-05-10 06:02:43'),
(26, 'Memasak dan Makanan', '2024-05-10 06:02:52', '2024-05-10 06:02:52'),
(27, 'Perjalanan dan Petualangan', '2024-05-10 06:03:04', '2024-05-10 06:03:04'),
(29, 'Hewan Peliharaan', '2024-05-10 06:03:54', '2024-05-10 06:03:54'),
(30, 'Biologi', '2024-05-10 06:12:17', '2024-05-10 06:12:17'),
(31, 'Lingkungan', '2024-05-10 06:12:30', '2024-05-10 06:12:30'),
(32, 'Seni dan Budaya', '2024-05-10 06:16:25', '2024-05-10 06:16:25'),
(33, 'Kesehatan dan Kebugaran', '2024-05-10 10:50:56', '2024-05-10 10:50:56');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_04_24_014928_create_buku_table', 1),
(7, '2024_04_24_015442_create_anggota_table', 1),
(8, '2024_04_24_020044_create_kategori_table', 1),
(9, '2024_04_24_023358_create_buku_kategori_table', 1),
(10, '2024_04_24_015345_create_peminjaman_table', 2),
(11, '2024_05_06_042826_create_peminjaman_buku_table', 3);

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
  `anggota_id` bigint UNSIGNED DEFAULT NULL,
  `kode_peminjaman` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_peminjaman` datetime DEFAULT NULL,
  `tanggal_pengembalian` datetime DEFAULT NULL,
  `tanggal_dikembalikan` datetime DEFAULT NULL,
  `denda` int DEFAULT NULL,
  `status` enum('Dipinjam','Dikembalikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `anggota_id`, `kode_peminjaman`, `tanggal_peminjaman`, `tanggal_pengembalian`, `tanggal_dikembalikan`, `denda`, `status`, `created_at`, `updated_at`) VALUES
(23, 12, 'P20240523', '2024-05-11 00:00:00', '2024-05-13 00:00:00', '2024-05-11 00:00:00', 0, 'Dikembalikan', '2024-05-11 09:37:42', '2024-05-11 13:12:47'),
(24, 13, 'P20240524', '2024-05-11 00:00:00', '2024-05-15 00:00:00', '2024-05-31 00:00:00', 16000, 'Dikembalikan', '2024-05-11 09:38:18', '2024-05-11 18:07:50'),
(25, 11, 'P20240525', '2024-05-11 00:00:00', '2024-05-15 00:00:00', '2024-05-12 00:00:00', 0, 'Dikembalikan', '2024-05-11 13:40:07', '2024-05-11 15:50:01');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_buku`
--

CREATE TABLE `peminjaman_buku` (
  `id` bigint UNSIGNED NOT NULL,
  `peminjaman_id` bigint UNSIGNED NOT NULL,
  `buku_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman_buku`
--

INSERT INTO `peminjaman_buku` (`id`, `peminjaman_id`, `buku_id`, `created_at`, `updated_at`) VALUES
(30, 19, 18, NULL, NULL),
(31, 20, 17, NULL, NULL),
(32, 21, 14, NULL, NULL),
(33, 21, 13, NULL, NULL),
(34, 22, 16, NULL, NULL),
(35, 22, 14, NULL, NULL),
(36, 23, 18, NULL, NULL),
(37, 24, 17, NULL, NULL),
(38, 25, 17, NULL, NULL),
(39, 25, 16, NULL, NULL),
(40, 25, 15, NULL, NULL),
(41, 26, 18, NULL, NULL);

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `is_admin`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'MOH. AGUNG NURSALIM', 1, 'mohagungnursalim@gmail.com', NULL, '$2y$12$SafnyOZ1MhVl88ySVA7yGemDQszF0aNVJ526w6nZbFl2chnHmW1gm', 'D2PVi11R8roAXpMx09t7k5mxpvh92YENgqGia5CGlDgKy68fZBrCT9gg1atH', '2024-04-29 19:42:26', '2024-05-11 08:20:51'),
(13, 'Stiven Cahyati Angely', NULL, 'stiven@gmail.com', NULL, '$2y$12$4yeprvItFayY2d1LSfVS9ecC9EEJcC2yAE51xPU2Wq/N/292QVP3e', 'OF4B2DwUB1veDQZCrcBP9d9eDFN53vnWaos2ugY5BIdBbNh1Ch524sCG3C4c', '2024-05-05 02:40:23', '2024-05-07 18:39:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anggota_email_unique` (`email`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buku_kategori`
--
ALTER TABLE `buku_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `buku_kategori`
--
ALTER TABLE `buku_kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
