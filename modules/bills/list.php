<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
if (isPost() && !empty($filterAll['billId'])) {
    $billId = (int) $filterAll['billId'];

    // Kiểm tra đơn hàng có tồn tại
    $bill = selectOne("SELECT status FROM bills WHERE id = $billId");
    if ($bill) {
        $billStatus = $bill['status'];
        $newStatus  = isset($filterAll['status']) ? (int) $filterAll['status'] : $billStatus;

        // Nếu trạng thái thay đổi
        if ($billStatus != $newStatus) {
            if ($newStatus == 2) {
                // ✅ Xác đóng đơn → trừ số lượng sản phẩm
                $listBillDetail = selectAll("SELECT * FROM products_bill WHERE id_bill = $billId");

                foreach ($listBillDetail as $item) {
                    $productDetailId = (int) $item['id_product_detail'];
                    $productDetail   = selectOne("SELECT amount FROM products_detail WHERE id = $productDetailId");

                    if ($productDetail) {
                        $newAmount = max(0, $productDetail['amount'] - $item['amount_buy']);

                        update('products_detail', ['amount' => $newAmount], "id =$productDetailId");
                        if ($newAmount == 0) {
                            setFlashData('smg', '⚠️ Một số sản phẩm đã hết hàng.');
                            setFlashData('smg_type', 'warning');
                        }
                    }
                }
                $dataUpdate = ['status' => $newStatus];
            } elseif ($newStatus == 4 || $newStatus == -1) {
                // ✅ Hoàn tất hoặc Hủy đơn
                $dataUpdate = [
                    'end_date' => date('Y-m-d H:i:s'),
                    'status'   => $newStatus
                ];

                if ($newStatus == -1) {
                    // ✅ Hủy đơn → cộng lại số lượng
                    $listBillDetail = selectAll("SELECT * FROM products_bill WHERE id_bill = $billId");

                    foreach ($listBillDetail as $item) {
                        $productDetailId = (int) $item['id_product_detail'];
                        $productDetail   = selectOne("SELECT amount FROM products_detail WHERE id = $productDetailId");

                        if ($productDetail) {
                            $newAmount = $productDetail['amount'] + $item['amount_buy'];
                            update('products_detail', ['amount' => $newAmount], "id = $productDetailId");
                        }
                    }
                }
            } else {
                $dataUpdate = ['status' => $newStatus];
            }
        } else {
            $dataUpdate = ['status' => $newStatus];
        }

        // Cập nhật trạng thái đơn
        $updateOk = update('bills', $dataUpdate, "id = $billId");
        if ($updateOk) {
            if (empty(getFlashData('smg'))) {
                setFlashData('smg', '✅ Cập nhật trạng thái thành công!');
                setFlashData('smg_type', 'success');
            }
        } else {
            setFlashData('smg', '❌ Hệ thống đang lỗi, vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', '❌ Đơn hàng không tồn tại.');
        setFlashData('smg_type', 'danger');
    }
}


// Kiểm tra có search hay không
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
    $amount = getCountRows("SELECT * FROM bills WHERE id LIKE '%$value%'");
    if ($amount > 0) {
        $listBills = selectAll("SELECT * FROM bills WHERE id LIKE '%$value%'");
    } else {
        setFlashData('smg', '❌ Đơn không tồn tại');
        setFlashData('smg_type', 'danger');
    }
} else {
    $listBills = selectAll("SELECT * FROM bills ORDER BY id");
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

// Layout
$data = ['pageTitle' => 'Danh sách đơn hàng'];
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data['role'] = $role;

    if ($role == 1) {
        layout('header_admin', $data);
    } elseif ($role == 2) {
        layout('header_manager', $data);
    }
}
?>

