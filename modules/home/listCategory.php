<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$errors = [];
$filterAll = filter();
$categoryId = $filterAll['categoryId'];
$category = selectOne("SELECT * FROM categories WHERE id = $categoryId ");
$listProduct = selectAll("SELECT * FROM products");
$role = -1;
$userId = -1;
$userStatus = -1;

if (isGet()) {
    if (!empty($filterAll['type'])) {
        if ($filterAll['type'] == 'new') {
            $listProduct = selectAll("SELECT * FROM products WHERE id_category = $categoryId ORDER BY created_at DESC LIMIT 10");
        } else if ($filterAll['type'] == 'hot') {
            $listProduct = selectAll("SELECT * FROM products WHERE id_category = $categoryId  ORDER BY sold DESC LIMIT 10");
        }
        if ($filterAll['type'] == 'lowhight') {
            $listProduct = selectAll("SELECT * FROM products WHERE id_category = $categoryId ORDER BY price ASC");
        }
        if ($filterAll['type'] == 'hightlow') {
            $listProduct = selectAll("SELECT * FROM products WHERE id_category = $categoryId ORDER BY price DESC");
        }
    } else {
        $listProduct = selectAll("SELECT * FROM products WHERE id_category = $categoryId");
    }
}
if (isPost()) {
    $role = $filterAll['role'];
    $userId = $filterAll['userId'];
    $categoryId = $filterAll['categoryId'];
    if (!empty($filterAll['start']) && !empty($filterAll['end'])) {
        if ($filterAll['end'] < $filterAll['start']) {
            $errors['range']['input'] = 'Nhập khoảng không đúng';
        }
        if ($filterAll['end'] < 0 || $filterAll['start'] < 0) {
            $errors['range']['lower'] = 'Không được nhập sổ nhỏ hơn 0';
        }
    }
    if (empty($errors)) {
        if (empty($filterAll['material']) && empty($filterAll['start']) && empty($filterAll['end'])) {
            $listProduct = selectAll("SELECT * FROM products");
        } else {
            $sql = "SELECT * FROM products WHERE id_category = $categoryId ";
            if (!empty($filterAll['material'])) {
                $sql .= ' AND (';
                foreach ($filterAll['material'] as $key => $item) {
                    $sql .= " material = '$item' OR";
                }
                $sql = trim($sql, 'OR');
                $sql .= ')';
            }
            if (!empty($filterAll['start'])) {
                $start = $filterAll['start'];
                $sql .= " AND (price >= $start) ";
            }
            if (!empty($filterAll['end'])) {
                $end = $filterAll['end'];
                $sql .= " AND (price <= $end) ";
            }
            $listProduct = selectAll("$sql");
        }
    } else {
        setFlashData('errors', $errors);
    }
    $errors = getFlashData('errors');
}
$data = ['pageTitle' => 'Trang danh mục',];
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = [
        'categoryId' => $filterAll['categoryId'],
        'pageTitle' => 'Trang danh mục',
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
            $user = selectOne("SELECT * FROM users WHERE id = $userId");
            $userStatus = $user['status'];
            $cartCount = getCountCart($userId);
            if (!empty($filterAll['count'])) {
                $count = $filterAll['count'];
            } else {
                $count = 0;
            }
            $data = [
                'categoryId' => $categoryId,
                'pageTitle' => 'Trang danh mục',
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

<body class="bg-gray-50 font-body">
    <!-- Elegant Category Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (!empty($category['image'])): ?>
            <div class="relative h-[400px] rounded-3xl overflow-hidden shadow-xl">
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('<?php echo _IMGC_ . $category['image']; ?>')"></div>
                <div class="category-hero absolute inset-0 flex items-center justify-center">
                    <div class="text-center max-w-3xl px-8">
                        <nav class="breadcrumb mb-4">
                            <a href="<?php echo _WEB_HOST; ?>">Trang chủ</a> /
                            <span class="text-white/80"><?php echo $category['name_category']; ?></span>
                        </nav>
                        <h1 class="font-display text-4xl lg:text-5xl font-semibold text-white mb-4 tracking-tight">
                            <?php echo $category['name_category']; ?>
                        </h1>
                        <p class="text-xl text-white/90 font-light leading-relaxed">
                            Khám phá bộ sưu tập <?php echo strtolower($category['name_category']); ?> cao cấp,
                            thiết kế tinh tế và chất lượng vượt trội
                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Minimalist Fallback Banner -->
            <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-3xl p-12 text-center">
                <nav class="breadcrumb mb-4">
                    <a href="<?php echo _WEB_HOST; ?>">Trang chủ</a> /
                    <span><?php echo $category['name_category']; ?></span>
                </nav>
                <h1 class="font-display text-4xl font-semibold text-gray-900 mb-4">
                    <?php echo $category['name_category']; ?>
                </h1>
                <p class="text-gray-600 text-lg">
                    Bộ sưu tập nội thất cao cấp - Giảm giá đến 45%
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">

            <!-- Elegant Sidebar Filters -->
            <aside class="lg:col-span-1">
                <div class="sticky top-8">
                    <div class="filter-card rounded-2xl p-8">
                        <h3 class="font-display text-xl font-semibold text-gray-900 mb-6">
                            Bộ lọc sản phẩm
                        </h3>

                        <!-- Quick Filters -->
                        <div class="mb-8">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Sắp xếp</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a class="filter-link block py-2 text-sm font-medium"
                                        href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=new&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">
                                        Mới nhất
                                    </a>
                                </li>
                                <li>
                                    <a class="filter-link block py-2 text-sm font-medium"
                                        href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=hot&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">
                                        Bán chạy nhất
                                    </a>
                                </li>
                                <li>
                                    <a class="filter-link block py-2 text-sm font-medium"
                                        href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=lowhight&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">
                                        Giá thấp đến cao
                                    </a>
                                </li>
                                <li>
                                    <a class="filter-link block py-2 text-sm font-medium"
                                        href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=hightlow&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">
                                        Giá cao đến thấp
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="divider-elegant"></div>

                        <!-- Advanced Filters Form -->
                        <form id="filter" method="POST" class="space-y-6">
                            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                            <input type="hidden" name="role" value="<?php echo $role; ?>">
                            <input type="hidden" name="categoryId" value="<?php echo $categoryId; ?>">

                            <!-- Price Range -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Khoảng giá</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <input name="start" type="number" min="0" placeholder="Từ"
                                        class="input-elegant w-full px-3 py-2 text-sm rounded-lg">
                                    <input name="end" type="number" min="0" placeholder="Đến"
                                        class="input-elegant w-full px-3 py-2 text-sm rounded-lg">
                                </div>
                                <?php echo form_error($errors, 'range', '<p class="text-red-500 text-xs mt-2">', '</p>'); ?>
                            </div>

                            <!-- Materials Filter -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Vật liệu</h4>
                                <div class="space-y-3 max-h-48 overflow-auto">
                                    <?php
                                    $listProductFull = selectAll("SELECT DISTINCT material FROM products WHERE id_category = $categoryId");
                                    foreach ($listProductFull as $product):
                                        $productMaterial = $product['material'];
                                    ?>
                                        <label class="flex items-center gap-3 text-sm cursor-pointer">
                                            <input type="checkbox" name="material[]"
                                                value="<?php echo htmlspecialchars($productMaterial); ?>"
                                                class="checkbox-elegant h-4 w-4">
                                            <span class="text-gray-700"><?php echo htmlspecialchars($productMaterial); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Filter Button -->
                            <button type="submit"
                                class="btn-filter w-full py-3 text-sm font-medium rounded-lg">
                                Áp dụng bộ lọc
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <main class="lg:col-span-3">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-display text-2xl font-semibold text-gray-900 mb-2">
                                <?php echo !empty($title) ? $title : 'Sản phẩm nổi bật'; ?>
                            </h2>
                            <p class="text-gray-600">
                                <?php echo count($listProduct); ?> sản phẩm được tìm thấy
                            </p>
                        </div>
                    </div>
                    <div class="divider-elegant"></div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                    <?php if (!empty($listProduct)): ?>
                        <?php
                        $animationIndex = 1;
                        foreach ($listProduct as $product):
                            $categorieId = $product['id_category'];
                            $category = selectOne("SELECT is_deleted FROM categories WHERE id = $categorieId");
                            $isDelete = $category['is_deleted'];
                            if ($product['is_deleted'] != 1 && $isDelete != 1):
                                $productId = $product['id'];
                                $productDetail = selectOne("SELECT * FROM products_detail WHERE id_product = $productId");
                                $thumb = !empty($product['thumbnail'])
                                    ? _IMGP_ . $product['thumbnail']
                                    : _IMGP_ . 'placeholder.png';
                        ?>
                                <article class="product-card-minimal rounded-2xl overflow-hidden group animate-slide-up stagger-<?php echo min($animationIndex, 6); ?>">
                                    <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>"
                                        class="block">

                                        <div class="relative overflow-hidden bg-gray-100">
                                            <img src="<?php echo $thumb; ?>"
                                                alt="<?php echo htmlspecialchars($product['name_product']); ?>"
                                                class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">

                                            <!-- Sale Badge -->
                                            <div class="absolute top-4 right-4">
                                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                                    Giảm <?php echo rand(15, 40); ?>%
                                                </span>
                                            </div>

                                            <!-- Quick View Overlay -->
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center">
                                                <span class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                                                    Xem chi tiết
                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-6">
                                            <h3 class="font-display text-lg font-medium text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                <?php echo htmlspecialchars($product['name_product']); ?>
                                            </h3>

                                            <div class="mb-4">
                                                <span class="price-elegant text-xl font-semibold">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
                                                </span>
                                                <span class="text-gray-400 line-through ml-2 text-sm">
                                                    <?php echo number_format($product['price'] * 1.3, 0, ',', '.'); ?>₫
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="stock-info">
                                                    Còn <?php echo $productDetail['amount']; ?> sản phẩm
                                                </span>
                                                <div class="text-yellow-400 text-sm">
                                                    ★★★★★
                                                </div>
                                            </div>

                                            <!-- Product Features -->
                                            <div class="mt-4 space-y-1 text-xs text-gray-500">
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                                    <span>Miễn phí vận chuyển</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                                    <span>Bảo hành 24 tháng</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                        <?php
                                $animationIndex++;
                            endif;
                        endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-400 text-3xl">📦</span>
                            </div>
                            <h3 class="font-display text-xl font-medium text-gray-900 mb-2">
                                Không tìm thấy sản phẩm
                            </h3>
                            <p class="text-gray-500">
                                Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm từ khóa khác
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination (if needed) -->
                <?php if (!empty($products_page)): ?>
                    <div class="mt-12 flex items-center justify-between">
                        <p class="text-sm text-gray-700">
                            Trang
                            <span class="font-medium"><?php echo $products_page['current_page']; ?></span>
                            /
                            <span class="font-medium"><?php echo $products_page['page']; ?></span>
                        </p>
                        <nav class="isolate inline-flex -space-x-px rounded-lg shadow-sm" aria-label="Pagination">
                            <a href="?act=products&id=<?php echo $_GET['id']; ?>&page=<?php echo max(1, $products_page['current_page'] - 1); ?>"
                                class="relative inline-flex items-center px-3 py-2 text-gray-400 bg-white ring-1 ring-inset ring-gray-300 rounded-l-lg hover:bg-gray-50 <?php echo ($products_page['current_page'] == 1) ? 'pointer-events-none opacity-50' : ''; ?>">
                                ← Trước
                            </a>
                            <?php
                            if (!empty($products_page)) {
                                for ($i = 1; $i <= $products_page['page']; $i++) {
                                    if (($products_page['current_page'] - 3 < $i && $i <= $products_page['current_page']) || ($i > $products_page['current_page'] && $i < $products_page['current_page'] + 3)) {
                                        $isActive = ($products_page['current_page'] == $i);
                                        $base = "relative inline-flex items-center px-4 py-2 text-sm font-medium ";
                                        $classes = $isActive ? "z-10 bg-blue-600 text-white" : "text-gray-900 bg-white ring-1 ring-inset ring-gray-300 hover:bg-gray-50";
                                        echo '<a href="?act=products&id=' . $_GET['id'] . '&page=' . $i . '" class="' . $base . $classes . '">' . $i . '</a>';
                                    }
                                }
                            }
                            ?>
                            <a href="?act=products&id=<?php echo $_GET['id']; ?>&page=<?php echo min($products_page['page'], $products_page['current_page'] + 1); ?>"
                                class="relative inline-flex items-center px-3 py-2 text-gray-400 bg-white ring-1 ring-inset ring-gray-300 rounded-r-lg hover:bg-gray-50 <?php echo ($products_page['current_page'] == $products_page['page']) ? 'pointer-events-none opacity-50' : ''; ?>">
                                Sau →
                            </a>
                        </nav>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Hộp quà -->
    <?php if (isset($role) && $role != -1 && $role != 1 && $role != 2 && $role != 3 &&  $userStatus == 1): ?>
        <a href="?module=home&action=listVoucher&userId=<?php echo $userId; ?>"
            class="gift-icon fixed bottom-6 left-6 w-24 h-24 cursor-pointer z-50 animate-bounce">
            <img src="<?php echo _IMGG_; ?>git.png"
                alt="Gift"
                class="w-full h-full object-contain drop-shadow-lg">
        </a>
    <?php endif; ?>

    <!-- Elegant Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h3 class="font-display text-xl font-semibold text-white mb-4">META.vn</h3>
                <p class="text-gray-400 mb-6">Nội thất cao cấp cho ngôi nhà hoàn hảo</p>
                <div class="text-sm">
                    © <?php echo date('Y'); ?> <span class="text-white font-medium">META.vn</span> - Mua sắm trực tuyến chính hãng
                </div>
            </div>
        </div>
    </footer>
</body>
<?php layout('footer'); ?>