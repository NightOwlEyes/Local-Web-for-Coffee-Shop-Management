-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for cafe_management
CREATE DATABASE IF NOT EXISTS `cafe_management` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cafe_management`;

-- Dumping structure for table cafe_management.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2628 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cafe_management.menu: ~6 rows (approximately)
INSERT INTO `menu` (`id`, `name`, `price`, `description`, `image`, `created_at`) VALUES
	(5, 'Cà phê latte đá xay', 30000.00, 'Cà phê latte đá xay thơm ngon', 'latte.jpg', '2025-04-07 17:32:19'),
	(18, 'Bánh flan', 8000.00, 'Bánh flan mềm mịn', 'flan.jpg', '2025-04-07 17:40:46'),
	(2618, 'Bánh sừng bò', 18000.00, 'Bánh sừng bò còn được gọi là bánh croa-xăng (từ tiếng Pháp croissant), có nguồn gốc từ Áo', '1744088147_cach-lam-banh-sung-bo-banh-croissant-ngan-lop-thom-ngon-noi-tieng-cua-phap-202108061216171587.jpg', '2025-04-08 04:55:47'),
	(2619, 'Cà phê muối', 35000.00, 'Cà phê muối là một món thức uống từ các nguyên liệu chính là cà phê, sữa (sữa đặc, sữa tươi lên men) và muối ăn tinh.', '1744088215_cach-lam-ca-phe-muoi-1024x1024-1.jpg', '2025-04-08 04:56:55'),
	(2620, 'Cà phê chồn', 42000.00, 'Cà phê chồn một loại cà phê đặc biệt, một thứ đồ uống được xếp vào loại hiếm nhất trên thế giới.', '1744088341_fi1APPXbcnvPP4bd8RXjKeaLSnxWWapbsqM.jpg', '2025-04-08 04:59:01'),
	(2621, 'Capuchino', 39000.00, 'Cà phê capuchino gồm có 3 phần, đó là: cà phê espresso, sữa nóng được tạo bọt và một ít bột cacao', '1744088474_cappuccino-cafe-cua-y.jpg', '2025-04-08 05:01:14'),
	(2624, 'Cà phê phin', 25000.00, 'Cà phê phin, một thức uống mang đậm nét văn hóa Việt', '1744444244_cach-pha-ca-phe-phin-nho-va-lon-ngon-dam-da-202409201151.jpg', '2025-04-12 07:50:44'),
	(2625, 'Cà phê sữa chua', 35000.00, 'Vị đắng nhẹ quyện cùng cùng vị chua ngọt sẽ kích thích các giác quan của bạn khi thưởng thức.', '1744444316_ava-1200x676-7.jpg', '2025-04-12 07:51:56'),
	(2626, 'Cà phê trứng', 37000.00, 'Cà phê trứng - một trong những loại cà phê được xếp vào hàng ngon nhất thế giới có “quê gốc” là Hà Nội.', '1744444830_ca-phe-trung-sai-gon-2.webp', '2025-04-12 08:00:30'),
	(2627, 'Bánh tiramisu', 50000.00, 'Bánh tiramisu là một trong những món ăn nhẹ mà bạn nên ăn cùng khi uống cà phê.', '1744444959_5-loai-banh-an-kem-khi-uong-ca-phedocx-1623671153055.webp', '2025-04-12 08:02:39');

-- Dumping structure for table cafe_management.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` varchar(10) NOT NULL,
  `staff_id` varchar(10) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','transfer') NOT NULL,
  `cash_received` decimal(10,2) DEFAULT NULL,
  `cash_returned` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cafe_management.orders: ~20 rows (approximately)
