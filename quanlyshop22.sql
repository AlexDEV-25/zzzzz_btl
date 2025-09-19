-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 19, 2025 lúc 01:17 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlyshop22`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bills`
--

CREATE TABLE `bills` (
  `id` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `payment_method` int(11) DEFAULT NULL,
  `transfer_method` varchar(200) DEFAULT NULL,
  `total` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_voucher` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bills`
--

INSERT INTO `bills` (`id`, `date`, `end_date`, `status`, `payment_method`, `transfer_method`, `total`, `id_user`, `id_voucher`) VALUES
(36, '2025-09-17 02:14:28', NULL, 3, 0, NULL, 31900000, 13, NULL),
(37, '2025-09-17 02:15:00', NULL, 2, 1, NULL, 25430000, 13, NULL),
(38, '2025-09-17 02:16:30', NULL, 3, 0, NULL, 2950000, 13, 4),
(39, '2025-09-17 02:19:18', '2025-09-17 02:52:04', 4, 0, NULL, 9696000, 13, 3),
(40, '2025-09-17 02:45:12', '2025-09-17 02:52:22', 4, 0, NULL, 101582480, 13, 3),
(41, '2025-09-17 02:55:31', NULL, 0, 0, NULL, 17800000, 14, NULL),
(42, '2025-09-17 02:55:39', '2025-09-17 02:58:52', -1, 0, NULL, 6060000, 14, NULL),
(43, '2025-09-17 02:55:46', NULL, 0, 0, NULL, 26400000, 14, NULL),
(44, '2025-09-17 02:55:52', '2025-09-17 02:56:09', 4, 0, NULL, 20180000, 14, NULL),
(45, '2025-09-18 03:26:07', '2025-09-18 03:26:38', 4, 0, NULL, 6500000, 14, NULL),
(46, '2025-09-18 14:26:34', NULL, 0, 0, NULL, 9190000, 14, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(10) NOT NULL,
  `count` int(11) DEFAULT NULL,
  `id_user` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `count`, `id_user`) VALUES
(13, 0, 12),
(14, 0, 13),
(15, 0, 14);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL,
  `name_category` varchar(200) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name_category`, `image`, `is_deleted`) VALUES
(1, 'Nội thất phòng ngủ', 'noi-that-phong-ngu-moho_d28ec3a50aba41fcada1da72a8d0ea4a_2048x2048.jpg', 0),
(2, 'Nội thất phòng khách', 'noi-that-phong-khach-moho_f32c152d36b2405cb48a5f42550a7ae1_2048x2048.jpg', 0),
(3, 'Nội thất phòng ăn', 'pro_nau_noi_that_moho_ban_an___4__9d0804e2bf484bbea69bf862631abf55_2048x2048.jpg', 0),
(4, 'Nội thất phòng làm việc', 'z5884497835969_4835d706ab353e190c7757e4ca9b6bde_7dac15f6a6ae47c1805e979edaeb4d10_2048x2048.jpg', 0),
(5, 'moi moi', 'tu-ao-hien-dai-4.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL COMMENT 'mã đơn hàng',
  `thanh_vien` varchar(100) NOT NULL COMMENT 'thành viên thanh toán',
  `money` float NOT NULL COMMENT 'số tiền thanh toán',
  `code` varchar(255) DEFAULT NULL COMMENT 'ghi chú thanh toán',
  `vnp_response_code` varchar(255) NOT NULL COMMENT 'mã phản hồi',
  `code_vnpay` varchar(255) NOT NULL COMMENT 'mã giao dịch vnpay',
  `code_bank` varchar(255) NOT NULL COMMENT 'mã ngân hàng',
  `time` datetime NOT NULL COMMENT 'thời gian chuyển khoản'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `code_product` varchar(50) NOT NULL DEFAULT '0',
  `name_product` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` float NOT NULL,
  `origin_price` int(11) DEFAULT NULL,
  `thumbnail` varchar(200) DEFAULT NULL,
  `material` varchar(200) DEFAULT NULL,
  `sold` int(11) DEFAULT 0,
  `created_at` varchar(200) NOT NULL,
  `id_category` int(10) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `code_product`, `name_product`, `description`, `price`, `origin_price`, `thumbnail`, `material`, `sold`, `created_at`, `id_category`, `is_deleted`) VALUES
