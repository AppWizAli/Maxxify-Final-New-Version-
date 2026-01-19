-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 01:04 PM
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
-- Database: `mcqmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(191) NOT NULL,
  `bank_account_holder_name` text DEFAULT NULL,
  `bank_account_number` text DEFAULT NULL,
  `bank_account_iban` text DEFAULT NULL,
  `jazzCash_account_holder_name` text DEFAULT NULL,
  `jazzCash_account_number` text DEFAULT NULL,
  `easyPasisa_account_holder_name` text DEFAULT NULL,
  `easyPasisa_account_number` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `bank_name`, `bank_account_holder_name`, `bank_account_number`, `bank_account_iban`, `jazzCash_account_holder_name`, `jazzCash_account_number`, `easyPasisa_account_holder_name`, `easyPasisa_account_number`) VALUES
(1, 'Faysal bank ', 'Muhammad Atif ', '6262738389393', 'Pk72849929384748992', 'Muhammad Atif ', '03123912686', 'Muhammad Atif ', '03123912686 ');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `role`, `password`, `created_at`) VALUES
(10, 'FAIZANKHAN01', 'FK687266@GMAIL.COM', 'admin', '$2y$10$x5PbOgqGa23AvxXbfhKAcOyPxbH4L5r1FfUBou3G8znlsuxvgVUmS', '2025-09-01 19:13:37'),
(11, 'usamakhalid712712@gmail.com', 'usamakhalid712712@gmail.com', 'admin', '$2y$10$K7VY5Fn0/OqqxsdqICMzwuo.35onTyEAvFdVr5yAsqaoha4Q18I9a', '2025-09-11 19:12:20');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mcq_id` int(11) NOT NULL,
  `selected_option` varchar(1) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `user_id`, `mcq_id`, `selected_option`, `is_correct`, `created_at`) VALUES
