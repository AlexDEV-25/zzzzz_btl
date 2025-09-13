<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Quản lý người dùng'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <?php
    if (!defined('_CODE')) die('Access denied...');
    ?>

    <!-- Header -->
    <header class="z-20 bg-white border-b border-gray-200 sticky top-0">
        <nav aria-label="Top" class="bg-white relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">

            <!-- Menu và tìm kiếm -->
            <div class="flex-1 flex items-center justify-center lg:justify-start gap-6">
                <!-- Desktop Menu -->
                <div class="hidden lg:flex space-x-8">
                    <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard&role=-1" class="font-medium text-gray-700 hover:text-gray-800">Home</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=error&action=404" class="font-medium text-gray-700 hover:text-gray-800">Hotline</a>
                </div>

                <!-- Form tìm kiếm ở giữa -->
                <div class="flex-1 flex justify-center px-4">
                    <form autocomplete="off" autocapitalize="off" method="POST" action="" class="flex items-center w-full max-w-3xl gap-2" action="?module=home&action=productsSearch">
                        <input type="text" name="search" placeholder="Nhập từ khóa..." class="flex-1 h-10 rounded-md pl-4 bg-gray-100">
                        <input type="hidden" name="role" value="-1">
                        <?php if (!empty($data['categoryId'])): ?><input type="hidden" name="categoryId" value="<?php echo $data['categoryId']; ?>"><?php endif; ?>
                        <?php if (!empty($data['productId'])): ?><input type="hidden" name="productId" value="<?php echo $data['productId']; ?>"><?php endif; ?>
                        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Tài khoản -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=login" class="text-gray-700 hover:text-gray-800 font-medium">Đăng nhập</a>
                    <span class="h-6 w-px bg-gray-200"></span>
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=register" class="text-gray-700 hover:text-gray-800 font-medium">Đăng ký</a>
                </div>
        </nav>
    </header>

    <script>
        document.querySelector('.open-menu')?.addEventListener('click', () => {
            document.querySelector('.menu')?.classList.remove('hidden');
        });
        document.querySelector('.close-menu')?.addEventListener('click', () => {
            document.querySelector('.menu')?.classList.add('hidden');
        });
    </script>
</body>

</html>