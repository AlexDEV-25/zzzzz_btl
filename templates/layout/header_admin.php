<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['pageTitle'] ?? 'Quản lý người dùng'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php if (!defined('_CODE')) die('Access denied...'); ?>
    <!-- Header -->
    <header class="z-20 bg-white border-b border-gray-200 sticky top-0">
        <nav class="bg-white relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">


            <!-- Menu desktop + tìm kiếm + tài khoản -->
            <div class="flex-1 flex items-center justify-center lg:justify-start gap-6">
                <!-- Menu desktop -->
                <div class="hidden lg:flex space-x-8">
                    <a href="<?php echo _WEB_HOST; ?>?module=home&action=admin&role=1" class="font-medium text-gray-700 hover:text-gray-800">Trang Admin</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=categories&action=list&role=1" class="font-medium text-gray-700 hover:text-gray-800">Danh mục</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=products&action=list&role=1" class="font-medium text-gray-700 hover:text-gray-800">Sản phẩm</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=users&action=list&role=1" class="font-medium text-gray-700 hover:text-gray-800">Tài khoản</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=vouchers&action=list&role=1" class="font-medium text-gray-700 hover:text-gray-800">Voucher</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=bills&action=list&role=1" class="font-medium text-gray-700 hover:text-gray-800">Đơn hàng</a>

                </div>

                <!-- Thanh tìm kiếm -->
                <div class="flex-1 flex justify-center px-4">
                    <form autocomplete="off" method="POST" class="flex items-center w-full max-w-3xl gap-2" action="?module=home&action=productsSearch">
                        <input type="text" name="search" placeholder="Nhập từ khóa..." class="flex-1 h-10 rounded-md pl-4 bg-gray-100">
                        <input type="hidden" name="role" value="<?php echo 1; ?>">
                        <?php if (!empty($data['categoryId'])): ?><input type="hidden" name="categoryId" value="<?php echo $data['categoryId']; ?>"><?php endif; ?>
                        <?php if (!empty($data['productId'])): ?><input type="hidden" name="productId" value="<?php echo $data['productId']; ?>"><?php endif; ?>
                        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Dropdown tài khoản -->
                <div class="hidden lg:flex items-center space-x-4">
                    <div class="relative text-end">
                        <button id="user-menu-button" class="inline-flex items-center rounded-full bg-white p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <img class="h-8 w-8 rounded-full" src="https://github.com/mdo.png" alt="User avatar">
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1">
                            <a href="<?php echo _WEB_HOST; ?>?module=home&action=admin&role=1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Trang admin</a>
                            <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard&role=1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Trang chủ</a>
                            <a href="?module=auth&action=logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đăng xuất</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <script>
        // Mở/đóng menu di động
        document.querySelector('.open-menu')?.addEventListener('click', () => document.querySelector('.menu')?.classList.remove('hidden'));
        document.querySelector('.close-menu')?.addEventListener('click', () => document.querySelector('.menu')?.classList.add('hidden'));

        // Mở/đóng dropdown tài khoản
        document.querySelector('#user-menu-button')?.addEventListener('click', () => {
            document.querySelector('#user-menu').classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            const menu = document.querySelector('#user-menu');
            const btn = document.querySelector('#user-menu-button');
            if (!btn.contains(e.target) && !menu.contains(e.target)) menu.classList.add('hidden');
        });
    </script>
</body>

</html>