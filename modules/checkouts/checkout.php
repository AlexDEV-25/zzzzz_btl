<?php
if (!defined('_CODE')) {
    die("Truy cập thất bại");
}
$errors = [];
$filterAll = filter();
// Lấy dữ liệu checkout từ session
$checkoutData = getSession('checkout_data');

if (!$checkoutData || empty($checkoutData['userId']) || empty($checkoutData['productCartIds'])) {
    setFlashData('smg', 'Không có sản phẩm để thanh toán!');
    setFlashData('smg_type', 'danger');
    redirect('?module=cart&action=cart');
}

$userId = intval($checkoutData['userId']);
$productCartIds = $checkoutData['productCartIds'];
$amount_buy = $checkoutData['amount_buy'];
$cartId = intval($checkoutData['cartId']);

// Nếu là admin thì redirect sang admin
if ($userId == 1) {
    redirect('?module=home&action=admin');
}

$user = selectOne("SELECT * FROM users WHERE id = $userId");
$cartCount = getCountCart($userId);

// Lấy sản phẩm giỏ hàng và tính tổng
$total = 0;
$products = [];
foreach ($productCartIds as $productCartId) {
    $productCartId = intval($productCartId);
    $productCart = selectOne("SELECT * FROM products_cart WHERE id = $productCartId");
    if (!$productCart) continue;

    $productDetailId = intval($productCart['id_product_detail']);
    $productDetail = selectOne("SELECT * FROM products_detail WHERE id = $productDetailId");
    if (!$productDetail) continue;

    $productId = intval($productDetail['id_product']);
    $product = selectOne("SELECT * FROM products WHERE id = $productId");
    if (!$product) continue;

    $amount = intval($amount_buy[$productCartId]);
    $total += $product['price'] * $amount;

    $products[] = [
        'name' => $product['name_product'],
        'image' => $productDetail['image'],
        'amount' => $amount,
        'price' => $product['price']
    ];
}

// --- Xử lý voucher ---
$discountValue = 0;
$voucherCode = '';

