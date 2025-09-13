<!-- bảng điều khiển -->
<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$filterAll = filter();
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
}
$role = -1;
$userId = -1;
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = ['pageTitle' => 'Tìm kiểm',];
    if ($role == -1) {
        layout('header_dashboard', $data);
    } elseif ($role == 1) {
        layout('header_admin', $data);
    } elseif ($role == 2) {
        layout('header_manager', $data);
    } elseif ($role == 3) {
        layout('header_empoloyee', $data);
    } else {
        if (!empty($filterAll['userId'])) {
            $userId = $filterAll['userId'];
            $cartCount = getCountCart($userId);
            if (!empty($filterAll['count'])) {
                $count = $filterAll['count'];
            } else {
                $count = 0;
            }
            $data = [
                'pageTitle' => 'Tìm kiếm',
                'count' => $cartCount,
                'userId' => $userId
            ];
            layout('header_custom', $data);
        }
    }
} else {
    $data = ['pageTitle' => 'Tìm kiểm',];
    layout('header_dashboard', $data);
}

?>

<body class="bg-gray-100">
    <!-- Banner -->
    <header class="relative bg-cover bg-bottom h-96 rounded-xl mx-auto max-w-7xl my-10" style="background-image: url('<?php echo _IMGB_; ?>/banner.jpg');">
        <div class="absolute inset-0 bg-black/30 flex flex-col items-center justify-center rounded-xl text-center px-6 lg:px-80">
            <h1 class="text-4xl sm:text-5xl font-bold text-white tracking-tight">Giảm Giá Đến 45% - Sản Phẩm Chính Hãng</h1>
            <p class="mt-4 text-lg text-white">Mua sắm trực tuyến tại META.vn - Giá tốt, Uy tín</p>
        </div>
    </header>

    <!-- Nội dung chính -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10 grid grid-cols-1 lg:grid-cols-4 gap-6">

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
                                <a href="?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>"
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

        <!-- Sản phẩm -->
        <main class="lg:col-span-3">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">
                <?php echo !empty($value) ? 'Sản phẩm bạn muốn tìm' : 'Sản phẩm nổi bật'; ?>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $listProduct = selectAll("SELECT * FROM products WHERE name_product LIKE '%$value%'");
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
                                <img src="<?php echo _IMGP_ . $product['thumbnail']; ?>" alt="<?php echo $product['name_product']; ?>" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo $product['name_product']; ?></h3>
                                    <p class="text-rose-700 font-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                                    <p class="text-gray-600 text-sm">Còn lại: <?php echo $productDetail['amount']; ?> sản phẩm</p>
                                </div>
                            </a>
                        </div>
                <?php
                    endif;
                endforeach; ?>
            </div>
        </main>

    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-6">
        <p class="mb-0">© 2024 META.vn - Mua sắm online chính hãng</p>
    </footer>

</body>
<?php layout('footer'); ?>