(104, 41, 40, 'a', 1, '2025-08-11 16:40:48'),
(105, 41, 41, 'b', 0, '2025-08-11 16:40:54'),
(106, 41, 54, 'a', 1, '2025-08-11 16:43:25'),
(107, 41, 55, 'd', 0, '2025-08-11 16:43:30'),
(108, 41, 94, 'a', 1, '2025-08-11 16:43:56'),
(109, 41, 95, 'c', 0, '2025-08-11 16:44:00'),
(225, 43, 226, 'a', 0, '2025-08-21 21:59:39'),
(226, 43, 227, 'a', 0, '2025-08-21 21:59:42'),
(227, 43, 228, 'a', 0, '2025-08-21 21:59:45'),
(228, 43, 229, 'a', 0, '2025-08-21 21:59:48'),
(229, 43, 230, 'a', 0, '2025-08-21 21:59:51'),
(230, 43, 231, 'a', 0, '2025-08-21 21:59:54'),
(238, 43, 232, 'b', 1, '2025-08-25 23:02:57'),
(239, 43, 233, 'a', 0, '2025-08-25 23:03:00'),
(240, 43, 234, 'c', 0, '2025-08-25 23:03:02'),
(241, 43, 235, 'b', 1, '2025-08-25 23:03:08'),
(242, 43, 236, 'c', 0, '2025-08-25 23:03:13'),
(243, 43, 237, 'b', 1, '2025-08-25 23:03:17'),
(244, 43, 238, 'b', 1, '2025-08-25 23:03:20'),
(245, 43, 239, 'b', 1, '2025-08-25 23:03:22'),
(339, 44, 103, 'a', 0, '2025-08-13 20:10:26'),
(340, 44, 104, 'a', 0, '2025-08-13 20:10:30'),
(341, 44, 105, 'c', 0, '2025-08-13 20:10:34'),
(342, 44, 106, 'a', 0, '2025-08-13 20:10:37'),
(343, 44, 107, 'c', 0, '2025-08-13 20:10:42'),
(344, 44, 108, 'c', 0, '2025-08-13 20:10:45'),
(345, 43, 240, 'd', 1, '2025-08-17 15:57:31'),
(346, 43, 241, 'a', 0, '2025-08-17 15:57:35'),
(347, 43, 242, 'b', 0, '2025-08-17 15:57:38'),
(348, 43, 243, 'd', 1, '2025-08-17 15:57:42'),
(349, 43, 244, 'a', 0, '2025-08-15 13:57:57'),
(350, 43, 245, 'a', 0, '2025-08-15 13:58:00'),
(351, 43, 246, 'a', 0, '2025-08-15 13:58:01'),
(352, 43, 247, 'a', 0, '2025-08-15 13:58:12'),
(353, 43, 248, 'a', 0, '2025-08-15 13:56:07'),
(354, 43, 249, 'a', 0, '2025-08-15 13:58:20'),
(355, 43, 250, 'a', 0, '2025-08-15 13:58:22'),
(356, 43, 251, 'a', 0, '2025-08-15 13:58:24'),
(357, 43, 280, 'a', 0, '2025-08-15 13:58:25'),
(358, 43, 281, 'a', 0, '2025-08-15 13:58:27'),
(359, 43, 282, 'a', 0, '2025-08-15 13:58:31'),
(360, 43, 283, 'a', 0, '2025-08-15 13:58:32'),
(361, 43, 284, 'a', 0, '2025-08-15 13:58:33'),
(362, 43, 285, 'a', 0, '2025-08-15 13:58:35'),
(363, 43, 286, 'a', 0, '2025-08-15 13:58:36'),
(364, 43, 287, 'a', 0, '2025-08-15 13:58:37'),
(365, 43, 289, 'a', 0, '2025-08-15 13:58:40'),
(366, 43, 290, 'a', 0, '2025-08-15 13:58:41'),
(367, 43, 291, 'a', 0, '2025-08-15 13:58:43'),
(368, 43, 320, 'a', 0, '2025-08-15 13:58:44'),
(369, 43, 321, 'a', 0, '2025-08-15 13:58:46'),
(370, 43, 322, 'a', 0, '2025-08-15 13:58:47'),
(371, 43, 323, 'a', 0, '2025-08-15 13:58:49'),
(372, 43, 324, 'a', 0, '2025-08-15 13:58:51'),
(373, 43, 325, 'a', 0, '2025-08-15 13:58:52'),
(374, 43, 326, 'a', 0, '2025-08-15 13:58:56'),
(375, 43, 327, 'a', 0, '2025-08-15 13:58:58'),
(376, 43, 328, 'a', 0, '2025-08-15 13:58:59'),
(377, 43, 329, 'a', 0, '2025-08-15 13:59:01'),
(378, 43, 330, 'a', 0, '2025-08-15 13:59:02'),
(379, 43, 331, 'a', 0, '2025-08-15 13:59:04'),
(380, 43, 288, 'a', 0, '2025-08-15 13:58:39'),
(381, 43, 257, 'a', 0, '2025-08-15 14:00:18'),
(382, 43, 258, 'a', 0, '2025-08-15 14:00:19'),
(383, 43, 259, 'a', 0, '2025-08-15 14:00:21'),
(384, 43, 260, 'a', 0, '2025-08-15 14:00:22'),
(385, 43, 297, 'a', 0, '2025-08-15 14:00:26'),
(386, 43, 298, 'a', 0, '2025-08-15 14:00:27'),
(387, 43, 299, 'a', 0, '2025-08-15 14:00:29'),
(388, 43, 300, 'a', 0, '2025-08-15 13:59:51'),
(389, 43, 337, 'a', 0, '2025-08-15 14:00:33'),
(390, 43, 338, 'a', 0, '2025-08-15 14:00:35'),
(391, 43, 339, 'a', 0, '2025-08-15 14:00:36'),
(392, 43, 340, 'a', 0, '2025-08-15 14:00:41'),
(393, 43, 252, 'a', 0, '2025-08-15 14:02:33'),
(394, 43, 253, 'a', 0, '2025-08-15 14:02:44'),
(395, 43, 254, 'a', 0, '2025-08-15 14:01:17'),
(396, 43, 255, 'a', 0, '2025-08-15 14:02:49'),
(397, 43, 256, 'a', 0, '2025-08-15 14:02:55'),
(398, 43, 292, 'a', 0, '2025-08-15 14:01:22'),
(399, 43, 293, 'a', 0, '2025-08-15 14:02:57'),
(400, 43, 294, 'a', 0, '2025-08-15 14:02:59'),
(401, 43, 295, 'a', 0, '2025-08-15 14:03:10'),
(402, 43, 296, 'a', 0, '2025-08-15 14:03:12'),
(403, 43, 332, 'a', 0, '2025-08-15 14:03:15'),
(404, 43, 333, 'a', 0, '2025-08-15 14:03:22'),
(405, 43, 334, 'a', 0, '2025-08-15 14:03:24'),
(406, 43, 336, 'a', 0, '2025-08-15 14:03:27'),
(407, 43, 335, 'a', 0, '2025-08-15 14:03:26'),
(408, 41, 103, 'a', 0, '2025-08-20 13:34:58'),
(425, 43, 261, 'd', 1, '2025-08-18 20:12:12'),
(426, 43, 262, 'b', 0, '2025-08-18 20:12:18'),
(427, 43, 264, 'a', 0, '2025-08-18 20:12:22'),
(428, 43, 265, 'a', 0, '2025-08-18 20:12:28'),
(429, 43, 266, 'a', 0, '2025-08-18 20:12:32'),
(430, 43, 267, 'a', 0, '2025-08-18 20:12:38'),
(431, 43, 302, 'c', 0, '2025-08-18 20:12:45'),
(432, 43, 303, 'b', 0, '2025-08-18 20:12:49'),
(433, 43, 304, 'c', 0, '2025-08-18 20:12:53'),
(434, 43, 305, 'c', 0, '2025-08-18 20:12:58'),
(435, 43, 306, 'd', 1, '2025-08-18 20:13:08'),
(436, 43, 307, 'b', 0, '2025-08-18 20:13:13'),
(437, 43, 341, 'a', 0, '2025-08-18 20:13:17'),
(438, 43, 342, 'b', 0, '2025-08-18 20:13:20'),
(439, 43, 343, 'c', 0, '2025-08-18 20:13:24'),
(440, 43, 344, 'a', 0, '2025-08-18 20:13:46'),
(441, 43, 345, 'a', 0, '2025-08-18 20:14:03'),
(442, 43, 346, 'b', 0, '2025-08-18 20:14:11'),
(443, 43, 347, 'b', 0, '2025-08-18 20:14:15'),
(444, 41, 104, 'a', 0, '2025-08-20 13:35:02'),
(546, 47, 103, 'b', 1, '2025-08-28 12:09:37');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `teacher_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `percentage`, `expiry_date`, `created_at`, `teacher_id`) VALUES
(6, '123', 5.00, '2025-07-31', '2025-07-25 18:22:49', NULL),
(7, 'Atif01', 20.00, '2026-04-01', '2025-08-13 18:46:36', 6);

-- --------------------------------------------------------

--
-- Table structure for table `free_attempts`
--

CREATE TABLE `free_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `high_achievers`
--

