-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for quanlyshop2
CREATE DATABASE IF NOT EXISTS `quanlyshop2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `quanlyshop2`;

-- Dumping structure for table quanlyshop2.bills
CREATE TABLE IF NOT EXISTS `bills` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `payment_method` int(11) DEFAULT NULL,
  `transfer_method` varchar(200) DEFAULT NULL,
  `total` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_voucher` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `count` int(11) DEFAULT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_category` varchar(200) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_product` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` float NOT NULL,
  `origin_price` int(11) DEFAULT NULL,
  `thumbnail` varchar(200) DEFAULT NULL,
  `material` varchar(200) DEFAULT NULL,
  `sold` int(11) DEFAULT 0,
  `created_at` varchar(200) NOT NULL,
  `id_category` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_category` (`id_category`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.products_bill
CREATE TABLE IF NOT EXISTS `products_bill` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `amount_buy` int(10) NOT NULL,
  `id_product_detail` int(10) NOT NULL,
  `id_bill` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_product_detail` (`id_product_detail`),
  KEY `id_bill` (`id_bill`),
  CONSTRAINT `products_bill_ibfk_1` FOREIGN KEY (`id_product_detail`) REFERENCES `products_detail` (`id`),
  CONSTRAINT `products_bill_ibfk_2` FOREIGN KEY (`id_bill`) REFERENCES `bills` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.products_cart
CREATE TABLE IF NOT EXISTS `products_cart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `amount_buy` int(10) NOT NULL,
  `id_product_detail` int(10) NOT NULL,
  `id_cart` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_product_detail` (`id_product_detail`),
  KEY `id_cart` (`id_cart`),
  CONSTRAINT `products_cart_ibfk_1` FOREIGN KEY (`id_product_detail`) REFERENCES `products_detail` (`id`),
  CONSTRAINT `products_cart_ibfk_2` FOREIGN KEY (`id_cart`) REFERENCES `cart` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.products_detail
CREATE TABLE IF NOT EXISTS `products_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL,
  `code_color` varchar(200) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `id_product` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_product` (`id_product`),
  CONSTRAINT `products_detail_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` varchar(200) NOT NULL,
  `stars` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `id_product` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_reviews_products` (`id_product`),
  KEY `FK_reviews_users` (`id_user`),
  CONSTRAINT `FK_reviews_products` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_reviews_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.tokenlogin
CREATE TABLE IF NOT EXISTS `tokenlogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `create_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tokenlogin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `phone` varchar(13) DEFAULT NULL,
  `fullname` varchar(200) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `forgotToken` varchar(100) NOT NULL,
  `activeToken` varchar(100) NOT NULL,
  `status` int(10) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `birthday` varchar(50) DEFAULT NULL,
  `role` int(10) DEFAULT 0,
  `avatar` varchar(200) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table quanlyshop2.vouchers
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(10) NOT NULL,
  `code` varchar(200) NOT NULL,
  `discount` int(11) NOT NULL,
  `unit` tinyint(1) NOT NULL DEFAULT 0,
  `start` date NOT NULL,
  `end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
