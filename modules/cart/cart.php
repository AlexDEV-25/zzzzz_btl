<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$filterAll = filter();
checkDie($filterAll);
$errors = [];
$selectedDetailId = !empty($filterAll['detailId']) ? (int)$filterAll['detailId'] : null;
// Xử lý POST trước để tránh lỗi header
if (isPost()) {
    if (!empty($filterAll['amount_buy']) && !empty($filterAll['productCartId'])) {
        // Xóa các amount_buy không hợp lệ
        foreach ($filterAll['amount_buy'] as $key1 => $item1) {
            $k = 0;
            foreach ($filterAll['productCartId'] as $key2 => $item2) {
                if ($key1 == $item2) {
                    $k = 1;
                }
            }
            if ($k == 0) {
                unset($filterAll['amount_buy'][$key1]);
            }
        }

        // Lấy userId và cartId
        $userId = !empty($filterAll['userId']) ? $filterAll['userId'] : null;
        $cartId = null;
        if ($userId) {
            $rowCart = getCountRows("SELECT * FROM cart WHERE id_user = $userId");
            if ($rowCart > 0) {
                $cart = selectOne("SELECT * FROM cart WHERE id_user = $userId");
                $cartId = $cart['id'];
            }
        }

        // Cập nhật số lượng trong products_cart
        foreach ($filterAll['productCartId'] as $key2 => $item2) {
            $amount_buy = $filterAll['amount_buy'][$item2];
            $dataUpdate = [
                'amount_buy' => $amount_buy,
            ];
            $condition = "id = $item2";
            $UpdateStatusProductCart = update('products_cart', $dataUpdate, $condition);
        }

        // Lưu dữ liệu vào session để sử dụng ở trang checkout
        setSession('checkout_data', [
            'productCartIds' => $filterAll['productCartId'],
            'amount_buy' => $filterAll['amount_buy'],
            'userId' => $userId,
            'role' => 0,
            'cartId' => $cartId
        ]);

        // Chuyển hướng đến trang checkout
        redirect('?module=checkouts&action=checkout&role=0&userId=' . $userId);
    } else {
        setFlashData('smg', '❌ Bạn phải chọn sản phẩm muốn mua!!');
        setFlashData('smg_type', 'danger');
    }
}

// Kiểm tra userId và chuyển hướng trước khi xuất HTML
if (!empty($filterAll['userId'])) {
    $userId = $filterAll['userId'];
    if ($filterAll['userId'] == 1) {
        redirect('?module=home&action=admin');
    }
} else {
    redirect('?module=auth&action=register');
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
        <form method="POST" class="space-y-4">
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
                                <input
                                    type="checkbox"
                                    name="productCartId[]"
                                    value="<?php echo $productCartId ?>"
                                    class="product-check h-5 w-5 text-blue-600 focus:ring-blue-500"
                                    <?php echo ($selectedDetailId && $productDetailId == $selectedDetailId) ? 'checked' : ''; ?>>
                                <img
                                    src="<?php echo _IMGP_ . $productDetail['image']; ?>"
                                    alt="<?php echo $product['name_product'] ?>"
                                    class="w-16 h-16 object-cover rounded">
                                <span class="text-lg font-medium text-gray-700"><?php echo $product['name_product']; ?></span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <input
                                    type="number"
                                    name="amount_buy[<?php echo $productCart['id']; ?>]"
                                    value="<?php echo $amount ?>"
                                    min="1"
                                    max="<?php echo $productDetail['amount']; ?>"
                                    class="amount-input w-20 p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    data-price="<?php echo $product['price']; ?>">
                                <span class="text-lg font-medium text-gray-700"><?php echo number_format($product['price'] * $productCart['amount_buy']); ?> đ</span>
                                <a href="javascript:void(0)"
                                    class="update-link text-green-600 hover:text-green-800"
                                    data-id="<?php echo $productCartId; ?>"
                                    data-user="<?php echo $userId; ?>">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                                <a
                                    href="<?php echo _WEB_HOST; ?>?module=cart&action=deleteProductCart&userId=<?php echo $userId; ?>&productCartId=<?php echo $productCartId; ?>"
                                    onclick="return confirm('Bạn có chắc chắn muốn xoá?')"
                                    class="text-red-600 hover:text-red-800">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <input type="hidden" value="0" name="role">
                <input type="hidden" value="<?php echo $userId; ?>" name="userId">
                <input type="hidden" value="<?php echo $cartId; ?>" name="cartId">
            </div>

            <!-- Tổng tiền -->
            <div class="flex justify-between items-center mt-4">
                <span class="text-xl font-semibold text-gray-800">Tổng tiền:</span>
                <span id="total-price" class="text-2xl font-bold text-red-600">0 đ</span>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors mt-4">
                Thanh Toán
            </button>
        </form>
    </div>

    <script>
        function updateTotal() {
            let total = 0;
            document.querySelectorAll(".product-check:checked").forEach(check => {
                const parent = check.closest("div.flex.items-center.justify-between");
                const input = parent.querySelector(".amount-input");
                const price = parseInt(input.getAttribute("data-price"));
                const amount = parseInt(input.value);
                total += price * amount;
            });
            document.getElementById("total-price").textContent = total.toLocaleString("vi-VN") + " đ";
        }

        // Sự kiện cho checkbox và input số lượng
        document.querySelectorAll(".product-check, .amount-input").forEach(el => {
            el.addEventListener("input", updateTotal);
            el.addEventListener("change", updateTotal);
        });

        document.querySelectorAll(".update-link").forEach(link => {
            link.addEventListener("click", function() {
                let id = this.dataset.id;
                let userId = this.dataset.user;
                let input = document.querySelector(`input[name='amount_buy[${id}]']`);
                let amount = parseInt(input.value);
                let max = parseInt(input.getAttribute("max")); // lấy số lượng tồn từ attribute

                if (amount > max) {
                    alert("❌ Chỉ còn " + max + " sản phẩm trong kho!");
                    input.value = max;
                    return;
                }

                if (confirm("Cập nhật số lượng sản phẩm này?")) {
                    window.location.href = `?module=cart&action=updateProductCart&userId=${userId}&productCartId=${id}&amount=${amount}`;
                }
            });
        });

        // 👉 Gọi khi trang vừa load
        window.addEventListener("DOMContentLoaded", updateTotal);
    </script>
</body>
<?php layout('footer'); ?>