INSERT INTO `orders` (`id`, `order_id`, `staff_id`, `total_amount`, `payment_method`, `cash_received`, `cash_returned`, `order_date`) VALUES
	(1, 'HD0001', 'NV280', 120000.00, 'cash', 130000.00, 10000.00, '2025-04-08 07:58:55'),
	(2, 'HD0002', 'NV280', 120000.00, 'cash', 130000.00, 10000.00, '2025-04-08 08:00:15'),
	(3, 'HD0003', 'NV280', 151000.00, 'transfer', 0.00, 0.00, '2025-04-08 08:01:19'),
	(4, 'HD0004', 'NV280', 93000.00, 'transfer', 0.00, 0.00, '2025-04-08 08:01:39'),
	(5, 'HD0005', 'NV280', 25000.00, 'cash', 50000.00, 25000.00, '2025-04-08 08:04:11'),
	(6, 'HD0006', 'NV280', 8000.00, 'transfer', 0.00, 0.00, '2025-04-08 08:04:21'),
	(7, 'HD0007', 'NV280', 0.00, 'transfer', 0.00, 0.00, '2025-04-08 08:05:11'),
	(8, 'HD0008', 'NV280', 55000.00, 'cash', 100000.00, 45000.00, '2025-04-08 08:05:31'),
	(9, 'HD0009', 'NV280', 33000.00, 'transfer', 0.00, 0.00, '2025-04-08 08:07:44'),
	(10, 'HD0010', 'NV280', 80000.00, 'transfer', 0.00, 0.00, '2025-04-08 08:08:47'),
	(14, 'HD0011', 'NV280', 85000.00, 'transfer', 0.00, -85000.00, '2025-04-08 09:16:53'),
	(15, 'HD0012', 'NV280', 55000.00, 'cash', 60000.00, 5000.00, '2025-04-08 09:17:56'),
	(16, 'HD0013', 'NV582', 83000.00, 'transfer', 0.00, -83000.00, '2025-04-08 09:19:06'),
	(17, 'HD0014', 'NV582', 38000.00, 'transfer', 0.00, 0.00, '2025-04-08 09:23:45'),
	(18, 'HD0015', 'NV582', 33000.00, 'cash', 50000.00, 17000.00, '2025-04-08 09:23:59'),
	(19, 'HD0016', 'NV280', 71000.00, 'cash', 71000.00, 0.00, '2025-04-08 10:57:32'),
	(20, 'HD0017', 'NV280', 140000.00, 'cash', 200000.00, 60000.00, '2025-04-08 11:04:05'),
	(21, 'HD0018', 'NV280', 55000.00, 'cash', 60000.00, 5000.00, '2025-04-09 06:05:46'),
	(22, 'HD0019', 'NV582', 66000.00, 'transfer', 0.00, 0.00, '2025-04-09 06:06:58'),
	(23, 'HD0020', 'NV489', 45000.00, 'cash', 100000.00, 55000.00, '2025-04-09 06:44:20');

-- Dumping structure for table cafe_management.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` varchar(10) NOT NULL,
  `menu_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cafe_management.order_items: ~38 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `quantity`, `price`, `subtotal`) VALUES
	(1, '2', 5, 4, 30000.00, 120000.00),
	(2, '3', 5, 2, 30000.00, 60000.00),
	(3, '3', 18, 2, 8000.00, 16000.00),
	(4, '3', 2616, 3, 25000.00, 75000.00),
	(5, '4', 12, 1, 25000.00, 25000.00),
	(6, '4', 18, 1, 8000.00, 8000.00),
	(7, '4', 2616, 1, 25000.00, 25000.00),
	(8, '4', 2619, 1, 35000.00, 35000.00),
	(9, '5', 12, 1, 25000.00, 25000.00),
	(10, '6', 18, 1, 8000.00, 8000.00),
	(11, '8', 12, 1, 25000.00, 25000.00),
	(12, '8', 5, 1, 30000.00, 30000.00),
	(13, '9', 18, 1, 8000.00, 8000.00),
	(14, '9', 12, 1, 25000.00, 25000.00),
	(15, '10', 5, 1, 30000.00, 30000.00),
	(16, '10', 12, 2, 25000.00, 50000.00),
	(17, '14', 5, 2, 30000.00, 60000.00),
	(18, '14', 12, 1, 25000.00, 25000.00),
	(19, '15', 5, 1, 30000.00, 30000.00),
	(20, '15', 12, 1, 25000.00, 25000.00),
	(21, '16', 12, 3, 25000.00, 75000.00),
	(22, '16', 18, 1, 8000.00, 8000.00),
	(23, '17', 5, 1, 30000.00, 30000.00),
	(24, '17', 18, 1, 8000.00, 8000.00),
	(25, '18', 18, 1, 8000.00, 8000.00),
	(26, '18', 2616, 1, 25000.00, 25000.00),
	(27, '19', 5, 1, 30000.00, 30000.00),
	(28, '19', 18, 2, 8000.00, 16000.00),
	(29, '19', 2616, 1, 25000.00, 25000.00),
	(30, '20', 2616, 2, 25000.00, 50000.00),
	(31, '20', 2618, 5, 18000.00, 90000.00),
	(32, 'HD0018', 5, 1, 30000.00, 30000.00),
	(33, 'HD0018', 12, 1, 25000.00, 25000.00),
	(34, 'HD0019', 12, 1, 25000.00, 25000.00),
	(35, 'HD0019', 18, 2, 8000.00, 16000.00),
	(36, 'HD0019', 2616, 1, 25000.00, 25000.00),
	(37, 'HD0020', 18, 2, 8000.00, 16000.00),
	(38, 'HD0020', 2622, 1, 29000.00, 29000.00);

