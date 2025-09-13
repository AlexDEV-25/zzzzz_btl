<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$filterAll = filter();
$data = [
    'pageTitle' => 'Danh sách đơn hàng',
];
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = [
        'role' => $role,
    ];
    if ($role == 1) {
        layout('header_admin', $data);
    } elseif ($role == 2) {
        layout('header_manager', $data);
    }
}

if (isPost()) {
    if (!empty($filterAll['billId'])) {
        $billIdCondition = $filterAll['billId'];
        $bill = selectOne("SELECT status FROM bills WHERE id = $billIdCondition ");
        $billStatus = $bill['status'];
        if (!empty($filterAll['status'])) {
            if ($billStatus != $filterAll['status']) {
                if ($filterAll['status'] == 1) {
                    // ✅ Khi xác nhận đơn thì trừ số lượng sản phẩm
                    $listBillDetail = selectAll("SELECT * FROM products_bill WHERE id_bill = $billIdCondition ");

                    foreach ($listBillDetail as $item):
                        $productDetailId = $item['id_product_detail'];
                        $productDetail = selectOne("SELECT amount FROM products_detail WHERE id = $productDetailId ");

                        // Trừ số lượng
                        $newAmount = $productDetail['amount'] - $item['amount_buy'];
                        if ($newAmount < 0) {
                            $newAmount = 0; // tránh âm số
                        }

                        // Cập nhật lại số lượng
                        $dataUpdateAmount = [
                            'amount' => $newAmount,
                        ];
                        $conditionProductDetail = "id = $productDetailId";
                        $UpdateStatusAmount = update('products_detail', $dataUpdateAmount, $conditionProductDetail);

                        // Nếu về 0 thì báo hết hàng
                        if ($newAmount == 0) {
                            setFlashData('smg', 'Sản phẩm trong kho đã hết');
                            setFlashData('smg_type', 'danger');
                        }
                    endforeach;

                    $dataUpdate = [
                        'status' => $filterAll['status']
                    ];
                } elseif ($filterAll['status'] == 2) {
                    $dataUpdate = [
                        'end_date' => date('Y-m-d H:i:s'),
                        'status' => $filterAll['status']
                    ];
                } elseif ($filterAll['status'] == -1) {
                    $dataUpdate = [
                        'end_date' => date('Y-m-d H:i:s'),
                        'status' => $filterAll['status']
                    ];
                    $listBillDetail = selectAll("SELECT * FROM products_bill WHERE id_bill = $billIdCondition ");

                    foreach ($listBillDetail as $item):
                        $productDetailId = $item['id_product_detail'];
                        $productDetail = selectOne("SELECT amount FROM products_detail WHERE id = $productDetailId ");
                        $dataUpdateAmount = [
                            'amount' => $productDetail['amount'] + $item['amount_buy'],
                        ];
                        $conditionProductDetail = "id = $productDetailId";
                        $UpdateStatusAmount = update('products_detail', $dataUpdateAmount, $conditionProductDetail);
                    endforeach;
                }
            } else {
                $dataUpdate = [
                    'status' => $filterAll['status']
                ];
            }

            $condition = "id = $billIdCondition";
            $UpdateStatus = update('bills', $dataUpdate, $condition);
            if ($UpdateStatus) {
                if (empty(getFlashData('smg'))) { // nếu chưa có thông báo hết hàng
                    setFlashData('smg', 'Sửa trạng thái thành công!!');
                    setFlashData('smg_type', 'success');
                }
            } else {
                setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
                setFlashData('smg_type', 'danger');
            }
        }
    }
}

// kiểm tra có search hay không
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
    $amount = getCountRows("SELECT * FROM bills WHERE id LIKE '%$value%'");
    if ($amount > 0) {
        $listBills = selectAll("SELECT * FROM bills WHERE id LIKE '%$value%'");
    } else {
        setFlashData('smg', 'Đơn hàng không tồn tại');
        setFlashData('smg_type', 'danger');
    }
} else {
    $listBills = selectAll("SELECT * FROM bills ORDER BY date");
}
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-6">Quản lý đơn hàng</h1>

    <!-- Form tìm kiếm -->
    <div class="flex justify-end mb-6">
        <form method="post" action="" class="flex gap-2">
            <input type="search" name="search" placeholder="Nhập Mã Đơn"
                class="bg-gray-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            <button type="submit"
                class="text-white bg-sky-500 hover:bg-sky-600 font-medium rounded-lg text-sm px-5 py-2.5">Tìm kiếm</button>
        </form>
    </div>

    <!-- Thông báo -->
    <?php if (!empty($smg)) {
        getSmg($smg, $smg_type);
    } ?>

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
                                <?php if ($item['status'] == 1): ?>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800">Đã xác nhận</span>
                                <?php elseif ($item['status'] == 0): ?>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800">Chưa xác nhận</span>
                                <?php elseif ($item['status'] == 2): ?>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-green-100 text-green-800">Đã hoàn thành</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-red-100 text-red-800">Đã hủy đơn</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <form action="" method="POST" class="flex gap-2 items-center">
                                    <select name="status"
                                        class="border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500">
                                        <option value="1">Xác nhận Đơn</option>
                                        <option value="-1">Hủy đơn</option>
                                        <option value="2">Đã hoàn thành</option>
                                    </select>
                                    <input type="hidden" name="role" value="<?php echo $role ?>">
                                    <input type="hidden" name="userId" value="<?php echo $userId ?>">
                                    <input type="hidden" name="billId" value="<?php echo $billId ?>">
                                    <button type="submit"
                                        class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-md text-sm">Xác nhận</button>
                                </form>
                                <a href="<?php echo _WEB_HOST; ?>?module=bills&action=billDetail&role=<?php echo $role; ?>&userId=<?php echo $item['id_user']; ?>&billId=<?php echo $item['id']; ?>"
                                    class="block mt-2 text-sky-600 font-semibold">Chi tiết</a>
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
</div>

<?php
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = [
        'role' => $role,
    ];
    if ($role == 1) {
        layout('footer_admin', $data);
    } elseif ($role == 2) {
        layout('footer_manager', $data);
    }
}
?>