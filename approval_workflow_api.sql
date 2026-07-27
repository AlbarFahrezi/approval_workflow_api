-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 06:39 AM
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
-- Database: `approval_workflow_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_histories`
--

CREATE TABLE `approval_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `approval_request_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(255) NOT NULL,
  `to_status` varchar(255) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval_histories`
--

INSERT INTO `approval_histories` (`id`, `approval_request_id`, `user_id`, `from_status`, `to_status`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'draft', 'submitted', 'Request submitted', '2026-07-20 22:58:06', '2026-07-20 22:58:06'),
(2, 1, 3, 'submitted', 'approved', 'Disetujui oleh Manager.', '2026-07-20 23:55:43', '2026-07-20 23:55:43'),
(3, 3, 2, 'draft', 'submitted', 'Request submitted', '2026-07-20 23:57:34', '2026-07-20 23:57:34'),
(4, 3, 3, 'submitted', 'rejected', 'Ditolak karena anggaran belum tersedia.', '2026-07-20 23:57:39', '2026-07-20 23:57:39'),
(5, 4, 2, 'draft', 'submitted', 'Request submitted', '2026-07-21 00:08:52', '2026-07-21 00:08:52'),
(6, 4, 3, 'submitted', 'rejected', 'Budget belum tersedia.', '2026-07-21 00:10:15', '2026-07-21 00:10:15'),
(8, 8, 2, 'draft', 'submitted', 'Request submitted', '2026-07-21 01:02:34', '2026-07-21 01:02:34'),
(9, 10, 2, 'draft', 'submitted', 'Request submitted', '2026-07-24 00:41:14', '2026-07-24 00:41:14'),
(10, 10, 7, 'submitted', 'approved', 'Disetujui oleh manager.', '2026-07-24 01:00:14', '2026-07-24 01:00:14'),
(11, 5, 2, 'draft', 'submitted', 'Request submitted', '2026-07-24 01:06:39', '2026-07-24 01:06:39'),
(12, 5, 7, 'submitted', 'rejected', 'Budget belum tersedia.', '2026-07-24 01:06:47', '2026-07-24 01:06:47');

-- --------------------------------------------------------

--
-- Table structure for table `approval_requests`
--

CREATE TABLE `approval_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('draft','submitted','approved','rejected') NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval_requests`
--

INSERT INTO `approval_requests` (`id`, `user_id`, `title`, `description`, `status`, `submitted_at`, `approved_at`, `rejected_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Pembelian Laptop Baru', 'Laptop untuk divisi IT dan Developer (ini update).', 'approved', '2026-07-20 22:58:05', '2026-07-20 23:55:43', NULL, '2026-07-20 20:10:46', '2026-07-20 23:55:43'),
(3, 3, 'Pembelian Kayu', 'Mengajukan pembelian kayu.', 'rejected', '2026-07-20 23:57:34', NULL, '2026-07-20 23:57:39', '2026-07-20 23:56:54', '2026-07-20 23:57:39'),
(4, 2, 'Pembelian Monitor', 'Mengajukan pembelian monitor baru.', 'rejected', '2026-07-21 00:08:52', NULL, '2026-07-21 00:10:15', '2026-07-21 00:06:18', '2026-07-21 00:10:15'),
(5, 2, 'Pembelian Keyboard', 'Mengajukan pembelian Keyboard baru.', 'rejected', '2026-07-24 01:06:39', NULL, '2026-07-24 01:06:47', '2026-07-21 00:23:00', '2026-07-24 01:06:47'),
(8, 2, 'Pembelian jendela', 'Mengajukan pembelian jendela baru.', 'submitted', '2026-07-21 01:02:34', NULL, NULL, '2026-07-21 01:02:13', '2026-07-21 01:02:34'),
(10, 2, 'Pembelian Totebag', 'Laptop untuk divisi Produk', 'approved', '2026-07-24 00:41:14', '2026-07-24 01:00:14', NULL, '2026-07-22 23:40:15', '2026-07-24 01:00:14'),
(11, 9, 'Pembelian CCTV', 'Mengajukan pembelian CCTV untuk sistem Pengawasan.', 'draft', NULL, NULL, NULL, '2026-07-23 19:10:30', '2026-07-23 19:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_21_014127_create_personal_access_tokens_table', 2),
(5, '2026_07_21_014414_add_role_to_users_table', 3),
(6, '2026_07_21_025446_create_approval_requests_table', 4),
(7, '2026_07_21_041140_create_approval_histories_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'b31c927e10ea89628194960fe28de9412020e51f28695504182038bd71dac067', '[\"*\"]', NULL, NULL, '2026-07-20 19:21:52', '2026-07-20 19:21:52'),
(3, 'App\\Models\\User', 2, 'auth_token', '6706476a0ac33710441243d85245d12c6fe9f3dc7145088e89b6edf8d38f19ec', '[\"*\"]', NULL, NULL, '2026-07-20 20:08:16', '2026-07-20 20:08:16'),
(4, 'App\\Models\\User', 2, 'auth_token', 'f03d951a3b8270bf70dcad7607d7fb0b37d580b621763ef5892e4733a7caa3fc', '[\"*\"]', '2026-07-20 20:20:20', NULL, '2026-07-20 20:08:40', '2026-07-20 20:20:20'),
(5, 'App\\Models\\User', 2, 'auth_token', '706d935fb1d9d03032ff42ec4f462714eb12d4fad380a707b07685fe225de457', '[\"*\"]', '2026-07-20 23:57:34', NULL, '2026-07-20 22:55:57', '2026-07-20 23:57:34'),
(6, 'App\\Models\\User', 3, 'auth_token', '3801da6ad76ae2092fcaaeb6517b4d6b6cd64b5091a9392ad140afdca78b7b77', '[\"*\"]', NULL, NULL, '2026-07-20 23:37:23', '2026-07-20 23:37:23'),
(7, 'App\\Models\\User', 3, 'auth_token', '2a7d44291a34f6b0d5089ce046956d19a5e39778e48b50e175de0543bf162be2', '[\"*\"]', '2026-07-21 00:03:30', NULL, '2026-07-20 23:37:42', '2026-07-21 00:03:30'),
(8, 'App\\Models\\User', 2, 'auth_token', 'd820122c1fb368dd6a7992abaf98ef6ce99334493943dd896a9a4c6e37948c8a', '[\"*\"]', '2026-07-21 00:23:00', NULL, '2026-07-21 00:05:34', '2026-07-21 00:23:00'),
(9, 'App\\Models\\User', 3, 'auth_token', 'fe355c3c3e48cb54b8ef11fec3293edb9061169d541ffab46cc42e56dc0d11b4', '[\"*\"]', '2026-07-21 00:17:36', NULL, '2026-07-21 00:09:15', '2026-07-21 00:17:36'),
(10, 'App\\Models\\User', 2, 'auth_token', 'da7dbdf71d44965ed534dae310d03f4f919883ca96826d768a73bfe1b58aa5ff', '[\"*\"]', '2026-07-21 00:23:43', NULL, '2026-07-21 00:22:23', '2026-07-21 00:23:43'),
(11, 'App\\Models\\User', 2, 'auth_token', 'adb8b58817726906e40d2f4d5c7635b68f5d83dbe5dc9b5b141e7dab5f2b5fcd', '[\"*\"]', '2026-07-22 00:37:04', NULL, '2026-07-21 00:38:04', '2026-07-22 00:37:04'),
(12, 'App\\Models\\User', 1, 'auth_token', '0fbbd21882229ab3dc9681580aa3693749c0911fce49762f0ce5807b919be5a2', '[\"*\"]', '2026-07-21 02:01:59', NULL, '2026-07-21 01:50:59', '2026-07-21 02:01:59'),
(13, 'App\\Models\\User', 4, 'auth_token', '76b25fc59e0ad8a28f98f116dcdd70b68fa940083721d9dc9f0a66d8a428ffdc', '[\"*\"]', NULL, NULL, '2026-07-21 01:58:58', '2026-07-21 01:58:58'),
(14, 'App\\Models\\User', 2, 'auth_token', '692e96dc36e3b4b932f174ce8f1b3211f252945c57e0559a7f5aee62f88bd60f', '[\"*\"]', '2026-07-21 02:03:44', NULL, '2026-07-21 02:03:23', '2026-07-21 02:03:44'),
(15, 'App\\Models\\User', 3, 'auth_token', '9b5f9ae7d64fccf1ff772b6a3e35a3785fcb677bb28391ba498a064473382813', '[\"*\"]', '2026-07-21 02:04:16', NULL, '2026-07-21 02:04:07', '2026-07-21 02:04:16'),
(17, 'App\\Models\\User', 3, 'auth_token', '119f57e3a27fff3df64bb17a4b130efde88104d4c3a2f020f6e9c490ed575690', '[\"*\"]', NULL, NULL, '2026-07-21 18:45:23', '2026-07-21 18:45:23'),
(18, 'App\\Models\\User', 3, 'auth_token', '99c8c28cbf6a3ad81dea242fff7dcd1e4e88d15c0362c5343fc4cc787fbd9f8c', '[\"*\"]', NULL, NULL, '2026-07-21 18:45:38', '2026-07-21 18:45:38'),
(19, 'App\\Models\\User', 1, 'auth_token', '2aa6d6bf531e9ea48b60a0fb6e4da1b17df8acdac94f3f4b16800ecffb9305cc', '[\"*\"]', '2026-07-21 19:25:32', NULL, '2026-07-21 18:48:18', '2026-07-21 19:25:32'),
(20, 'App\\Models\\User', 5, 'auth_token', 'ce6bcc8b58b9627887744cc9780e29d53020d0d4f8e35306adf041768b293574', '[\"*\"]', NULL, NULL, '2026-07-21 19:05:43', '2026-07-21 19:05:43'),
(21, 'App\\Models\\User', 6, 'auth_token', 'cc77f980a91257140e1b4d46179e67b40710867070bfde4d9980a12787f5b791', '[\"*\"]', NULL, NULL, '2026-07-21 19:06:28', '2026-07-21 19:06:28'),
(22, 'App\\Models\\User', 7, 'auth_token', 'aa8eae7f08d1beed2099a08fc37dea632e680dd1ee556d8b24ebfb55baf0e942', '[\"*\"]', '2026-07-21 19:27:26', NULL, '2026-07-21 19:26:50', '2026-07-21 19:27:26'),
(23, 'App\\Models\\User', 8, 'auth_token', '2998cd2449d328f16a02700122f0f66b2ffc36172821204cf8d52f6bf9dbda14', '[\"*\"]', NULL, NULL, '2026-07-21 20:35:50', '2026-07-21 20:35:50'),
(24, 'App\\Models\\User', 1, 'auth_token', 'e1c668fb4317722b3d5b6a525260b17460b742c1107af0dc5a392cf32867ee24', '[\"*\"]', '2026-07-21 23:27:08', NULL, '2026-07-21 23:19:42', '2026-07-21 23:27:08'),
(25, 'App\\Models\\User', 7, 'auth_token', 'd790eb0ec0ca14e9f09653a8c1feda4c9645e641bd1908d175bb17b1e86b076e', '[\"*\"]', NULL, NULL, '2026-07-21 23:34:13', '2026-07-21 23:34:13'),
(26, 'App\\Models\\User', 2, 'auth_token', 'd93afd9628a0a919ec0e4f9c19ff2c39f4a897e082072bb55d3d141d6abcbef8', '[\"*\"]', '2026-07-21 23:59:02', NULL, '2026-07-21 23:34:23', '2026-07-21 23:59:02'),
(27, 'App\\Models\\User', 7, 'auth_token', 'b37e0b37f4b743b94a515c180353fb169bc1d99a712453c4450aaf571a118308', '[\"*\"]', '2026-07-22 00:55:17', NULL, '2026-07-21 23:59:43', '2026-07-22 00:55:17'),
(28, 'App\\Models\\User', 1, 'auth_token', '8694a279e8dc8afddcd871c1768efbb8a103823243bf8858e808b4b776fa5f0b', '[\"*\"]', '2026-07-22 00:59:01', NULL, '2026-07-22 00:58:43', '2026-07-22 00:59:01'),
(29, 'App\\Models\\User', 2, 'auth_token', 'c3fa1b3ca31c6b145a7f3b60c12f1e8b45673e45f6d181ff95b5224bd507749d', '[\"*\"]', '2026-07-22 01:00:32', NULL, '2026-07-22 01:00:07', '2026-07-22 01:00:32'),
(30, 'App\\Models\\User', 7, 'auth_token', '34d6605cb50e5dbec8dfe948c5f9cdec2eadf3d1f6bd90dd7debf9f496dbb473', '[\"*\"]', '2026-07-22 01:03:38', NULL, '2026-07-22 01:02:45', '2026-07-22 01:03:38'),
(31, 'App\\Models\\User', 7, 'auth_token', 'd8f4cdca4d8eed210ae62c78b76c7d477d93e16a775e546bc3742b7361807ddf', '[\"*\"]', '2026-07-22 01:46:12', NULL, '2026-07-22 01:45:45', '2026-07-22 01:46:12'),
(32, 'App\\Models\\User', 2, 'auth_token', '02839f274548effe260ab0534e90ec0660b2a68aff4bec90b15440cf6e09eab2', '[\"*\"]', NULL, NULL, '2026-07-22 19:08:28', '2026-07-22 19:08:28'),
(33, 'App\\Models\\User', 1, 'auth_token', 'd4b11393d45bed509fe83966c9b6b3a438e3b8e14ba06a7c0597fd5ac80298aa', '[\"*\"]', NULL, NULL, '2026-07-22 19:57:49', '2026-07-22 19:57:49'),
(34, 'App\\Models\\User', 1, 'auth_token', 'c221864c0d4db39d7c3f87ed1920b4bcbf87835dc54da43f5f0ebb011cb37e3e', '[\"*\"]', '2026-07-22 23:23:49', NULL, '2026-07-22 23:18:03', '2026-07-22 23:23:49'),
(35, 'App\\Models\\User', 2, 'auth_token', '5994534ba9235ce4c8188f77e70db16b3d0ab01f8dc7474cf352b25cdfd202b7', '[\"*\"]', '2026-07-22 23:40:48', NULL, '2026-07-22 23:33:34', '2026-07-22 23:40:48'),
(36, 'App\\Models\\User', 9, 'auth_token', '51ef5c961df2ec98daa1a84bf0049978fdf25e0bb696f6d084a637e9a43a6b4c', '[\"*\"]', NULL, NULL, '2026-07-23 02:04:55', '2026-07-23 02:04:55'),
(37, 'App\\Models\\User', 9, 'auth_token', 'c94e78f0c4209b43b53922f29df0680f4518146c193ab165cf45758e6e8eddb5', '[\"*\"]', NULL, NULL, '2026-07-23 02:14:55', '2026-07-23 02:14:55'),
(38, 'App\\Models\\User', 9, 'auth_token', '6d3de82417c3eb65fdc4dd00e070d94cf57b40a35469ea66d1c31af775487818', '[\"*\"]', NULL, NULL, '2026-07-23 02:16:09', '2026-07-23 02:16:09'),
(40, 'App\\Models\\User', 9, 'auth_token', 'e781cd73c2e8d2c2d9520e7879f17196be4364cc4a30d1ef08762cec8952e633', '[\"*\"]', '2026-07-23 19:48:46', NULL, '2026-07-23 18:06:47', '2026-07-23 19:48:46'),
(41, 'App\\Models\\User', 1, 'auth_token', '623e5e5b513d30f7e4a968b0a0ee31b86881a38bc8cb5b228071527763c40b19', '[\"*\"]', '2026-07-24 00:22:56', NULL, '2026-07-23 19:51:39', '2026-07-24 00:22:56'),
(42, 'App\\Models\\User', 2, 'auth_token', '516f062d49dbae9e1891f81b9444c74d020e7e50ca973325c7111f857f0c354e', '[\"*\"]', '2026-07-24 01:06:39', NULL, '2026-07-24 00:22:01', '2026-07-24 01:06:39'),
(43, 'App\\Models\\User', 7, 'auth_token', '3a1b590111097ca13d3468728a9efee2bda674512a940c0b6875b150330e866f', '[\"*\"]', '2026-07-26 18:35:34', NULL, '2026-07-24 00:59:45', '2026-07-26 18:35:34'),
(44, 'App\\Models\\User', 1, 'auth_token', 'baf9791183c2ef1a5d1f5d9be099f9642297aaaa74fb3ae83b029e20855e19e0', '[\"*\"]', NULL, NULL, '2026-07-26 18:23:39', '2026-07-26 18:23:39'),
(45, 'App\\Models\\User', 1, 'auth_token', '9ca5b8427762f7c067d87cc4de0f6c44944e851ce58b05f6907debb3e955d3ee', '[\"*\"]', '2026-07-26 18:49:35', NULL, '2026-07-26 18:48:34', '2026-07-26 18:49:35'),
(46, 'App\\Models\\User', 1, 'auth_token', '135b330445a972b306973d94b5b5c306f443b0c1b03467e9a5f7fa5bbdf9b901', '[\"*\"]', '2026-07-26 20:26:05', NULL, '2026-07-26 19:28:22', '2026-07-26 20:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6sQzRraxExNbfFBhUpChEOsgLRnXBahQ4sETq0Oo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI5Y2owb0RTRzlLNDJPWUtLSzdScHBKcGt1bUZxZzRJclk5S1ZuVnV3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1784858208),
('g6c8MvkMMiLoEwmqGuSuQc4Im3X3vNK5mzYFiSnn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.129.1 Chrome/148.0.7778.280 Electron/42.6.0 Safari/537.36', 'eyJfdG9rZW4iOiJQV3FYbGZHOHM4SHNLcVBRTXZsQjVVdWQ5SmdpOTI5bzNDOFBJWXppIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1784711861),
('mdAannzRQNvS6fqpsBSm5lSVzQK97HpntCJ7E4Sz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIyTFRDOXRnU2NkREVpZVJHRkZGUWoyVExIYUJFSHB3cVlFbmQ2UzVRIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1784711854),
('ooksHyTLOyAyuCAKOdXzOgv6JOT8K0UPHzACLYhG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJHUDBXRjl5R2F5OGZVaGdYTjFIbmFMOFFtTmoydGdyUGF6SUhDTXh5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kb2NzXC9hcGkiLCJyb3V0ZSI6InNjcmFtYmxlLmRvY3MudWkifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1784711831),
('x3k17PZDwEiWbYcd1DQLAIWpApK4kqx61fZ9evcy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJCTGhJOU9sMmZ4T2JVV01QZnNzZ1VSWFJ5bmV4ZXJsV09GMXFaRHR2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1784711840);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','manager','employee') NOT NULL DEFAULT 'employee',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', 'admin', NULL, '$2y$12$OyVd/NqwwgSAxst5i7RcWe/Fg3s.Tmug/7PtQ/aTXrIB/r6lQboju', NULL, '2026-07-20 19:21:52', '2026-07-20 19:21:52'),
(2, 'Employee', 'employee@example.com', 'employee', NULL, '$2y$12$bmTZ41BwgSVK/y1efsUIe.OoW.OEBXrGiTtyLVkOcQpS2qOZ.cuEG', NULL, '2026-07-20 20:08:16', '2026-07-20 20:08:16'),
(3, 'Faisal Update', 'faisal@example.com', 'manager', NULL, '$2y$12$Xfbx.1XETPd7X5Rjkr07R.7YDQAFQ7Cq/3FHklDmOXtr9yA1lf9NC', NULL, '2026-07-20 23:37:23', '2026-07-21 18:43:16'),
(6, 'Saino Saipul', 'ino@example.com', 'employee', NULL, '$2y$12$/s2xHHeWZIx9vsVl5SOti.9VMWtCAtVF3gewAIYizPBFrHUBo9ln2', NULL, '2026-07-21 19:06:28', '2026-07-21 19:06:28'),
(7, 'Richard', 'richard@example.com', 'manager', NULL, '$2y$12$7ecP8aP80kUj35mdL0eKUuJ.wXcDSPGnxKfsR5HOEYM3f/17b4jkm', NULL, '2026-07-21 19:25:33', '2026-07-21 19:25:33'),
(8, 'Indonesia', 'indo@example.com', 'employee', NULL, '$2y$12$6d7.5qdr18RPV.pP2A9F4O25ALw3SQXqDH9UdWPkXfP9U6/CdiK2C', NULL, '2026-07-21 20:35:50', '2026-07-21 20:35:50'),
(9, 'Albar Update', 'AlbarUpdate@gmail.com', 'employee', NULL, '$2y$12$TCK8L4DHjQXzaroBL7inzO3lG8lRywPwIf9ac3vp2qmh2RXBw/IFm', NULL, '2026-07-23 02:04:55', '2026-07-23 18:41:41'),
(10, 'Regina Handayani', 'regina@example.com', 'manager', NULL, '$2y$12$KJYRVs13W1PL2PiDghBYX.wPcl7rUtWjuVDLapvANreWA9g/N6Xy6', NULL, '2026-07-26 20:16:57', '2026-07-26 20:16:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_histories`
--
ALTER TABLE `approval_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approval_histories_approval_request_id_foreign` (`approval_request_id`),
  ADD KEY `approval_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `approval_requests`
--
ALTER TABLE `approval_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approval_requests_user_id_foreign` (`user_id`);

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
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval_histories`
--
ALTER TABLE `approval_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `approval_requests`
--
ALTER TABLE `approval_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_histories`
--
ALTER TABLE `approval_histories`
  ADD CONSTRAINT `approval_histories_approval_request_id_foreign` FOREIGN KEY (`approval_request_id`) REFERENCES `approval_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approval_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `approval_requests`
--
ALTER TABLE `approval_requests`
  ADD CONSTRAINT `approval_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
