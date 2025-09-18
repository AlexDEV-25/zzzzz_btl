<!-- bảng điều khiển -->
<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$filterAll = filter();
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
} else {
    $value = '';
}
$role = -1;
$userId = -1;
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = ['pageTitle' => 'Tìm kiếm',];
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
    $data = ['pageTitle' => 'Tìm kiếm',];
    layout('header_dashboard', $data);
}
?>

<body class="bg-white font-body">
    <!-- Elegant Banner -->
    <div class="my-8 max-w-7xl mx-auto relative rounded-3xl overflow-hidden shadow-elegant">
        <div class="h-[450px] bg-cover bg-center bg-gray-200"
            style="background-image: url('<?php echo _IMGB_; ?>banner.jpg');">
            <div class="hero-bg h-full flex items-center justify-center px-8">
                <div class="text-center max-w-4xl">
                    <h1 class="font-display text-5xl lg:text-6xl font-semibold text-white mb-6 tracking-tight">
                        <?php echo !empty($value) ? 'Kết quả tìm kiếm' : 'Nội Thất Cao Cấp'; ?>
                        <span class="block text-3xl lg:text-4xl font-normal mt-2 text-white/90">
                            Giảm giá đến 45%
                        </span>
                    </h1>
                    <p class="text-xl text-white/80 mb-8 font-light leading-relaxed">
                        Khám phá bộ sưu tập nội thất hiện đại tại
                        <span class="font-semibold">META.vn</span> - Chất lượng, Thẩm mỹ, Bền vững
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#products"
                            class="btn-primary px-8 py-3 rounded-full text-lg font-medium inline-flex items-center justify-center">
                            Khám phá ngay
                        </a>
                        <a href="#products"
                            class="btn-outline px-8 py-3 rounded-full text-lg font-medium inline-flex items-center justify-center">
                            Xem bộ sưu tập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">

            <!-- Minimalist Categories -->
            <aside class="lg:col-span-1">
                <div class="sticky top-8">
                    <h2 class="font-display text-2xl font-semibold text-gray-900 mb-8">
                        Danh mục sản phẩm
                    </h2>

                    <nav class="bg-white rounded-2xl shadow-elegant p-8">
                        <ul class="space-y-1">
                            <?php
                            $listCategory = selectAll("SELECT * FROM categories");
                            foreach ($listCategory as $category):
                                if ($category['is_deleted'] != 1):
                                    $categoryId = $category['id'];
                            ?>
                                    <li>
                                        <a href="?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>"
                                            class="category-link block py-3 text-base font-medium">
                                            <?php echo $category['name_category']; ?>
                                        </a>
                                    </li>
                            <?php
                                endif;
                            endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Elegant Products Grid -->
            <main class="lg:col-span-3">
                <div class="mb-12">
                    <h2 class="font-display text-3xl font-semibold text-gray-900 mb-3">
                        <?php echo !empty($value) ? 'Sản phẩm bạn muốn tìm' : 'Sản phẩm nổi bật'; ?>
                    </h2>
                    <p class="text-elegant text-lg">
                        <?php echo !empty($value) ? 'Danh sách kết quả phù hợp với từ khóa bạn nhập' : 'Tuyển chọn những món nội thất cao cấp nhất'; ?>
                    </p>
                    <div class="divider"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
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
                            <article class="card-minimal rounded-2xl overflow-hidden group">
                                <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>"
                                    class="block">

                                    <div class="relative overflow-hidden bg-gray-50">
                                        <img src="<?php echo _IMGP_ . $product['thumbnail']; ?>"
                                            alt="<?php echo $product['name_product']; ?>"
                                            class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-105" />

                                        <!-- Sale Badge -->
                                        <div class="absolute top-4 right-4">
                                            <span class="sale-badge">
                                                Giảm <?php echo ceil(100 - ($product['price'] * 100) / $product['origin_price']); ?>%
                                            </span>
                                        </div>

                                        <!-- Quick View Overlay -->
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center">
                                            <button class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 px-6 py-2 rounded-full font-medium transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                                                Xem chi tiết
                                            </button>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="font-display text-xl font-medium text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            <?php echo $product['name_product']; ?>
                                        </h3>

                                        <div class="mb-4">
                                            <span class="price-text text-2xl font-semibold">
                                                <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
                                            </span>
                                            <span class="text-gray-400 line-through ml-2 text-sm">
                                                <?php echo number_format($product['origin_price'], 0, ',', '.'); ?>₫
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between mb-6">
                                            <span class="minimal-badge">
                                                Còn <?php echo $productDetail['amount']; ?> sản phẩm
                                            </span>
                                            <div class="text-gray-400 text-sm">
                                                ★★★★★ (<?php
                                                        $start = getCountRows("SELECT * FROM reviews WHERE id_product = $productId");
                                                        echo $start; ?>)
                                            </div>
                                        </div>

                                        <div class="space-y-2 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2"></span>
                                                Miễn phí vận chuyển
                                            </div>
                                            <div class="flex items-center">
                                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2"></span>
                                                Bảo hành 24 tháng
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                    <?php
                        endif;
                    endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Elegant Footer -->
    <footer class="bg-gray-50 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h3 class="font-display text-2xl font-semibold text-gray-900 mb-4">
                    META.vn
                </h3>
                <p class="text-elegant text-lg mb-8 max-w-2xl mx-auto">
                    Nơi khởi nguồn cho không gian sống đẹp. Chúng tôi mang đến những sản phẩm nội thất cao cấp, thiết kế tinh tế và chất lượng bền vững.
                </p>

                <!-- Services -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-blue-600 text-xl">🚚</span>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1">Giao hàng miễn phí</h4>
                        <p class="text-sm text-gray-600">Đơn hàng từ 2 triệu</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-blue-600 text-xl">🔧</span>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1">Lắp đặt chuyên nghiệp</h4>
                        <p class="text-sm text-gray-600">Đội ngũ kỹ thuật giàu kinh nghiệm</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-blue-600 text-xl">🛡️</span>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1">Bảo hành dài hạn</h4>
                        <p class="text-sm text-gray-600">Lên đến 24 tháng</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-blue-600 text-xl">💬</span>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1">Tư vấn 24/7</h4>
                        <p class="text-sm text-gray-600">Hỗ trợ khách hàng tận tâm</p>
                    </div>
                </div>

                <div class="divider"></div>

                <p class="text-gray-600">
                    © 2025 <span class="font-semibold text-gray-900">META.vn</span> - Nội thất cao cấp cho ngôi nhà của bạn
                </p>
            </div>
        </div>
    </footer>

    <!-- Hộp quà -->
    <?php if (isset($role) && $role != -1 && $role != 1 && $role != 2 && $role != 3): ?>
        <a href="?module=home&action=listVoucher&userId=<?php echo $userId; ?>"
            class="gift-icon fixed bottom-6 left-6 w-24 h-24 cursor-pointer z-50 animate-bounce">
            <img src="<?php echo _IMGG_; ?>git.png"
                alt="Gift"
                class="w-full h-full object-contain drop-shadow-lg">
        </a>
    <?php endif; ?>
</body>

<?php layout('footer'); ?>