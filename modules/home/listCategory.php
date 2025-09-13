<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$filterAll = filter();
$categoryId = $filterAll['categoryId'];
$category = selectOne("SELECT * FROM categories WHERE id = $categoryId ");
$data = ['pageTitle' => 'Trang danh mục',];
$role = -1;
$userId = -1;
$cartCount = -1;
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
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
    $title = 'Sản phẩm bạn muốn tìm';
    if (getCountRows("SELECT * FROM products WHERE name_product LIKE '%$value%' OR DESCRIPTION LIKE '%$value%'") > 0) {
        $listProduct = selectAll("SELECT * FROM products WHERE name_product LIKE '%$value%' OR DESCRIPTION LIKE '%$value%'");
    } else {
        $title = 'Sản phẩm bạn muốn tìm';
        setFlashData('smg', "sản phẩm bạn muốn tìm không có đây là các sản phẩm khác");
        setFlashData('smg_type', "danger");
        $smg = getFlashData('smg');
        $smg_type = getFlashData('smg_type');
        $listProduct = selectAll("SELECT * FROM products");
    }
} else {
    $title = 'Sản phẩm nổi bật';
    $listProduct = selectAll("SELECT * FROM products");
}
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

$errors = [];
if (isPost()) {
    $categoryId = $filterAll['categoryId'];
    // validate
    if (!empty($filterAll['start']) && !empty($filterAll['end'])) {
        if ($filterAll['end'] < $filterAll['start']) {
            $errors['range']['input'] = 'nhập khoảng không đúng';
        }
        if ($filterAll['end'] < 0 || $filterAll['start'] < 0) {
            $errors['range']['lower'] = 'không được nhập sổ nhỏ hơn 0';
        }
    }
    if (empty($errors)) {
        if (empty($filterAll['material']) && empty($filterAll['start']) && empty($filterAll['end'])) {
            if (!empty($filterAll['search'])) {
                $value = $filterAll['search'];
                $title = 'Sản phẩm bạn muốn tìm';
                if (getCountRows("SELECT * FROM products WHERE name_product LIKE '%$value%' OR DESCRIPTION LIKE '%$value%'") > 0) {
                    $listProduct = selectAll("SELECT * FROM products WHERE name_product LIKE '%$value%' OR DESCRIPTION LIKE '%$value%'");
                } else {
                    $title = 'Sản phẩm bạn muốn tìm';
                    setFlashData('smg', "sản phẩm bạn muốn tìm không có đây là các sản phẩm khác");
                    setFlashData('smg_type', "danger");
                    $smg = getFlashData('smg');
                    $smg_type = getFlashData('smg_type');
                    $listProduct = selectAll("SELECT * FROM products");
                }
            } else {
                $title = 'Sản phẩm nổi bật';
                $listProduct = selectAll("SELECT * FROM products");
            }
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
?>

<head>
    <style>
        /* Small extras to match feel */
        .product-card img {
            height: 220px;
            width: 100%;
            object-fit: cover;
        }

        /* For mobile filter overlay z-index control */
        .filter-overlay {
            z-index: 60;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Banner / Hero (use category image if exists) -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <?php if (!empty($category['image'])): ?>
            <div class="bg-cover bg-center h-[420px] rounded-xl mt-4 overflow-hidden shadow-md"
                style="background-image: url('<?php echo _IMGC_ . $category['image']; ?>')">
                <div class="bg-black bg-opacity-25 h-full w-full flex items-center justify-center">
                    <div class="px-6 sm:px-12 text-center">
                        <h1 class="text-3xl sm:text-4xl font-bold text-white drop-shadow">
                            <?php echo $category['name_category']; ?>
                        </h1>
                        <p class="mt-2 text-white/90 text-center">
                            Danh mục <?php echo $category['name_category']; ?> — các mẫu nổi bật, chất lượng.
                        </p>
                    </div>
                </div>
            </div>


        <?php else: ?>
            <!-- Fallback promo banner -->
            <div class="bg-red-600 text-white rounded-xl mt-4 p-6 shadow-md">
                <h2 class="text-2xl font-bold">Giảm Giá Đến 45% - Sản Phẩm Chính Hãng</h2>
                <p class="mt-1">Mua sắm trực tuyến tại Nhà Xinh - Giá tốt, Uy tín</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content area -->
    <div class="mt-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-x-8 gap-y-10">
            <!-- Sidebar filters (desktop) -->
            <aside class="hidden lg:block">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh mục</h3>
                    <ul class="space-y-3 text-sm text-gray-700 mb-6">
                        <li><a class="hover:text-indigo-600" href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=new&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">Mới nhất</a></li>
                        <li><a class="hover:text-indigo-600" href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=hot&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">Bán chạy</a></li>
                        <li><a class="hover:text-indigo-600" href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=lowhight&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">Giá: thấp → cao</a></li>
                        <li><a class="hover:text-indigo-600" href="<?php echo _WEB_HOST; ?>?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&type=hightlow&userId=<?php echo $userId; ?>&role=<?php echo $role; ?>">Giá: cao → thấp</a></li>
                    </ul>

                    <form id="filter" method="POST" class="space-y-4">
                        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                        <input type="hidden" name="role" value="<?php echo $role; ?>">
                        <input type="hidden" name="categoryId" value="<?php echo $categoryId; ?>">

                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Khoảng giá</h4>
                            <div class="flex items-center gap-3">
                                <input name="start" type="number" min="0" placeholder="Từ" class="w-1/2 bg-gray-50 border rounded-md px-3 py-2 text-sm">
                                <input name="end" type="number" min="0" placeholder="Đến" class="w-1/2 bg-gray-50 border rounded-md px-3 py-2 text-sm">
                            </div>
                            <?php echo form_error($errors, 'range', '<p class="text-red-600 text-xs mt-2">', '</p>'); ?>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Vật liệu</h4>
                            <div class="space-y-2 max-h-48 overflow-auto pr-2">
                                <?php
                                $listProductFull = selectAll("SELECT DISTINCT material FROM products WHERE id_category = $categoryId");
                                foreach ($listProductFull as $product):
                                    $productMaterial = $product['material'];
                                ?>
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="material[]" value="<?php echo htmlspecialchars($productMaterial); ?>" class="h-4 w-4 rounded border-gray-300 text-indigo-600">
                                        <span class="text-gray-700"><?php echo htmlspecialchars($productMaterial); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                Lọc
                            </button>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Products area -->
            <section class="lg:col-span-3">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900"><?php echo !empty($title) ? $title : 'Sản phẩm nổi bật'; ?></h2>
                    <div class="hidden sm:flex items-center text-sm text-gray-600 gap-4">
                        <span>Hiển thị <?php echo isset($count) && $count !== '' ? $count : 'tất cả'; ?></span>
                    </div>
                </div>

                <!-- grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php if (!empty($listProduct)): ?>
                        <?php foreach ($listProduct as $product):
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
                                <article class="bg-white rounded-lg shadow-sm hover:shadow-md overflow-hidden">
                                    <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>">
                                        <div class="h-56 bg-gray-100 overflow-hidden">
                                            <img src="<?php echo $thumb; ?>" alt="<?php echo htmlspecialchars($product['name_product']); ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-4">
                                            <h3 class="text-base font-medium text-gray-900 mb-2"><?php echo htmlspecialchars($product['name_product']); ?></h3>
                                            <p class="text-indigo-600 font-semibold"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                                            <p class="text-gray-600 text-sm"> Còn lại: <?php echo $productDetail['amount']; ?> sản phẩm </p>
                                        </div>
                                    </a>
                                </article>
                        <?php
                            endif;
                        endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500">Không có sản phẩm nào.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- (Optional) pagination area if needed -->
                <?php if (!empty($products_page)): ?>
                    <div class="mt-8 flex items-center justify-between">
                        <p class="text-sm text-gray-700">
                            Hiển thị
                            <span class="font-medium"><?php echo $products_page['current_page']; ?></span>
                            /
                            <span class="font-medium"><?php echo $products_page['page']; ?></span>
                            trang
                        </p>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <!-- Prev -->
                            <a href="?act=products&id=<?php echo $_GET['id']; ?>&page=<?php echo max(1, $products_page['current_page'] - 1); ?>" class="relative inline-flex items-center px-2 py-2 text-gray-400 bg-white ring-1 ring-inset ring-gray-300 rounded-l-md hover:bg-gray-50 <?php echo ($products_page['current_page'] == 1) ? 'pointer-events-none opacity-50' : ''; ?>">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php
                            if (!empty($products_page)) {
                                for ($i = 1; $i <= $products_page['page']; $i++) {
                                    if (($products_page['current_page'] - 3 < $i && $i <= $products_page['current_page']) || ($i > $products_page['current_page'] && $i < $products_page['current_page'] + 3)) {
                                        $isActive = ($products_page['current_page'] == $i);
                                        $base = "relative inline-flex items-center px-4 py-2 text-sm font-semibold ";
                                        $classes = $isActive ? "z-10 bg-indigo-600 text-white" : "text-gray-900 bg-white ring-1 ring-inset ring-gray-300 hover:bg-gray-50";
                                        echo '<a href="?act=products&id=' . $_GET['id'] . '&page=' . $i . '" class="' . $base . $classes . '">' . $i . '</a>';
                                    }
                                }
                            }
                            ?>
                            <!-- Next -->
                            <a href="?act=products&id=<?php echo $_GET['id']; ?>&page=<?php echo min($products_page['page'], $products_page['current_page'] + 1); ?>" class="relative inline-flex items-center px-2 py-2 text-gray-400 bg-white ring-1 ring-inset ring-gray-300 rounded-r-md hover:bg-gray-50 <?php echo ($products_page['current_page'] == $products_page['page']) ? 'pointer-events-none opacity-50' : ''; ?>">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                <?php endif; ?>

            </section>
        </div>
    </div>
    <!-- Simple Footer (visible) -->
    <footer class="mt-12 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm">
            © <?php echo date('Y'); ?> Nhà Xinh — Mua sắm trực tuyến chính hãng
        </div>
    </footer>

    <!-- Footer layouts from your system (kept as in original) -->
    <?php
    if (isset($filterAll['role'])) {
        if ($filterAll['role'] == 1) {
            layout('footer_admin', $data);
        } else if ($filterAll['role'] == 2) {
            layout('footer_manager', $data);
        } else if ($filterAll['role'] == 3) {
            layout('footer_employee', $data);
        } else {
            layout('footer_custom', $data);
        }
    } else {
        layout('footer_dashboard', $data);
    }
    ?>

    <!-- Small JS for mobile filter toggle -->
    <script>
        document.getElementById('open-filter-mobile')?.addEventListener('click', function() {
            document.getElementById('mobile-filter')?.classList.remove('hidden');
        });
        document.getElementById('close-filter-mobile')?.addEventListener('click', function() {
            document.getElementById('mobile-filter')?.classList.add('hidden');
        });
        // close overlay when clicking background
        document.querySelector('#mobile-filter > .absolute')?.addEventListener('click', function() {
            document.getElementById('mobile-filter')?.classList.add('hidden');
        });
    </script>
</body>

<?php
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = [
        'role' => $role,
    ];
    if ($role == 1) {
        layout('header_admin', $data);
    } elseif ($role == 2) {
        layout('header_manager', $data);
    } elseif ($role == 3) {
        layout('header_employee', $data);
    } elseif ($role == 0) {
        layout('header_custom', $data);
    } else {
        layout('header_dashboard', $data);
    }
}
?>