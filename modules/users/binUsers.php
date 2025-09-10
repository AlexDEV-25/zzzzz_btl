<!DOCTYPE html>
<html lang="vi">
<?php
$data = [
    'pageTitle' => 'Danh sách người dùng',
    'userId' => 1
];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Danh sách người dùng'; ?></title>
    <!-- <link rel="icon" href="/Project-One-FPT/asset/images/favicon.ico" type="image/x-icon" /> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link rel="stylesheet" href="/Project-One-FPT/asset/css/style.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php
    if (!defined('_CODE')) {
        die('Access denied...');
    }
    layout('header_admin', $data);

    // Kiểm tra trạng thái đăng nhập
    if (!isLogin()) {
        redirect('?module=auth&action=login');
    }
    $filterAll = filter();
    // Kiểm tra có search hay không
    if (!empty($filterAll['search'])) {
        $value = $filterAll['search'];
        $amount = getCountRows("SELECT * FROM users WHERE fullname LIKE '%$value%'");
        if ($amount > 0) {
            $listUsers = selectAll("SELECT * FROM users WHERE fullname LIKE '%$value%'");
        } else {
            setFlashData('smg', 'danh mục không tồn tại');
            setFlashData('smg_type', 'danger');
        }
    } else {
        $listUsers = selectAll("SELECT * FROM users ORDER BY update_at");
    }

    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
    ?>

    <div class="min-h-full">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Quản lý người dùng</h1>
                <div class="flex gap-4">
                    <form method="post" action="">
                        <div class="flex">
                            <input type="hidden" name='act' value="users" class="hidden">
                            <input type="search" class="form-control bg-gray-100 rounded-md px-2 py-2" name="search" placeholder="Nhập Tên Khách Hàng">
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
                    <!-- Nút quay lại -->
                    <a href="?module=users&action=list"
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 
                                focus:ring-4 focus:outline-none focus:ring-gray-300 
                                font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
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
                                <th scope="col" class="px-6 py-3">Họ tên</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Số điện thoại</th>
                                <th scope="col" class="px-6 py-3">Trạng thái</th>
                                <th scope="col" class="px-6 py-3" width="10%">Khôi phục</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listUsers)):
                                $count = 0;
                                foreach ($listUsers as $item):
                                    if ($item['is_deleted'] == 1):
                                        $count++;
                            ?>
                                        <tr class="bg-white hover:bg-gray-50">
                                            <td class="px-6 py-4"><?php echo $count; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['fullname']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['email']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['phone']; ?></td>
                                            <td class="px-6 py-4"><?php echo $item['status'] == 1 ? '<span class="inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Đã kích hoạt</span>' : '<span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Chưa kích hoạt</span>'; ?></td>
                                            <td class="px-6 py-4">
                                                <a href="<?php echo _WEB_HOST; ?>?module=users&action=restore&id=<?php echo $item['id']; ?>"
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
                                    <td colspan="7" class="px-6 py-4 text-center text-red-600 bg-red-100">Không có người dùng nào!!</td>
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