CREATE TABLE `high_achievers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('MDCAT','NUMS','FSC') NOT NULL,
  `marks` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `top_rated` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `high_achievers`
--

INSERT INTO `high_achievers` (`id`, `name`, `category`, `marks`, `image`, `created_at`, `top_rated`) VALUES
(7, 'LAIBA KHAN', 'MDCAT', 195, 'uploads/IMG-20241103-WA0029.jpg', '2025-08-13 18:39:01', 1),
(8, 'RUMAISA', 'NUMS', 197, 'uploads/IMG-20250311-WA0015(1).jpg', '2025-08-13 18:40:02', 0),
(9, 'Umaima', 'MDCAT', 193, 'uploads/IMG-20240929-WA0011(2).jpg', '2025-08-13 18:41:08', 0),
(10, 'Dua', 'NUMS', 192, 'uploads/IMG-20241103-WA0023(1).jpg', '2025-08-13 18:41:51', 0),
(11, 'SAIMA', 'MDCAT', 192, 'uploads/IMG-20230716-WA0089(1)_1.jpg', '2025-08-13 18:42:55', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mcqs`
--

CREATE TABLE `mcqs` (
  `id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` char(1) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `option_a_explanation` text DEFAULT NULL,
  `option_b_explanation` text DEFAULT NULL,
  `option_c_explanation` text DEFAULT NULL,
  `option_d_explanation` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mcqs`
--

INSERT INTO `mcqs` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `topic_id`, `explanation`, `option_a_explanation`, `option_b_explanation`, `option_c_explanation`, `option_d_explanation`, `created_at`) VALUES
(548, 'Ex modi sunt velit ', 'Ipsum et ut reprehen', 'Dolore blanditiis se', 'Dolore quia libero c', 'Molestiae illum dic', 'A', 101, 'Voluptas mollitia il', 'Dolorem libero venia', 'Id consequatur itaqu', 'Laborum Aspernatur ', 'Esse pariatur Excep', '2026-01-08 16:08:47');

-- --------------------------------------------------------

--
-- Table structure for table `mcq_categories`
--

CREATE TABLE `mcq_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `mcq_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mcq_categories`
--

INSERT INTO `mcq_categories` (`id`, `name`, `mcq_type_id`) VALUES
(1, 'Chapter Wise', 3),
(2, 'Past Papers', 3),
(3, 'Mock Test', 3),
(4, 'Chapter Wise', 1),
(5, 'Past Papers', 1),
(6, 'Mock Test', 1),
(7, 'Chapter Wise', 2),
(8, 'Past Papers', 2),
(9, 'Mock Test', 2);

-- --------------------------------------------------------

--
-- Table structure for table `mcq_types`
--

CREATE TABLE `mcq_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mcq_types`
--

INSERT INTO `mcq_types` (`id`, `name`) VALUES
(1, 'MDCAT'),
(2, 'NUMS'),
(3, 'F.Sc');

-- --------------------------------------------------------

--
-- Table structure for table `packagepoints`
--

CREATE TABLE `packagepoints` (
  `id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `point` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packagepoints`
--

INSERT INTO `packagepoints` (`id`, `package_id`, `point`) VALUES
(4, 1, '✓ ALL MCQS WITH EXPLANATION'),
(5, 1, '✓ MDCAT CHAPTER WISE MCQS'),
(6, 1, '✓ MDCAT PAST PAPERS'),
(7, 1, '✓ MDCAT MOCK TEST'),
(8, 1, '✓ NUMS CHAPTER WISE MCQS'),
(9, 1, '✓ NUMS PAST PAPERS'),
(10, 1, '✓ NUMS MOCK TEST'),
(11, 1, '✓ DASHBOARD FEATURES'),
(12, 1, '✓ CHAPTER-WISE SHORTLISTINGS'),
(13, 1, '✓ LIVE PROGRESS'),
(14, 1, '✖️ FSC CHAPTER WISE MCQS'),
(15, 1, '✖️ FSC PAST PAPERS'),
(16, 1, '✖️ FSC MOCK TEST'),
(17, 1, '✖️ Ai QUIZ BUILDER'),
(32, 2, '✓ ALL MCQS WITH EXPLANATION'),
(33, 2, '✓ MDCAT CHAPTER WISE MCQS'),
(34, 2, '✓ MDCAT PAST PAPERS'),
(35, 2, '✓ MDCAT MOCK TEST'),
(36, 2, '✓ NUMS CHAPTER WISE MCQS'),
(37, 2, '✓ NUMS PAST PAPERS'),
(38, 2, '✓ NUMS MOCK TEST'),
(39, 2, '✓ DASHBOARD FEATURES'),
(40, 2, '✓ CHAPTER-WISE SHORTLISTINGS'),
(41, 2, '✓ LIVE PROGRESS'),
(42, 2, '✓ FSC CHAPTER WISE MCQS'),
(43, 2, '✓ FSC PAST PAPERS'),
(44, 2, '✓ FSC MOCK TEST'),
(45, 2, '✖️ Ai QUIZ BUILDER'),
(46, 3, '✓ ALL MCQS WITH EXPLANATION'),
(47, 3, '✓ MDCAT CHAPTER WISE MCQS'),
(48, 3, '✓ MDCAT PAST PAPERS'),
(49, 3, '✓ MDCAT MOCK TEST'),
(50, 3, '✓ NUMS CHAPTER WISE MCQS'),
(51, 3, '✓ NUMS PAST PAPERS'),
(52, 3, '✓ NUMS MOCK TEST'),
(53, 3, '✓ DASHBOARD FEATURES'),
(54, 3, '✓ CHAPTER-WISE SHORTLISTINGS'),
(55, 3, '✓ LIVE PROGRESS'),
(56, 3, '✓ FSC CHAPTER WISE MCQS'),
(57, 3, '✓ FSC PAST PAPERS'),
(58, 3, '✓ FSC MOCK TEST'),
(59, 3, '✓ Ai QUIZ BUILDER');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `price`) VALUES
(1, 'Begineer', '1000'),
(2, 'Moderate', '3000'),
(3, 'Professional', '5000');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answers`
--

CREATE TABLE `quiz_answers` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_index` int(11) NOT NULL,
  `selected_answer` varchar(1) NOT NULL,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `mcq_id` int(11) NOT NULL,
  `question_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` int(11) NOT NULL,
  `review` text NOT NULL,
  `field` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `number`, `review`, `field`, `image`, `created_at`) VALUES
