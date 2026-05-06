-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 07:11 PM
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
-- Database: `discover_saudi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$ZNcpqY6UYuyYAvAosphBi.daG25M76hQ4diJk/yGka/Bo.NVktx6O');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `activities` text DEFAULT NULL,
  `landmarks` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `gallery_image1` varchar(255) DEFAULT NULL,
  `gallery_image2` varchar(255) DEFAULT NULL,
  `gallery_image3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `category`, `description`, `location`, `features`, `activities`, `landmarks`, `main_image`, `gallery_image1`, `gallery_image2`, `gallery_image3`, `created_at`) VALUES
(1, 'الرياض', 'وسط', 'عاصمة المملكة العربية السعودية ومركزها الاقتصادي', 'وسط المملكة', 'برج المملكة، قصر المصمك', 'زيارة المتاحف، التسوق', 'برج الفيصلية، قصر المصمك', 'riyadh.jpg', NULL, NULL, NULL, '2026-05-05 21:15:29'),
(2, 'مكة المكرمة', 'غربية', 'مدينة دينية يقصدها المسلمون للحج', 'غرب المملكة', 'المسجد الحرام، الكعبة المشرفة', 'العبادة، الطواف', 'الكعبة المشرفة، مسعى الصفا والمروة', 'makkah.jpg', NULL, NULL, NULL, '2026-05-05 21:15:29'),
(3, '<br /><b>Warning</b>:  Undefined variable $data in <b>C:\\xampp\\htdocs\\web_project\\admin\\update.php</', 'وسطى', '<br />\r\n<b>Warning</b>:  Undefined variable $data in <b>C:\\xampp\\htdocs\\web_project\\admin\\update.php</b> on line <b>124</b><br />\r\n<br />\r\n<b>Warning</b>:  Trying to access array offset on value of type null in <b>C:\\xampp\\htdocs\\web_project\\admin\\update.php</b> on line <b>124</b><br />', '', '', '', '', '69fb43adbb40a.jpg', NULL, NULL, NULL, '2026-05-05 21:15:29'),
(4, '<br /><b>Warning</b>:  Undefined variable $data in <b>C:\\xampp\\htdocs\\web_project\\admin\\update.php</', 'وسطى', '<br />\r\n<b>Warning</b>:  Undefined variable $data in <b>C:\\xampp\\htdocs\\web_project\\admin\\update.php</b> on line <b>124</b><br />\r\n<br />\r\n<b>Warning</b>:  Trying to access array offset on value of type null in <b>C:\\xampp\\htdocs\\web_project\\admin\\update.php</b> on line <b>124</b><br />', '', '', '', '', '69fb456670e74.png', NULL, NULL, NULL, '2026-05-06 13:43:02'),
(5, 'الدمام', 'شرقية', 'الدمام فيها بحر', 'شرق المملكة العربية السعودية', 'فيها بحر', 'زيارة البحر', 'بحر', '69fb49e2d02b4.png', NULL, NULL, NULL, '2026-05-06 14:02:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
