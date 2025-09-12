<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

require_once('config.php');
require_once(_WEB_PATH . '\\includes\\database.php');
require_once(_WEB_PATH . '\\includes\\session.php');
require_once(_WEB_PATH . '\\includes\\functions.php');

$filterAll = filter();
$errors = [];

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
        setFlashData('smg', 'bạn phải chọn sản phẩm muốn mua!!');
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

$data = [
    'pageTitle' => "giỏ hàng",
    'count' => $cartCount,
    'role' => 0,
    'userId' => $userId
];
layout('header_custom', $data);

// Lấy thông báo flash
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Giỏ Hàng</h1>

        <?php if (!empty($smg)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $smg_type == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo $smg; ?>
            </div>
        <?php endif; ?>

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
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500">
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
                                    class="w-20 p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <span class="text-lg font-medium text-gray-700"><?php echo number_format($product['price']); ?> đ</span>
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
                <input type="hidden" value="0" name="role">
                <input type="hidden" value="<?php echo $productDetailId; ?>" name="productDetailId">
                <input type="hidden" value="<?php echo $productId; ?>" name="productId">
                <input type="hidden" value="<?php echo $userId; ?>" name="userId">
                <input type="hidden" value="<?php echo $cartId; ?>" name="cartId">
            </div>
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors mt-4">
                Thanh Toán
            </button>
        </form>
    </div>
</body>

<?php
layout('footer_custom', $data);
?>