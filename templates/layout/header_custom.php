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
    <?php if (!defined('_CODE')) die('Access denied...'); ?>

    <!-- Menu di động -->
    <div class="relative z-40 lg:hidden hidden menu" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-40 flex">
            <div class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl">
                <div class="flex px-4 pb-2 pt-5">
                    <button type="button" class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md close-menu">
                        <span class="sr-only">Đóng menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                    <a href="?module=home&action=dashboard&userId=<?php echo $data['userId'] ?? ''; ?>" class="block p-2 font-medium text-gray-900">Home</a>
                    <a href="?module=cart&action=customCart&userId=<?php echo $data['userId'] ?? ''; ?>" class="block p-2 font-medium text-gray-900">Thông tin đơn</a>
                </div>
                <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                    <a href="?module=auth&action=login" class="block p-2 font-medium text-gray-900">Đăng nhập</a>
                    <a href="?module=auth&action=register" class="block p-2 font-medium text-gray-900">Đăng ký</a>
                </div>
            </div>
            <div class="flex-1 bg-black bg-opacity-25 overlay"></div>
        </div>
    </div>

    <!-- Header -->
    <header class="z-20 bg-white border-b border-gray-200 sticky top-0">
        <nav class="bg-white relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <!-- Logo + menu di động -->
            <div class="flex items-center gap-2">
                <button type="button" class="p-2 text-gray-400 rounded-md lg:hidden open-menu">
                    <span class="sr-only">Mở menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard&userId=<?php echo $data['userId'] ?? ''; ?>" class="flex items-center">
                    <img class="h-9 sm:h-11 w-auto rounded-md" src="https://th.bing.com/th/id/OIP.cdh5Bm1mwTTH7Q2GFTg1xwHaHq?rs=1&pid=ImgDetMain" alt="Logo">
                </a>
            </div>

            <!-- Menu desktop + tìm kiếm + tài khoản -->
            <div class="flex-1 flex items-center justify-center lg:justify-start gap-6">
                <!-- Menu desktop -->
                <div class="hidden lg:flex space-x-8">
                    <a href="?module=home&action=dashboard&userId=<?php echo $data['userId'] ?? ''; ?>&count=<?php echo $data['count'] ?? ''; ?>" class="font-medium text-gray-700 hover:text-gray-800">Home</a>
                    <a href="?module=cart&action=customCart&userId=<?php echo $data['userId'] ?? ''; ?>" class="font-medium text-gray-700 hover:text-gray-800">Thông tin đơn</a>
                </div>

                <!-- Thanh tìm kiếm -->
                <div class="flex-1 flex justify-center px-4">
                    <form autocomplete="off" method="POST" class="flex items-center w-full max-w-3xl gap-2">
                        <input type="text" name="search" placeholder="Nhập từ khóa..." class="flex-1 h-10 rounded-md pl-4 bg-gray-100">
                        <?php if (!empty($data['userId'])): ?><input type="hidden" name="userId" value="<?php echo $data['userId']; ?>"><?php endif; ?>
                        <?php if (!empty($data['categoryId'])): ?><input type="hidden" name="categoryId" value="<?php echo $data['categoryId']; ?>"><?php endif; ?>
                        <?php if (!empty($data['productId'])): ?><input type="hidden" name="productId" value="<?php echo $data['productId']; ?>"><?php endif; ?>
                        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Tài khoản + giỏ hàng -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="?module=cart&action=cart&userId=<?php echo $data['userId'] ?? ''; ?>" class="flex items-center gap-2 text-gray-700 hover:text-gray-800">
                        <i class="fas fa-shopping-bag"></i>
                        <span><?php echo $data['count'] ?? '0'; ?></span>
                    </a>
                    <div class="relative text-end">
                        <button id="user-menu-button" class="inline-flex items-center rounded-full bg-white p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <img class="h-8 w-8 rounded-full" src="<?php
                                                                    if (!empty($data['userId'])) {
                                                                        $user = selectOne("SELECT * FROM users WHERE id = " . $data['userId']);
                                                                        echo !empty($user['avatar']) ? _IMGP_ . $user['avatar'] : 'https://github.com/mdo.png';
                                                                    }
                                                                    ?>" alt="User avatar">
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cài đặt</a>
                            <a href="?module=users&action=customEdit&userId=<?php echo $data['userId'] ?? ''; ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thông tin người dùng</a>
                            <a href="?module=auth&action=logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đăng xuất</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <script>
        // Mở/đóng menu di động
        document.querySelector('.open-menu')?.addEventListener('click', () => {
            document.querySelector('.menu')?.classList.remove('hidden');
        });
        document.querySelector('.close-menu')?.addEventListener('click', () => {
            document.querySelector('.menu')?.classList.add('hidden');
        });

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