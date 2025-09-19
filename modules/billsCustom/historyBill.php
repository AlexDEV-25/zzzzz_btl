<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

$filterAll = filter();
$errors = [];
$userId = $filterAll['userId'];
$listBill = selectAll("SELECT * FROM bills WHERE id_user = $userId");
$cartCount = getCountCart($userId);
$rowCart = getCountRows("SELECT * FROM cart WHERE id_user = $userId");
$data = [
    'pageTitle' => "Lịch sử đơn hàng",
    'count' => $cartCount,
    'role' => 0,
    'userId' => $userId
];
layout('header_custom', $data);
?>

<body class="bg-gray-100">
    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">📦 Lịch sử đơn hàng</h1>

        <?php foreach ($listBill as $item): ?>
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200 hover:shadow-xl transition">

                <!-- Header đơn hàng -->
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Mã đơn: #<?php echo $item['id']; ?></h2>
                    <p class="text-sm text-gray-500">Ngày đặt: <?= $item['date'] ?></p>
                    <?php
                    if ($item['status'] == 0) {
                        echo '<span class="ml-2 inline-flex items-center rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">Chưa xác nhận</span>';
                    } elseif ($item['status'] == 1) {
                        echo '<span class="ml-2 inline-flex items-center rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">Đã xác nhận</span>';
                    } elseif ($item['status'] == 2) {
                        echo '<span class="ml-2 inline-flex items-center rounded-md bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700">Đang đóng gói</span>';
                    } elseif ($item['status'] == 3) {
                        echo '<span class="ml-2 inline-flex items-center rounded-md bg-orange-100 px-2 py-1 text-xs font-medium text-orange-700">Đang vận chuyển</span>';
                    } elseif ($item['status'] == 4) {
                        echo '<span class="ml-2 inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Đã hoàn thành</span>';
                    } else {
                        echo '<span class="ml-2 inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Đã hủy</span>';
                    }
                    ?>
                </div>

                <!-- Danh sách sản phẩm -->
                <div class="divide-y divide-gray-200">
                    <?php
                    $billId = $item['id'];
                    $listDetailBill = selectAll("SELECT * FROM products_bill WHERE id_bill = $billId");
                    if (!empty($listDetailBill)):
                        foreach ($listDetailBill as $DetailBill):
                            $productDetailId = $DetailBill['id_product_detail'];
                            $productDetail = selectOne("SELECT * FROM products_detail WHERE id = $productDetailId");
                            $productId = $productDetail['id_product'];
                            $product = selectOne("SELECT * FROM products WHERE id = $productId");
                    ?>
                            <div class="flex items-center justify-between py-4">
                                <div class="flex items-center gap-4">
                                    <img src="<?php echo _IMGP_ . $productDetail['image']; ?>"
                                        alt="<?php echo $product['name_product']; ?>"
                                        class="w-16 h-16 rounded object-cover border">
                                    <div>
                                        <p class="font-medium text-gray-800"><?php echo $product['name_product']; ?></p>
                                        <p class="text-sm text-gray-500">Số lượng: x<?php echo $DetailBill['amount_buy']; ?></p>
                                    </div>
                                </div>
                                <p class="font-semibold text-gray-700">
                                    <?php echo number_format($product['price'] * $DetailBill['amount_buy'], 0, ',', '.'); ?> đ
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 py-4 text-center">Không có sản phẩm nào trong đơn hàng này.</p>
                    <?php endif; ?>
                </div>

                <!-- Tổng tiền + Nút chi tiết -->
                <div class="flex justify-between items-center mt-4">
                    <p class="text-lg font-bold text-gray-900">
                        Tổng tiền: <?php echo number_format($item['total'], 0, ',', '.'); ?> đ
                    </p>
                    <a href="?module=billsCustom&action=customBillDetail&role=0&userId=<?php echo $userId; ?>&billId=<?php echo $item['id']; ?>"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                        Chi tiết đơn hàng
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
<?php layout('footer'); ?>