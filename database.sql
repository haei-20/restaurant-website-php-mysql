-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 18, 2025 lúc 11:50 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `restaurant_website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `clients`
--

CREATE TABLE `clients` (
  `client_id` int(5) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(50) NOT NULL,
  `client_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `clients`
--

INSERT INTO `clients` (`client_id`, `client_name`, `client_phone`, `client_email`) VALUES
(9, 'Clinet 1', '02020202020', 'client1@gmail.com'),
(10, 'Client 10', '0638383933', 'client10@gmail.com'),
(11, 'Client 11', '06242556272', 'client11@yahoo.fr'),
(16, 'Client 14', '0203203203', 'client14@gmail.com'),
(18, 'Client 12', '02920320', 'client12@yahoo.fr'),
(19, 'Test', '1034304300', 'test@gmail.com'),
(21, 'BDCCN  ng Th H', '0342090254', 'dangthiha20012004@gmail.com'),
(22, 'BDCCN  ng Th H', '0342090254', 'dangthiha20012004@gmail.com'),
(23, 'dang ha', '0342090254', 'dangthiha20012004@gmail.com'),
(24, 'dang ha', '0342090254', 'dangthiha20012004@gmail.com'),
(25, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(26, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(37, 'admin', '0342090254', 'dangthiha20012004@gmail.com'),
(38, 'mèo', '3333333333', 'hadt.b22cn253@stu.ptit.edu.vn'),
(39, 'Hà Đặng Thị', '0342090254', 'hadt.b22cn253@stu.ptit.edu.vn'),
(40, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(41, '', '', ''),
(42, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(43, '', '', ''),
(44, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(45, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(46, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(47, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(49, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(50, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(51, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(52, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(53, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(54, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(55, 'hè', '3333333333', 'rowm.art2004@gmail.com'),
(56, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(57, 'Ha Dang', '3333333333', 'conbanthan10a1@gmail.com'),
(58, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(59, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(60, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(61, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(62, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(63, 'test 1', '233673728', 'rowm.art2004@gmail.com'),
(64, 'test 2', '233673728', 'rowm.art2004@gmail.com'),
(65, 'test 3', '3333333333', 'rowm.art2004@gmail.com'),
(66, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(67, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(68, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(69, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(70, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(71, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(72, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(73, 'test 3', '3333333333', 'rowm.art2004@gmail.com'),
(74, 'test 3', '3333333333', 'rowm.art2004@gmail.com'),
(75, 'test 3', '3333333333', 'rowm.art2004@gmail.com'),
(76, 'test 3', '3333333333', 'rowm.art2004@gmail.com'),
(77, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(78, 'Ha ha', '233673728', 'rowm.art2004@gmail.com'),
(79, 'Ha ha', '0342090254', 'rowm.art2004@gmail.com'),
(80, 'test4', '0342090254', 'dangthiha20012004@gmail.com'),
(81, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(82, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(83, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com'),
(84, 'admin', '0342090254', 'dangthiha20012004@gmail.com'),
(85, 'admin', '0342090254', 'dangthiha20012004@gmail.com'),
(86, 'admin', '0342090254', 'dangthiha20012004@gmail.com'),
(87, 'admin', '0342090254', 'dangthiha20012004@gmail.com'),
(88, 'admin', '0342090254', 'dangthiha20012004@gmail.com'),
(89, 'B22DCCN253 - Đặng Thị Hà', '0342090254', 'dangthiha20012004@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `image_gallery`
--

CREATE TABLE `image_gallery` (
  `image_id` int(2) NOT NULL,
  `image_name` varchar(30) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `image_gallery`
--

INSERT INTO `image_gallery` (`image_id`, `image_name`, `image`) VALUES
(1, 'Moroccan Tajine', '58146_Moroccan Chicken Tagine.jpeg'),
(2, 'Italian Pasta', 'img_1.jpg'),
(3, 'Cook', 'img_2.jpg'),
(4, 'Pizza', 'img_3.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `in_order`
--

CREATE TABLE `in_order` (
  `id` int(5) NOT NULL,
  `order_id` int(5) NOT NULL,
  `menu_id` int(5) NOT NULL,
  `quantity` int(3) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `in_order`
--

INSERT INTO `in_order` (`id`, `order_id`, `menu_id`, `quantity`) VALUES
(8, 10, 16, 1),
(9, 11, 12, 1),
(10, 11, 16, 1),
(11, 12, 11, 1),
(12, 12, 12, 1),
(13, 12, 16, 1),
(14, 13, 8, 1),
(15, 13, 1, 1),
(16, 14, 12, 1),
(17, 14, 16, 1),
(18, 15, 18, 3),
(19, 16, 2, 3),
(20, 17, 2, 3),
(21, 17, 7, 2),
(22, 17, 18, 1),
(23, 18, 12, 2),
(24, 18, 5, 1),
(25, 18, 6, 2),
(26, 19, 7, 2),
(27, 19, 8, 2),
(28, 19, 12, 1),
(29, 19, 5, 1),
(30, 20, 2, 2),
(31, 20, 18, 2),
(32, 32, 2, 1),
(33, 32, 18, 1),
(34, 33, 2, 1),
(35, 34, 2, 2),
(36, 35, 2, 2),
(37, 36, 2, 1),
(38, 36, 17, 1),
(39, 37, 2, 1),
(40, 38, 2, 1),
(41, 38, 18, 2),
(42, 39, 2, 1),
(43, 39, 18, 2),
(44, 39, 11, 1),
(45, 40, 9, 5),
(46, 41, 5, 2),
(47, 42, 2, 20),
(48, 43, 2, 1),
(49, 44, 12, 1),
(50, 45, 2, 3),
(51, 46, 1, 2),
(52, 46, 18, 1),
(53, 47, 2, 1),
(54, 48, 2, 1),
(55, 48, 5, 7),
(56, 49, 2, 1),
(57, 50, 2, 1),
(58, 50, 1, 1),
(59, 51, 2, 1),
(60, 52, 2, 1),
(61, 53, 2, 2),
(62, 54, 2, 1),
(63, 54, 9, 3),
(64, 55, 2, 1),
(65, 55, 6, 1),
(66, 56, 9, 2),
(67, 57, 2, 2),
(68, 57, 18, 1),
(69, 58, 6, 5),
(70, 58, 18, 2),
(71, 59, 2, 2),
(72, 59, 11, 1),
(73, 60, 2, 20),
(74, 60, 18, 1),
(75, 61, 12, 2),
(76, 62, 2, 1),
(77, 62, 17, 1),
(78, 63, 2, 1),
(79, 63, 9, 1),
(80, 64, 2, 1),
(81, 64, 7, 2),
(82, 65, 2, 2),
(83, 66, 6, 3),
(84, 66, 18, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(5) NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `menu_description` varchar(255) NOT NULL,
  `menu_price` decimal(6,2) NOT NULL,
  `menu_image` varchar(255) NOT NULL,
  `category_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_name`, `menu_description`, `menu_price`, `menu_image`, `category_id`) VALUES
(1, 'Pizza Hải Sản', 'Đế bánh mỏng giòn phủ hải sản tươi kết hợp phô mai béo ngậy.', 20.00, '91284_Pizza hải sản.png', 5),
(2, 'Hamburger', 'Nỗi đau tồn tại, nuôi dưỡng tinh hoa. Vị trí vững vàng, mềm mại êm ái, đường cong trang trọng, nâng đỡ hoàn hảo, không thiếu sót.', 30.00, '20989_hamburger-with-fries.jpg', 1),
(3, 'Kem', 'Kem lạnh mịn mượt đa dạng hương vị, ăn kèm trái cây hoặc sốt', 20.00, 'summer-dessert-sweet-ice-cream.jpg', 12),
(5, 'Coffee', 'Cà phê pha phin đậm đặc, thơm nồng, thường thêm sữa theo ý thích.', 40.00, 'coffee.jpeg', 14),
(6, 'Tra Da', 'Trà lạnh thanh mát, thêm chanh tươi và đá xua tan oi bức.', 3.20, '76643_ice_tea.jpg', 14),
(7, 'Bucatini', 'Mì ống dài ruột rỗng dai giòn, thường trộn sốt kem hoặc sốt cà.', 20.00, 'macaroni.jpeg', 4),
(8, 'Cannelloni', 'Ống mì nhồi nhân thịt hoặc phô mai, phủ sốt béchamel rồi nướng vàng rộm.', 300.00, 'cooked_pasta.jpeg', 4),
(9, 'Margherita', 'Đế bánh giòn phủ sốt cà chua tươi, phô mai Mozzarella và lá húng.', 24.00, 'pizza.jpeg', 5),
(11, 'Moroccan Tajine', 'Thịt hoặc rau củ hầm cùng gia vị Bắc Phi trong nồi đất thơm nồng.', 70.00, '58146_Moroccan Chicken Tagine.jpeg', 13),
(12, 'Moroccan Bissara', 'Súp đậu fava xay nhuyễn, nấu cùng tỏi và dầu ôliu đậm đà.', 19.00, '61959_Bissara.jpg', 13),
(16, 'Couscous', 'Hạt lúa mì hấp tơi ăn kèm rau củ và thịt hầm ngọt bùi.', 20.00, '76635_57738_w1024h768c1cx256cy192.jpg', 13),
(17, 'Salad rau', 'Salad là hỗn hợp nhiều loại rau củ/trái cây được trộn cùng với một loại nước sốt.', 25.00, '79690_11-mon-salad-giam-can-giup-giam-2kg-tuan-nguyen-lieu-kiem-dau-cung-co-155560.jpg', 6),
(18, 'Trà Sữa Đào', 'Khi thưởng thức, bạn sẽ như lạc vào một thế giới mới lạ, tràn ngập hương thơm của đào và vị ngọt của trà sữa.', 50.00, '61725_mot-ly-tra-sua-dao.png', 14);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu_categories`
--

CREATE TABLE `menu_categories` (
  `category_id` int(3) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `menu_categories`
--

INSERT INTO `menu_categories` (`category_id`, `category_name`) VALUES
(1, 'burgers'),
(4, 'pasta'),
(5, 'pizzas'),
(6, 'salads'),
(12, 'Món Tráng Miệng'),
(13, 'Đồ ăn truyền thống'),
(14, 'Đồ Uống');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `placed_orders`
--

CREATE TABLE `placed_orders` (
  `order_id` int(5) NOT NULL,
  `order_time` datetime NOT NULL,
  `client_id` int(5) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `delivered` tinyint(1) NOT NULL DEFAULT 0,
  `delivering` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_time` datetime DEFAULT NULL,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `cancel_time` datetime DEFAULT NULL,
  `canceled_by` varchar(50) DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `placed_orders`
--

INSERT INTO `placed_orders` (`order_id`, `order_time`, `client_id`, `delivery_address`, `delivered`, `delivering`, `delivery_time`, `canceled`, `cancel_time`, `canceled_by`, `cancellation_reason`, `total_amount`) VALUES
(7, '2020-06-22 12:01:00', 9, 'Bloc A Nr 80000 Hay ElAgadir', 0, 0, NULL, 1, '2025-05-09 13:33:10', NULL, NULL, 0.00),
(8, '2020-06-23 06:07:00', 10, 'Chengdu, China', 0, 0, NULL, 1, NULL, NULL, '', 0.00),
(9, '2020-06-24 16:40:00', 11, 'Hay El Houda Agadir', 1, 0, NULL, 0, NULL, NULL, NULL, 0.00),
(10, '2023-07-01 04:02:00', 16, 'Bloc A', 1, 0, NULL, 0, NULL, NULL, NULL, 0.00),
(11, '2023-10-30 20:09:00', 18, 'Test testst asds', 1, 0, NULL, 0, NULL, NULL, NULL, 0.00),
(12, '2023-10-30 21:46:00', 19, 'tests sd', 1, 0, NULL, 0, NULL, NULL, NULL, 0.00),
(13, '2025-05-05 10:16:00', 23, 'PTIT', 1, 0, '2025-05-09 12:24:37', 0, NULL, NULL, NULL, 0.00),
(14, '2025-05-09 06:46:00', 24, 'PTIT', 0, 0, NULL, 1, '2025-05-09 12:24:56', NULL, '', 0.00),
(15, '2025-05-09 07:52:00', 25, 'PTIT', 1, 0, '2025-05-14 13:30:46', 0, NULL, NULL, NULL, 0.00),
(16, '2025-05-09 08:07:00', 26, 'PTIT', 0, 0, NULL, 1, '2025-05-14 13:41:11', 'Admin', 'khách hàng yc hủy', 0.00),
(17, '2025-05-13 18:31:00', 39, 'PTIT', 1, 1, '2025-05-14 15:25:21', 0, NULL, NULL, NULL, 0.00),
(18, '2025-05-14 08:29:00', 40, 'PTIT', 0, 0, NULL, 1, '2025-05-14 15:25:28', 'Admin', 'hết hàng', 0.00),
(19, '2025-05-14 11:45:00', 41, '', 1, 0, '2025-05-14 23:37:42', 0, NULL, NULL, NULL, 0.00),
(20, '2025-05-14 19:34:00', 42, 'PTIT', 1, 0, '2025-05-15 14:51:47', 0, NULL, NULL, NULL, 0.00),
(21, '2025-05-14 20:13:00', 43, '', 0, 0, NULL, 1, '2025-05-15 01:28:32', 'Admin', 'sai', 0.00),
(22, '2025-05-14 20:25:00', 44, 'PTIT', 0, 0, NULL, 1, '2025-05-15 01:28:24', 'Admin', 'sai', 0.00),
(23, '2025-05-14 20:26:00', 45, 'PTIT', 0, 0, NULL, 1, '2025-05-15 01:28:19', 'Admin', 'sao', 0.00),
(24, '2025-05-14 20:26:00', 46, 'PTIT', 0, 0, NULL, 1, '2025-05-15 01:28:05', 'Admin', 'sai', 0.00),
(25, '2025-05-14 20:29:00', 47, 'PTIT', 1, 0, '2025-05-15 14:04:24', 0, NULL, NULL, NULL, 0.00),
(26, '2025-05-14 20:38:00', 49, 'PTIT', 1, 0, '2025-05-15 14:51:51', 0, NULL, NULL, NULL, 0.00),
(28, '2025-05-14 20:40:00', 51, '', 1, 0, '2025-05-15 14:51:53', 0, NULL, NULL, NULL, 0.00),
(29, '2025-05-14 20:48:00', 52, '', 1, 0, '2025-05-15 14:51:56', 0, NULL, NULL, NULL, 0.00),
(30, '2025-05-14 20:48:00', 53, 'PTIT', 1, 0, '2025-05-15 14:51:58', 0, NULL, NULL, NULL, 0.00),
(31, '2025-05-14 20:54:00', 54, 'PTIT', 1, 0, '2025-05-15 14:52:01', 0, NULL, NULL, NULL, 0.00),
(32, '2025-05-15 08:43:08', 55, 'ptit', 1, 0, '2025-05-15 14:52:03', 0, NULL, NULL, NULL, 100.00),
(33, '2025-05-15 08:43:43', 56, 'ptit', 1, 0, '2025-05-15 14:52:05', 0, NULL, NULL, NULL, 50.00),
(34, '2025-05-15 08:44:52', 57, 'ptit', 1, 0, '2025-05-15 14:52:08', 0, NULL, NULL, NULL, 100.00),
(35, '2025-05-15 08:52:41', 58, 'gg', 1, 0, '2025-05-15 14:52:12', 0, NULL, NULL, NULL, 100.00),
(36, '2025-05-15 08:57:00', 59, 'ptit', 1, 0, '2025-05-15 14:52:14', 0, NULL, NULL, NULL, 75.00),
(37, '2025-05-15 08:57:40', 60, 'gg', 1, 0, '2025-05-15 14:52:16', 0, NULL, NULL, NULL, 50.00),
(38, '2025-05-15 09:03:41', 61, 'gg', 1, 0, '2025-05-15 14:52:17', 0, NULL, NULL, NULL, 150.00),
(39, '2025-05-15 09:06:27', 62, 'gg', 1, 0, '2025-05-15 14:52:21', 0, NULL, NULL, NULL, 220.00),
(40, '2025-05-15 09:07:45', 63, 'ptit', 1, 0, '2025-05-15 14:52:23', 0, NULL, NULL, NULL, 120.00),
(41, '2025-05-15 09:10:05', 64, 'hihihi', 1, 0, '2025-05-15 14:52:25', 0, NULL, NULL, NULL, 80.00),
(42, '2025-05-15 09:25:24', 65, 'hn', 1, 0, '2025-05-15 14:52:27', 0, NULL, NULL, NULL, 1000.00),
(43, '2025-05-15 09:26:01', 66, 'hn', 1, 0, '2025-05-15 14:52:28', 0, NULL, NULL, NULL, 50.00),
(44, '2025-05-15 09:26:57', 67, 'ff', 1, 0, '2025-05-15 14:52:32', 0, NULL, NULL, NULL, 19.00),
(45, '2025-05-15 09:31:30', 68, 'mt', 1, 0, '2025-05-15 14:52:34', 0, NULL, NULL, NULL, 150.00),
(46, '2025-05-15 09:35:40', 69, 'hn', 1, 0, '2025-05-15 14:53:02', 0, NULL, NULL, NULL, 850.00),
(47, '2025-05-15 09:36:57', 70, 'tq', 1, 0, '2025-05-15 15:09:57', 0, NULL, NULL, NULL, 50.00),
(48, '2025-05-15 10:09:43', 71, 'tq', 1, 0, '2025-05-15 15:09:54', 0, NULL, NULL, NULL, 330.00),
(49, '2025-05-15 10:10:17', 72, 'tq', 1, 0, '2025-05-15 15:10:37', 0, NULL, NULL, NULL, 50.00),
(50, '2025-05-15 10:21:16', 73, 'hn', 1, 0, '2025-05-15 15:21:34', 0, NULL, NULL, NULL, 450.00),
(51, '2025-05-15 10:48:31', 74, 'hhh', 1, 0, '2025-05-16 20:28:09', 0, NULL, NULL, NULL, 50.00),
(52, '2025-05-15 11:41:03', 75, 'hf', 0, 0, NULL, 1, '2025-05-16 20:28:35', 'Admin', 'khách yc hủy', 50.00),
(53, '2025-05-16 15:29:38', 76, 'hf', 1, 0, '2025-05-22 22:42:03', 0, NULL, NULL, NULL, 100.00),
(54, '2025-05-16 16:31:41', 77, 'PTIT', 1, 0, '2025-05-22 23:27:06', 0, NULL, NULL, NULL, 122.00),
(55, '2025-05-16 18:09:16', 78, 'hmmmm', 1, 0, '2025-05-23 13:16:35', 0, NULL, NULL, NULL, 53.20),
(56, '2025-05-22 17:41:30', 79, 'hmmmm', 1, 0, '2025-05-23 13:16:39', 0, NULL, NULL, NULL, 48.00),
(57, '2025-05-23 08:15:59', 80, 'ptit', 1, 0, '2025-05-23 17:49:53', 0, NULL, NULL, NULL, 150.00),
(58, '2025-05-23 13:02:28', 81, 'ptit', 1, 0, '2025-05-23 19:50:36', 0, NULL, NULL, NULL, 116.00),
(59, '2025-05-23 14:49:59', 82, 'ptit', 0, 0, NULL, 1, '2025-05-23 19:51:39', 'Admin', 'hết hàng', 130.00),
(60, '2025-05-23 17:46:28', 83, 'ptit', 1, 0, '2025-05-23 22:51:35', 0, NULL, NULL, NULL, 650.00),
(61, '2025-05-23 17:51:52', 84, 'ptit', 0, 0, NULL, 1, '2025-05-23 22:52:32', 'Admin', 'thử nghiệm', 38.00),
(62, '2025-05-23 17:54:11', 85, 'ptit', 1, 0, '2025-05-23 23:53:18', 0, NULL, NULL, NULL, 55.00),
(63, '2025-05-23 18:10:18', 86, 'ptit', 0, 0, NULL, 1, '2025-05-23 23:53:27', 'Admin', 'thử nghiệm', 54.00),
(64, '2025-05-23 18:53:01', 87, 'ptit', 0, 0, NULL, 0, NULL, NULL, NULL, 70.00),
(65, '2025-05-24 03:27:21', 88, 'ptit', 0, 0, NULL, 0, NULL, NULL, NULL, 60.00),
(66, '2025-05-24 03:31:26', 89, 'ptit', 0, 0, NULL, 0, NULL, NULL, NULL, 59.60);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(2) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `full_name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `full_name`, `password`) VALUES
(1, 'admin_user', 'user_admin@gmail.com', '0342090254', 'Dang ha', '8cb2237d0679ca88db6464eac60da96345513964');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `website_settings`
--

CREATE TABLE `website_settings` (
  `option_id` int(5) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `website_settings`
--

INSERT INTO `website_settings` (`option_id`, `option_name`, `option_value`) VALUES
(1, 'restaurant_name', 'VINCENT PIZZA'),
(2, 'restaurant_email', 'vincent.pizza@gmail.com'),
(3, 'admin_email', 'admin_email@gmail.com'),
(4, 'restaurant_phonenumber', '088866777555'),
(5, 'restaurant_address', 'Me Tri, Nam Tu Liem, Ha Noi');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Chỉ mục cho bảng `image_gallery`
--
ALTER TABLE `image_gallery`
  ADD PRIMARY KEY (`image_id`);

--
-- Chỉ mục cho bảng `in_order`
--
ALTER TABLE `in_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu` (`menu_id`),
  ADD KEY `fk_order` (`order_id`);

--
-- Chỉ mục cho bảng `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `FK_menu_category_id` (`category_id`);

--
-- Chỉ mục cho bảng `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `placed_orders`
--
ALTER TABLE `placed_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_client` (`client_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`option_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT cho bảng `image_gallery`
--
ALTER TABLE `image_gallery`
  MODIFY `image_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `in_order`
--
ALTER TABLE `in_order`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT cho bảng `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `category_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `placed_orders`
--
ALTER TABLE `placed_orders`
  MODIFY `order_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `option_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `in_order`
--
ALTER TABLE `in_order`
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`),
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `placed_orders` (`order_id`);

--
-- Các ràng buộc cho bảng `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `FK_menu_category_id` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`category_id`);

--
-- Các ràng buộc cho bảng `placed_orders`
--
ALTER TABLE `placed_orders`
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
