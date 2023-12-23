-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-12-23 07:16:27
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gs_bm_db2`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `gs_bm_books`
--

CREATE TABLE `gs_bm_books` (
  `id` int(12) NOT NULL,
  `name` varchar(64) NOT NULL,
  `url` text NOT NULL,
  `content` text NOT NULL,
  `created_date` datetime NOT NULL,
  `update_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `gs_bm_books`
--

INSERT INTO `gs_bm_books` (`id`, `name`, `url`, `content`, `created_date`, `update_date`) VALUES
(21, '初めてのPHP', 'https://amzn.asia/d/efyN9ym', '初めてですよ～', '2023-12-22 19:13:46', '2023-12-22 19:17:39'),
(22, 'プログラミングPHP', 'https://amzn.asia/d/0pjBl1z', 'ぷろぐらみんぐするぞー', '2023-12-22 19:14:49', '2023-12-22 19:17:51'),
(23, 'PHP: The Good Parts', 'https://amzn.asia/d/i0iOrGI', 'どんなパーツがあるんだい？', '2023-12-22 19:15:26', '2023-12-22 19:18:07');

-- --------------------------------------------------------

--
-- テーブルの構造 `gs_bm_dog_ear`
--

CREATE TABLE `gs_bm_dog_ear` (
  `id` int(12) NOT NULL,
  `book_id` int(12) NOT NULL,
  `page_number` int(11) NOT NULL,
  `line_number` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_date` datetime NOT NULL,
  `update_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `gs_bm_dog_ear`
--

INSERT INTO `gs_bm_dog_ear` (`id`, `book_id`, `page_number`, `line_number`, `content`, `created_date`, `update_date`) VALUES
(40, 21, 1, 11, 'あいうえお', '2023-12-22 19:15:34', '2023-12-22 19:15:45'),
(41, 21, 10, 5, 'かきくけこ', '2023-12-22 19:15:46', '2023-12-22 19:16:10'),
(42, 21, 50, 9, 'さしすせそ', '2023-12-22 19:16:22', '2023-12-22 19:16:37'),
(43, 22, 1, 7, 'たたたたた', '2023-12-22 19:16:42', '2023-12-22 19:17:02'),
(44, 22, 5, 9, 'ちちちちち', '2023-12-22 19:16:43', '2023-12-22 19:17:22'),
(45, 22, 56, 4, 'つつつつつ', '2023-12-22 19:16:44', '2023-12-22 19:17:27'),
(46, 23, 8, 9, 'はははははaaaaa', '2023-12-22 19:18:08', '2023-12-23 13:27:47'),
(47, 23, 34, 4, 'ひひひひひ', '2023-12-22 19:18:08', '2023-12-22 19:18:33'),
(48, 23, 56, 8, 'ふふふふふ', '2023-12-22 19:18:09', '2023-12-22 19:18:36'),
(49, 23, 0, 0, '', '2023-12-23 10:51:04', '2023-12-23 10:51:04'),
(50, 23, 0, 0, '', '2023-12-23 10:55:09', '2023-12-23 10:55:09'),
(51, 23, 0, 0, '', '2023-12-23 10:55:22', '2023-12-23 10:55:22'),
(52, 23, 0, 0, '', '2023-12-23 13:27:19', '2023-12-23 13:27:19');

-- --------------------------------------------------------

--
-- テーブルの構造 `gs_bm_order`
--

CREATE TABLE `gs_bm_order` (
  `id` int(12) NOT NULL,
  `type` enum('books','dog_ear') NOT NULL,
  `book_id` int(12) DEFAULT NULL,
  `order` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `gs_bm_order`
--

INSERT INTO `gs_bm_order` (`id`, `type`, `book_id`, `order`) VALUES
(1, 'books', NULL, '[21,22,23]'),
(2, 'dog_ear', 23, '[48,46,47]'),
(9, 'dog_ear', 21, '[40,41,42]'),
(10, 'dog_ear', 22, '[43,44,45]');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_bm_books`
--
ALTER TABLE `gs_bm_books`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `gs_bm_dog_ear`
--
ALTER TABLE `gs_bm_dog_ear`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `gs_bm_order`
--
ALTER TABLE `gs_bm_order`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `gs_bm_books`
--
ALTER TABLE `gs_bm_books`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- テーブルの AUTO_INCREMENT `gs_bm_dog_ear`
--
ALTER TABLE `gs_bm_dog_ear`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- テーブルの AUTO_INCREMENT `gs_bm_order`
--
ALTER TABLE `gs_bm_order`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