<body>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-6">Quản lý đơn hàng</h1>

        <!-- Hiển thị thông báo -->
        <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>

        <!-- Form tìm kiếm (giống categories) -->
        <form method="post" action="" class="mb-6">
            <div class="flex items-center gap-2">
                <input type="search" name="search" placeholder="Nhập mã đơn cần tìm..." class="flex-1 bg-white border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <input type="hidden" class="form-control" name="role" value="<?php echo $role; ?>">
                <button type="submit" class="text-white bg-sky-500 hover:bg-sky-600 font-medium rounded-lg text-sm px-5 py-2.5">Tìm kiếm</button>
            </div>
        </form>

        <!-- Bảng danh sách -->
        <div class="relative overflow-x-auto shadow sm:rounded-lg max-h-[500px]">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">STT</th>
                        <th scope="col" class="px-6 py-3">Mã đơn</th>
                        <th scope="col" class="px-6 py-3">Ngày đặt</th>
                        <th scope="col" class="px-6 py-3">Người mua</th>
                        <th scope="col" class="px-6 py-3">Tiền</th>
                        <th scope="col" class="px-6 py-3">Trạng thái</th>
                        <th scope="col" class="px-6 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listBills)):
                        $count = 0;
                        foreach ($listBills as $item):
                            $count++;
                            $billId = $item['id'];
                            $userId = $item['id_user'];
                            $user = selectOne("SELECT fullname FROM users WHERE id = $userId ");
                    ?>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4"><?php echo $count; ?></td>
                                <td class="px-6 py-4 font-medium text-gray-900"><?php echo $item['id']; ?></td>
                                <td class="px-6 py-4"><?php echo $item['date']; ?></td>
                                <td class="px-6 py-4"><?php echo $user['fullname']; ?></td>
                                <td class="px-6 py-4"><?php echo number_format($item['total'], 0, '.', '.'); ?> &#8363;</td>
                                <td class="px-6 py-4">
                                    <?php if ($item['status'] == 0): ?>
                                        <span class="badge bg-yellow-100 text-yellow-800">Chưa xác nhận</span>
                                    <?php elseif ($item['status'] == 1): ?>
                                        <span class="badge bg-blue-100 text-blue-800">Đã xác nhận</span>
                                    <?php elseif ($item['status'] == 2): ?>
                                        <span class="badge bg-purple-100 text-purple-800">Đang đóng gói</span>
                                    <?php elseif ($item['status'] == 3): ?>
                                        <span class="badge bg-orange-100 text-orange-800">Đang vận chuyển</span>
                                    <?php elseif ($item['status'] == 4): ?>
                                        <span class="badge bg-green-100 text-green-800">Đã hoàn thành</span>
                                    <?php else: ?>
                                        <span class="badge bg-red-100 text-red-800">Đã hủy đơn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 flex gap-2 items-center">
                                    <form action="" method="POST" class="flex gap-2 items-center">
                                        <select name="status"
                                            class="border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500">
                                            <option value="1">Xác nhận Đơn</option>
                                            <option value="2">Đang đóng gói</option>
                                            <option value="3">Đang giao hàng</option>
                                            <option value="4">Đã hoàn thành</option>
                                            <option value="-1">Hủy đơn</option>
                                        </select>
                                        <input type="hidden" name="role" value="<?php echo $role ?>">
                                        <input type="hidden" name="userId" value="<?php echo $userId ?>">
                                        <input type="hidden" name="billId" value="<?php echo $billId ?>">
                                        <button type="submit"
                                            class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-md text-sm">
                                            Cập nhật
                                        </button>
                                    </form>
                                    <a href="<?php echo _WEB_HOST; ?>?module=bills&action=billDetail&role=<?php echo $role; ?>&userId=<?php echo $item['id_user']; ?>&billId=<?php echo $item['id']; ?>"
                                        class="text-sky-600 font-semibold hover:underline">Chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-red-600">Không có đơn hàng nào!!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-end">
            <form action="?module=bills&action=exportBills" method="POST" target="_blank">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md text-sm font-medium">
                    Xuất đơn đã xác nhận
                </button>
            </form>
        </div>
    </div>
</body>
<?php layout('footer'); ?>