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

-- Dumping data for table quanlyshop2.bills: ~2 rows (approximately)
INSERT INTO `bills` (`id`, `date`, `end_date`, `status`, `payment_method`, `transfer_method`, `total`, `id_user`, `id_voucher`) VALUES
	(8, '2025-09-09 17:10:20', '2025-09-09 17:23:54', 2, NULL, NULL, 134987000, 2, NULL),
	(9, '2025-09-09 17:12:06', '2025-09-09 17:23:35', 2, NULL, NULL, 4060000, 2, NULL);

-- Dumping structure for table quanlyshop2.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `count` int(11) DEFAULT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table quanlyshop2.cart: ~1 rows (approximately)
INSERT INTO `cart` (`id`, `count`, `id_user`) VALUES
	(5, 4, 4),
	(6, 3, 2);

-- Dumping structure for table quanlyshop2.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_category` varchar(200) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table quanlyshop2.categories: ~5 rows (approximately)
INSERT INTO `categories` (`id`, `name_category`, `image`) VALUES
	(1, 'Sofa', 'sofa-9.jpg'),
	(2, 'Bàn', 'table.jpg'),
	(3, 'Giường ngủ', 'bed.jpg'),
	(4, 'Tủ quần áo', 'mau-tu-quan-ao.jpg'),
	(5, 'Tủ giày', 'BST-Coastal-4-768x512.jpg');

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

-- Dumping data for table quanlyshop2.products: ~24 rows (approximately)
INSERT INTO `products` (`id`, `name_product`, `description`, `price`, `origin_price`, `thumbnail`, `material`, `sold`, `created_at`, `id_category`) VALUES
	(1, 'Sofa Bolero 3 chỗ', 'Sofa Bolero 3 chỗ và Đôn vải xanh 18 sở hữu phần chân kim loại được sơn đen và phần nệm được bọc vải cao cấp màu xanh dương. Kiểu dáng thiết kế của sofa Bolero tuy đơn giản nhưng lại mang đến sự tinh tế cho không gian phòng khách còn là sản phẩm sofa đáng sở hữu bởi thiết kế và trải nghiệm sử dụng.', 6500000, 10969000, 'SOFA-JADORA-25-CHO-VAI-VACT8594-VACT3120-768x511.jpg', 'Kim loại, Vải', 19, '2025-09-09 09:44:54', 1),
	(2, 'Sofa Miami 3 chỗ + 1 armchair', 'Sofa Miami 3 chỗ và 1 armchair đặc trưng của phong cách Scandinavian đơn giản. Kiểu dáng hướng đến sự phù hợp với nhiều đối tượng sử dụng khác nhau. Thiết kế dạng 3.1 chỗ cho phép chủ nhân có thêm nhiều lựa chọn trong việc sắp đặt để có không gian phòng khách ưng ý. Lưng sofa bọc cách điệu tạo điểm nhấn cho cả không gian phòng khách nhà bạn. Tại Nhà Xinh có đa dạng các mẫu sofa đẹp hiện đại với kiểu dáng phong phú, phù hợp với nhiều thiết kế phòng khách.', 25400000, 27900000, '500-nhaxinh-phong-khach-sofa-bridge.jpg', 'Gỗ', 14, '2025-09-09 09:10:07', 1),
	(3, 'Bàn nước Elegance', 'Bàn nước Elegance với thiết kế tối giản nhưng vẫn toát lên nét sang trọng và tinh tế. Nhờ kết cấu độc đáo nên sản phẩm có trọng lượng nhẹ nhàng nhưng vô cùng chắc chắn. Sản phẩm phù hợp với không gian nội thất hiện đại và đặc biệt là phong cách Scandinavian.', 25430000, 26470000, '1000-ban-nuoc-cognac-768x511.jpg', 'Gỗ Ash', 3, '2025-09-09 09:12:13', 2),
	(4, 'Giường Coastal 1m8', 'Giường ngủ Coastal mang đến cảm giác như đang nằm trên bãi biển dài bình yên, với đường cong êm ái ở đầu giường, các cạnh cùng phần vạt hở duyên dáng hình chữ V, gợi nhớ đến hình ảnh chim hải âu bay trên biển. BST Coastal ban đầu được thiết kế cho căn hộ nghỉ dưỡng ở vùng duyên hải. Nhưng với sự sáng tạo và phá cách, Coastal trở nên năng động và phù hợp với nhiều không gian sống, mang thiên nhiên vào mọi không gian.', 3000000, 5800000, 'BST-Coastal-4-768x512.jpg', 'Gỗ Ash', 0, '2025-09-09 09:13:15', 3),
	(10, 'Sofa Miami 2 chỗ', 'Sofa Miami khoác lên mình một màu xám tinh tế, tối giản, mang đến không gian phòng khách hiện đại, trang nhã. Sofa Miami 2 chỗ vải xám sử dụng khung gỗ bọc vải kết hợp cùng với phần nệm ngồi có thể tháo rời, dễ dàng vệ sinh hiệu quả.', 3943200, 5894000, 'phong-khach-miami-vang-2-cho-768x511.jpg', 'Gỗ', 4, '2025-09-09 09:14:24', 1),
	(11, 'Sofa 3 chỗ Elegance', 'Sofa 3 chỗ trong bộ sưu tập mới Elegance, bộ sưu tập được lấy cảm hứng từ nội thất tinh xảo, nhẹ nhàng, nền nã nhưng đơn giản Elegance là sự kết hợp tuyệt hảo giữa sự bền chắc và trọng lượng tối giản. Sofa 3 chỗ da xanh có phần khung bằng gỗ tần bì đặc, tự nhiên được nhập khẩu từ Mỹ, phần nệm được bọc da bò S4 nhập khẩu Italy.', 7987000, 12903000, 'ban-3.jpg', 'Gỗ Ash, da bò', 1, '2025-09-09 09:15:46', 1),
	(12, 'Sofa ONA HER 3 chỗ', 'Sofa 3 chỗ ONA HER với bề mặt lớp da phủ cao cấp, mang đến trải nghiệm tinh tế mỗi khi chạm nhẹ lên bề mặt sản phẩm. Chân gỗ oak cùng đường nét bo tròn và thu nhỏ dần xuống phía dưới, tạo nên một thiết kế tinh xảo mà đầy vững chắc. Một tỉ lệ hoàn hảo cùng các đường may tỉ mỉ, sofa ONA HER giúp không gian căn hộ toát lên nét sang trọng và đẳng cấp.', 8900000, 10930000, 'ona-him-da-xanh-nau-768x511.jpg', 'Gỗ Oak, da bò', 0, '2025-09-09 09:17:18', 1),
	(13, 'Sofa Jadora 2.5 chỗ vải VACT8594/VACT3120', 'Sofa Jadora là sản phẩm được thiết kế và sản xuất bởi Nhà Xinh. Với kiểu dáng rộng rãi cùng phần đệm ngồi êm ái, Jadora hứa hẹn sẽ mang đến cho người dùng trải nghiệm thư thái nhất. Trong phiên bản mới, Jadora khoác lên mình màu sắc trang nhã, hiện đại với sự kết hợp của các tông màu mới mẻ sẽ là điểm nhấn tuyệt vời cho tổ ấm của bạn. Thiết kế tựa như một chiếc giường ngủ, sofa Jadora rất phù hợp để bạn ngồi đọc sách hoặc ngả lưng thư giãn. Sản phẩm dễ dàng phối hợp được với nhiều thiết kế khác như bàn nước hoặc bàn bên để tạo nên không gian sống độc đáo.', 8790900, 13789000, 'sofa_vai_poppy_mau_hong_12_goc_trai-768x511.jpeg', 'Khung gỗ - Nệm bọc vải 2 màu - 5 gối', 1, '2025-09-09 09:22:56', 1),
	(14, 'Bàn nước Fence', 'Bàn nước Fence, kim loại, kính', 7500000, 9600000, 'Ban-nuoc-Fence-768x511.jpg', 'Kim loại', 0, '2025-09-09 09:24:28', 2),
	(15, 'ghế tình yêu', 'ghe tinh yeu bao phe voi em ny bao lien dinh vs a quan nguyen okeeee em yeeu ', 1000, 1000000, 'Ghe-69-cao-cap-tai-Noi-That-Xuyen-A.jpg', 'da and go', 0, '2025-09-09 09:25:30', 1),
	(16, 'Sofa Cognac', 'Bàn nước Cognac được nhập khẩu, kết hợp độc đáo giữa gỗ mộc mạc và chân thép cho phòng khách nhà bạn thêm phong cách', 4060000, 5900000, 'phong-khach-cognac-5001.jpg', 'Gỗ Ash', 1, '2025-09-09 09:26:45', 1),
	(17, 'Bàn nước Cognac 2', 'Bàn nước Cognac mẫu 2 là sản phẩm mang phong cách cổ điển đến từ pháp, nó hoàn hảo từ sự kết hợp giữa khung kim loại và gỗ tái chế, sẽ thật tuyệt cho những cá nhân sành điệu đang tìm kiếm chiếc bàn nước này. Dòng sản phẩm phù hợp với hầu hết các thiết kế nhà hiện đại, đương đại hoặc chiết trung. Hình dạng vuông tạo nên sự phù hợp thực tế và chức năng trong không gian phòng của bạn.', 6060000, 7800000, 'ban_nuoc_cognac_2_chan_sat_pjf078_12-768x511.jpg', 'Gỗ tái chế', 0, '2025-09-09 09:28:29', 2),
	(18, 'Bàn bên Retiro gold L', 'Bàn bên Retiro gold L', 2800700, 3839999, 'BAN-BEN-RETIRO-GOLD-L-40X10-16713485L-2-3-768x511.jpg', 'Kim loại', 0, '2025-09-09 09:29:41', 2),
	(19, 'Bàn Dubai', 'Bàn bên Dubai là sản phẩm nằm trong bộ sưu tập (Dubai) line hàng đến từ đội ngủ thiết kế Nhà Xinh Mang phong cách RUSTIC tạo nên nét duyên dáng độc đáo cho ngôi nhà. Được thiết kế với khung kim loại sơn tĩnh điện trang trí, mặt bàn bằng gỗ (MDF) phủ lớp Laminate tạo nét đặc trưng cho phong cách trang trí của bạn. Một sản phẩm hợp xu hướng cho bất kỳ phòng nào, nó hoàn hảo để sử dụng làm bàn cuối ghế sofa hoặc làm bàn đầu giường trong phòng ngủ.', 3800700, 5639000, 'ban-ben-dubai-1-768x511.gif', 'Kim loại', 0, '2025-09-09 09:30:45', 2),
	(20, 'Giường ngủ gỗ Maxine', 'Giường ngủ gỗ Maxine 1m8 với đường nét hài hòa cùng thiết kế tinh xảo tạo vẻ ngoài sang trọng. Sản phẩm sử dụng khung gỗ hoàn thiện MDF veneer Walnut nên rất chắc chắn. Sản phẩm đem đến trải nghiệm thư giãn giúp bạn tận hưởng trọn vẹn giấc ngủ ngon. Giường Maxine có 2 kích thước là 1m6 và 1m8 cho bạn thoải mái lựa chọn theo nhu cầu sử dụng.', 6780000, 8900000, '103444-giuong-softly-1m8-vai-s8w-light-13-768x511.jpg', 'Gỗ Okumi', 0, '2025-09-09 09:31:50', 3),
	(21, 'Giường hộc kéo Iris', 'Giường hộc kéo Iris 1m6 với thiết kế đóng nút đầu giường, điểm đặc biệt là bốn ngăn chứa đồ rộng thuận tiện cất những vật dụng trong phòng ngủ như gối, mền, drap hết sức gọn gàng. Chắc chắn sẽ là sự lựa chọn tối ưu cho không gian phòng ngủ hiện đại. Giường hộc kéo Iris có 2 kích thước 1m6 và 1m8, đa dạng màu sắc.', 7800600, 12980000, 'giuong_iris_1m6_stone.jpg', 'Gỗ', 0, '2025-09-09 09:32:55', 3),
	(22, 'Giường ngủ Wynn', 'Giường ngủ bọc vải tổng hợp Wynn 1m8 (thuộc thương hiệu Calliragis - nội thất nhập khẩu Ý) với thiết kế độc đáo, sang trọng xen lẫn giữa nét hiện đại và chút cổ điển thể hiện ở những nút nhấn phần bọc đầu giường, màu nâu sang trọng cho không gian phòng ngủ thêm tinh tế mang lại cảm giác thoải mái.', 10800000, 15900000, 'phong-ngu-wynn1-500.jpg', 'G', 0, '2025-09-09 09:35:01', 3),
	(23, 'Giường ngủ bọc vải Softly G', 'Giường ngủ bọc vải Softly G 1m6 S9C được nhập khẩu từ thương hiệu nổi tiếng Calligaris của Ý, với đầu giường lớn, có đệm, vỏ bọc vải có thể tháo rời hoàn toàn. Giường Softly là lựa chọn hoàn hảo cho phòng ngủ thanh lịch.', 5670000, 6570000, 'Giuong-ngu-boc-vai-Softly-G-1m6-S8W-2-768x495.jpg', 'Gỗ', 0, '2025-09-09 09:36:52', 1),
	(24, 'Giường Leman', 'Giường Leman 1m8 vải VACT7464', 6700000, 9600000, 'giuong-leman-1m8-111430-768x511.jpg', 'Gỗ', 0, '2025-09-09 09:38:28', 3),
	(25, 'Tủ áo Wabrobe', 'Tủ áo Wabrobe', 8800000, 11870000, 'Tu-ao-Wabrobe-02-2-768x511.jpg', 'MDF Laminate', 0, '2025-09-09 09:39:13', 4),
	(26, 'Tủ áo Acrylic', 'Tủ áo Acrylic', 4560000, 5400000, 'Tu-ao-Acrylic-768x511.jpg', 'Thùng MFC', 0, '2025-09-09 09:40:12', 4),
	(27, 'Tủ áo hiện đại siêu mới', 'Mẫu tủ áo hiện đại của Nhà Xinh với thiết kế giản đơn, tối đa tiện ích bằng nhiều ngăn kéo và khoảng trống để cất trữ quần áo và đồ đạc.', 5670000, 6700000, 'tu-ao-hien-dai-500.jpg', 'MFC', 0, '2025-09-09 09:42:08', 4),
	(28, 'Tủ 3 buồng', 'Mẫu tủ áo hiện đại của Nhà Xinh với thiết kế giản đơn, tối đa tiện ích bằng nhiều ngăn kéo và khoảng trống để cất trữ quần áo và đồ đạc.', 4509000, 567000, '3_91000_1-768x513.jpg', 'MFC', 0, '2025-09-09 09:43:35', 4),
	(29, 'Tủ áo Maxine', 'Tủ áo Maxine chứa đựng đầy đủ công năng tối ưu cho việc cất trữ quần áo bằng việc bố trí sắp xếp hợp lý các ngăn tủ. Những chi tiết về phụ kiện cao cấp giúp cho việc thao tác nhẹ nhàng. Bề ngoài, tủ được thiết kế duyên dáng và thu hút với sắc nâu trầm và kim loại đồng. Maxine – Nét nâu trầm sang trọng Maxine, mang thiết kế vượt thời gian, gửi gắm và gói gọn lại những nét đẹp của thiên nhiên và con người nhưng vẫn đầy tính ứng dụng cao trong suốt hành trình phiêu lưu của nhà thiết kế người Pháp Dominique Moal. Bộ sưu tập nổi bật với màu sắc nâu trầm sang trọng, là sự kết hợp giữa gỗ, da bò và kim loại vàng bóng. Đặc biệt trên mỗi sản phẩm, những nét bo viên, chi tiết kết nối được sử dụng kéo léo tạo ra điểm nhất rất riêng cho bộ sưu tập. Maxine thể hiện nét trầm tư, thư giãn để tận hưởng không gian sống trong nhịp sống hiện đại. Sản phẩm thuộc BST Maxine được sản xuất tại Việt Nam.', 4560000, 5300000, '3-99496-1-768x511.jpg', 'Gỗ Okumi', 0, '2025-09-09 09:46:00', 4);

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

-- Dumping data for table quanlyshop2.products_bill: ~2 rows (approximately)
INSERT INTO `products_bill` (`id`, `amount_buy`, `id_product_detail`, `id_bill`) VALUES
	(10, 5, 3, 8),
	(11, 1, 10, 8),
	(12, 1, 15, 9);

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

-- Dumping data for table quanlyshop2.products_cart: ~6 rows (approximately)
INSERT INTO `products_cart` (`id`, `amount_buy`, `id_product_detail`, `id_cart`) VALUES
	(4, 9, 4, 5),
	(5, 1, 14, 5),
	(6, 4, 8, 5),
	(17, 1, 19, 6),
	(18, 1, 13, 6),
	(19, 1, 17, 6);

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

-- Dumping data for table quanlyshop2.products_detail: ~33 rows (approximately)
INSERT INTO `products_detail` (`id`, `amount`, `code_color`, `color`, `image`, `size`, `id_product`) VALUES
	(1, 22, '#a24e4e', 'Màu nâu', 'SOFA-JADORA-25-CHO-VAI-VACT8594-VACT3120-1-768x511.jpg', 'D2250 - R900 - C790/Đôn D720-R720-C420', 1),
	(2, 32, '#a24e4e', 'Màu nâu', 'SOFA-JADORA-25-CHO-VAI-VACT8594-VACT3120-1-768x511.jpg', 'D2250 - R900 - C790/Đôn D720-R720-C420', 1),
	(3, 38, '#000000', 'xanh đen', '500-73906-nha-xinh-phong-khach-sofa3cho-bridge2.jpg', 'D1965 - R835 - C805', 2),
	(4, 25, '#a69126', 'gỗ', '500-71317-nha-xinh-phong-khach-ban-nuoc2.jpg', 'D1200 - R600 - C400 mm', 3),
	(5, 25, '#a69126', 'gỗ', '500-71317-nha-xinh-phong-khach-ban-nuoc2.jpg', 'D1200 - R600 - C400 mm', 3),
	(6, 25, '#a69126', 'gỗ', '500-71317-nha-xinh-phong-khach-ban-nuoc2.jpg', 'D1200 - R600 - C400 mm', 3),
	(8, 30, '#000000', 'xanh', 'Giuong-Coastal-1m8-xanh-1-768x511.jpg', 'D2000 - R1800 - C1080 mm', 4),
	(10, 31, '#43604c', 'xanh đậm', '102421-sofa-elegance-3-cho-mau-tu-nhien-da-xanh-768x511.jpg', 'aqe12312', 11),
	(11, 23, '#334837', 'Xanh lá', 'sofa-ona-her-3-cho-da-xanh-s4-1-768x511.jpg', 'D1950-R900-C850', 12),
	(12, 43, '#c95b2c', 'Màu cam - Góc trái', 'sofa_vai_poppy_mau_hong-768x511.jpg', 'D2400/1350 - R830 - C700 mm', 13),
	(13, 32, '#000000', 'Màu đen', 'Ban-nuoc-Fence-1-768x511.jpg', 'D2100 - R900 - C750 mm', 14),
	(14, 6, '#f41010', 'Màu đỏ', 'Ghe-69-cao-cap-tai-Noi-That-Xuyen-A.jpg', 'D1700 - R850 - C770 mm', 15),
	(15, 22, '#c38822', 'màu da', 'phong-khach-cognac-5001.jpg', 'D2250 - R905 - C790 mm', 16),
	(16, 12, '#d3d61f', 'Màu vàng', 'ban-nuoc-cognac2-768x461.jpg', 'D2000 - R880 - C700mm', 17),
	(17, 5, '#c6752a', 'Màu cam nâu', 'BAN-BEN-RETIRO-GOLD-L-40X10-16713485L-2-3-768x511.jpg', 'D2200 - R1200 - C650/850 mm', 18),
	(18, 7, '#d9a73a', 'Màu gỗ', 'ban_ben_dubai_1-768x511.jpg', 'D1200 - R360 - C600 mm', 19),
	(19, 26, '#fbf9f9', 'Màu trắng', '103444-giuong-softly-1m8-vai-s8w-light-768x511.jpg', '323x234', 20),
	(20, 18, '#e1c537', 'Vàng', 'sofa-miami-2-cho-boc-vai-vang-2-768x511.jpg', 'D2300 - R800 - C760 mm', 10),
	(23, 34, '#e1c537', 'Vàng', 'sofa-miami-2-cho-boc-vai-vang-2-768x511.jpg', 'D2300 - R800 - C760 mm', 10),
	(26, 43, '#c95b2c', 'Màu cam - Góc trái', 'sofa_vai_poppy_mau_hong-768x511.jpg', 'D2400/1350 - R830 - C700 mm', 13),
	(29, 36, '#f41010', 'Màu đỏ', 'Ghe-69-cao-cap-tai-Noi-That-Xuyen-A.jpg', 'D1700 - R850 - C770 mm', 15),
	(30, 23, '#c38822', 'màu da', 'phong-khach-cognac-5001.jpg', 'D2250 - R905 - C790 mm', 16),
	(31, 23, '#c38822', 'màu da', 'phong-khach-cognac-5001.jpg', 'D2250 - R905 - C790 mm', 16),
	(33, 10, '#ffffff', 'Màu trắng', 'phong-ngu-giuong-hoc-keo-iris-4-768x512.jpg', 'D1600 - R750 - C450 mm', 21),
	(34, 23, '#b7c478', 'Màu be', 'phong-ngu-wynn1-500.jpg', 'D1200- R900- C400 mm', 22),
	(35, 41, '#c1b8b8', 'Màu kim loại', 'Giuong-ngu-boc-vai-Softly-G-1m6-S8W-2-768x495.jpg', '400x1010 mm', 23),
	(36, 33, '#c2bdbd', 'Màu xám', 'giuong-leman-1m8-111430-1-768x511.jpg', 'D450-R450-C500', 24),
	(38, 28, '#816565', 'Màu đỏ nâu', 'Tu-ao-Wabrobe-02-768x511.jpg', '1m8', 25),
	(39, 32, '#000000', 'Màu xám cao cấp', 'Tu-ao-Acrylic-768x511.jpg', '1m8', 26),
	(40, 16, '#d0cdcd', 'Màu xám', 'tu-ao-hien-dai-5-500.jpg', '1m8', 27),
	(41, 23, '#ab9c63', 'Màu gỗ', '3_91000_2-768x513.jpg', '1', 28),
	(42, 58, '#ab9c63', 'Màu gỗ', '3_91000_2-768x513.jpg', '1', 28),
	(43, 29, '#8f2d41', 'Màu gỗ', '3-99496-1-768x511.jpg', '1m8', 29);

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

-- Dumping data for table quanlyshop2.reviews: ~0 rows (approximately)

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

-- Dumping data for table quanlyshop2.tokenlogin: ~0 rows (approximately)

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

-- Dumping data for table quanlyshop2.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `email`, `phone`, `fullname`, `password`, `forgotToken`, `activeToken`, `status`, `address`, `birthday`, `role`, `avatar`, `create_at`, `update_at`, `is_deleted`) VALUES
	(1, 'hoangvanduc2504@gmail.com', '0327386554', 'Đức Hoàng', '$2y$10$vZxwkcVzBmjc3KK7iETGp.ly7LbUN80w8rUz65QU.pI5.2BOKNpAG', '', '', 1, NULL, NULL, 1, NULL, '2025-02-28 14:08:47', NULL, 0),
	(2, '22111061953@hunre.edu.vn', '0987654321', 'hoangduc', '$2y$10$AIXyXhEqRJFum9nsNkOQdOrOsG7XZus224gnm5oH36pPnOmNuSK.K', '', '', 1, 'hà nội', NULL, 0, '5.png', '2025-02-28 14:01:56', '2025-09-09 09:57:05', 0),
	(4, 'hoangvanduc2544@gmail.com', '0327386554', 'Đức Hoàng', '$2y$10$qAmBaPH/MNeHMFZ7dM0GuO3ozQ5P7PIpKCTiUYkZEzUTEwGv6o/9a', '', '', 1, NULL, NULL, 0, NULL, '2025-09-08 05:04:33', NULL, 0);

-- Dumping structure for table quanlyshop2.vouchers
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(10) NOT NULL,
  `code` varchar(200) NOT NULL,
  `discount` int(11) NOT NULL,
  `unit` tinyint(1) NOT NULL DEFAULT 0,
  `start` date NOT NULL,
  `end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table quanlyshop2.vouchers: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