if (!empty($filterAll['voucher'])) {
    $voucherCode = trim($filterAll['voucher']);
    $today = date('Y-m-d');
    $voucher = selectOne("SELECT * FROM vouchers WHERE code = '$voucherCode' 
    AND start <= '$today' AND end >= '$today'AND is_deleted = 0");

    if ($voucher) {
        if ($voucher['unit'] == 0) {
            $discountValue = $total * $voucher['discount'] / 100;
        } else {
            $discountValue = $voucher['discount'];
        }
    } else {
        setFlashData('smg', 'Mã voucher không tồn tại hoặc đã hết hạn!');
        setFlashData('smg_type', 'danger');
    }
}

$finalTotal = max(0, $total - $discountValue);

// --- Xử lý POST thanh toán ---
if (isPost() && isset($filterAll['checkout_submit'])) {
    $date = date('Y-m-d H:i:s');

    // lấy phương thức thanh toán từ form
    $paymentMethod = isset($filterAll['payment_method']) ? intval($filterAll['payment_method']) : 0;
    if ($paymentMethod == 0) {
        $dataInsertBill = [
            'id_user' => $userId,
            'total' => $finalTotal,
            'status' => 0,
            'date' => $date,
            'id_voucher' => $voucher['id'] ?? null,
            'payment_method' => $paymentMethod
        ];
        $insertStatusBill = insert('bills', $dataInsertBill);

        if ($insertStatusBill) {
            $bill = selectOne("SELECT * FROM bills WHERE id_user = $userId AND date = '$date'");
            $success = true;

            foreach ($productCartIds as $productCartId) {
                $productCartId = intval($productCartId);
                $productCart = selectOne("SELECT * FROM products_cart WHERE id = $productCartId");
                if (!$productCart) continue;

                $productDetailId = intval($productCart['id_product_detail']);
                $amount = intval($amount_buy[$productCartId]);

                $dataInsertBillDetail = [
                    'amount_buy' => $amount,
                    'id_product_detail' => $productDetailId,
                    'id_bill' => $bill['id']
                ];
                $insertStatusBillDetail = insert('products_bill', $dataInsertBillDetail);

                $productDetail = selectOne("SELECT * FROM products_detail WHERE id = $productDetailId");
                $dataUpdateProductDetail = [
                    'amount' => $productDetail['amount'] - $amount
                ];
                $updateProductDetail = update('products_detail', $dataUpdateProductDetail, "id = $productDetailId");

                $product = selectOne("SELECT * FROM products WHERE id = " . intval($productDetail['id_product']));
                $dataUpdateProduct = [
                    'sold' => $product['sold'] + $amount
                ];
                $updateProduct = update('products', $dataUpdateProduct, "id = " . intval($productDetail['id_product']));

                $deleteProductCart = delete('products_cart', "id = $productCartId");

                $success = $success && $insertStatusBillDetail && $updateProductDetail && $updateProduct && $deleteProductCart;
            }

            $count = $cartCount - count($productCartIds);
            $updateStatus = update('cart', ['count' => $count], "id_user = $userId");

            if ($success && $updateStatus) {
                removeSession('checkout_data');
                setFlashData('smg', 'Đặt hàng thành công!');
                setFlashData('smg_type', 'success');
                redirect('?module=checkouts&action=checkout_success&role=0&userId=' . $userId);
            } else {
                setFlashData('smg', 'Đặt hàng thất bại. Vui lòng thử lại!');
                setFlashData('smg_type', 'danger');
            }
        } else {
            setFlashData('smg', 'Không thể tạo hóa đơn. Vui lòng thử lại!');
            setFlashData('smg_type', 'danger');
        }
    } else {
        $dataInsertBill = [
            'id_user' => $userId,
            'total' => $finalTotal,
            'status' => 0,
            'date' => $date,
            'id_voucher' => $voucher['id'] ?? null,
            'payment_method' => $paymentMethod
        ];
        $voucher = [
            'voucher' => $voucherCode ?? null
        ];
        setSession('voucher', $voucher);
        setSession('vnpay', $dataInsertBill);
        confirm_vnpay();
    }
}
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$data = [
    'pageTitle' => "Thanh Toán",
    'count' => $cartCount,
    'role' => 0,
    'userId' => $userId
];
layout('header_custom', $data);
?>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Thanh Toán</h1>

        <?php if (!empty($smg)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $smg_type == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo $smg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Thông tin đơn hàng -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Thông tin đơn hàng</h2>
                    <?php foreach ($products as $product): ?>
                        <div class="flex items-center justify-between border-b py-4">
                            <div class="flex items-center space-x-4">
                                <img src="<?php echo _IMGP_ . $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-16 h-16 object-cover rounded">
                                <span class="text-lg font-medium text-gray-700"><?php echo $product['name']; ?></span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-lg font-medium text-gray-700">SL: <?php echo $product['amount']; ?></span>
                                <span class="text-lg font-medium text-gray-700"><?php echo number_format($product['price']); ?> đ</span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Hiển thị tổng tiền -->
                    <div class="flex justify-between mt-4">
                        <span class="text-lg font-semibold">Tổng tiền:</span>
                        <span class="text-lg font-semibold"><?php echo number_format($total); ?> đ</span>
                    </div>
                    <?php if ($discountValue > 0): ?>
                        <div class="flex justify-between mt-2 text-green-600">
                            <span>Giảm giá (<?php echo htmlspecialchars($voucherCode); ?>):</span>
                            <span>- <?php echo number_format($discountValue); ?> đ</span>
                        </div>
                        <div class="flex justify-between mt-2 text-blue-600 font-bold">
                            <span>Thành tiền:</span>
                            <span><?php echo number_format($finalTotal); ?> đ</span>
                        </div>
                    <?php endif; ?>

                    <!-- Ô nhập voucher -->
                    <div class="mt-4">
                        <label for="voucher" class="block text-sm font-medium text-gray-700">Mã giảm giá</label>
                        <div class="flex gap-2 mt-1">
                            <input type="text" name="voucher" id="voucher" value="<?php echo htmlspecialchars($voucherCode); ?>" class="flex-1 border rounded p-2" placeholder="Nhập mã giảm giá">
                            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded">Áp dụng</button>
                        </div>
                    </div>

                    <!-- Chọn phương thức thanh toán -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="0" class="mr-2" checked>
                                <span>Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="1" class="mr-2">
                                <span>Thanh toán qua VNPay</span>
                            </label>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $finalTotal; ?>" name="sotien">
                <!-- Thông tin khách hàng -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Thông tin khách hàng</h2>
                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <label>Họ tên</label>
                            <input name="fullname" type="text" class="mt-1 p-2 border rounded" value="<?php echo $user['fullname']; ?>">
                        </div>
                        <div class="flex flex-col">
                            <label>Email</label>
                            <input name="email" type="email" class="mt-1 p-2 border rounded" value="<?php echo $user['email']; ?>">
                        </div>
                        <div class="flex flex-col">
                            <label>SĐT</label>
                            <input name="phone" type="number" class="mt-1 p-2 border rounded" value="<?php echo $user['phone']; ?>">
                        </div>
                        <div class="flex flex-col">
                            <label>Địa chỉ</label>
                            <input name="address" type="text" class="mt-1 p-2 border rounded" value="<?php echo $user['address']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="checkout_submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                Xác Nhận Thanh Toán
            </button>
        </form>
    </div>
</body>

</html>

<?php
layout('footer_custom', $data);
?>