(1, 'Ali Hamza', 2147483647, 'saddddasdasdasdad\r\nasdddahfA\r\ndfdasF\r\nasF\r\naF\r\nASDSDA\r\nSADADD\r\nfsdF\r\nsdddDF\r\ndsF\r\nsdaF\r\nsddF\r\ndsF\r\ndsF\r\ndsdF\r\ndsF\r\nsdF\r\nsd', '1', 'reviews_images/review_689c9f1c0752e5.52945428.png', '2025-08-13 14:20:12'),
(2, 'RUMAISA ', 192, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd7d770f800.81890565.jpg', '2025-08-13 18:22:15'),
(3, 'LAIBA ', 193, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd81452b683.58074871.jpg', '2025-08-13 18:23:16'),
(4, 'LAIBA KHAN ', 195, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd873a2a889.05220158.jpg', '2025-08-13 18:24:51'),
(5, 'DUA ', 191, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd89eb8e8e7.04631997.jpg', '2025-08-13 18:25:34'),
(6, 'HIBA ', 194, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd8c50d4c32.12994140.jpg', '2025-08-13 18:26:13'),
(7, 'MUSKAN HAROON ', 193, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd907884363.03836247.jpg', '2025-08-13 18:27:19'),
(8, 'IFRA IQBAL ', 189, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd9359fa101.77363218.jpg', '2025-08-13 18:28:06'),
(9, 'Fariha ', 196, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'MDCAT ', 'reviews_images/review_689cd95b2b9de1.22443251.jpg', '2025-08-13 18:28:43'),
(10, 'SAIMA ', 192, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'NUMS ', 'reviews_images/review_689cd9a6dea7d6.14993145.jpg', '2025-08-13 18:29:58'),
(11, 'MUSEERA ', 194, 'This academy is very astonishing \r\nIt gives me good experience \r\nAll video lectures and mcqs explanation is very helpful', 'NUMS ', 'reviews_images/review_689cda1ec77f67.44271755.jpg', '2025-08-13 18:31:58');

-- --------------------------------------------------------

--
-- Table structure for table `shortlistings`
--

CREATE TABLE `shortlistings` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `topic_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `category_id`) VALUES
(43, 'BIOLOGY', 4),
(44, 'CHEMISTRY', 4),
(45, 'PHYSICS', 4),
(46, 'ENGLISH', 4),
(47, 'LOGICAL REASONING', 4),
(48, 'SINDH', 5),
(49, 'PUNJAB', 5),
(50, 'KPK', 5),
(51, 'AKU', 5),
(52, 'BALOCHISTAN', 5),
(53, 'FULL PMDC SYLLABUS TEST 1', 6),
(54, 'FULL PMDC SYLLABUS TEST 2', 6),
(55, 'FULL PMDC SYLLABUS TEST 3', 6),
(56, 'FULL PMDC SYLLABUS TEST 4', 6),
(57, 'BIOLOGY', 7),
(58, 'CHEMISTRY', 7),
(59, 'PHYSICS', 7),
(60, 'ENGLISH', 7),
(61, 'PAST PAPER 2024', 8),
(62, 'PAST PAPER 2023', 8),
(63, 'PAST PAPER 2022', 8),
(64, 'NUMS MOCK TEST 1', 9),
(65, 'NUMS MOCK TEST 2', 9),
(66, 'NUMS MOCK TEST 3', 9),
(67, 'BIOLOGY', 1),
(68, 'CHEMISTRY', 1),
(69, 'PHYSICS', 1),
(70, 'ENGLISH', 1),
(71, '2020', 2),
(72, '2022', 2),
(73, '2015', 2),
(74, 'FULL SYLLABUS TEST 1', 3),
(75, 'FULL SYLLABUS TEST 2', 3),
(76, 'FULL SYLLABUS TEST 3', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `coupon_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `phone_number`, `created_at`) VALUES
(6, 'Dr Atif', '03123912686', '2025-08-13 18:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_free` int(11) NOT NULL DEFAULT 1,
  `is_publish` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `subject_id`, `name`, `is_free`, `is_publish`) VALUES
(54, 1, 'Math Topic 1', 1, 1),
(55, 1, 'Math Topic 2', 1, 1),
(56, 2, 'Chemistry Topic 1', 1, 1),
(57, 2, 'Chemistry Topic 2', 1, 1),
(58, 3, 'Physics Topic 1', 1, 1),
(59, 3, 'Physics Topic 2', 1, 1),
(60, 4, 'Biology Topic 1', 1, 1),
(61, 4, 'Biology Topic 2', 1, 1),
(62, 5, 'English Topic 1', 1, 1),
(63, 5, 'English Topic 2', 1, 1),
(64, 6, 'Urdu Topic 1', 1, 1),
(65, 6, 'Urdu Topic 2', 1, 1),
(66, 7, 'Islamiat Topic 1', 1, 1),
(67, 7, 'Islamiat Topic 2', 1, 1),
(68, 8, 'Computer Topic 1', 1, 1),
(69, 8, 'Computer Topic 2', 1, 1),
(70, 9, 'History Topic 1', 1, 1),
(71, 9, 'History Topic 2', 1, 1),
(72, 10, 'Geography Topic 1', 1, 1),
(73, 10, 'Geography Topic 2', 1, 1),
(74, 11, 'Economics Topic 1', 1, 1),
(75, 11, 'Economics Topic 2', 1, 1),
(76, 12, 'Accounting Topic 1', 1, 1),
(77, 12, 'Accounting Topic 2', 1, 1),
(78, 13, 'Business Topic 1', 1, 1),
(79, 13, 'Business Topic 2', 1, 1),
(80, 14, 'Statistics Topic 1', 1, 1),
(81, 14, 'Statistics Topic 2', 1, 1),
(82, 15, 'Sociology Topic 1', 1, 1),
(83, 15, 'Sociology Topic 2', 1, 1),
(84, 16, 'Psychology Topic 1', 1, 1),
(85, 16, 'Psychology Topic 2', 1, 1),
(86, 17, 'Education Topic 1', 1, 1),
(87, 17, 'Education Topic 2', 1, 1),
(88, 18, 'General Knowledge Topic 1', 1, 1),
(89, 18, 'General Knowledge Topic 2', 1, 1),
(90, 43, 'BIOLOGICAL MOLECULES', 0, 0),
(91, 43, 'CELL STRUCTURE & FUNCTIONS', 0, 1),
(92, 44, 'FUNDAMENTAL OF CHEMISTRY', 1, 1),
(93, 44, 'GASES', 1, 1),
(94, 45, 'FORCE & MOTION', 1, 1),
(95, 45, 'VECTOR', 1, 1),
(96, 48, '2024', 1, 1),
(97, 53, 'TEST 1', 1, 1),
(98, 57, 'BIOLOGICAL MOLECULES', 0, 0),
(99, 61, '2024', 1, 1),
(100, 64, 'TEST 1', 1, 1),
(101, 67, 'BIOLOGICAL MOLECULES', 1, 1),
(102, 72, '2022', 0, 0),
(103, 74, 'TEST 1', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `created_at`, `phone_number`) VALUES
(41, 'mudassarazam13@gmail.com', 'mudassar', '$2y$10$ZE9uZiOvYQaCoAohExuEOOqfIfnhq4FvDfahA8uyixq7NGCHNvrDy', '2025-07-26 05:58:55', '03025184597');

-- --------------------------------------------------------

--
-- Table structure for table `user_quizzes`
--

CREATE TABLE `user_quizzes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deck_name` varchar(255) NOT NULL,
  `estimated_time` varchar(10) NOT NULL,
  `mcq_count` int(11) NOT NULL,
  `selected_topics` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','completed','archived') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_teacher_id` (`teacher_id`);

--
-- Indexes for table `free_attempts`
--
ALTER TABLE `free_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `high_achievers`
--
ALTER TABLE `high_achievers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mcqs`
--
ALTER TABLE `mcqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mcq_type_id` (`topic_id`);

--
-- Indexes for table `mcq_categories`
--
ALTER TABLE `mcq_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mcq_type` (`mcq_type_id`);

--
-- Indexes for table `mcq_types`
--
ALTER TABLE `mcq_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packagepoints`
--
ALTER TABLE `packagepoints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_answer` (`quiz_id`,`user_id`,`question_index`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_quiz_answers_quiz_user` (`quiz_id`,`user_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mcq_id` (`mcq_id`),
  ADD KEY `idx_quiz_questions_quiz_id` (`quiz_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shortlistings`
--
ALTER TABLE `shortlistings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_coupon_id` (`coupon_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_quizzes`
--
ALTER TABLE `user_quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_quizzes_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=547;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `free_attempts`
--
ALTER TABLE `free_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `high_achievers`
--
ALTER TABLE `high_achievers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mcqs`
--
ALTER TABLE `mcqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=549;

--
-- AUTO_INCREMENT for table `mcq_categories`
--
ALTER TABLE `mcq_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mcq_types`
--
ALTER TABLE `mcq_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `packagepoints`
--
ALTER TABLE `packagepoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=823;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `shortlistings`
--
ALTER TABLE `shortlistings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user_quizzes`
--
ALTER TABLE `user_quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `fk_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `mcqs`
--
ALTER TABLE `mcqs`
  ADD CONSTRAINT `mcqs_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);

--
-- Constraints for table `mcq_categories`
--
ALTER TABLE `mcq_categories`
  ADD CONSTRAINT `fk_mcq_type` FOREIGN KEY (`mcq_type_id`) REFERENCES `mcq_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packagepoints`
--
ALTER TABLE `packagepoints`
  ADD CONSTRAINT `packagepoints_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD CONSTRAINT `quiz_answers_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_answers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_questions_ibfk_2` FOREIGN KEY (`mcq_id`) REFERENCES `mcqs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `fk_coupon_id` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_quizzes`
--
ALTER TABLE `user_quizzes`
  ADD CONSTRAINT `user_quizzes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
