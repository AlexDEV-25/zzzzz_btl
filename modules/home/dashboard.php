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

<body class="bg-gray-100">
    <!-- Banner -->
    <div class="my-10 max-w-7xl mx-auto relative isolate bg-cover bg-bottom bg-[url('<?php echo _IMGB_; ?>banner.jpg')] h-[400px] rounded-xl">

        <div class="bg-black/30 absolute inset-0 flex flex-col items-center justify-center w-full px-10 lg:px-80 rounded-xl">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                    Giảm Giá Đến 45% - Sản Phẩm Chính Hãng
                </h1>
                <p class="mt-6 text-lg leading-8 text-white">
                    Mua sắm trực tuyến tại META.vn - Giá tốt, Uy tín
                </p>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Danh mục bên trái -->
            <aside class="lg:col-span-1">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Danh mục sản phẩm</h2>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <ul class="space-y-2">
                        <?php
                        $listCategory = selectAll("SELECT * FROM categories");
                        foreach ($listCategory as $category):
                            if ($category['is_deleted'] != 1):
                                $categoryId = $category['id'];
                        ?>
                                <li>
                                    <a href="?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&role=<?php echo  $role; ?>&userId=<?php echo $userId; ?>"
                                        class="block text-gray-700 hover:text-rose-700 font-medium">
                                        <?php echo $category['name_category']; ?>
                                    </a>
                                </li>
                        <?php
                            endif;
                        endforeach; ?>
                    </ul>
                </div>
            </aside>

            <!-- Phần sản phẩm -->
            <main class="lg:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    foreach ($listProduct as $product):
                        $categorieId = $product['id_category'];
                        $category = selectOne("SELECT is_deleted FROM categories WHERE id = $categorieId");
                        $isDelete = $category['is_deleted'];
                        if ($product['is_deleted'] != 1 && $isDelete != 1):
                            $productId = $product['id'];
                            $productDetail = selectOne("SELECT * FROM products_detail WHERE id_product = $productId");
                    ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>">
                                    <img src="<?php echo _IMGP_ . $product['thumbnail']; ?>"
                                        alt="<?php echo $product['name_product']; ?>"
                                        class="w-full h-48 object-cover" />
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900"><?php echo $product['name_product']; ?></h3>
                                        <p class="text-rose-700 font-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                                        <p class="text-gray-600 text-sm"> Còn lại: <?php echo $productDetail['amount']; ?> sản phẩm </p>
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
    <footer class="bg-gray-900 text-white text-center py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="mb-0">© 2025 META.vn - Mua sắm online chính hãng</p>
        </div>
    </footer>
    <!-- Hộp quà -->
    <?php if (isset($role) && $role != -1 && $role != 1 && $role != 2 && $role != 3): ?>
        <a href="?module=home&action=listVoucher&role=0&userId=<?php echo $userId; ?>" class="gift-icon">
            <img src="<?php echo _IMGG_; ?>git.png"
                alt="Gift">
        </a>

        <style>
            .gift-icon {
                position: fixed;
                bottom: 20px;
                /* cách đáy */
                left: 20px;
                /* cách trái */
                cursor: pointer;
                z-index: 9999;
                animation: bounce 1.5s infinite;
            }

            .gift-icon img {
                width: 100px;
                /* to hơn */
                height: 100px;
            }

            @keyframes bounce {

                0%,
                20%,
                50%,
                80%,
                100% {
                    transform: translateY(0);
                }

                40% {
                    transform: translateY(-20px);
                }

                60% {
                    transform: translateY(-10px);
                }
            }
        </style>
    <?php endif; ?>
</body>
<?php layout('footer'); ?>