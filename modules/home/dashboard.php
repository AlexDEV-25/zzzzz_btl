<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$filterAll = filter(); // từ log in[role+userID] or không gì cả
$listProduct = selectAll("SELECT * FROM products");
$data = ['pageTitle' => 'Trang chủ',];
$role = -1;
$userId = -1;
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = [
        'pageTitle' => 'Trang chủ',
    ];
    if ($role == -1) {
        layout('header_dashboard', $data);
    } elseif ($role == 1) {
        layout('header_admin', $data);
    } elseif ($role == 2) {
        layout('header_manager', $data);
    } elseif ($role == 3) {
        layout('header_employee', $data);
    } else {
        if (!empty($filterAll['userId'])) {
            $userId = $filterAll['userId'];
            $cartCount = getCountCart($userId);
            $data = [
                'pageTitle' => 'Trang chủ',
                'count' => $cartCount,
                'userId' => $userId
            ];
            layout('header_custom', $data);
        }
    }
} else {
    layout('header_dashboard', $data);
}
?>

<body class="bg-gray-100 font-sans">
    <!-- Banner -->
    <div class="my-10 max-w-7xl mx-auto relative isolate bg-cover bg-center h-[420px] rounded-2xl shadow-xl overflow-hidden"
        style="background-image: url('<?php echo _IMGB_; ?>banner.jpg');">

        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/30 flex flex-col items-center justify-center px-6 lg:px-32">
            <div class="text-center animate-fadeInUp">
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-wide text-white drop-shadow-lg">
                    Giảm Giá Đến 45% 🎉
                </h1>
                <p class="mt-4 text-lg lg:text-xl text-gray-200">
                    Mua sắm trực tuyến tại <span class="font-semibold text-rose-400">META.vn</span> - Giá tốt, Uy tín
                </p>
                <a href="#products"
                    class="inline-block mt-6 px-6 py-3 text-lg font-semibold text-white bg-rose-600 rounded-xl shadow-md hover:bg-rose-700 transition">
                    Mua ngay
                </a>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Danh mục -->
            <aside class="lg:col-span-1">
                <h2 class="text-2xl font-bold text-gray-800 mb-5">Danh mục sản phẩm</h2>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <ul class="space-y-3">
                        <?php
                        $listCategory = selectAll("SELECT * FROM categories");
                        foreach ($listCategory as $category):
                            if ($category['is_deleted'] != 1):
                                $categoryId = $category['id'];
                        ?>
                                <li>
                                    <a href="?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>"
                                        class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-rose-100 hover:text-rose-700 font-medium transition">
                                        <?php echo $category['name_category']; ?>
                                    </a>
                                </li>
                        <?php
                            endif;
                        endforeach; ?>
                    </ul>
                </div>
            </aside>

            <!-- Sản phẩm -->
            <main class="lg:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    foreach ($listProduct as $product):
                        $categorieId = $product['id_category'];
                        $category = selectOne("SELECT is_deleted FROM categories WHERE id = $categorieId");
                        $isDelete = $category['is_deleted'];
                        if ($product['is_deleted'] != 1 && $isDelete != 1):
                            $productId = $product['id'];
                            $productDetail = selectOne("SELECT * FROM products_detail WHERE id_product = $productId");
                    ?>
                            <div class="bg-white rounded-2xl shadow-md overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition duration-300">
                                <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>">
                                    <img src="<?php echo _IMGP_ . $product['thumbnail']; ?>"
                                        alt="<?php echo $product['name_product']; ?>"
                                        class="w-full h-52 object-cover" />
                                    <div class="p-5">
                                        <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">
                                            <?php echo $product['name_product']; ?>
                                        </h3>
                                        <p class="mt-2 text-rose-600 font-bold text-xl">
                                            <?php echo number_format($product['price'], 0, ',', '.'); ?> đ
                                        </p>
                                        <p class="text-gray-500 text-sm mt-1">
                                            Còn lại: <?php echo $productDetail['amount']; ?> sản phẩm
                                        </p>
                                    </div>
                                </a>
                            </div>
                    <?php
                        endif;
                    endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 text-center py-6 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="mb-0 text-lg">© 2025 <span class="text-white font-semibold">META.vn</span> - Mua sắm online chính hãng</p>
        </div>
    </footer>

    <!-- Hộp quà -->
    <?php if (isset($role) && $role != -1 && $role != 1 && $role != 2 && $role != 3): ?>
        <a href="?module=home&action=listVoucher&userId=<?php echo $userId; ?>"
            class="gift-icon fixed bottom-6 left-6 w-16 h-16 cursor-pointer z-50 animate-bounce">
            <img src="<?php echo _IMGG_; ?>git.png"
                alt="Gift"
                class="w-full h-full object-contain drop-shadow-lg">
        </a>
    <?php endif; ?>

    <!-- Thêm animation -->
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 1s ease-out forwards;
        }
    </style>
</body>

<?php layout('footer'); ?>