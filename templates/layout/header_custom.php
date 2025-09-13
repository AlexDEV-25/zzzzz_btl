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
    <!-- Header -->
    <header class="z-20 bg-white border-b border-gray-200 sticky top-0">
        <nav class="bg-white relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">

            <!-- Menu desktop + tìm kiếm + tài khoản -->
            <div class="flex-1 flex items-center justify-center lg:justify-start gap-6">
                <!-- Menu desktop -->
                <div class="hidden lg:flex space-x-8">
                    <a href="?module=home&action=dashboard&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="font-medium text-gray-700 hover:text-gray-800">Home</a>
                    <a href="?module=cart&action=customCart&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="font-medium text-gray-700 hover:text-gray-800">Thông tin đơn</a>
                </div>

                <!-- Thanh tìm kiếm -->
                <div class="flex-1 flex justify-center px-4">
                    <form autocomplete="off" method="POST" class="flex items-center w-full max-w-3xl gap-2" action="?module=home&action=productsSearch">
                        <input type="text" name="search" placeholder="Nhập từ khóa..." class="flex-1 h-10 rounded-md pl-4 bg-gray-100">
                        <input type="hidden" name="role" value="0">
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
                    <a href="?module=cart&action=cart&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="flex items-center gap-2 text-gray-700 hover:text-gray-800">
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
                            <a href="?module=users&action=customEdit&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thông tin người dùng</a>
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