<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Quản lý người dùng'; ?></title>
    <link rel="icon" href="<?php echo _WEB_HOST_TEMPLATES; ?>/images/favicon.ico" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <?php
    if (!defined('_CODE')) die('Access denied...');
    ?>

    <!-- Menu di động -->
    <div class="relative z-40 lg:hidden hidden menu" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-40 flex">
            <div class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl">
                <div class="flex px-4 pb-2 pt-5">
                    <button type="button" class="relative -m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400 close-menu">
                        <span class="sr-only">Đóng menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                    <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard" class="block p-2 font-medium text-gray-900">Home</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=error&action=404" class="block p-2 font-medium text-gray-900">Hotline</a>
                </div>
                <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=login" class="block p-2 font-medium text-gray-900">Đăng nhập</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=register" class="block p-2 font-medium text-gray-900">Đăng ký</a>
                </div>
            </div>
            <div class="flex-1 bg-black bg-opacity-25 overlay"></div>
        </div>
    </div>

    <!-- Header -->
    <header class="z-20 bg-white border-b border-gray-200 sticky top-0">
        <nav aria-label="Top" class="bg-white relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <button type="button" class="relative rounded-md bg-white p-2 text-gray-400 lg:hidden open-menu mr-2">
                    <span class="sr-only">Mở menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard" class="flex items-center">
                    <img class="h-9 sm:h-11 w-auto rounded-md" src="https://th.bing.com/th/id/OIP.cdh5Bm1mwTTH7Q2GFTg1xwHaHq?rs=1&pid=ImgDetMain" alt="Logo">
                </a>
            </div>

            <!-- Menu và tìm kiếm -->
            <div class="flex-1 flex items-center justify-center lg:justify-start gap-6">
                <!-- Desktop Menu -->
                <div class="hidden lg:flex space-x-8">
                    <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard" class="font-medium text-gray-700 hover:text-gray-800">Home</a>
                    <a href="<?php echo _WEB_HOST; ?>?module=error&action=404" class="font-medium text-gray-700 hover:text-gray-800">Hotline</a>
                </div>

                <!-- Form tìm kiếm ở giữa -->
                <div class="flex-1 flex justify-center px-4">
                    <form autocomplete="off" autocapitalize="off" method="POST" action="" class="flex items-center w-full max-w-3xl gap-2">
                        <input type="text" name="search" placeholder="Nhập từ khóa..." class="flex-1 h-10 rounded-md pl-4 bg-gray-100">
                        <?php if (!empty($data['userId'])): ?><input type="hidden" name="userId" value="<?php echo ''; ?>"><?php endif; ?>
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