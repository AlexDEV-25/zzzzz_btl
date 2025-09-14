<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$filterAll = filter();
$errors = [];

// Kiểm tra userId
if (!empty($filterAll['userId'])) {
    $userId = $filterAll['userId'];
} else {
    die();
}

// Lấy thông tin giỏ hàng
$cartCount = getCountCart($userId);
$rowCart = getCountRows("SELECT * FROM cart WHERE id_user = $userId");
$cartId = null;
if ($rowCart > 0) {
    $cart = selectOne("SELECT * FROM cart WHERE id_user = $userId");
    $cartId = $cart['id'];
}

// Lấy thông báo flash
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

$data = [
    'pageTitle' => "giỏ hàng",
    'count' => $cartCount,
    'role' => 0,
    'userId' => $userId
];
layout('header_custom', $data);
?>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Giỏ Hàng</h1>
        <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>

        <form method="POST" action="?module=checkouts&action=checkout&role=0&userId=<?php echo $userId; ?>" class="space-y-4">
            <div id="cart" class="bg-white rounded-lg shadow-md p-6">
                <?php
                $listProductCart = selectAll("SELECT * FROM products_cart WHERE id_cart = $cartId");
                if (empty($listProductCart)): ?>
                    <p class="text-center text-gray-600">Giỏ hàng của bạn đang trống.</p>
                <?php else: ?>
                    <?php foreach ($listProductCart as $productCart):
                        $productDetailId = $productCart['id_product_detail'];
                        $amount = $productCart['amount_buy'];
                        $productDetail = selectOne("SELECT * FROM products_detail WHERE id = $productDetailId");
                        $productId = $productDetail['id_product'];
                        $product = selectOne("SELECT * FROM products WHERE id = $productId");
                        $productCartId = $productCart['id'];
                    ?>
                        <div class="flex items-center justify-between border-b py-4">
                            <div class="flex items-center space-x-4">
                                <!-- Checkbox chọn sản phẩm -->
                                <input
                                    type="checkbox"
                                    name="productCartId[]"
                                    value="<?php echo $productCartId ?>"
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 product-checkbox">

                                <!-- Ảnh sản phẩm -->
                                <img
                                    src="<?php echo _IMGP_ . $productDetail['image']; ?>"
                                    alt="<?php echo $product['name_product'] ?>"
                                    class="w-16 h-16 object-cover rounded">

                                <span class="text-lg font-medium text-gray-700">
                                    <?php echo $product['name_product']; ?>
                                </span>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Số lượng -->
                                <input
                                    type="number"
                                    name="amount_buy[<?php echo $productCart['id']; ?>]"
                                    value="<?php echo $amount ?>"
                                    min="1"
                                    max="<?php echo $productDetail['amount']; ?>"
                                    class="w-20 p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 amount-input">

                                <!-- Giá -->
                                <span
                                    class="text-lg font-medium text-gray-700 price-item"
                                    data-price="<?php echo $product['price']; ?>">
                                    <?php echo number_format($product['price']); ?> đ
                                </span>

                                <!-- Xóa -->
                                <a
                                    href="<?php echo _WEB_HOST; ?>?module=cart&action=deleteProductCart&productCartId=<?php echo $productCartId; ?>&userId=<?php echo $userId; ?>&role=0"
                                    onclick="return confirm('Bạn có chắc chắn muốn xoá?')"
                                    class="text-red-600 hover:text-red-800">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Hidden input -->
                <input type="hidden" value="0" name="role">
                <input type="hidden" value="<?php echo $userId; ?>" name="userId">
                <input type="hidden" value="<?php echo $cartId; ?>" name="cartId">
            </div>

            <!-- Hiển thị tổng tiền -->
            <div class="flex justify-end mt-4">
                <span class="text-xl font-bold text-gray-800">
                    Tổng tiền: <span id="total-price">0 đ</span>
                </span>
            </div>

            <!-- Nút thanh toán -->
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors mt-4">
                Thanh Toán
            </button>
        </form>
    </div>

    <!-- Script tính tổng tiền -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const amounts = document.querySelectorAll('.amount-input');
            const prices = document.querySelectorAll('.price-item');
            const totalEl = document.getElementById('total-price');

            function calcTotal() {
                let total = 0;
                checkboxes.forEach((cb, index) => {
                    if (cb.checked) {
                        const amountInput = amounts[index];
                        const price = parseInt(prices[index].dataset.price);
                        const amount = parseInt(amountInput.value);
                        total += price * amount;
                    }
                });
                totalEl.textContent = total.toLocaleString() + " đ";
            }

            checkboxes.forEach(cb => cb.addEventListener('change', calcTotal));
            amounts.forEach(input => input.addEventListener('input', calcTotal));
        });
    </script>
</body>
<?php layout('footer'); ?>