(1, 'MA01', 'Giường Ngủ Có Hộc Và Ổ Điện MOHO VIENNA - MÀU TỰ NHIÊN', 'Giường ngủ gỗ có hộc &#38; ổ điện VIENNA của Nội Thất MOHO là lựa chọn thông minh dành cho gia đình trẻ hoặc cá nhân sống trong căn hộ nhỏ, yêu thích sự tiện dụng và phong cách tối giản. Với thiết kế đa năng, sản phẩm vừa tiết kiệm diện tích, vừa mang đến trải nghiệm sống tiện nghi và an toàn cho người dùng. Ưu điểm nổi bật •&#9;Tối ưu không gian lưu trữ: Tích hợp 2 ngăn kéo lớn bên hông giường, giúp cất giữ quần áo, chăn ga gối nệm và vật dụng cá nhân, giữ cho phòng ngủ luôn gọn gàng. •&#9;Kệ đầu giường tiện lợi: Thay thế tủ đầu giường, bạn có thể dễ dàng đặt sách, điện thoại hoặc đồ trang trí. •&#9;Trang bị ổ điện thông minh: Bao gồm cổng USB Type-C và Lightning, hỗ trợ sạc nhanh cho thiết bị điện tử ngay tại giường. •&#9;Thiết kế an toàn &#38; bền bỉ: Các góc giường bo tròn tinh tế, chân giường chắc chắn kèm đinh nhựa chống trượt, đảm bảo an toàn và hạn chế tiếng ồn. •&#9;Màu sắc linh hoạt: Hai lựa chọn màu gỗ tự nhiên và màu trắng, dễ dàng phối hợp với nhiều phong cách nội thất hiện đại.', 9190000, 11990000, 'pro_mau_tu_nhien_noi_that_moho_giuong_co_hoc_vienna_88ba7274638540c0afc3a4d40860ced1_master.jpg', 'Gỗ, kim loại', 29, '2025-09-18 06:29:03', 1, 0),
(2, 'MA02', 'Giường Phòng Ngủ 4 Món MOHO HOBRO', 'Gỗ tràm tự nhiên có khả năng chống mối mọt, côn trùng cao, hạn chế được khuyết tật bên trong gỗ và tạo nên sự gắn chắc để cho ra đời những món nội thất hoàn chỉnh.  Với tiêu chí ưu tiên là bảo vệ môi trường và cung cấp những sản phẩm an toàn, tốt cho sức khỏe của con người, MOHO đã cân nhắc và chọn lọc sử dụng những nguyên liệu tốt nhất trong từng sản phẩm. Bộ sưu tập HOBRO được làm từ các nguyên liệu : Gỗ tràm, gỗ công nghiệp MDF/ MFC phủ Melamin đạt chuẩn Carb P2 và gỗ cao su.', 19590000, 22980000, 'pro_nau_combo_phong_ngu_hobro_tu_thanh_treo_2bcb6532fc864a3dac94e48d84dbac84_master.jpg', 'Gỗ tràm', 22, '2025-09-18 06:35:33', 1, 0),
(3, 'MA03', 'Giường VLINE - VIENNA', '•  Giường sử dụng gỗ tràm tự nhiên kết hợp veneer gỗ sồi để tăng thẩm mỹ + độ bền.  •  Chân giường làm từ gỗ cao su tự nhiên (hoặc gỗ thông tùy nguồn) giúp chịu lực tốt.  •  Tấm phản (nền giường nơi đặt nệm) làm bằng gỗ plywood chuẩn CARB-P2, đảm bảo an toàn về chất lượng khí thải, ít độc hại.', 19200000, 16090000, 'pro_combo_mix_giuong_ngu_tu_cua_lua_2_canh_moho_30cd112d90c6413bbfc493ef2ad43772_master.jpg', ' Gỗ công nghiệp MFC phủ Melamin chuẩn CARB-P2 ', 16, '2025-09-18 06:38:49', 1, 0),
(4, 'MA04', 'Giường Có Hộc VIENNA', '•  “Có hộc”: giường có hộc kéo/ ngăn chứa để đồ bên trong – giúp tận dụng không gian lưu trữ tốt hơn, có thể để chăn ga, gối hoặc vật dụng nhỏ gọn.  •  “&#38; ổ điện”: tích hợp ổ điện (cổng sạc) để sạc thiết bị như điện thoại, máy tính bảng, rất tiện khi sử dụng buổi tối hay khi nằm trên giường', 16690000, 21800000, 'combo-phong-ngu-tu-cua-lua-giuong-co-hoc_1_ba2ae3204a7c4495ac9eb9231ec0e0db_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 9, '2025-09-18 06:42:10', 1, 0),
(10, 'MA05', ' Giường Phòng Ngủ MOHO VLINE Màu Nâu', 'Gỗ tự nhiên  Sử dụng gỗ cao su và gỗ tràm tự nhiên đảm bảo vệ độ chắc chắn cao, chịu lực tốt và bền lâu  Gỗ công nghiệp MDF/MFC  Gỗ công nghiệp (PB, MDF) đạt chuẩn CARB-P2 an toàn tuyệt đối cho người sức khỏe người dùng và đạt chứng nhận FSC bảo vệ và phát triển rừng bền vững.   Gỗ công nghiệp Plywood  Tấm phảm sử dụng chất liệu Plywood 12mm theo tiêu chuẩn CARBP2 vừa thân thiện với môi trường, đảm bảo sức khỏe và đặc biệt độ chịu lực tại 1 khu vực với diện tích 400 x 488mm lên tới 175kg khi dùng nệm trên 15cm.', 14450000, 19600000, 'pro_combo_2_mon_noi_that_moho_phong_ngu_vline__9__231cf74a806a4921950c74c1a5c5324c_master.png', 'Gỗ', 22, '2025-09-18 06:44:36', 1, 0),
(11, 'MA06', 'Set Tủ Quần Áo Kèm Tủ Nóc 2m5 VIENNA', 'Không gian lưu trữ phân tầng – Gọn gàng cho cả gia đình có con nhỏ  Với thiết kế 4 cánh chia làm 3 khoang chức năng rõ ràng, bộ tủ này giúp cả gia đình – đặc biệt là gia đình có trẻ nhỏ – sắp xếp đồ đạc khoa học, nhanh gọn và dễ lấy.  Tủ dưới được chia thông minh:  Tủ đôi dùng cho đồ vợ chồng: quần áo gấp, ngăn kéo đựng đồ nhỏ, phụ kiện  Tủ thanh treo cao thích hợp cho áo sơ mi, váy dài, áo khoác – giữ đồ phẳng phiu  Tủ kệ ngăn cực tiện để phân loại quần áo trẻ em, khăn sữa, hộp đồ, túi đựng theo từng tầng  Tủ nóc phía trên chạy liền mạch cả chiều ngang – hoàn hảo để cất chăn gối dự phòng, vali, đồ dùng theo mùa… tránh bụi bặm, an toàn và gọn gàng.', 9130000, 9800000, 'pro_mau_tu_nhien_tu_noc_vienna_5_6846910609194036ac4ce4f9a93d35a9.png', 'Gỗ Ash, da bò', 2, '2025-09-18 06:49:00', 1, 0),
(12, 'MA07', 'Tủ Quần Áo Nóc MOHO VIENNA', 'Thiết kế hiện đại – đồng bộ Tủ nóc gắn liền như phần mở rộng của tủ quần áo VIENNA, tạo cảm giác sang trọng và gọn gàng như tủ kịch trần  Tối ưu lưu trữ đồ ít dùng Lý tưởng để cất chăn mền mùa đông, vali, hộp kỷ niệm – những thứ bạn không dùng thường xuyên nhưng vẫn cần được sắp xếp gọn gàng.  Chất liệu cao cấp, đạt tiêu chuẩn xuất khẩu Sử dụng vật liệu theo tiêu chuẩn CARB-P2, đảm bảo an toàn cho sức khỏe và hạn chế tối đa mùi hắc của formaldehyde  Lắp đặt an toàn, cố định chắc chắn Tủ được bắt vít liên kết trực tiếp với nóc tủ quần áo bên dưới, đảm bảo an toàn trong suốt quá trình sử dụng.', 1350000, 1430000, 'pro_mau_tu_nhien_tu_noc_vienna_1_7951d3c5175a4c4aaa31406346da3d8c_master.png', 'Gỗ công nghiệp phủ Melamine CARB-P2', 6, '2025-09-18 06:54:11', 1, 0),
(13, 'MA08', 'Giường Ngủ MOHO KOSTER Màu Nâu 1m6', 'Koster – Những tác phẩm nghệ thuật Mid Century tuyệt vời mang đậm phong cách thiết kế Đan Mạch  Vào những năm thập niên 60 và 70, Đan Mạch đã chứng kiến sự phát triển mạnh mẽ của ngành công nghiệp nội thất. Với lối thiết kế sử dụng những đường cong mềm mại và hình học đơn giản, sự kết hợp hoàn hả giữa tự nhiên và hiện tại, pha chút táo bạo khi sử dụng những màu sắc tự nhiên nhưng sáng rực, đặc biệt là tập trung vào tính thực tiễn, đảm bảo sự thoải mái tối đa đã tạo nên những thiết kế vô cùng độc đáo, xuất sắc và mang đậm phong cách của những thập niên cũ.', 7350000, 8400000, 'pro_nau_noi_that_moho_giuong_ngu_moho_koster_mau_nau_1m6_10_51e6e6e31109428fb451e6599b8dd21d_master.jpg', 'Gỗ cao su và gỗ MFC/ MDF phủ Melamin chuẩn CARB P2', 3, '2025-09-18 06:55:54', 1, 0),
(14, 'MA09', 'Tủ Quần Áo Cửa Lùa VIENNA 1m2', 'Tối ưu không gian – Giải pháp gọn nhẹ cho mọi căn phòng Tủ sử dụng cơ chế cửa trượt hiện đại, loại bỏ hoàn toàn nhu cầu chừa khoảng mở cánh như tủ truyền thống. Nhờ đó, sản phẩm dễ dàng bố trí ở các không gian nhỏ như phòng ngủ compact, căn hộ studio hay vị trí sát giường, sát tường. Linh hoạt trong lưu trữ – Tối đa hóa công năng Thiết kế bên trong được tính toán hợp lý với thanh treo, kệ cố định và kệ tháo rời. Người dùng có thể tùy chỉnh bố cục để chứa quần áo, chăn màn, hộp phụ kiện... phù hợp với nhu cầu sử dụng hàng ngày.', 7690000, 9700000, 'pro_mau_tu_nhien_tu_canh_lua_moho_vienna_b976796a233940f8a8f38a608a1e813b_master.png', 'Gỗ công nghiệp MFC phủ Melamin chuẩn CARB-P2', 16, '2025-09-18 06:58:36', 1, 0),
(15, 'MA10', 'Giường Phòng Ngủ MOHO VLINE Màu Tự Nhiên', '•  Đầu giường được thiết kế nghiêng, với hai bản gỗ lớn, tạo độ cong hợp lý để bạn có thể tựa lưng thư giãn khi ngồi đọc sách, xem điện thoại…  •  Giường có tấm phản nguyên khối (không xương hay thanh rời quá nhỏ), giúp tăng tính vững chắc, tránh lún hoặc võng khi sử dụng nệm dày.  •  Khung đỡ + thanh chịu lực được thiết kế khít nhau, giúp tổng thể chắc chắn, hạn chế tiếng kêu, đảm bảo nệm không bị trượt ra ngoài khi sử dụng. ', 14680000, 19800000, 'Ghe-69-cao-cap-tai-Noi-That-Xuyen-A.jpg', 'Gỗ tràm tự nhiên, Veneer gỗ tràm tự nhiên', 1, '2025-09-18 07:06:07', 1, 1),
(16, 'MA11', 'Giường Phòng Ngủ MOHO VLINE', '•  Đầu giường được thiết kế nghiêng, với hai bản gỗ lớn, tạo độ cong hợp lý để bạn có thể tựa lưng thư giãn khi ngồi đọc sách, xem điện thoại…  •  Giường có tấm phản nguyên khối (không xương hay thanh rời quá nhỏ), giúp tăng tính vững chắc, tránh lún hoặc võng khi sử dụng nệm dày.  •  Khung đỡ + thanh chịu lực được thiết kế khít nhau, giúp tổng thể chắc chắn, hạn chế tiếng kêu, đảm bảo nệm không bị trượt ra ngoài khi sử dụng. ', 14680000, 19470000, 'pro_combo_2_mon_noi_that_moho_combo___3__d4da43d867c44b71974f28c65cdb7097_master.png', 'Gỗ tràm tự nhiên', 2, '2025-09-18 07:15:00', 1, 1),
(17, 'MA12', 'Bàn nước Cognac 2', 'Bàn nước Cognac mẫu 2 là sản phẩm mang phong cách cổ điển đến từ pháp, nó hoàn hảo từ sự kết hợp giữa khung kim loại và gỗ tái chế, sẽ thật tuyệt cho những cá nhân sành điệu đang tìm kiếm chiếc bàn nước này. Dòng sản phẩm phù hợp với hầu hết các thiết kế nhà hiện đại, đương đại hoặc chiết trung. Hình dạng vuông tạo nên sự phù hợp thực tế và chức năng trong không gian phòng của bạn.', 6060000, 7800000, 'ban_nuoc_cognac_2_chan_sat_pjf078_12-768x511.jpg', 'Gỗ tái chế', 4, '2025-09-18 07:26:01', 1, 1),
(18, 'MA13', 'Tủ Quần Áo Gỗ Có Gương MOHO GRENAA 2 ', '•  Có gương gắn bên ngoài: giúp bạn tiện thử đồ ngay trước khi bước ra khỏi tủ, tăng công năng sử dụng &#38; tiết kiệm không gian treo/mở cửa.  •  Cửa tủ 2 cánh rộng, có thể có phần gương nằm ở 1 trong 2 cánh — hoặc gương có thể toàn bộ một cánh, tùy mẫu (thông tin chi tiết mẫu cụ thể). •  Bên trong có thanh treo quần áo + ngăn kệ: phục vụ việc treo đồ, gấp đồ, để phụ kiện. Vừa đủ linh hoạt cho nhu cầu hàng ngày.  •  Kiểu dáng gần như hiện đại – tối giản, phù hợp phong cách nội thất phòng ngủ đơn giản, thoáng, thiên về sử dụng công năng nhiều hơn là họa tiết cầu kỳ.', 4890000, 6100000, 'pro_1m2_tu_quan_ao_grenaa_noi_that_moho_main_2921c7f6218a4972b42ef058e10c9c3a_master.png', 'Gỗ công nghiệp phủ Melamine CARB-P2', 3, '2025-09-18 07:43:48', 1, 0),
(19, 'MA14', 'Tủ Quần Áo Ubeda Ngăn Kệ 201 Có Gương', '•  Có gương gắn bên ngoài: giúp bạn tiện thử đồ ngay trước khi bước ra khỏi tủ, tăng công năng sử dụng &#38; tiết kiệm không gian treo/mở cửa.  •  Cửa tủ 2 cánh rộng, có thể có phần gương nằm ở 1 trong 2 cánh — hoặc gương có thể toàn bộ một cánh, tùy mẫu (thông tin chi tiết mẫu cụ thể). •  Bên trong có thanh treo quần áo + ngăn kệ: phục vụ việc treo đồ, gấp đồ, để phụ kiện. Vừa đủ linh hoạt cho nhu cầu hàng ngày.  •  Kiểu dáng gần như hiện đại – tối giản, phù hợp phong cách nội thất phòng ngủ đơn giản, thoáng, thiên về sử dụng công năng nhiều hơn là họa tiết cầu kỳ.', 6990000, 8200000, 'pro_mau_tu_nhien_noi_that_moho_set_tu_ubeda_3_canh_nhieu_kich_thuoc_4_5d411dace2224f3093e5491377493c66_master.png', 'Gỗ cao su và gỗ MFC chuẩn CARB P2', 2, '2025-09-18 07:45:56', 1, 0),
(20, 'MA15', 'Giường ngủ gỗ Maxine', 'Giường ngủ gỗ Maxine 1m8 với đường nét hài hòa cùng thiết kế tinh xảo tạo vẻ ngoài sang trọng. Sản phẩm sử dụng khung gỗ hoàn thiện MDF veneer Walnut nên rất chắc chắn. Sản phẩm đem đến trải nghiệm thư giãn giúp bạn tận hưởng trọn vẹn giấc ngủ ngon. Giường Maxine có 2 kích thước là 1m6 và 1m8 cho bạn thoải mái lựa chọn theo nhu cầu sử dụng.', 6780000, 8900000, '103444-giuong-softly-1m8-vai-s8w-light-13-768x511.jpg', 'Gỗ Okumi', 2, '2025-09-18 17:10:03', 1, 0),
(21, 'MA16', 'Giường Phòng Ngủ 2 Món MOHO RANDER HOTEL (Có Ổ Điện - 2m)', '•  Thiết kế đầu giường lấy cảm hứng từ khách sạn cao cấp: kiểu sang trọng, thanh lịch, tạo cảm giác phòng ngủ như resort/khách sạn 5 sao.  •  Phong cách hiện đại, tối giản Hàn Quốc: ít chi tiết rườm rà, đường nét gọn gàng, phù hợp với nội thất căn hộ hiện đại.  •  Tủ đầu giường tích hợp ổ điện: tiện để sạc điện thoại, máy tính bảng, etc. mà không cần dây nối dài xa; giúp thuận tiện sử dụng ngay bên giường.  •  Melamine phủ bề mặt: chống trầy xước, dễ lau chùi, giữ vẻ sáng &#38; sạch sẽ lâu hơn so với gỗ chỉ sơn thường.  •  Chân &#38; góc được thiết kế/dán xử lý cẩn thận (góc bo tròn, chân giường vững chắc) để giảm tiếng kêu, an toàn hơn khi sử dụng, đặc biệt có trẻ em. ', 6499000, 9600000, 'pro_mau_tu_nhien_noi_that_moho_combo_giuong_moho_rander_2m_71d2d7d969204401a05bc83378a5510b_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 07:34:33', 1, 0),
(22, 'MA17', 'Giường ngủ Wynn', 'Đầu giường cao, bọc nệm: đầu giường thường có độ cao để bạn có thể tựa lưng ngồi đọc sách, xem TV. Bọc vải nhung (velvet) hoặc vải linen, hoặc da giả, tạo cảm giác mềm mại, sang trọng.  Khung giường: khung sườn làm từ gỗ hoặc gỗ công nghiệp chắc chắn, xung quanh giường có thể bọc lớp đệm + vải để tạo tính “ôm” hơn.  Chân giường thấp hoặc trung bình: tùy thiết kế tối giản hoặc luxury; chân có thể bằng gỗ hoặc chân sắt, ít khi quá cao để giữ sự cân đối.  Tấm phản / nền giường: có thể là thanh gỗ hoặc plywood, có độ chịu lực tốt; nếu bọc nệm thì lớp đệm phía trên nền phản tạo cảm giác êm hơn.  Vải bọc: vải chất lượng cao, khả năng chống bẩn, dễ lau, màu sắc trung tính hoặc tone trầm hợp nhiều phong cách nội thất.', 10800000, 15900000, 'phong-ngu-wynn1-500.jpg', 'G', 0, '2025-09-18 07:50:00', 1, 0),
(23, 'MA18', 'Giường ngủ bọc vải Softly G', 'Giường ngủ bọc vải Softly G 1m6 S9C được nhập khẩu từ thương hiệu nổi tiếng Calligaris của Ý, với đầu giường lớn, có đệm, vỏ bọc vải có thể tháo rời hoàn toàn. Giường Softly là lựa chọn hoàn hảo cho phòng ngủ thanh lịch.', 5670000, 6570000, 'Giuong-ngu-boc-vai-Softly-G-1m6-S8W-2-768x495.jpg', 'Gỗ', 7, '2025-09-09 09:36:52', 1, 0),
(24, 'MA19', 'Giường Leman', 'Kiểu thiết kế / phong cách  Đầu giường có nghiêng hay thẳng? Có bọc nệm (fabric/da) hay hoàn toàn bằng gỗ?  Có phần chân giường nổi bật, chân gỗ kiểu gì, hình dáng như thế nào?  Có tay viền, viền décor hay chỉ tối giản?  Chất liệu &#38; cấu tạo  Gỗ tự nhiên hay gỗ công nghiệp? Nếu gỗ công nghiệp thì loại nào (MDF / MFC / plywood)?  Bề mặt hoàn thiện: veneer, sơn phủ, melamine, sơn PU, da/vải nếu bọc?  Tấm phản (nền giường) là dạng thanh gỗ, plywood nguyên tấm, hay các thanh nhỏ rời?  Kích thước  Chiều ngang (rộng) – thường tương ứng với kích cỡ nệm: 1m2, 1m4, 1m6, 1m8…  Chiều dài &#38; cao đầu giường, độ cao từ sàn lên gầm (nếu bạn muốn để đồ bên dưới hoặc vệ sinh dễ dàng)', 6700000, 9600000, 'giuong-leman-1m8-111430-768x511.jpg', 'Gỗ', 2, '2025-09-18 07:51:07', 1, 0),
(25, 'MA20', 'Tủ áo Wabrobe', 'Cánh mở đơn / cánh mở đôi: hai loại phổ biến — tủ 2 cánh mở sang hai bên hoặc tủ cánh trượt nếu muốn tiết kiệm không gian.  Có gương hoặc không: thường có gương gắn bên ngoài một cánh hoặc toàn bộ cánh để soi quần áo tiện lợi.  Ngăn kệ + thanh treo: bên trong gồm thanh để treo áo/quần, ngăn kệ để gấp đồ hoặc phụ kiện (tất, mũ, khăn). Đôi khi có ngăn kéo nhỏ (drawers) để đựng đồ nhỏ.  Ngăn phụ &#38; khoang phụ: khoang nhỏ, ngăn kéo bên hông hoặc phía dưới để đồ lót, phụ kiện hay giày dép tùy tủ thiết kế.', 8800000, 11870000, 'Tu-ao-Wabrobe-02-2-768x511.jpg', 'MDF Laminate', 3, '2025-09-18 07:52:05', 1, 0),
(26, 'MA21', 'Tủ áo Acrylic', '•  “Acrylic” ở đây thường là cánh tủ hoặc bề mặt trang trí được phủ bằng lớp Acrylic sáng bóng hoặc “bóng gương”. Bề mặt này rất mịn, có độ phản chiếu nhẹ, mang lại cảm giác sang trọng, hiện đại.  •  Thùng / khung tủ thường làm từ gỗ công nghiệp (MDF hoặc MFC), có loại lõi chống ẩm, để giảm ảnh hưởng của độ ẩm môi trường •  Bề mặt cánh tủ Acrylic cao cấp (ví dụ là thương hiệu An Cường) được sử dụng phổ biến để đảm bảo độ bóng, độ bền và tính thẩm mỹ', 4560000, 5400000, 'Tu-ao-Acrylic-768x511.jpg', 'Thùng MFC', 0, '2025-09-18 07:54:41', 1, 0),
(27, 'MA22', 'Tủ áo hiện đại siêu mới', '•  Cửa tủ 2 cánh rộng, có thể có phần gương nằm ở 1 trong 2 cánh — hoặc gương có thể toàn bộ một cánh, tùy mẫu (thông tin chi tiết mẫu cụ thể). •  Bên trong có thanh treo quần áo + ngăn kệ: phục vụ việc treo đồ, gấp đồ, để phụ kiện. Vừa đủ linh hoạt cho nhu cầu hàng ngày.  •  Kiểu dáng gần như hiện đại – tối giản, phù hợp phong cách nội thất phòng ngủ đơn giản, thoáng, thiên về sử dụng công năng nhiều hơn là họa tiết cầu kỳ.', 5670000, 6700000, 'tu-ao-hien-dai-500.jpg', 'MFC', 0, '2025-09-18 07:55:32', 1, 0),
(28, 'MA23', 'Tủ 3 buồng', '•  Cửa tủ 2 cánh rộng, có thể có phần gương nằm ở 1 trong 2 cánh — hoặc gương có thể toàn bộ một cánh, tùy mẫu (thông tin chi tiết mẫu cụ thể). •  Bên trong có thanh treo quần áo + ngăn kệ: phục vụ việc treo đồ, gấp đồ, để phụ kiện. Vừa đủ linh hoạt cho nhu cầu hàng ngày.  •  Kiểu dáng gần như hiện đại – tối giản, phù hợp phong cách nội thất phòng ngủ đơn giản, thoáng, thiên về sử dụng công năng nhiều hơn là họa tiết cầu kỳ.', 4509000, 5670000, '3_91000_1-768x513.jpg', 'MFC', 0, '2025-09-18 07:56:19', 1, 0),
(29, 'MA24', 'Tủ áo Maxine', 'Tủ áo Maxine chứa đựng đầy đủ công năng tối ưu cho việc cất trữ quần áo bằng việc bố trí sắp xếp hợp lý các ngăn tủ. Những chi tiết về phụ kiện cao cấp giúp cho việc thao tác nhẹ nhàng. Bề ngoài, tủ được thiết kế duyên dáng và thu hút với sắc nâu trầm và kim loại đồng. Maxine – Nét nâu trầm sang trọng Maxine, mang thiết kế vượt thời gian, gửi gắm và gói gọn lại những nét đẹp của thiên nhiên và con người nhưng vẫn đầy tính ứng dụng cao trong suốt hành trình phiêu lưu của nhà thiết kế người Pháp Dominique Moal. Bộ sưu tập nổi bật với màu sắc nâu trầm sang trọng, là sự kết hợp giữa gỗ, da bò và kim loại vàng bóng. Đặc biệt trên mỗi sản phẩm, những nét bo viên, chi tiết kết nối được sử dụng kéo léo tạo ra điểm nhất rất riêng cho bộ sưu tập. Maxine thể hiện nét trầm tư, thư giãn để tận hưởng không gian sống trong nhịp sống hiện đại. Sản phẩm thuộc BST Maxine được sản xuất tại Việt Nam.', 4560000, 5300000, '3-99496-1-768x511.jpg', 'Gỗ Okumi', 0, '2025-09-18 07:56:47', 1, 0),
(41, 'MA25', 'Giường Ngủ Có Hộc Và Ổ Điện MOHO VIENNA - MÀU TRẮNG', 'Tối ưu hóa không gian lưu trữ Tích hợp 2 ngăn kéo lớn bên hông giường, cung cấp không gian lưu trữ rộng rãi cho quần áo, chăn màn và các vật dụng cá nhân, giúp phòng ngủ luôn gọn gàng và ngăn nắp. Với màu tự nhiên, phiên bản thường; 2 ngăn kéo cố định gắn bên phải của giường. Với màu trắng - phiên bản nâng cấp; 2 ngăn kéo có thể lắp ghép linh hoạt bên trái hoặc bên phải theo mong muốn của khách. Kệ đầu giường đa năng: Thiết kế kệ đầu giường tiện lợi để trang trí, đặt sách, điện thoại hoặc các vật dụng cá nhân, loại bỏ nhu cầu mua thêm tủ đầu giường. Thiết kế thông minh và tiện dụng - Trang bị ổ điện với cổng sạc nhanh USB Type-C và cổng Lightning, cho phép sạc các thiết bị điện tử ngay tại giường một cách dễ dàng và thuận tiện; đảm bảo độ bền và chất lượng vượt trội. - Dây điện Cadivi 2 lõi x 0.75: Sử dụng dây điện chất lượng cao từ thương hiệu Cadivi, đảm bảo an toàn và hiệu suất truyền tải điện An toàn và bền bỉ - Thiết kế bo góc tròn: Các góc giường được bo tròn tinh tế, đảm bảo an toàn cho người sử dụng, đặc biệt là trẻ nhỏ. - Chân giường chắc chắn: Được trang bị đinh chân nhựa chống trượt, giúp giường ổn định trên mọi bề mặt sàn và giảm thiểu tiếng ồn khi sử dụng. Màu sắc đa dạng Sản phẩm có hai tùy chọn màu sắc: màu gỗ tự nhiên và màu trắng, dễ dàng kết hợp với nhiều phong cách nội thất khác nhau.', 9900000, 11900000, 'pro_trang_noi_that_moho_giuong_co_hoc_vienna_1m6_1_ede5117be7cd4ad3a745fedd914a67bc_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 07:31:42', 1, 0),
(42, 'MA26', 'Ghế Sofa 1m6 MOHO NARVIK Ver.2', '•  Kiểu dáng hiện đại, phong cách Bắc Âu — tinh giản gọn gàng, phù hợp với không gian sống hiện đại, không quá rườm rà.  •  Kích thước 1m6 (160 cm) — vừa vặn với phòng khách nhỏ, căn hộ studio, hoặc làm ghế phụ, ghế đơn kết hợp bàn nhỏ  •  Tay vịn mỏng &#38; tựa lưng cao — giúp tiết kiệm diện tích bên cạnh vẫn giữ cảm giác thoải mái khi ngồi lâu, tựa lưng + cổ được hỗ trợ tốt.  •  Vải polyester cao cấp: chống nhăn, chống thấm/kháng nước nhẹ, dễ lau vệ sinh khi có bẩn.  •  Chân gỗ tự nhiên (cao su) nâng ghế lên, tạo khoảng gầm — giúp vệ sinh dễ hơn và nhìn ghế thoáng, nhẹ nhàng hơn. ', 8850000, 9900000, 'pro_mau_tu_nhien_ghe_sofa_1m6_narvik_noi_that_moho_1f112f84fee3476a816fb1a98a35c05c_master.jpg', 'Gỗ cao su tự nhiên', 0, '2025-09-18 08:12:15', 2, 0),
(43, 'MA27', ' Ghế Sofa Băng 2m2 VERONA', 'Phù hợp gia đình &#38; phòng khách có diện tích trung bình đến lớn — chiều dài 2m2 giúp ngồi nhiều người vẫn thoải mái hoặc nằm thư giãn tốt.  Thiết kế vải nhung gân mang lại vẻ sang trọng, mềm mại, tạo điểm nhấn cho không gian nội thất phòng khách.  Đệm dày &#38; độ đàn hồi tốt giúp người sử dụng không cảm thấy mỏi lưng khi ngồi lâu.  Tay và tựa lưng cao giúp thoải mái hơn khi ngồi hoặc tựa; phù hợp cả đọc sách, xem phim.', 19800000, 21990000, 'pro_cam_ghe_sofa_bang_verona_noi_that_moho_3_2f9712b53b934cdea6cb4633013932d2_master.png', 'Khung gỗ thông', 0, '2025-09-18 14:30:59', 2, 0),
(44, 'MA28', 'Ghế Sofa Băng 2m5 FLORENCE', 'ích thước phủ bì: Dài 250 cm × Sâu 80 cm × Cao 80 cm  Màu sắc: Kem  Giá hiện tại: khoảng 17.290.000 ₫ (giảm từ ~21.290.000 ₫)  Khung ghế: Gỗ tự nhiên  Chân ghế: Gỗ cao su  Đệm + vải bọc: Vải &#38; đệm mút cao cấp, có khả năng trượt nước nhẹ (chống thấm nhẹ, dễ lau chùi khi bị đổ nước nhỏ)  Chính sách: Miễn phí giao hàng &#38; lắp đặt tại TP.HCM, Hà Nội, Ecopark, Biên Hòa và một số quận Bình Dương  Bảo hành: 5 năm, bảo trì trọn đời', 18600000, 21290000, 'pro_kem_ghe_sofa_2m5_florence__main_6490a318835548299eb49eb5f4684f88_master.png', 'Khung gỗ tự nhiên', 0, '2025-09-18 14:34:34', 2, 0),
(45, 'MA29', 'Ghế Sofa AURORA - MOHO Signature', '•  Phong cách: cao cấp, sang trọng, mang hơi hướng Ý hiện đại – đường cong mềm mại, tay và tựa bo cong, tạo khối liền mạch.  •  Chân ghế: inox cao cấp, thiết kế thanh mảnh, hoàn thiện sáng bóng.  •  Vỏ bọc: sử dụng vải xám nhạt trung tính; đường viền da nhân tạo đen tương phản tạo điểm nhấn.  •  Khung ghế: gỗ tự nhiên, chắc chắn, chống cong vênh theo thời gian.  •  Bộ gối đi kèm: 8 chiếc, với 3 sắc độ màu trắng, xám và đen — vừa tăng tính thẩm mỹ vừa tiện sử dụng', 34560000, 40267000, 'pro_xam_sofa_bang_aurora_noi_that_moho_main_00a56dddff814830b9a837021adfc56c_master.jpg', 'Vải: 100% polyester', 0, '2025-09-18 14:43:05', 2, 0),
(46, 'MA30', 'Ghế Sofa MOHO SOLUNA - MOHO Signature', '•  Rất thoải mái cho ngồi lâu nhờ thiết kế tựa lưng &#38; tựa tay dày, đệm mềm. •  Mang phong cách thư giãn, tinh giản, đậm nét thẩm mỹ Ý; màu sắc (xanh olive) tạo điểm nhấn độc đáo, khác với tone trung tính thông thường  •  Khung &#38; chân gỗ tốt, thiết kế cao cấp → độ bền &#38; tính thẩm mỹ cao. •  Kích thước vừa phải, phù hợp phòng khách có diện tích trung bình-lớn.', 27860000, 35750000, 'pro_olive_sofa_bang_sofa_y_soluna_noi_that_moho_ba431ffcd00f4381aa2166890894d54d_master.jpg', ' Vải: 100% polyester', 0, '2025-09-18 16:50:12', 2, 0),
(47, 'MA31', 'Ghế Sofa Da MOHO RIGA 2m', '•  Thiết kế hiện đại, phong cách Hàn Quốc nhẹ nhàng, thanh lịch — giúp không gian phòng khách sáng và thư giãn hơn  •  Đệm sâu và cấu trúc hỗ trợ tốt, phù hợp cho nhiều tư thế ngồi: ngồi thẳng, ngồi xếp bằng, nằm xem TV… •  Vật liệu và kết cấu đảm bảo độ bền: chân gỗ cao su, đệm + lò xo + dây đai đàn hồi — hạn chế xuống cấp nhanh  •  Vải bọc chống bẩn giúp dễ vệ sinh — phù hợp gia đình, có trẻ nhỏ hoặc thú cưng.', 11230000, 12990000, 'pro_camel_sofa_riga_3_57acfab5f9e14e16af5d749ee01a0046_master.jpg', 'Gỗ cao su tự nhiên', 0, '2025-09-18 16:58:06', 2, 0),
(48, 'MA32', 'Ghế Sofa Băng Gỗ Tự Nhiên MOHO VLINE 601', '•  Thiết kế gỗ tự nhiên + đường nét tối giản mang vẻ đẹp ấm áp, gần gũi và thích hợp với nhiều phong cách nội  •  Kích thước vừa phải — 180 cm dài — phù hợp phòng khách trung bình hoặc khi muốn đặt ghế đơn kèm bàn trà nhỏ mà không chiếm quá nhiều diện tích  •  Vải bọc dễ vệ sinh, vật liệu gỗ cao su chịu lực &#38; độ bền khá cao nếu được bảo quản tốt  •  Tấm phản Plywood chuẩn CARB-P2 → giảm được nguy cơ khí độc hại, tốt cho sức khỏe người dùng', 9800000, 11490000, 'pro_nau_pk_vline_be__2_200ff959901e4117bd03bf0bb8fe923a_master.jpg', 'Gỗ cao su tự nhiên', 0, '2025-09-18 16:59:42', 2, 0),
(49, 'MA33', 'Ghế Sofa MOHO HALDEN 801', '•  Thiết kế phong cách Bắc Âu: đường nét mềm mại, tay ghế bo cong tạo cảm giác thân thiện &#38; sang trọng  •  Chất liệu tốt, tính năng chống thấm, chống dầu giúp bảo vệ màu và sử dụng thực tế dễ hơn trong gia đình có trẻ nhỏ hoặc dễ bẩn  •  Kích thước vừa phải — không quá lớn, phù hợp phòng khách trung bình, không gian đặt sofa không quá rộng  •  Tính linh hoạt trong vệ sinh &#38; bảo dưỡng nhờ vỏ đệm tháo rời, khóa dán cố định đệm tốt hơn', 7100000, 10790000, 'pro_nau_noi_that_moho_ghesofa_a_5eece5b4358f4d4eb80fe060663e4869_master.jpg', 'Gỗ cao su tự nhiên', 0, '2025-09-18 17:01:35', 2, 0),
(50, 'MA34', 'Tủ Tivi Dalumd (Màu Nâu Hạnh Nhân, 160)', 'Màu nâu hạnh nhân mang tính ấm áp — dễ phối hợp với nội thất &#38; màu sàn nhà.  Kích thước 160 cm phù hợp nhiều không gian phòng khách vừa &#38; nhỏ — đủ rộng để đặt TV + các thiết bị phụ trợ mà không chiếm quá lối đi.  Giá ưu đãi giảm khá mạnh so với giá gốc.  Thiết kế gọn gàng &#38; bề mặt dễ vệ sinh — thuận tiện khi sử dụng lâu dài.', 4870000, 6290000, 'pro_nau_noi_that_moho_tutv_dalumd_4_79e6b0b0fb324f918658085686bcaa75_master.jpg', 'Gỗ cao su tự nhiên', 0, '2025-09-18 17:03:35', 2, 0),
(51, 'MA35', 'Kệ Tivi Style Hàn KLINE', '•  Kiểu dáng hiện đại &#38; tối giản — phù hợp với phong cách Hàn Quốc, tạo không gian phòng khách thoáng &#38; thanh lịch.  •  Nhiều ngăn chứa: có 1 ngăn tủ + 4 ngăn kéo rộng thích hợp để remote, sách, phụ kiện; giúp giảm lộn xộn.  •  Ray trượt giảm chấn giúp tránh tiếng ồn khi đóng mở — cảm giác sử dụng tốt hơn rất nhiều trong sinh hoạt hàng ngày.  •  Chất liệu đảm bảo: gỗ công nghiệp chuẩn quốc tế + gỗ cao su cho phần chịu lực → độ bền tốt, ít cong vênh nếu sử dụng &#38; bảo quản đúng cách. ', 4700000, 5990000, 'pro_mau_trang_ke_tivi_kline_7a024cfb938644f88114008d2e90565c_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:05:49', 2, 0),
(52, 'MA36', 'Bàn Ăn 4 - 6 Ghế Gỗ Tự Nhiên SERENA', 'Gỗ tự nhiên (có thể gỗ cao su, gỗ sồi, gỗ tần bì, vân gỗ đẹp) — giúp độ bền tốt, cảm giác ấm áp, tự nhiên.  Kích cỡ bàn đủ rộng cho 4-6 người: nếu 4 ghế thì bàn có thể dài ~120-140 cm, nếu 6 ghế thì khoảng 160-180 cm.  Mặt bàn dày, chân bàn chắc chắn, có thể bo tròn các góc để an toàn nếu nhà có trẻ nhỏ.  Ghế ăn kiểu đơn hoặc có thể kèm ghế băng, lưng tựa thoải mái, đệm nếu có thể giúp ngồi lâu không mỏi.  Hoàn thiện bề mặt bằng sơn chống thấm hoặc phủ lớp bảo vệ để dễ lau chùi.', 15600000, 19800000, 'pro_nau_bo_ban_an_4_cho_6_cho_serena_noi_that_moho_1_d378e99967c04773b8b2c039fe8f87e2_master.png', 'Gỗ sồi chuẩn CARB - P2', 0, '2025-09-18 17:12:46', 3, 0),
(53, 'MA37', ' Ghế Ăn Gỗ Cao Su Tự Nhiên SERENA', 'Thiết kế đơn giản, mộc mạc, dễ phối với bàn ăn &#38; nội thất gỗ khác trong phòng ăn.  Gỗ cao su tự nhiên giúp sản phẩm có thể sử dụng lâu dài, chịu tải tốt với sinh hoạt hàng ngày.  Giá hợp lý trong tầm ghế gỗ — thích hợp nếu muốn đầu tư ghế chất lượng mà không quá đắt.  Màu tự nhiên + màu nâu là những màu trung tính, dễ kết hợp với nhiều phong cách &#38; màu tường/sàn.', 2100000, 2990000, 'pro_nau_bo_ban_an_4_cho_6_cho_serena_noi_that_moho_4_402d257671794aee9b8f0157e2408a36_master.png', 'Gỗ cao su tự nhiên', 0, '2025-09-18 17:14:05', 3, 0),
(54, 'MA38', 'Ghế Bàn Ăn Gỗ Tự Nhiên PLANK', 'Là ghế ăn bằng gỗ tự nhiên (có thể là gỗ cao su, gỗ sồi, gỗ tràm hoặc loại gỗ khác có độ cứng tốt)  Kiểu thiết kế “Plank” thường ám chỉ phần mặt ngồi + lưng ghế làm từ thanh gỗ tấm dài, phẳng, hơi bản lớn, hoặc nhiều thanh ghép chắc chắn  Tựa lưng có thể hơi nghiêng để ngồi thoải mái hơn  Chân ghế chắc, kiểu chân thẳng hoặc hơi bo để giữ sự đơn giản &#38; nét mộc mạc tự nhiên  Có thể không có đệm, hoặc nếu có thì đệm mỏng để giữ cảm giác tự nhiên của gỗ', 1990000, 2990000, 'pro_mau_tu_nhien_ghe_go_plank_noi_that_moho_d8a3adeeb49548589f02a236a7931a30_master.jpg', 'Gỗ', 0, '2025-09-18 17:15:44', 3, 0),
(55, 'MA38', 'Bàn Ăn Gỗ Tự Nhiên PLANK | Veneer Gỗ Sồi', 'Chất liệu mặt bàn: Veneer gỗ sồi tự nhiên — mặt bàn sồi trắng/nâu sáng với vân gỗ mềm mại, có lớp phủ bảo vệ (ví dụ sơnPU hoặc sơn chống thấm) để giữ bề mặt mịn, bền và không bị ố.  Thân / khung / chân bàn từ gỗ tự nhiên (có thể là gỗ cao su, gỗ sồi hoặc loại gỗ có độ cứng tương thích) để đảm bảo chịu lực và độ ổn định.  Thiết kế kiểu “Plank” thường có những tấm gỗ bản lớn liền hoặc ghép mộng, bo cạnh nhẹ nhàng hoặc giữ nguyên cạnh để tạo nét mộc mạc, thô mộc hơn nếu theo phong cách rustic/hay industrial.  Kích thước phổ biến: dài khoảng 140-180 cm cho bàn 4-6 ghế, rộng ~ 75-90 cm, cao ~ 65-75 cm — tùy chuẩn sử dụng và phong cách bàn – nếu thấp kiểu kiểu ngồi thấp hơn hoặc cao hơn mức bàn ăn tiêu chuẩn.  Hoàn thiện nét chi tiết: bo góc bàn để an toàn; chân bàn có thiết kế chắc chắn (chân thẳng, chân chữ X, chân vuông…), phủ lớp bảo vệ chân tránh trầy sàn.', 6990000, 7990000, 'pro_nau_bo_ban_ghe_4_cho_6_cho_plank_noi_that_moho_5_main_7a37814db7ee440480447827ef5b5996_master.jpg', 'Gỗ sồi', 0, '2025-09-18 17:17:45', 3, 0),
(56, 'MA39', 'Ghế Ăn Gỗ Cao Su Tự Nhiên MOHO MILAN 601 Màu Gỗ', 'Thiết kế nhỏ gọn, phù hợp phòng ăn có diện tích vừa và nhỏ hoặc dùng làm ghế ăn thông thường.  Vật liệu tự nhiên &#38; chất lượng hoàn thiện tốt → độ bền cao nếu sử dụng &#38; bảo quản đúng cách.  Vải polyester giúp dễ lau chùi khi bị bẩn.  Có nhiều lựa chọn màu (gỗ tự nhiên &#38; nâu) → dễ phối với bàn ăn và nội thất khác.', 1700000, 1990000, 'pro_mau_tu_nhien_noi_that_moho_ghe___1__c85c9935c3814b139a5835e38495b1c8_master.jpg', 'Gỗ cao su tự nhiên', 0, '2025-09-18 17:19:17', 3, 0),
(57, 'MA40', 'Bàn Ăn SCANIA (Màu Tự Nhiên, Mặt Vân Đá, 140)', 'Mặt bàn vân đá giúp bàn ăn sang hơn, dễ lau chùi, không dễ thấm nước nếu xử lý tốt  Kích thước ~ 140 cm là vừa đủ cho 4-6 người ăn bình thường mà không quá lớn chiếm diện tích cho phòng ăn nhỏ-vừa  Màu gỗ + vân đá tạo sự hài hòa giữa ấm và lạnh — phù hợp nhiều phong cách nội thất (hiện đại, Bắc Âu, tối giản)', 2980000, 5690000, 'pro_mau_tu_nhien_noi_that_moho_ban_an___1__397cdb0b50f7424dbab78ab97ed15a02_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:24:15', 3, 0),
(58, 'MA41', 'Bàn Ăn Gỗ Tự Nhiên SCANIA (Màu Nâu, Mặt Vân Đá, 140)', 'Mặt vân đá sang trọng — rất phù hợp nếu bạn muốn không gian bàn ăn có điểm nhấn hoặc hơi hướng cao cấp.  Màu nâu + gỗ tự nhiên dễ phối nội thất — với sàn, tường, ghế, phụ kiện nhà bếp.  Kích thước 140 cm là hợp lý cho gia đình 4-6 người, đủ rộng mà không chiếm quá lối đi nếu phòng ăn vừa.  Chân bàn dày giúp cảm giác chắc chắn khi sử dụng — đặt dĩa, xoong nồi nặng lên bàn không rung lắc quá nhiều.', 4860000, 5690000, 'pro_mau_nau_noi_that_moho_ban___1__eb04d869c61a4528b227980f68526b50_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:23:21', 3, 0),
(59, 'MA42', 'Bàn Ăn Gỗ Tự Nhiên SCANIA (Màu Nâu - Mặt Vân Đá)', 'Có mặt đá tăng tính sang trọng, điểm nhấn cho phòng ăn.  Gỗ tự nhiên &#38; màu nâu tạo cảm giác ấm áp, dễ phối nội thất.  Số ghế linh hoạt — nếu chọn 4 ghế sẽ vừa phòng nhỏ; 6 ghế khi muốn nhiều người dùng.  Thiết kế bộ giúp không gian phòng ăn nhìn đồng bộ, đẹp mắt hơn.', 8600000, 9900000, 'pro_bo_ban_an_6_ghe_noi_that_moho_combo___1__da6e9ffaa4654bf4a48d83cd5c7cfbd2_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:26:52', 3, 0),
(60, 'MA43', 'Bàn Phòng Ăn Narvik 201 Màu Nâu', 'Chi phí hợp lý so với mua lẻ từng món — bộ combo giảm nhiều so với tổng giá lẻ.  Diện tích bàn + ghế phù hợp cho 4 người ăn; bàn 1.2 m không quá to nên không chiếm lối đi trong phòng ăn nhỏ.  Chất liệu gỗ cao su + MFC/MDF chuẩn CARB-P2 giúp độ bền &#38; độ ổn định tương đối tốt; phần vân gỗ nâu tạo cảm giác ấm áp.  Miễn phí lắp đặt &#38; giao hàng sẽ tiết kiệm đáng kể chi phí phát sinh.', 6800000, 9900000, 'pro_mau_nau_noi_that_moho_combo___1__a12728609b9a40379720aef46b6e6b94_master.jpg', 'Gỗ cao su tự nhiên', 0, '2025-09-18 17:29:46', 3, 0),
(61, 'MA44', 'Bàn Làm Việc Gỗ MOHO VLINE 601 Màu Nâu', '•  Kích thước vừa phải, gọn gàng — phù hợp phòng làm việc nhỏ, góc học tập hoặc phòng ngủ. •  Thiết kế chân chắc chắn + thanh ngang hỗ trợ → bàn ổn định, ít rung khi đặt máy tính hoặc các thiết bị nhẹ.  •  Màu nâu + veneer gỗ tràm tạo vẻ ấm áp, dễ phối cùng các nội thất khác. •  Mặt bàn phong cách veneer + lớp phủ đảm bảo độ bóng, dễ lau chùi. •  Bảo hành 5 năm; miễn phí giao hàng &#38; lắp đặt ở nhiều khu vực lớn ', 2180000, 2990000, 'pro_nau_noi_that_moho_ban_lam_viec_vline_601_a_e60e2f8b72854311ae12424eed3cb88a_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:32:19', 4, 0),
(62, 'MA45', 'Bàn Làm Việc Gỗ MOHO VLINE 601 Màu Tự Nhiên', '•  Kích thước nhỏ gọn, phù hợp đặt góc làm việc, phòng ngủ, văn phòng nhỏ — không chiếm quá nhiều diện tích. •  Màu gỗ tự nhiên + veneer sồi mang lại cảm giác ấm áp, tự nhiên, dễ phối với tường &#38; nội thất khác. •  Chân gỗ cao su khá chắc chắn, thiết kế thanh ngang giúp tăng sự ổn định.  •  Tiêu chuẩn CARB-P2 cho mặt bàn → giảm được mùi, độc tố, an toàn hơn với sức khỏe.  •  Bảo hành 5 năm &#38; dịch vụ bảo trì lâu dài giúp yên tâm sử dụng. ', 2180000, 2990000, 'pro_mau_tu_nhien_noi_that_moho_ban_lam_viec_vline_1_92060b73c393469181d9d24218c38851_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:33:55', 4, 0),
(63, 'MA46', 'Bàn Làm Việc Gỗ MOHO WORKS 701', 'Diện tích mặt bàn đủ rộng để đặt laptop + màn hình phụ hoặc sách vở + các vật dụng làm việc phụ trợ, mà vẫn giữ được không gian thoải mái.  Mặt bàn dày + chân bàn chắc → độ chịu lực tốt, ít bị cong khi để nhiều thiết bị lên.  Chống trầy xước tốt, nếu sử dụng bình thường sẽ giữ được bề mặt đẹp lâu.  Thiết kế hiện đại &#38; tối giản → dễ phối với nhiều phong cách nội thất.  Tính năng điều chỉnh chân giúp bàn cân bằng nếu đặt trên sàn không đều.', 2450000, 2990000, 'pro_den_noi_that_moho_ban_lam_viec_work_701_1m2_4_2e73c6f0e04844dd95e8bf2a7e2d933f_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:35:15', 4, 0),
(64, 'MA46', 'Bàn Làm Việc Gỗ MOHO FYN 601 Màu Nâu', '•  Thiết kế đơn giản, tinh tế, phong cách hiện đại, hợp với nhiều không gian làm việc hoặc học tập  •  Diện tích mặt bàn rộng rãi (120 × 60) đủ để đặt laptop, màn hình phụ, các vật dụng văn phòng mà vẫn có khoảng không làm việc thoải mái. •  Hộc kéo giúp để vật nhỏ gọn như phụ kiện máy tính, sổ sách, bút,… tiện lấy mà không cần di chuyển nhiều. •  Vật liệu veneer sồi + chân gỗ sồi tạo cảm giác chắc chắn, sang trọng. •  CARB-P2 là tiêu chuẩn an toàn về khí phát thải → tốt cho sức khỏe người sử dụng  •  Bảo hành dài – 5 năm + miễn phí giao &#38; lắp đặt tại nhiều khu vực lớn.', 2870000, 3260000, 'pro_nau_noi_that_moho_ban_lam_viec_go_fyn_nau2_f607075e46254fc190dfabcd5108f91c_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:36:51', 4, 0),
(65, 'MA47', ' Kệ Để Sách 3 Tầng MOHO WORKS 703', 'Gọn, cao vừa phải – phù hợp với phòng làm việc hoặc phòng sách nhỏ, không chiếm diện tích quá lớn.  Vật liệu PB + khung sắt làm tăng độ bền, chịu tải ổn định nếu sử dụng đúng công năng.  Thiết kế đơn giản &#38; màu sắc trung tính dễ phối nội thất.  Giá tốt so với những kệ có kích thước tương đương có thêm phụ kiện/phần trang trí.', 2460000, 2990000, 'pro_trang_noi_that_moho_ke_de_sach_9_34a0af0251ae4894ba717dbdfdfe88a5_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:39:02', 4, 0),
(66, 'MA48', 'Kệ Để Sách Tủ Khóa MOHO WORKS 702', 'Thiết kế đa năng: vừa làm kệ sách, vừa tủ hồ sơ với khóa → bảo mật &#38; gọn gàng hơn khi lưu trữ tài liệu quan trọng.  Chiều cao lớn giúp tận dụng không gian thẳng đứng, nhiều tầng chứa → lưu được nhiều sách / đồ trang trí.  Chất liệu và khung kim loại hỗ trợ độ bền &#38; giúp kệ đứng vững hơn.  Khoảng giữa các tầng cao thích hợp cho sách lớn, album, hoặc những đồ trang trí cao.', 2980000, 3860000, 'pro_trang_noi_that_moho_ke_de_sach_tu_khoa_11_27da17a32ee34d3682ad2cbce1c9606a_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:40:14', 4, 0),
(67, 'MA49', 'Bàn Máy Tính Gỗ MOHO WORKS 702', 'Mặt bàn rộng &#38; sâu vừa phải → đủ để đặt laptop + màn hình hay nhiều phụ kiện làm việc mà vẫn có chỗ rộng để kê tay/đồ dùng hỗ trợ.  Giá đỡ ổ điện tích hợp giúp góc làm việc gọn gàng hơn, đỡ rối dây.  Chân &#38; khung kim loại + mặt bàn dày tạo cảm giác chắc chắn, ít rung lắc khi sử dụng.  Tùy chọn màu (trắng/đen) dễ phối nội thất, phù hợp nhiều phong cách từ hiện đại đến tối giản', 2870000, 3590000, 'pro_trang_noi_that_moho_ban_may_tinh_1_07121e2728134e579bdfd34fd6e3c184_master.jpg', 'Gỗ công nghiệp phủ Melamine CARB-P2', 0, '2025-09-18 17:41:45', 4, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products_bill`
--

CREATE TABLE `products_bill` (
  `id` int(10) NOT NULL,
  `amount_buy` int(10) NOT NULL,
  `id_product_detail` int(10) NOT NULL,
  `id_bill` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products_bill`
--

INSERT INTO `products_bill` (`id`, `amount_buy`, `id_product_detail`, `id_bill`) VALUES
(46, 1, 1, 36),
(47, 1, 3, 36),
(48, 1, 4, 37),
(49, 1, 8, 38),
(50, 2, 16, 39),
(51, 2, 12, 40),
(52, 2, 4, 40),
(53, 1, 14, 40),
(54, 2, 1, 40),
(55, 1, 35, 40),
(56, 1, 20, 40),
(57, 1, 11, 40),
(58, 1, 18, 40),
(59, 1, 13, 40),
(60, 1, 16, 40),
(61, 2, 17, 40),
(62, 1, 15, 40),
(63, 2, 11, 41),
(64, 1, 16, 42),
(65, 3, 38, 43),
(66, 2, 36, 44),
(67, 1, 19, 44),
(68, 1, 1, 45),
(69, 1, 1, 46);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products_cart`
--

CREATE TABLE `products_cart` (
  `id` int(10) NOT NULL,
  `amount_buy` int(10) NOT NULL,
  `id_product_detail` int(10) NOT NULL,
  `id_cart` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products_detail`
--

CREATE TABLE `products_detail` (
  `id` int(10) NOT NULL,
  `amount` float NOT NULL,
  `code_color` varchar(200) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `id_product` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products_detail`
--

INSERT INTO `products_detail` (`id`, `amount`, `code_color`, `color`, `image`, `size`, `id_product`) VALUES
(1, 100, '#d2af84', 'Màu kem', 'pro_mau_tu_nhien_noi_that_moho_giuong_co_hoc_vienna_88ba7274638540c0afc3a4d40860ced1_master.jpg', '1m8x2m', 1),
(2, 100, '#d2af84', 'Màu kem', 'pro_mau_tu_nhien_noi_that_moho_giuong_co_hoc_vienna_88ba7274638540c0afc3a4d40860ced1_master.jpg', '1m8x2m', 1),
(3, 100, '#d17823', 'Nâu', 'pro_nau_combo_phong_ngu_hobro_tu_thanh_treo_2bcb6532fc864a3dac94e48d84dbac84_master.jpg', '1m8x2m', 2),
(4, 100, '#d5810b', 'Nâu', 'pro_combo_mix_giuong_ngu_tu_cua_lua_2_canh_moho_30cd112d90c6413bbfc493ef2ad43772_master.jpg', '1m6x1m8', 3),
(5, 100, '#d5810b', 'Nâu', 'pro_combo_mix_giuong_ngu_tu_cua_lua_2_canh_moho_30cd112d90c6413bbfc493ef2ad43772_master.jpg', '1m6x1m8', 3),
(6, 100, '#d5810b', 'Nâu', 'pro_combo_mix_giuong_ngu_tu_cua_lua_2_canh_moho_30cd112d90c6413bbfc493ef2ad43772_master.jpg', '1m6x1m8', 3),
(8, 100, '#955104', 'Nâu', 'combo-phong-ngu-tu-cua-lua-giuong-co-hoc_1_ba2ae3204a7c4495ac9eb9231ec0e0db_master.jpg', '1m6x1m8', 4),
(10, 100, '#7a3a05', 'Nâu', 'pro_mau_tu_nhien_tu_noc_vienna_5_6846910609194036ac4ce4f9a93d35a9.png', 'D200 x R60 x C250 (cm)', 11),
(11, 100, '#884507', 'Nâu', 'pro_mau_tu_nhien_tu_noc_vienna_1_7951d3c5175a4c4aaa31406346da3d8c_master.png', ' D50 x R60 x C40 (cm)', 12),
(12, 100, '#9d4f06', 'Nâu', 'pro_nau_noi_that_moho_giuong_ngu_moho_koster_mau_nau_1m6_10_51e6e6e31109428fb451e6599b8dd21d_master.jpg', ' Dài 214,3 X Rộng 167 X Cao 80 (cm) - phù hợp với nệm 1m6.', 13),
(13, 100, '#915603', 'Nâu', 'pro_mau_tu_nhien_tu_canh_lua_moho_vienna_b976796a233940f8a8f38a608a1e813b_master.png', ' Dài 120cm x Rộng 60cm x Cao 200cm ', 14),
(14, 100, '#865809', 'Nâu', 'pro_combo_2_mon_noi_that_moho_combo___3__d4da43d867c44b71974f28c65cdb7097_master.png', '1m6x1m8', 15),
(15, 100, '#723e03', 'Nâu', 'pro_combo_2_mon_noi_that_moho_combo___3__d4da43d867c44b71974f28c65cdb7097_master.png', '1m6x1m8', 16),
(16, 9, '#d3d61f', 'Màu vàng', 'pro_combo_2_mon_noi_that_moho_combo___3__d4da43d867c44b71974f28c65cdb7097_master.png', 'D2000 - R880 - C700mm', 17),
(17, 100, '#c6752a', 'Nâu', 'pro_1m2_tu_quan_ao_grenaa_noi_that_moho_main_2921c7f6218a4972b42ef058e10c9c3a_master.png', 'Dài 120 X Rộng 60 X Cao 200 (cm)', 18),
(18, 100, '#e2d9c5', 'Trắng', 'pro_mau_tu_nhien_noi_that_moho_set_tu_ubeda_3_canh_nhieu_kich_thuoc_4_5d411dace2224f3093e5491377493c66_master.png', ' Dài 120 X Rộng 60 X Cao 200 (cm)  ---', 19),
(19, 24, '#fbf9f9', 'Màu trắng', '103444-giuong-softly-1m8-vai-s8w-light-768x511.jpg', '323x234', 20),
(20, 100, '#864304', 'Nâu', 'sofa-miami-2-cho-boc-vai-vang-2-768x511.jpg', '1m8x2m', 10),
(23, 100, '#864304', 'Nâu', 'sofa-miami-2-cho-boc-vai-vang-2-768x511.jpg', '1m8x2m', 10),
(26, 100, '#9d4f06', 'Nâu', 'pro_nau_noi_that_moho_giuong_ngu_moho_koster_mau_nau_1m6_10_51e6e6e31109428fb451e6599b8dd21d_master.jpg', ' Dài 214,3 X Rộng 167 X Cao 80 (cm) - phù hợp với nệm 1m6.', 13),
(29, 100, '#865809', 'Nâu', 'pro_combo_2_mon_noi_that_moho_combo___3__d4da43d867c44b71974f28c65cdb7097_master.png', '1m6x1m8', 15),
(30, 100, '#723e03', 'Nâu', 'pro_combo_2_mon_noi_that_moho_combo___3__d4da43d867c44b71974f28c65cdb7097_master.png', '1m6x1m8', 16),
(31, 100, '#723e03', 'Nâu', 'pro_combo_2_mon_noi_that_moho_combo___3__d4da43d867c44b71974f28c65cdb7097_master.png', '1m6x1m8', 16),
(33, 100, '#dad2d2', 'Trắng ', 'pro_mau_tu_nhien_noi_that_moho_combo_giuong_moho_rander_2m_71d2d7d969204401a05bc83378a5510b_master.jpg', 'Rộng 165 x Dài 213 x Cao 100 (cm)', 21),
(34, 23, '#b7c478', 'Màu be', 'phong-ngu-wynn1-500.jpg', 'D1200- R900- C400 mm', 22),
(35, 34, '#c1b8b8', 'Màu kim loại', 'Giuong-ngu-boc-vai-Softly-G-1m6-S8W-2-768x495.jpg', '400x1010 mm', 23),
(36, 31, '#c2bdbd', 'Màu xám', 'giuong-leman-1m8-111430-1-768x511.jpg', 'D450-R450-C500', 24),
(38, 25, '#816565', 'Màu đỏ nâu', 'Tu-ao-Wabrobe-02-768x511.jpg', '1m8X1m4x0.6m', 25),
(39, 32, '#000000', 'Màu xám cao cấp', 'Tu-ao-Acrylic-768x511.jpg', '1m6x1m3x0.6m', 26),
(40, 16, '#d0cdcd', 'Màu xám', 'tu-ao-hien-dai-5-500.jpg', '1m8X1m4x0.6m', 27),
(41, 23, '#ab9c63', 'Màu gỗ', '3_91000_2-768x513.jpg', '1m8x1m4x0.6m', 28),
(42, 23, '#ab9c63', 'Màu gỗ', '3_91000_2-768x513.jpg', '1m8x1m4x0.6m', 28),
(43, 29, '#8f2d41', 'Màu gỗ', '3-99496-1-768x511.jpg', '1m8X1m4x0.6m', 29),
(45, 100, '#d2af84', 'Màu kem', 'pro_mau_tu_nhien_noi_that_moho_giuong_co_hoc_vienna_88ba7274638540c0afc3a4d40860ced1_master.jpg', '1m8x2m', 1),
(47, 100, '#d2af84', 'Màu kem', 'pro_mau_tu_nhien_noi_that_moho_giuong_co_hoc_vienna_88ba7274638540c0afc3a4d40860ced1_master.jpg', '1m8x2m', 1),
(49, 100, '#d2af84', 'Màu kem', 'pro_mau_tu_nhien_noi_that_moho_giuong_co_hoc_vienna_88ba7274638540c0afc3a4d40860ced1_master.jpg', '1m8x2m', 1),
(50, 100, '#d2af84', 'Màu kem', 'pro_mau_tu_nhien_noi_that_moho_giuong_co_hoc_vienna_88ba7274638540c0afc3a4d40860ced1_master.jpg', '1m8x2m', 1),
(57, 100, '#813c04', 'Nâu', 'pro_trang_noi_that_moho_giuong_co_hoc_vienna_1m6_1_ede5117be7cd4ad3a745fedd914a67bc_master.jpg', 'Rộng 160/180 x Dài 218 x Cao 100 (cm)', 41),
(58, 100, '#c5bfbf', 'Xám', 'pro_mau_tu_nhien_ghe_sofa_1m6_narvik_noi_that_moho_1f112f84fee3476a816fb1a98a35c05c_master.jpg', 'Dài 160 × Rộng 81 × Cao 81 cm', 42),
(59, 111, '#6f3301', 'Nâu', 'pro_cam_ghe_sofa_bang_verona_noi_that_moho_3_2f9712b53b934cdea6cb4633013932d2_master.png', '2m2', 43),
(60, 111, '#f3ecec', 'Trắng ', 'pro_kem_ghe_sofa_2m5_florence__main_6490a318835548299eb49eb5f4684f88_master.png', '2m5', 44),
(61, 111, '#bdbdbd', 'Xám', 'pro_xam_sofa_bang_aurora_noi_that_moho_main_00a56dddff814830b9a837021adfc56c_master.jpg', 'D250x S109x C77 cm', 45),
(62, 111, '#4fc723', 'Xanh', 'pro_olive_sofa_bang_sofa_y_soluna_noi_that_moho_ba431ffcd00f4381aa2166890894d54d_master.jpg', 'D227 x S96 x 83 cm', 46),
(63, 111, '#8e4806', 'Nâu', 'pro_camel_sofa_riga_3_57acfab5f9e14e16af5d749ee01a0046_master.jpg', 'Dài 200cm x Rộng 83cm x Cao 80cm', 47),
(64, 111, '#e6e6e6', 'Trắng ', 'pro_nau_pk_vline_be__2_200ff959901e4117bd03bf0bb8fe923a_master.jpg', 'Dài: 180cm x Rộng 84cm x Cao 69cm', 48),
(65, 111, '#bd7905', 'Nâu', 'pro_nau_noi_that_moho_ghesofa_a_5eece5b4358f4d4eb80fe060663e4869_master.jpg', 'Dài 180cm x Rộng 85cm x Cao 82cm', 49),
(66, 111, '#985901', 'Nâu', 'pro_nau_noi_that_moho_tutv_dalumd_4_79e6b0b0fb324f918658085686bcaa75_master.jpg', 'Dài 160cm x Rộng 40cm x Cao 50cm', 50),
(67, 111, '#e6e5e5', 'Trắng ', 'pro_mau_trang_ke_tivi_kline_7a024cfb938644f88114008d2e90565c_master.jpg', 'Dài 180 cm x Rộng 40cm x Cao 55cm', 51),
(68, 111, '#764704', 'Nâu', 'pro_nau_bo_ban_an_4_cho_6_cho_serena_noi_that_moho_1_d378e99967c04773b8b2c039fe8f87e2_master.png', 'Dài 160 x Rộng 80 x Cao 75 cm', 52),
(69, 111, '#994805', 'Nâu', 'pro_nau_bo_ban_an_4_cho_6_cho_serena_noi_that_moho_4_402d257671794aee9b8f0157e2408a36_master.png', ' 460 x 520 x 820 (mm)', 53),
(70, 111, '#c38313', 'Màu tự nhiên', 'pro_mau_tu_nhien_ghe_go_plank_noi_that_moho_d8a3adeeb49548589f02a236a7931a30_master.jpg', '460 x 520 x 820 (mm)', 54),
(71, 111, '#864c09', 'Nâu', 'pro_nau_bo_ban_ghe_4_cho_6_cho_plank_noi_that_moho_5_main_7a37814db7ee440480447827ef5b5996_master.jpg', ' 160 x 85 x 75 cm', 55),
(72, 111, '#be6704', 'Nâu', 'pro_mau_tu_nhien_noi_that_moho_ghe___1__c85c9935c3814b139a5835e38495b1c8_master.jpg', 'Dài 52cm x Rộng 49cm x Cao 74cm', 56),
(73, 111, '#bc720b', 'Màu tự nhiên', 'pro_mau_tu_nhien_noi_that_moho_ban_an___1__397cdb0b50f7424dbab78ab97ed15a02_master.jpg', 'Chiều dài 140cm x Chiều rộng 70cm x Chiều cao 75cm ', 57),
(74, 111, '#974807', 'Nâu', 'pro_mau_nau_noi_that_moho_ban___1__eb04d869c61a4528b227980f68526b50_master.jpg', ' Chiều dài 140cm x Chiều rộng 70cm x Chiều cao 75cm ', 58),
(75, 111, '#8c4c03', 'Nâu', 'pro_bo_ban_an_6_ghe_noi_that_moho_combo___1__da6e9ffaa4654bf4a48d83cd5c7cfbd2_master.jpg', 'Chiều dài 140cm x Chiều rộng 70cm x Chiều cao 75cm ', 59),
(76, 111, '#9c582b', 'Nâu', 'pro_mau_nau_noi_that_moho_combo___1__a12728609b9a40379720aef46b6e6b94_master.jpg', 'Dài 1200 X Rộng 750 X Cao 750', 60),
(77, 111, '#9b5e08', 'Nâu', 'pro_nau_noi_that_moho_ban_lam_viec_vline_601_a_e60e2f8b72854311ae12424eed3cb88a_master.jpg', 'Dài 110cm x Rộng 55cm x Cao 74cm', 61),
(78, 111, '#914d0d', 'Màu tự nhiên', 'pro_mau_tu_nhien_noi_that_moho_ban_lam_viec_vline_1_92060b73c393469181d9d24218c38851_master.jpg', 'Dài 110cm x Rộng 55cm x Cao 74cm', 62),
(79, 111, '#000000', 'Đen', 'pro_den_noi_that_moho_ban_lam_viec_work_701_1m2_4_2e73c6f0e04844dd95e8bf2a7e2d933f_master.jpg', 'Dài 120/140cm x Rộng 60cm x Cao 72cm', 63),
(80, 111, '#b44104', 'Nâu', 'pro_nau_noi_that_moho_ban_lam_viec_go_fyn_nau2_f607075e46254fc190dfabcd5108f91c_master.jpg', 'Dài 120cm x Rộng 60cm x Cao 74cm', 64),
(81, 111, '#fff0f0', 'Trắng', 'pro_trang_noi_that_moho_ke_de_sach_9_34a0af0251ae4894ba717dbdfdfe88a5_master.jpg', 'Dài 80cm x Rộng 32cm x Cao 106cm ', 65),
(82, 111, '#ffffff', 'Trắng ', 'pro_trang_noi_that_moho_ke_de_sach_tu_khoa_11_27da17a32ee34d3682ad2cbce1c9606a_master.jpg', 'Dài 80cm x Rộng 32cm x Cao 174cm ', 66),
(83, 111, '#cbbdbd', 'Kem', 'pro_trang_noi_that_moho_ban_may_tinh_1_07121e2728134e579bdfd34fd6e3c184_master.jpg', ' Dài 120cm x Rộng 60cm x Cao 72cm', 67);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) NOT NULL,
  `content` varchar(200) NOT NULL,
  `stars` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `id_product` int(10) NOT NULL,
  `id_user` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `content`, `stars`, `created_at`, `id_product`, `id_user`) VALUES
(8, 'Tốt, giá hợp lý', 5, '', 24, 14),
(9, 'Rẻ', 5, '', 20, 14),
(10, 'đẹp', 5, '', 17, 13);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tokenlogin`
--

CREATE TABLE `tokenlogin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tokenlogin`
--

INSERT INTO `tokenlogin` (`id`, `user_id`, `token`, `create_at`) VALUES
(185, 9, '67a8c5bd2f30b05209217b6d681d9beb52df82c2', '2025-09-17 02:58:45'),
(187, 13, 'cb9bdf33a645d0b29a38cdaa9b9293c449ebdd40', '2025-09-17 03:39:47'),
(189, 9, 'b6e43ad3f23abe40196c722fd0cf5e256dd9efca', '2025-09-18 03:26:17'),
(192, 9, 'e2f867a1e514ed02abbc0b46a300ab8ab5f91e27', '2025-09-18 06:20:33'),
(197, 9, 'cf8f8334ba48643849836dab591752e7ea8b3d2c', '2025-09-18 15:34:52'),
(198, 1, '451efd7d2fb304f974bc97462b16433fbed37cdf', '2025-09-18 17:26:42'),
(199, 1, '466477f4e74d034735765ee3a004c51cc8130ebf', '2025-09-18 17:29:36'),
(200, 1, 'ece837edfdf88d3b426ba03ddde661de3b88b7b2', '2025-09-18 17:30:24'),
(201, 1, 'b70407a66337e5ae2de3e8a19e929fb701bc6834', '2025-09-18 17:43:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
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
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `fullname`, `password`, `forgotToken`, `activeToken`, `status`, `address`, `birthday`, `role`, `avatar`, `create_at`, `update_at`, `is_deleted`) VALUES
(1, 'dnguyenquoc45@gmail.com', '0395235814', 'Nguyễn Quốc Đạt', '$2y$10$Q6dZ.5acdqW2NFGdNHw60OOnIbsjC63k4etS/Kp3CWWLaxXU3RND6', '', '', 1, 'Thôn Hương Quất, xã Thành Công, huyện Khoái Châu, tỉnh Hưng Yên', NULL, 1, NULL, '2025-09-18 15:49:40', NULL, 0),
(4, 'mavantho123@gmail.com', '0987654321', 'Linh Chi', '$2y$10$qAmBaPH/MNeHMFZ7dM0GuO3ozQ5P7PIpKCTiUYkZEzUTEwGv6o/9a', '', '', 1, 'Tuyên Quang', NULL, 2, NULL, '2025-09-08 05:04:33', NULL, 0),
(5, 'hoangvanduc2504@gmail.com', '0327386554', 'Đức Hoàng', '$2y$10$vZxwkcVzBmjc3KK7iETGp.ly7LbUN80w8rUz65QU.pI5.2BOKNpAG', '', '', 1, 'Số Nhà 2, Ngách 123/32 Đường Xuân Phương, Tổ 4', NULL, 2, NULL, '2025-02-28 14:08:47', NULL, 0),
(7, 'nguyenVyKhanh1@gmail.com', '0987654321', 'khanhL', '$2y$10$NZWPZrqhjmSmI.loBoa89u9AtMKWiMGYbXrjwFc4nvjjpcDDntc0i', '', '', 0, 'Thành Trì, Hà Nội', NULL, 2, NULL, '2025-09-14 13:56:07', NULL, 0),
(8, 'lovandung123@gmail.com', '0912345678', 'Mây', '$2y$10$mq1mMAFKv0wRwllsb.xQjecLOn8slczGxkJS9s7p7opeJxX03UlVu', '', '', 0, 'điện biên', NULL, 0, '5.png', '2025-09-12 05:14:44', '2025-09-14 03:26:11', 0),
(9, 'dat123@gmail.com', '0987654312', 'Quốc Đạt', '$2y$10$0WU6Nt6u0Ohj8OkOtLB7nO0Jhd8lh51ayDYVg3L8GJW7c.GkNsK3C', '', '', 1, 'Hưng Yên', '\r\n', 3, NULL, '2025-09-18 15:48:40', NULL, 0),
(11, 'abc1@gmail.com', '0987654321', 'abc_nhanvien', '$2y$10$bcykJGxBp82LQ/78JfW99.usHHZH6s7pD6zA3njQqPPO3isbpSStO', '', '', 3, 'Thái Bình', NULL, 3, NULL, '2025-09-14 13:57:37', NULL, 0),
(12, 'abc@gmail.com', '0987654321', 'Khách1', '$2y$10$T/6XWb9ZZ1JNiGoqsT27sOCaAzaARCCJHX6kLEaR3iyvKRQvL7E6e', '', 'f8835f35609d381655d7eef02cccc267610c3b05', NULL, NULL, NULL, 0, NULL, '2025-09-17 02:11:45', NULL, 0),
(13, 'abc12@gmail.com', '0987654321', 'Đạt_Khách', '$2y$10$TV7.WUOfy/75B9.BICsIteositbZD64XIDdGFMNuSvJSn.uOTfgVC', '', '20554b2e7a0f33d5883484dbfa63a7b4692065ec', NULL, 'Thành Công, Khoái Châu, Hưng Yên', NULL, 0, '', '2025-09-17 02:12:00', '2025-09-17 02:13:34', 0),
(14, 'abc123@gmail.com', '0987654321', 'Khách2', '$2y$10$u9A4xEFixy/vVYtextou6ONyOZgQeL2lyiqhYkOhtQ52qT1r.sxZu', '', '5c66d052c62c6df0e97fbcd777ffd87503d67c1a', 1, NULL, NULL, 0, NULL, '2025-09-17 02:53:57', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(10) NOT NULL,
  `code` varchar(200) NOT NULL,
  `discount` int(11) NOT NULL,
  `unit` tinyint(1) NOT NULL DEFAULT 0,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount`, `unit`, `start`, `end`, `is_deleted`) VALUES
(1, 'b', 30, 0, '2025-09-01', '2025-09-19', 0),
(2, 'a', 20000, 1, '2025-09-01', '2025-09-30', 0),
(3, 'c', 20, 0, '2025-09-01', '2025-10-09', 0),
(4, 'd', 50000, 1, '2025-09-01', '2025-09-30', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`);

--
-- Chỉ mục cho bảng `products_bill`
--
ALTER TABLE `products_bill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product_detail` (`id_product_detail`),
  ADD KEY `id_bill` (`id_bill`);

--
-- Chỉ mục cho bảng `products_cart`
--
ALTER TABLE `products_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product_detail` (`id_product_detail`),
  ADD KEY `id_cart` (`id_cart`);

--
-- Chỉ mục cho bảng `products_detail`
--
ALTER TABLE `products_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_reviews_products` (`id_product`),
  ADD KEY `FK_reviews_users` (`id_user`);

--
-- Chỉ mục cho bảng `tokenlogin`
--
ALTER TABLE `tokenlogin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT cho bảng `products_bill`
--
ALTER TABLE `products_bill`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `products_cart`
--
ALTER TABLE `products_cart`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT cho bảng `products_detail`
--
ALTER TABLE `products_detail`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `tokenlogin`
--
ALTER TABLE `tokenlogin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id`);

--
-- Các ràng buộc cho bảng `products_bill`
--
ALTER TABLE `products_bill`
  ADD CONSTRAINT `products_bill_ibfk_1` FOREIGN KEY (`id_product_detail`) REFERENCES `products_detail` (`id`),
  ADD CONSTRAINT `products_bill_ibfk_2` FOREIGN KEY (`id_bill`) REFERENCES `bills` (`id`);

--
-- Các ràng buộc cho bảng `products_cart`
--
ALTER TABLE `products_cart`
  ADD CONSTRAINT `products_cart_ibfk_1` FOREIGN KEY (`id_product_detail`) REFERENCES `products_detail` (`id`),
  ADD CONSTRAINT `products_cart_ibfk_2` FOREIGN KEY (`id_cart`) REFERENCES `cart` (`id`);

--
-- Các ràng buộc cho bảng `products_detail`
--
ALTER TABLE `products_detail`
  ADD CONSTRAINT `products_detail_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `FK_reviews_products` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_reviews_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `tokenlogin`
--
ALTER TABLE `tokenlogin`
  ADD CONSTRAINT `tokenlogin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
