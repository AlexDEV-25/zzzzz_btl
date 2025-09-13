<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$filterAll = filter();
// Kiểm tra có search hay không
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
    $amount = getCountRows("SELECT * FROM products WHERE id LIKE '%$value%'");
    if ($amount > 0) {
        $listProducts = selectAll("SELECT * FROM products WHERE id LIKE '%$value%'");
    } else {
        setFlashData('smg', '❌ Sản phẩm không tồn tại');
        setFlashData('smg_type', 'danger');
    }
} else {
    $listProducts = selectAll("SELECT * FROM products ORDER BY created_at");
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$data = [
    'pageTitle' => 'Danh sách sản phẩm',
];
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = [
        'role' => $role,
    ];
    if ($role == 1) {
        layout('header_admin', $data);
    } else if ($role == 2) {
        layout('header_manager', $data);
    }
} else {
    die();
}
?>

<body>
    <div class="min-h-full">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Quản lý sản phẩm</h1>
                <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>
                <div class="flex gap-4">
                    <form method="post" action="">
                        <div class="flex">
                            <input type="hidden" name='act' value="products" class="hidden">
                            <input type="search" class="form-control bg-gray-100 rounded-md px-2 py-2" name="search" placeholder="Nhập Mã Sản Phẩm">
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
                    <a href="?module=products&action=add&role=<?php echo $role; ?>"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
              hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 
              font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Thêm sản phẩm <i class="fa-solid fa-plus"></i>
                    </a>

                    <!-- Nút thùng rác -->
                    <a href="?module=products&action=binProducts&role=<?php echo $role; ?>"
                        class="text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 
              hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
              font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Thùng rác <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3">STT</th>
                                <th scope="col" class="px-6 py-3">Mã sản phẩm</th>
                                <th scope="col" class="px-6 py-3">Tên</th>
                                <th scope="col" class="px-6 py-3">Hình ảnh</th>
                                <th scope="col" class="px-6 py-3">Giá bán</th>
                                <th scope="col" class="px-6 py-3">Giá gốc</th>
                                <th scope="col" class="px-6 py-3">Số lượt bán</th>
                                <th scope="col" class="px-6 py-3">Ngày đăng</th>
                                <th scope="col" class="px-6 py-3" width="5%">Sửa</th>
                                <th scope="col" class="px-6 py-3" width="5%">Xoá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listProducts)):
                                $count = 0;
                                foreach ($listProducts as $item):
                                    if ($item['is_deleted'] != 1):
                                        $count++;
                            ?>
                                        <tr class="bg-white hover:bg-gray-50">
                                            <td class="px-6 py-4"><?php echo $count; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['id']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['name_product']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['thumbnail']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['price']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['origin_price']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['sold']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['created_at']; ?></td>
                                            <td class="px-6 py-4"><a href="<?php echo _WEB_HOST; ?>?module=products&action=edit&id=<?php echo $item['id']; ?>&role=<?php echo $role; ?>" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                            <td class="px-6 py-4"><a href="<?php echo _WEB_HOST; ?>?module=products&action=delete&id=<?php echo $item['id']; ?>&role=<?php echo $role; ?>" onclick="return confirm('Bạn có chắc chắn muốn xoá?')" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center"><i class="fa-solid fa-trash"></i></a></td>
                                        </tr>
                                <?php
                                    endif;
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="10" class="px-6 py-4 text-center text-red-600 bg-red-100">Không có sản phẩm nào!!</td>
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
</body>
<?php layout('footer'); ?>