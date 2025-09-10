<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Danh sách voucher'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php
    if (!defined('_CODE')) {
        die('Access denied...');
    }
    $filterAll = filter();
    $data = [
        'pageTitle' => 'Danh sách voucher',
        'userId' => 1
    ];
    layout('header_admin', $data);

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
        $listVouchers = selectAll("SELECT * FROM vouchers ORDER BY id DESC");
    }

    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
    ?>

    <div class="min-h-full">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Quản lý Voucher</h1>
                <div class="flex gap-4">
                    <form method="post" action="">
                        <div class="flex">
                            <input type="hidden" name='act' value="vouchers" class="hidden">
                            <input type="search" class="form-control bg-gray-100 rounded-md px-2 py-2" name="search" placeholder="Nhập Mã Voucher">
                            <?php if (!empty($data['userId'])): ?>
                                <input type="hidden" class="form-control" name="userId" value="<?php echo $data['userId']; ?>">
                            <?php endif; ?>
                            <button type="submit" class="text-white bg-sky-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <div class="flex mb-4 gap-2">
                    <!-- Nút thêm -->
                    <a href="?module=vouchers&action=add"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
              hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 
              font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Thêm voucher <i class="fa-solid fa-plus"></i>
                    </a>

                    <!-- Nút thùng rác -->
                    <a href="?module=vouchers&action=binVouchers"
                        class="text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 
              hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
              font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Thùng rác <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
                <?php if (!empty($smg)) {
                    echo '<div class="mb-4 p-4 rounded-md ' . ($smg_type === 'danger' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') . '">' . $smg . '</div>';
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
                                <th scope="col" class="px-6 py-3" width="5%">Sửa</th>
                                <th scope="col" class="px-6 py-3" width="5%">Xoá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listVouchers)):
                                $count = 0;
                                foreach ($listVouchers as $item):
                                    $count++;
                            ?>
                                    <tr class="bg-white hover:bg-gray-50">
                                        <td class="px-6 py-4"><?php echo $count; ?></td>
                                        <td class="px-6 py-4 font-bold text-gray-900"><?php echo $item['code']; ?></td>
                                        <td class="px-6 py-4"><?php echo $item['discount']; ?></td>
                                        <td class="px-6 py-4">
                                            <?php echo $item['unit'] == 0 ? '%' : 'VNĐ'; ?>
                                        </td>
                                        <td class="px-6 py-4"><?php echo $item['start']; ?></td>
                                        <td class="px-6 py-4"><?php echo $item['end']; ?></td>
                                        <td class="px-6 py-4"><a href="<?php echo _WEB_HOST; ?>?module=vouchers&action=edit&id=<?php echo $item['id']; ?>" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                        <td class="px-6 py-4"><a href="<?php echo _WEB_HOST; ?>?module=vouchers&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xoá voucher này?')" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center"><i class="fa-solid fa-trash"></i></a></td>
                                    </tr>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-red-600 bg-red-100">Không có voucher nào!!</td>
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