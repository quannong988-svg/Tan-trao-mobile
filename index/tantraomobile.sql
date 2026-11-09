-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 25, 2026 lúc 10:23 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tantraomobile`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`) VALUES
(1, 'admin', '$2y$10$G30qb3zqE9URAqthkqDBmOyAQduEw4MXS003CQk.IVxynooztP5OK', 'Quản trị viên');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon` varchar(10) DEFAULT '?',
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`) VALUES
(1, 'iPhone', 'iphone', '📱', 1),
(2, 'Samsung', 'samsung', '📱', 2),
(3, 'OPPO', 'oppo', '📱', 3),
(4, 'Xiaomi', 'xiaomi', '📱', 4),
(5, 'Vivo', 'vivo', '📱', 5),
(6, 'Điện thoại Gaming', 'gaming', '🎮', 6),
(7, 'Máy thanh lý', 'thanh-ly', '🔥', 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT '',
  `note` text DEFAULT NULL,
  `total` decimal(12,0) DEFAULT 0,
  `status` varchar(30) DEFAULT 'Chờ xử lý',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `phone`, `address`, `note`, `total`, `status`, `created_at`) VALUES
(1, 'Bùi Gia Hưng', '0375120857', 'DhTT', 'Bo 500tr', 21890000, 'Chờ xử lý', '2026-09-25 07:29:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(12,0) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `qty`) VALUES
(1, 1, 2, 'iPhone 14 Pro Max 256GB', 14900000, 1),
(2, 1, 11, 'Vivo iQOO Z11x', 6990000, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `brand` varchar(50) DEFAULT '',
  `price` decimal(12,0) NOT NULL DEFAULT 0,
  `sale_price` decimal(12,0) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'no-image.png',
  `description` text DEFAULT NULL,
  `specs` text DEFAULT NULL,
  `stock` int(11) DEFAULT 10,
  `sold_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_liquidation` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `brand`, `price`, `sale_price`, `image`, `description`, `specs`, `stock`, `sold_count`, `is_featured`, `is_liquidation`, `created_at`) VALUES
(1, 1, 'iPhone 16 Pro 128GB', 'iphone-16-pro', 'Apple', 27990000, NULL, '16pro.jpg', 'iPhone 16 Pro chính hãng VN/A, màn hình Super Retina XDR, chip A18 Pro mạnh mẽ.', 'Màn hình 6.3\" OLED | Chip A18 Pro | Camera 48MP | Pin 3582mAh', 15, 120, 1, 0, '2026-09-25 06:34:11'),
(2, 1, 'iPhone 14 Pro Max 256GB', 'iphone-14-pro-max', 'Apple', 14900000, NULL, '14promax.jpg', 'iPhone 14 Pro Max hàng chính hãng, hiệu năng mạnh, camera đỉnh cao.', 'Màn hình 6.7\" | Chip A16 Bionic | Camera 48MP | Pin 4323mAh', 8, 89, 1, 0, '2026-09-25 06:34:11'),
(3, 1, 'iPhone 12 Pro Max', 'iphone-12-pro-max', 'Apple', 11900000, NULL, '1264gb.jpg', 'iPhone 12 Pro Max, thiết kế sang trọng, hiệu năng ổn định.', 'Màn hình 6.7\" OLED | Chip A14 Bionic | Camera 12MP | Pin 3687mAh', 10, 67, 0, 0, '2026-09-25 06:34:11'),
(4, 1, 'iPhone 12 64GB', 'iphone-12-64gb', 'Apple', 7900000, NULL, '1264gb.jpg', 'iPhone 12 bản 64GB, lựa chọn tối ưu chi phí.', 'Màn hình 6.1\" OLED | Chip A14 Bionic | Camera 12MP | Pin 2815mAh', 12, 54, 0, 0, '2026-09-25 06:34:11'),
(5, 2, 'Samsung Galaxy A57 5G', 'samsung-galaxy-a57-5g', 'Samsung', 8990000, 7990000, 'a57.jpg', 'Samsung Galaxy A57 5G, pin trâu, camera sắc nét, giá sinh viên.', 'Màn hình 6.7\" Super AMOLED | Snapdragon | Camera 50MP | Pin 5000mAh', 20, 41, 1, 0, '2026-09-25 06:34:11'),
(6, 2, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Samsung', 22990000, NULL, 's24.jpg', 'Flagship Samsung với bút S-Pen, camera zoom 100x.', 'Màn hình 6.8\" QHD+ | Snapdragon 8 Gen 3 | Camera 200MP | Pin 5000mAh', 6, 33, 1, 0, '2026-09-25 06:34:11'),
(7, 3, 'Oppo Reno11 Pro 5G', 'oppo-reno11-pro', 'OPPO', 10500000, 16990000, 'reno11.jpg', 'Oppo Reno11 Pro 5G, thiết kế thời trang, camera chân dung đẹp.', 'Màn hình 6.7\" AMOLED | Dimensity 8200 | Camera 50MP | Pin 4600mAh', 9, 45, 1, 0, '2026-09-25 06:34:11'),
(8, 4, 'Xiaomi Redmi Turbo 4 Pro', 'redmi-turbo-4-pro', 'Xiaomi', 11390000, NULL, 'turbo4.jpg', 'Redmi Turbo 4 Pro hiệu năng gaming mạnh mẽ, giá tốt.', 'Màn hình 6.67\" AMOLED 144Hz | Snapdragon 8s Gen 4 | Camera 50MP | Pin 6000mAh', 14, 156, 1, 0, '2026-09-25 06:34:11'),
(9, 4, 'Xiaomi 17 Ultra', 'xiaomi-17-ultra', 'Xiaomi', 29990000, 39990000, 'xiaomi17ultra.png', 'Xiaomi 17 Ultra - flagship camera Leica, hiệu năng đỉnh cao.', 'Màn hình 6.9\" AMOLED | Snapdragon 8 Elite | Camera Leica 50MP | Pin 6000mAh', 5, 22, 0, 0, '2026-09-25 06:34:11'),
(10, 5, 'Vivo iQOO Z10 Turbo Pro 5G', 'iqoo-z10-turbo-pro-5g', 'Vivo', 12990000, NULL, 'z11x.jpg', 'iQOO Z10 Turbo Pro, cấu hình khủng dành cho game thủ.', 'Màn hình 6.78\" AMOLED 144Hz | Snapdragon 8s Gen 3 | Camera 50MP | Pin 7500mAh', 7, 19, 0, 0, '2026-09-25 06:34:11'),
(11, 5, 'Vivo iQOO Z11x', 'vivo-iqoo-z11x', 'Vivo', 6990000, NULL, 'z11x.jpg', 'iQOO Z11x, pin khủng 6500mAh, giá học sinh sinh viên.', 'Màn hình 6.72\" LCD 120Hz | Snapdragon 6 Gen 1 | Camera 50MP | Pin 6500mAh', 18, 78, 0, 0, '2026-09-25 06:34:11'),
(12, 6, 'ZTE Nubia RedMagic 11 Pro', 'red-magic-11-pro', 'ZTE', 19590000, NULL, 'redmagic11pro.png', 'Điện thoại gaming đỉnh cao, tản nhiệt quạt turbo.', 'Màn hình 6.85\" AMOLED 144Hz | Snapdragon 8 Elite | Quạt tản nhiệt | Pin 7050mAh', 4, 12, 0, 0, '2026-09-25 06:34:11'),
(13, 7, 'iPhone 14 Pro Max 256GB - Thanh lý', 'iphone-14-promax-256gb-thanh-ly', 'Apple', 13900000, 29490000, '14thanhli.jpg', 'Máy thanh lý like new 99%, bảo hành 6 tháng.', 'Màn hình 6.7\" | Chip A16 Bionic | Camera 48MP | Pin 4323mAh', 2, 8, 0, 1, '2026-09-25 06:34:11'),
(14, 7, 'iPhone 16 - Thanh lý', 'iphone-16-thanh-ly', 'Apple', 13900000, 25990000, '16.jpg', 'iPhone 16 thanh lý, máy đẹp, đầy đủ phụ kiện.', 'Màn hình 6.1\" | Chip A18 | Camera 48MP | Pin 3561mAh', 3, 15, 0, 1, '2026-09-25 06:34:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
