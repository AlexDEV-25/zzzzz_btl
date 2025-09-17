<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$filterAll = filter();
$errors = [];

$productId = $filterAll['productId'];
$product = selectOne("SELECT * FROM products WHERE id = $productId");
$detail = selectOne("SELECT * FROM products_detail WHERE id_product = $productId");
$detailId = $detail['id'];

$data = [
    'pageTitle' => $product['name_product'],
];
// thêm vào giỏ
if (isPost()) {
    if (!empty($filterAll['detailId'])) {
        $userId = $filterAll['userId'];
        $cartCount = getCountCart($userId);
        $cartId = selectOne("SELECT * FROM cart WHERE id_user =$userId")['id'];
        $detailId = $filterAll['detailId'];
        $rowProductCart = getCountRows("SELECT * FROM products_cart WHERE id_product_detail =$detailId");
        if ($rowProductCart > 0) {
            $count = $cartCount;
        } else {
            $count = $cartCount + 1;
            $dataUpdate = [
                'count' => $count,
            ];
            $condition = "id_user = $userId";
            update('cart', $dataUpdate, $condition);
        }
        if (!empty($filterAll['amount_buy'])) {
            $detailId = $filterAll['detailId'];
            $amount = $filterAll['amount_buy'];
            $rowProductCart = getCountRows("SELECT * FROM products_cart WHERE id_product_detail =$detailId");
            if ($rowProductCart > 0) {
                $ProductCart = selectOne("SELECT * FROM products_cart WHERE id_product_detail =$detailId");
                $dataUpdate = [
                    'amount_buy' => $ProductCart['amount_buy'] + $amount,
                ];
                $condition = "id_product_detail =$detailId";
                update('products_cart', $dataUpdate, $condition);
            } else {
                $dataInsertProductCart = [
                    'id_product_detail' =>  $detailId,
                    'amount_buy' => $amount,
                    'id_cart' => $cartId
                ];
                insert('products_cart', $dataInsertProductCart);
            }
        }
    }
}
$role = -1;
$userId = -1;
if (isset($filterAll['role'])) {
    $role = $filterAll['role'];
    $data = [
        'productId' => $productId,
        'pageTitle' => $product['name_product'],
    ];
    if ($role == -1) {
        layout('header_dashboard', $data);
    } else if ($role == 1) {
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
                'productId' => $productId,
                'pageTitle' => $product['name_product'],
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


<body class="luxury-gradient min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb Élégant -->
        <nav class="mb-8">
            <div class="flex items-center space-x-2 text-sm">
                <a href="?module=home&action=dashboard&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>"
                    class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                    Trang chủ
                </a>
                <span class="text-gray-400">•</span>
                <span class="text-gray-900 font-semibold luxury-text"><?php echo $product['name_product']; ?></span>
            </div>
        </nav>

        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-start">
            <!-- Section Image Produit -->
            <div class="relative">
                <div class="bg-white rounded-2xl product-shadow p-8 mb-6">
                    <div class="relative overflow-hidden rounded-xl">
                        <img src="<?php echo _IMGP_ . $detail['image']; ?>"
                            alt="<?php echo $product['name_product']; ?>"
                            class="w-full h-96 lg:h-[500px] object-cover transition-transform duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                </div>
            </div>

            <!-- Section Informations Produit -->
            <div class="lg:sticky lg:top-8">
                <div class="bg-white rounded-2xl product-shadow p-8 elegant-border">
                    <!-- Titre et Prix -->
                    <div class="mb-8">
                        <h1 class="text-3xl lg:text-4xl font-bold luxury-text mb-4 leading-tight">
                            <?php echo $product['name_product']; ?>
                        </h1>

                        <div class="flex items-baseline space-x-4 mb-6">
                            <span class="text-3xl font-bold price-accent">
                                <?php echo number_format($product['price'], 0, ',', '.') . ' đ'; ?>
                            </span>
                            <span class="text-lg text-gray-500 line-through">
                                <?php echo number_format($product['origin_price'], 0, ',', '.') . ' đ'; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Formulaire Commande -->
                    <form action="" method="POST" class="space-y-6">
                        <!-- Spécifications Produit -->
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-gray-600 font-medium text-sm uppercase tracking-wide">Màu sắc</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full border-2 border-gray-300 shadow-sm"
                                        style="background-color: <?php echo $detail['code_color']; ?>"></div>
                                    <span class="text-gray-700 font-medium"><?php echo $detail['code_color']; ?></span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label for="quantity" class="text-gray-600 font-medium text-sm uppercase tracking-wide">
                                    Số lượng
                                </label>
                                <input type="number" id="quantity" name="amount_buy"
                                    value="1" min="1" max="<?php echo $detail['amount'] ?>"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>

                        <!-- Détails Techniques -->
                        <div class="space-y-4 py-6 border-t border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Vật liệu</span>
                                <span class="text-gray-900 font-semibold luxury-text"><?php echo $product['material']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Kích thước</span>
                                <span class="text-gray-900 font-semibold luxury-text"><?php echo $detail['size']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Còn lại</span>
                                <span class="text-green-600 font-semibold"><?php echo $detail['amount']; ?> sản phẩm</span>
                            </div>
                        </div>

                        <input type="hidden" value="<?php echo $detailId; ?>" name="detailId">
                        <input type="hidden" value="<?php echo $userId; ?>" name="userId">
                        <input type="hidden" value="<?php echo $role; ?>" name="role">
                        <input type="hidden" value="<?php echo $productId; ?>" name="productId">

                        <?php if (isset($role) && $role != -1 && $role != 1 && $role != 2 && $role != 3): ?>
                            <button type="submit"
                                class="w-full py-4 px-8 btn-luxury text-white font-semibold rounded-xl text-lg">
                                Thêm vào giỏ hàng
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Section Description -->
        <div class="mt-16">
            <div class="bg-white rounded-2xl product-shadow p-8 elegant-border">
                <h2 class="text-2xl font-bold luxury-text mb-6 border-b border-gray-100 pb-4">
                    Mô tả sản phẩm
                </h2>
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 leading-relaxed text-lg"><?php echo $product['description']; ?></p>
                </div>
            </div>
        </div>

        <!-- Section Évaluations -->
        <div class="mt-16">
            <div class="bg-white rounded-2xl product-shadow p-8 elegant-border">
                <h2 class="text-2xl font-bold luxury-text mb-8 border-b border-gray-100 pb-4">
                    Đánh giá sản phẩm
                </h2>

                <!-- Formulaire d'évaluation -->
                <?php if (!empty($userId)): ?>
                    <?php if (!empty($filterAll['billId'])): ?>
                        <div class="review-card rounded-xl p-6 mb-8">
                            <form action="?module=reviews&action=review" method="POST" class="space-y-4">
                                <input type="hidden" name="productId" value="<?php echo $productId; ?>">
                                <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                                <input type="hidden" name="role" value="<?php echo $role; ?>">

                                <div>
                                    <label for="content" class="block text-sm font-semibold text-gray-800 mb-3">
                                        Chia sẻ trải nghiệm của bạn:
                                    </label>
                                    <textarea id="content" name="content" rows="4"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200"
                                        placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm..." required></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">Đánh giá:</label>
                                    <div class="flex space-x-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" name="stars" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" class="hidden" required>
                                            <label for="star<?php echo $i; ?>" class="text-2xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors duration-200">★</label>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="px-6 py-3 btn-luxury text-white rounded-lg font-medium hover:shadow-lg transition-all duration-200">
                                    Gửi đánh giá
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="review-card rounded-xl p-6 mb-8">
                            <p class="text-gray-600 italic">Bạn cần mua hàng để có thể đánh giá sản phẩm.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="review-card rounded-xl p-6 mb-8">
                        <p class="text-gray-600">
                            Bạn cần <a href="?module=auth&action=login" class="text-gray-900 font-semibold hover:underline">đăng nhập</a> để đánh giá sản phẩm.
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Liste des évaluations -->
                <div class="space-y-6">
                    <?php
                    $listReviews = selectAll("SELECT * FROM reviews WHERE id_product = $productId");
                    if (!empty($listReviews)): ?>
                        <?php foreach ($listReviews as $review):
                            $userId = selectOne("SELECT * FROM reviews")['id_user'];
                            $username = selectOne("SELECT * FROM users WHERE id = $userId")['fullname'];
                        ?>
                            <div class="review-card rounded-xl p-6 border-l-4 border-gray-900">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="font-bold text-gray-900 luxury-text"><?php echo $username; ?></span>
                                    <div class="flex text-yellow-400 text-lg">
                                        <?php echo str_repeat('★', $review['stars']) . str_repeat('☆', 5 - $review['stars']); ?>
                                    </div>
                                </div>
                                <p class="text-gray-700 leading-relaxed"><?php echo $review['content']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="review-card rounded-xl p-8 text-center">
                            <p class="text-gray-500 italic">Chưa có đánh giá nào cho sản phẩm này.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Produits Similaires -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold luxury-text mb-8">Sản phẩm tương tự</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php
                $categoryId = $product['id_category'];
                $listProduct = selectAll("SELECT * FROM products WHERE id_category = $categoryId AND id <> $productId");
                foreach ($listProduct as $productItem):
                    $categorieId = $productItem['id_category'];
                    $category = selectOne("SELECT is_deleted FROM categories WHERE id = $categorieId");
                    $isDelete = $category['is_deleted'];
                    if ($productItem['is_deleted'] != 1  && $isDelete != 1):
                        $productId = $productItem['id'];
                ?>
                        <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&role=<?php echo $role; ?>&userId=<?php echo $userId; ?>"
                            class="group bg-white rounded-xl product-shadow overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative overflow-hidden">
                                <img src="<?php echo _IMGP_ . $productItem['thumbnail']; ?>"
                                    alt="<?php echo $productItem['thumbnail']; ?>"
                                    class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="p-6">
                                <h3 class="font-semibold text-gray-900 luxury-text line-clamp-2 mb-3 text-sm lg:text-base">
                                    <?php echo $productItem['name_product']; ?>
                                </h3>
                                <div class="space-y-2">
                                    <p class="text-lg font-bold price-accent">
                                        <?php echo number_format($productItem['price'], 0, ',', '.'); ?> đ
                                    </p>
                                    <p class="text-sm text-green-600 font-medium">
                                        Còn lại: <?php echo $detail['amount']; ?> sản phẩm
                                    </p>
                                </div>
                            </div>
                        </a>
                <?php
                    endif;
                endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Hộp quà -->
    <?php if (isset($role) && $role != -1 && $role != 1 && $role != 2 && $role != 3): ?>
        <a href="?module=home&action=listVoucher&userId=<?php echo $userId; ?>"
            class="gift-icon fixed bottom-6 left-6 w-16 h-16 cursor-pointer z-50 animate-bounce">
            <img src="<?php echo _IMGG_; ?>git.png"
                alt="Gift"
                class="w-full h-full object-contain drop-shadow-lg">
        </a>
    <?php endif; ?>

    <script>
        // Gestion des étoiles pour l'évaluation
        document.querySelectorAll('input[name="stars"]').forEach(input => {
            input.addEventListener('change', function() {
                const value = parseInt(this.value);
                const labels = document.querySelectorAll('label[for^="star"]');

                labels.forEach((label, index) => {
                    if (index < value) {
                        label.classList.add('text-yellow-400');
                        label.classList.remove('text-gray-300');
                    } else {
                        label.classList.remove('text-yellow-400');
                        label.classList.add('text-gray-300');
                    }
                });
            });
        });
    </script>
</body>
<?php layout('footer'); ?>