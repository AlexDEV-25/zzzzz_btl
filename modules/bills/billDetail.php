<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
$userId = $filterAll['userId'];
$billId = $filterAll['billId'];

$bill = selectOne("SELECT * FROM bills WHERE id = $billId ");
$billDetail = selectAll("SELECT * FROM products_bill WHERE id_bill = $billId ");

$data = [
    'pageTitle' => 'Chi tiết đơn hàng',
    'userId' => $userId
];
layout('header_admin', $data);

if (!isLogin()) {
    redirect('?module=auth&action=login');
}

// ====== Xử lý voucher ======
$voucherInfo = null;
$beforeDiscount = 0;
foreach ($billDetail as $item) {
    $productDetailId = $item['id_product_detail'];
    $productDetail = selectOne("SELECT * FROM products_detail WHERE id = $productDetailId ");
    $productId = $productDetail['id_product'];
    $product = selectOne("SELECT * FROM products WHERE id = $productId");

    $beforeDiscount += $item['amount_buy'] * $product['price'];
}

if (!empty($bill['id_voucher'])) {
    $voucherId = $bill['id_voucher'];
    $voucherInfo = selectOne("SELECT * FROM vouchers WHERE id = $voucherId");

    if ($voucherInfo) {
        if ($voucherInfo['unit'] == 0) {
            // Giảm theo %
            $discountValue = ($beforeDiscount * $voucherInfo['discount']) / 100;
        } else {
            // Giảm theo số tiền
            $discountValue = $voucherInfo['discount'];
        }

        $afterDiscount = max(0, $beforeDiscount - $discountValue);
    } else {
        $afterDiscount = $beforeDiscount;
    }
} else {
    $afterDiscount = $beforeDiscount;
}
?>

<div class="min-h-full bg-gray-100 py-10">
    <div class="max-w-4xl mx-auto">
        <!-- Tiêu đề -->
        <header class="bg-white shadow mb-6">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Chi tiết đơn hàng</h1>
                <p class="text-sm text-gray-500">Mã đơn hàng #<?php echo $bill['id']; ?></p>
            </div>
        </header>

        <!-- Thông tin đơn -->
        <div class="bg-white p-6 shadow-md rounded-md mb-6">
            <div class="flex justify-between">
                <div>
                    <p><span class="font-medium text-gray-600">Ngày đặt:</span> <?php echo $bill['date']; ?></p>
                    <p class="mt-2"><span class="font-medium text-gray-600">Trạng thái:</span>
                        <?php
                        if ($bill['status'] == 1) {
                            echo '<span class="ml-2 inline-flex items-center rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">Đã xác nhận</span>';
                        } else if ($bill['status'] == 0) {
                            echo '<span class="ml-2 inline-flex items-center rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">Chưa xác nhận</span>';
                        } else if ($bill['status'] == 2) {
                            echo '<span class="ml-2 inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Đã hoàn thành</span>';
                        } else {
                            echo '<span class="ml-2 inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Đã hủy</span>';
                        }
                        ?>
                    </p>
                </div>
                <div>
                    <p class="font-medium text-gray-600">Tổng tiền:</p>
                    <p class="text-xl font-bold text-gray-900">
                        <?php echo number_format($afterDiscount, 0, ',', '.'); ?> đ
                    </p>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="bg-white shadow-md rounded-md overflow-hidden">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Sản phẩm</th>
                        <th class="px-6 py-3 font-semibold">Số lượng</th>
                        <th class="px-6 py-3 font-semibold">Đơn giá</th>
                        <th class="px-6 py-3 font-semibold">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($billDetail as $item):
                        $productDetailId = $item['id_product_detail'];
                        $productDetail = selectOne("SELECT * FROM products_detail WHERE id = $productDetailId ");
                        $productId = $productDetail['id_product'];
                        $product = selectOne("SELECT * FROM products WHERE id = $productId");
                    ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img class="w-14 h-14 rounded-md object-cover border"
                                    src="<?php echo _IMGP_ . $productDetail['image']; ?>"
                                    alt="<?php echo $product['name_product']; ?>">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo $product['name_product'] ?></p>
                                </div>
                            </td>
                            <td class="px-6 py-4"><?php echo $item['amount_buy']; ?></td>
                            <td class="px-6 py-4"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                <?php echo number_format($item['amount_buy'] * $product['price'], 0, ',', '.'); ?> đ
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tổng kết -->
        <div class="bg-white mt-6 p-6 shadow-md rounded-md space-y-3">
            <div class="flex justify-between text-gray-700">
                <span>Tạm tính</span>
                <span><?php echo number_format($beforeDiscount, 0, ',', '.'); ?> đ</span>
            </div>

            <?php if ($voucherInfo): ?>
                <div class="flex justify-between text-gray-700">
                    <span>Voucher đã áp dụng (<?php echo $voucherInfo['code']; ?>)</span>
                    <span>
                        -<?php
                            echo ($voucherInfo['unit'] == 0)
                                ? $voucherInfo['discount'] . '%'
                                : number_format($voucherInfo['discount'], 0, ',', '.') . ' đ';
                            ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="flex justify-between text-lg font-bold text-gray-900 border-t pt-3">
                <span>Thành tiền</span>
                <span><?php echo number_format($afterDiscount, 0, ',', '.'); ?> đ</span>
            </div>
        </div>
    </div>
</div>

<?php
layout('footer_admin');
?>