-- Dumping structure for table cafe_management.staff
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_card` varchar(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_id` (`staff_id`),
  UNIQUE KEY `id_card` (`id_card`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cafe_management.staff: ~4 rows (approximately)
INSERT INTO `staff` (`id`, `staff_id`, `name`, `dob`, `phone`, `email`, `id_card`, `image`, `hire_date`, `created_at`) VALUES
	(3, 'NV489', 'Nguyễn Thị Tường Vy', '2023-10-11', '0123456789', 'heovyvy@gmail.com', '0987654321', '1744057250_476772473_1201361421560631_1699352291040625509_n.jpg', '2025-04-07', '2025-04-07 20:20:22'),
	(4, 'NV582', 'Võ Tấn Tài', '2004-11-21', '0706080813', 'votantai229@gmail.com', '01234567890', '1744086759_images.jpg', '2025-04-08', '2025-04-08 04:32:39'),
	(5, 'NV280', 'Đỗ Huy Trúc', '2004-04-07', '0828396764', 'dohuytruc1922tlh@gmail.com', '070204011258', '1744439849_NightOwlEyes_mini_panda_con_capucha_programando_en_la_computado_7976ef89-ce54-4f15-b48d-836ce4cdccf2 (1).png', '2025-04-08', '2025-04-08 04:33:23'),
	(6, 'NV422', 'Phạm Quốc Tường', '2004-01-01', '0397294388', 'tuongpham184@gmail.com', '0702040397294388', '1744087043_Screen_Shot_2024-03-15_at_10.53.41_AM.webp', '2025-04-08', '2025-04-08 04:37:23');

-- Dumping structure for table cafe_management.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL,
  `staff_id` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2368 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table cafe_management.users: ~5 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `role`, `staff_id`, `created_at`) VALUES
	(1, 'admin', '$2y$10$gRJZFJHyToIPwmNVafhfFeMCfRe1AyrTONZOje9.OKsp5fV1.zWNy', 'admin', NULL, '2025-04-07 17:31:22'),
	(371, 'nguynthtngvy89', '$2y$10$7D46yndByFqAvmY0KJvp9.1XfCWlVgRX3fkSotdDu1sfnXEvQ5HTq', 'staff', 'NV489', '2025-04-07 20:20:22'),
	(603, 'vtnti82', '$2y$10$rnjSRYVqySeUx/bjKuZ9j.wTDbnm2nc5pikIwJiwODjOHJDpO1bfK', 'staff', 'NV582', '2025-04-08 04:32:39'),
	(610, 'huytrc80', '$2y$10$sS0LlIAmkbFDgk17A1ubqewvN8KU2Brw5ScBBVIvGSMV22y5O5rl.', 'staff', 'NV280', '2025-04-08 04:33:23'),
	(613, 'phmquctng22', '$2y$10$iL3rV.lmH60iR4PtOTfmxO3A/A/W2zFpKvG2uh7jqh/EIKB644H3e', 'staff', 'NV422', '2025-04-08 04:37:23');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
