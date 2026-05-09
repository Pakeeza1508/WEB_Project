-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2026 at 05:03 PM
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
-- Database: `shopping_db`
--
CREATE DATABASE IF NOT EXISTS `shopping_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shopping_db`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `spid` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT 1,
  `item_type` enum('featured','catalog') DEFAULT 'catalog'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `userid`, `total_amount`, `status`, `order_date`) VALUES
(1, 1, 265000, 'Pending', '2026-05-08 13:51:08'),
(2, 1, 248500, 'Pending', '2026-05-08 14:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `pid` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `tag` varchar(50) DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pid`, `name`, `category`, `price`, `image`, `tag`) VALUES
(18, 'CK Shoes', 'Shoes', 4500, 'https://i5.walmartimages.com/seo/Calvin-Klein-Womens-Celbi-Lace-Up-Faux-Leather-Casual-And-Fashion-Sneakers_7efd7cd8-60f3-4585-9e2c-f3b63b4f2077.0c15544888ff62d4bcb7fb740a96105d.jpeg?odnHeight=540&odn', 'new'),
(19, 'LV Dress', 'Fashion', 1000, 'https://www.fashiongonerogue.com/wp-content/uploads/2021/01/Zara-Spring-2021-Style-Guide01.jpg', 'new'),
(20, 'Puma Shoes', 'Shoes', 45000, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/398846/01/sv01/fnd/EEA/fmt/png/Speedcat-OG-Sneakers-Unisex', 'new'),
(21, 'Zara Dress', 'Fashion', 25000, 'https://www.thefashionlaw.com/wp-content/uploads/2020/03/379_image-asset.jpg', 'new'),
(22, 'Prada Sunglasses', 'Fashion', 320000, 'https://ainak.pk/wp-content/uploads/2025/03/prada-spr-17w-4.webp', 'new'),
(23, 'Kylie Lipstick', 'Cosmetics', 150000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWzCBYnqefaUWILDht02ageONpSd7cyXPTzQ&s', 'trending'),
(24, 'Adidas Shoes', 'Shoes', 65000, 'https://brand.assets.adidas.com/image/upload/f_auto,q_auto:best,fl_lossy/if_w_gt_600,w_600/shoes_men_tcc_d_44a809233a.jpg', 'trending'),
(25, 'Cyberpunk Jacket', 'Clothing', 12000, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=600&auto=format&fit=crop', 'bestseller'),
(26, 'Ray-Ban Aviator', 'Accessories', 18000, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?q=80&w=600&auto=format&fit=crop', 'bestseller'),
(27, 'Nike Air Max', 'Shoes', 12000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTKV9dQp60slQ63E1UJMI6-HxoUs2Aq9m9Gcg&s', 'trending'),
(28, 'LV Bag', 'Bag', 32000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGwqYVoRiS2tw50Gkh2kP9a3K2PbhREn4Irg&s', 'trending'),
(29, 'Rayban Meta Glasses', 'Sunglasses', 12000, 'https://i.ebayimg.com/00/s/MTUwMFgxNTAw/z/I08AAOSwDgdlSU8c/$_57.JPG?set_id=880000500F', 'bestseller');

-- --------------------------------------------------------

--
-- Table structure for table `shop_products`
--

DROP TABLE IF EXISTS `shop_products`;
CREATE TABLE `shop_products` (
  `spid` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 4.5,
  `discount` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_products`
--

INSERT INTO `shop_products` (`spid`, `name`, `category`, `price`, `image`, `rating`, `discount`, `created_at`) VALUES
(1, 'Nike Air Force 1', 'Shoes', 18000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ89-kQXSqeXUlQos6rZLsfXuxHY2Twttp89w&s', 4.8, 10, '2026-05-08 13:01:15'),
(2, 'Adidas Ultraboost', 'Shoes', 32000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSrdmeD7E4uSxfrJBMK5fJ1065MB0uPD2RL9Q&s', 4.9, 15, '2026-05-08 13:01:15'),
(3, 'Puma RS-X Sneakers', 'Shoes', 15000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmmaVMjtFHkTGQpG0NsuqFWZcjXOzA-D4p8Q&s', 4.6, 5, '2026-05-08 13:01:15'),
(4, 'Converse Classic High Top', 'Shoes', 12000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTNmuAUWZaUgVn2DfcVQ1Kb5t3uVBLDBieRw&s', 4.5, 0, '2026-05-08 13:01:15'),
(5, 'New Balance 550', 'Shoes', 20000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaaVo70RXWWHv9FITRR6tjTCAywdG1z9NgiA&s', 4.7, 8, '2026-05-08 13:01:15'),
(6, 'Zara Summer Dress', 'Fashion', 9000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTK_5PPPnE7QhtN6IQoBIHi4mxwMyRknibKtw&s', 4.5, 12, '2026-05-08 13:04:04'),
(7, 'H&M Oversized Hoodie', 'Fashion', 7000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpLoW5p5vtoLhsKmcMxt4TdQBUMFOYk4CZQ&s', 4.4, 20, '2026-05-08 13:04:04'),
(8, 'Levi’s Denim Jacket', 'Fashion', 14000, 'https://jsbrothers.pk/cdn/shop/files/9CEDE52D-350A-4100-9F28-21AB3FA06937.jpg?v=1735831783', 4.8, 10, '2026-05-08 13:04:04'),
(9, 'Uniqlo Basic Tee', 'Fashion', 2500, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT71B4LjQ6bgQBNZq-6HXKCzEiysYt5Q1pe6Q&s', 4.3, 5, '2026-05-08 13:04:04'),
(10, 'Nike Sports Tracksuit', 'Fashion', 16000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBWDgwKO__m2LPwhNTrplnEpIIStRCAJBcxA&s', 4.6, 15, '2026-05-08 13:04:04'),
(11, 'Ray-Ban Aviator', 'Sunglasses', 18000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQp0PzjTLC2vAJvCm11G4gq3cJJUU-vJaUXGg&s', 4.9, 0, '2026-05-08 13:04:17'),
(12, 'Prada Luxury Shades', 'Sunglasses', 45000, 'https://images.unsplash.com/photo-1508296695146-257a814070b4?q=80&w=600&auto=format&fit=crop', 4.8, 10, '2026-05-08 13:04:17'),
(13, 'Oakley Sports Glasses', 'Sunglasses', 22000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmMg7hlG_NswcKDqkzpSzKKzfhkh_j-t1eEA&s', 4.6, 5, '2026-05-08 13:04:17'),
(14, 'Gucci Designer Shades', 'Sunglasses', 60000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRodFRva8we8ZBfGGI9hI7RK1h_SnQpiOLJEQ&s', 5.0, 8, '2026-05-08 13:04:17'),
(15, 'Leather Crossbody Bag', 'Accessories', 8500, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKgZ-jkc2f1P5aQtpgf5aLV86J5IbKFV-qig&s', 4.5, 0, '2026-05-08 13:04:32'),
(16, 'Gucci Handbag', 'Accessories', 75000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOejD71XfnRYGC_S42D99a9bkqDrgQgxkENQ&s', 4.9, 5, '2026-05-08 13:04:32'),
(17, 'Nike Gym Backpack', 'Accessories', 6500, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQgmSmI4x1qeQnPvtaFYxGgLJqVgkc41dBhVQ&s', 4.4, 10, '2026-05-08 13:04:32'),
(18, 'Luxury Leather Wallet', 'Accessories', 5000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUxL09MykN0C5vNLJswN9k5xdTX1wPNtpomA&s', 4.6, 0, '2026-05-08 13:04:32'),
(19, 'Rolex Classic Watch', 'Accessories', 250000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS6WdqW9CBjXamvIP_rcblShcRxJPaH3W91OA&s', 5.0, 0, '2026-05-08 13:04:42'),
(20, 'Apple Watch Series', 'Accessories', 90000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTC8j-jsMzW9HyoUMPs6_JCa0fb8gncQblplA&s', 4.8, 12, '2026-05-08 13:04:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Minhal', 'minhal@gmail.com', '$2y$10$UBBE.ERChkunfP.viO.p/.mGQGa97IZwJsn78/CeSUmHuPPxqpVWa', '2026-05-08 10:16:05');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `wid` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `spid` int(11) DEFAULT NULL,
  `item_type` enum('featured','catalog') DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `spid` (`spid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `shop_products`
--
ALTER TABLE `shop_products`
  ADD PRIMARY KEY (`spid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wid`),
  ADD KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `shop_products`
--
ALTER TABLE `shop_products`
  MODIFY `spid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`spid`) REFERENCES `shop_products` (`spid`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
