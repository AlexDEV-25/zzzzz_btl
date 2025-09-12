<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Danh sách voucher đã xoá',
    'role' => 1
];
layout('header_admin', $data);
$filterAll = filter();
// Kiểm tra trạng thái đăng nhập
if (!isLogin()) {
    redirect('?module=auth&action=login');
}

// Kiểm tra có search hay không
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
    $amount = getCountRows("SELECT * FROM vouchers WHERE code LIKE '%$value%'");
    if ($amount > 0) {
        $listVouchers = selectAll("SELECT * FROM vouchers WHERE code LIKE '%$value%'");
    } else {
        setFlashData('smg', 'Voucher không tồn tại');
        setFlashData('smg_type', 'danger');
    }
} else {
    $listVouchers = selectAll("SELECT * FROM vouchers ORDER BY id");
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<body>
    <div class="min-h-full">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Quản lý Voucher</h1>
                <div class="flex gap-4">
                    <form method="post" action="">
                        <div class="flex">
                            <input type="hidden" name='act' value="vouchers" class="hidden">
                            <input type="search" class="form-control bg-gray-100 rounded-md px-2 py-2" name="search" placeholder="Nhập Mã Voucher">
                            <input type="hidden" class="form-control" name="role" value="<?php echo 1; ?>">
                            <button type="submit" class="text-white bg-sky-500 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <div class="flex mb-4 gap-2">
                    <!-- Nút quay lại -->
                    <a href="?module=vouchers&action=list"
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 
                                focus:ring-4 focus:outline-none focus:ring-gray-300 
                                font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                <?php if (!empty($smg)) {
                    echo '<div class="mb-4 p-4 rounded-md ' . ($smg_type == 'danger' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') . '">' . $smg . '</div>';
                } ?>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3">STT</th>
                                <th scope="col" class="px-6 py-3">Mã Voucher</th>
                                <th scope="col" class="px-6 py-3">Giảm giá</th>
                                <th scope="col" class="px-6 py-3">Đơn vị</th>
                                <th scope="col" class="px-6 py-3">Ngày bắt đầu</th>
                                <th scope="col" class="px-6 py-3">Ngày kết thúc</th>
                                <th scope="col" class="px-6 py-3" width="10%">Khôi phục</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listVouchers)):
                                $count = 0;
                                foreach ($listVouchers as $item):
                                    if (!empty($item["is_deleted"]) && $item["is_deleted"] == 1):
                                        $count++;
                            ?>
                                        <tr class="bg-white hover:bg-gray-50">
                                            <td class="px-6 py-4"><?php echo $count; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['code']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['discount']; ?></td>
                                            <td class="px-6 py-4">
                                                <?php echo $item['unit'] == 0 ? '%' : 'VND'; ?>
                                            </td>
                                            <td class="px-6 py-4"><?php echo $item['start']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['end']; ?></td>
                                            <td class="px-6 py-4">
                                                <a href="<?php echo _WEB_HOST; ?>?module=vouchers&action=restore&id=<?php echo $item['id']; ?>"
                                                    class="text-white bg-green-500 hover:bg-green-600 
                                                        focus:ring-4 focus:outline-none focus:ring-green-300 
                                                        font-medium rounded-lg text-sm px-3 py-1.5 text-center">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    endif;
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-red-600 bg-red-100">Không có voucher nào!!</td>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php layout('footer_admin'); ?>
</body>

</html>