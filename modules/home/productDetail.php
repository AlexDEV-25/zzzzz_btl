<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

$filterAll = filter();
$errors = [];


$productId = $filterAll['productId'];

$product = selectOne("SELECT * FROM products WHERE id = $productId");
$detail = selectOne("SELECT * FROM products_detail WHERE id_product = $productId");

if (!empty($detail['id'])) {
    $detailId = $detail['id'];
}



$data = [
    'productId' => $productId,
    'pageTitle' => $product['name_product']
];

if (!empty($filterAll['userId'])) {
    $userId = $filterAll['userId'];
    $cartCount = getCountCart($userId);
    if (!empty($filterAll['search'])) {
        $value = $filterAll['search'];
        redirect('?module=home&action=productsSearch&search=' . $value . '&userId=' . $userId);
    }
    if ($filterAll['userId'] == 1) {
        layout('header_admin', $data);
    } else {
        $cart = selectOne("SELECT * FROM cart WHERE id_user = $userId");
        $cartId = $cart['id'];

        if (isPost()) {
            if (!empty($filterAll['detailId'])) {
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
            }
        } else {
            $count = $cartCount;
        }
        $data = [
            'productId' => $productId,
            'pageTitle' => $product['name_product'],
            'count' => $count,
            'userId' => $userId
        ];
        layout('header_custom', $data);

        if (!empty($filterAll['detailId']) && !empty($filterAll['amount_buy'])) {
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
} else {
    $userId = '';
    if (!empty($filterAll['search'])) {
        $value = $filterAll['search'];
        redirect('?module=home&action=productsSearch&search=' . $value . '&userId=' . $userId);
    }
    layout('header_dashboard', $data);
}


?>

<!DOCTYPE html>
<html lang="vi">

<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-6">
            <a href="?module=home" class="hover:text-gray-700">Trang chủ</a> ›
            <span><?php echo $product['name_product']; ?></span>
        </nav>

        <div class="lg:grid lg:grid-cols-3 lg:gap-x-8 lg:items-start">
            <!-- Ảnh sản phẩm -->
            <div class="lg:col-span-2 flex flex-col items-center">
                <img src="<?php echo _IMGP_ . $detail['image']; ?>"
                    alt="<?php echo $product['name_product']; ?>"
                    class="rounded-lg shadow-md max-h-[500px] object-contain">
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="mt-6 lg:mt-0 lg:col-span-1 lg:sticky lg:top-20 bg-white p-6 rounded-lg shadow">
                <h1 class="text-2xl font-bold text-gray-900 mb-2"><?php echo $product['name_product']; ?></h1>

                <div class="flex items-center gap-4 mb-4">
                    <span class="text-2xl text-red-600 font-semibold">
                        <?php echo number_format($product['price'], 0, ',', '.') . ' đ'; ?>
                    </span>
                    <span class="text-gray-400 line-through">
                        <?php echo number_format($product['origin_price'], 0, ',', '.') . ' đ'; ?>
                    </span>
                </div>

                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600">Màu sắc:</label>
                        <input type="color" value="<?php echo $detail['code_color']; ?>" disabled class="ml-2">
                    </div>

                    <div>
                        <label for="quantity" class="text-sm text-gray-600">Số lượng:</label>
                        <input type="number" id="quantity" name="amount_buy"
                            value="1" min="1" max="<?php echo $detail['amount'] ?>"
                            class="w-20 ml-2 border rounded-md px-2 py-1">
                    </div>

                    <p class="text-sm text-gray-700">Vật liệu: <span class="font-medium"><?php echo $product['material']; ?></span></p>
                    <p class="text-sm text-gray-700">Kích thước: <span class="font-medium"><?php echo $detail['size']; ?></span></p>
                    <p class="text-gray-600 text-sm"> Còn lại: <?php echo $detail['amount']; ?> sản phẩm </p>

                    <input type="hidden" value="<?php echo $detailId; ?>" name="detailId">
                    <input type="hidden" value="<?php echo $userId; ?>" name="userId">
                    <input type="hidden" value="1" name="count">
                    <input type="hidden" value="<?php echo $productId; ?>" name="productId">

                    <?php if (!empty($filterAll['userId']) && $filterAll['userId'] != 1): ?>
                        <button type="submit"
                            class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-500 transition">
                            Thêm vào giỏ hàng
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Mô tả -->
        <div class="mt-10 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Mô tả sản phẩm</h2>
            <p class="text-gray-700 leading-relaxed"><?php echo $product['description']; ?></p>
        </div>

        <!-- Đánh giá -->
        <div class="mt-10 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Đánh giá sản phẩm</h2>

            <!-- Form đánh giá: chỉ hiện nếu người dùng đã đăng nhập và không phải admin -->
            <?php if (!empty($filterAll['userId'])): ?>
                <?php if (!empty($filterAll['billId'])): ?>
                    <form action="?module=reviews&action=review" method="POST" class="mb-6 space-y-2">
                        <input type="hidden" name="productId" value="<?php echo $productId; ?>">
                        <input type="hidden" name="userId" value="<?php echo $userId; ?>">

                        <label for="content" class="block text-sm font-medium text-gray-700">Viết đánh giá:</label>
                        <textarea id="content" name="content" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm"
                            placeholder="Chia sẻ cảm nhận của bạn..." required></textarea>

                        <label class="block text-sm font-medium text-gray-700 mt-2">Đánh giá sao:</label>
                        <div class="flex space-x-1">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" name="stars" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                                <label for="star<?php echo $i; ?>" class="text-yellow-400 cursor-pointer">★</label>
                            <?php endfor; ?>
                        </div>

                        <button type="submit"
                            class="mt-2 py-2 px-4 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
                            Gửi đánh giá
                        </button>
                    </form>
                <?php else: ?>
                    <p class="text-gray-500 mb-4">Bạn cần mua hàng để đánh giá sản phẩm.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-gray-500 mb-4">Bạn cần <a href="?module=auth&action=login" class="text-blue-500 underline">đăng nhập</a> để đánh giá sản phẩm.</p>
            <?php endif; ?>

            <!-- Hiển thị danh sách đánh giá -->
            <div class="space-y-4">

                <?php
                $listReviews = selectAll("SELECT * FROM reviews WHERE id_product = $productId");
                $user = selectOne("SELECT * FROM users");
                if (!empty($listReviews)): ?>
                    <?php foreach ($listReviews as $review): ?>
                        <div class="border-b pb-4">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold"><?php echo $user['fullname']; ?></span>
                                <span class="text-yellow-400">
                                    <?php echo str_repeat('★', $review['stars']) . str_repeat('☆', 5 - $review['stars']); ?>
                                </span>
                            </div>
                            <p class="text-gray-700 mt-1"><?php echo $review['content']; ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">Chưa có đánh giá nào cho sản phẩm này.</p>
                <?php endif; ?>
            </div>
        </div>


        <!-- Sản phẩm tương tự -->
        <div class="mt-10">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Sản phẩm tương tự</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
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
                        <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&userId=<?php echo $userId ?? ''; ?>"
                            class="bg-white p-4 rounded-lg shadow hover:shadow-md transition block">
                            <img src="<?php echo _IMGP_ . $productItem['thumbnail']; ?>"
                                alt="<?php echo $productItem['thumbnail']; ?>"
                                class="rounded-md h-40 w-full object-cover mb-3">
                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2"><?php echo $productItem['name_product']; ?></h3>
                            <p class="text-red-600 font-semibold mt-1">
                                <?php echo number_format($productItem['price'], 0, ',', '.'); ?> đ
                            </p>
                            <p class="text-gray-600 text-sm"> Còn lại: <?php echo $detail['amount']; ?> sản phẩm </p>
                        </a>
                <?php
                    endif;
                endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php
if (!empty($filterAll['userId'])) {
    if ($filterAll['userId'] == 1) {
        layout('footer_admin', $data);
    } else {
        layout('footer_custom', $data);
    }
} else {
    layout('footer_dashboard', $data);
}
?>