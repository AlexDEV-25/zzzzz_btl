<?php
if (!defined('_CODE')) {
    die("Truy cập thất bại");
}

require_once('config.php');
require_once(_WEB_PATH . '\\includes\\database.php');
require_once(_WEB_PATH . '\\includes\\session.php');
require_once(_WEB_PATH . '\\includes\\functions.php');

$errors = [];

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
if ($userId === 1) {
    redirect('?module=home&action=admin');
}

$user = selectOne("SELECT * FROM users WHERE id = $userId");
$cartCount = getCountCart($userId);

// Lấy thông tin giỏ hàng
$rowCart = getCountRows("SELECT * FROM cart WHERE id_user = $userId");

// Lấy thông tin sản phẩm và tính tổng tiền
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

// Dữ liệu header

// Xử lý POST thanh toán
if (isPost()) {
    $date = date('Y-m-d H:i:s');

    $dataInsertBill = [
        'id_user' => $userId,
        'total' => $total,
        'status' => 0,
        'date' => $date
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

            // Thêm chi tiết hóa đơn
            $dataInsertBillDetail = [
                'amount_buy' => $amount,
                'id_product_detail' => $productDetailId,
                'id_bill' => $bill['id']
            ];
            $insertStatusBillDetail = insert('products_bill', $dataInsertBillDetail);

            // Cập nhật số lượng sản phẩm
            $productDetail = selectOne("SELECT * FROM products_detail WHERE id = $productDetailId");
            $dataUpdateProductDetail = [
                'amount' => $productDetail['amount'] - $amount
            ];
            $updateProductDetail = update('products_detail', $dataUpdateProductDetail, "id = $productDetailId");

            // Cập nhật số lượng đã bán
            $product = selectOne("SELECT * FROM products WHERE id = " . intval($productDetail['id_product']));
            $dataUpdateProduct = [
                'sold' => $product['sold'] + $amount
            ];
            $updateProduct = update('products', $dataUpdateProduct, "id = " . intval($productDetail['id_product']));

            // Xóa khỏi giỏ hàng
            $deleteProductCart = delete('products_cart', "id = $productCartId");

            $success = $success && $insertStatusBillDetail && $updateProductDetail && $updateProduct && $deleteProductCart;
        }

        // Cập nhật số lượng trong cart
        $count = $cartCount - count($productCartIds);
        $updateStatus = update('cart', ['count' => $count], "id_user = $userId");

        $errors = [];
        $filterAll = filter();
        // Validate fullname
        if (empty($filterAll['fullname'])) {
            $errors['fullname']['required'] = 'lỗi không nhập';
        } else {
            if (strlen($filterAll['fullname']) < 5) {
                $errors['fullname']['min'] = 'lỗi tên quá ngắn';
            }
        }
        // validate email
        if (empty($filterAll['email'])) {
            $errors['email']['required'] = 'lỗi không nhập';
        }
        // validate phone
        if (empty($filterAll['phone'])) {
            $errors['phone']['required'] = 'lỗi không nhập';
        } else {
            if (!isPhone($filterAll['phone'])) {
                $errors['phone']['isPhone'] = 'số điện thoại không hợp lệ';
            }
        }
        // validate address
        if (empty($filterAll['address'])) {
            $errors['address']['required'] = 'lỗi không nhập';
        }
        if (empty($errors)) {

            $dataUpdate = [
                'fullname' => $filterAll['fullname'],
                'email' => $filterAll['email'],
                'phone' => $filterAll['phone'],
                'address' => $filterAll['address'],
            ];


            $condition = "id = $userId";
            $UpdateStatus = update('users', $dataUpdate, $condition);
        }
        if ($success && $updateStatus) {
            removeSession('checkout_data');
            setFlashData('smg', 'Đặt hàng thành công!');
            setFlashData('smg_type', 'success');
            redirect('?module=checkouts&action=checkout_success&userId=' . $userId);
        } else {
            setFlashData('smg', 'Đặt hàng thất bại. Vui lòng thử lại!');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Không thể tạo hóa đơn. Vui lòng thử lại!');
        setFlashData('smg_type', 'danger');
    }
}

$data = [
    'pageTitle' => "Thanh Toán",
    'count' => $cartCount,
    'userId' => $userId
];
layout('header_custom', $data);

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Thanh Toán</h1>

        <?php if (!empty($smg)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $smg_type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
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
                                <span class="text-lg font-medium text-gray-700">Số lượng: <?php echo $product['amount']; ?></span>
                                <span class="text-lg font-medium text-gray-700"><?php echo number_format($product['price']); ?> đ</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="flex justify-between mt-4">
                        <span class="text-lg font-semibold">Tổng tiền:</span>
                        <span class="text-lg font-semibold"><?php echo number_format($total); ?> đ</span>
                    </div>
                </div>

                <!-- Thông tin khách hàng -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Thông tin khách hàng</h2>
                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <label>Họ tên</label>
                            <input name="fullname" type="text" class="form-control mt-1 p-2 border rounded" placeholder="Họ tên"
                                value="<?php echo $user['fullname']; ?>">
                            <?php echo form_error($errors, 'fullname', '<span class="error text-red-600">', '</span>'); ?>
                        </div>
                        <div class="flex flex-col">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control mt-1 p-2 border rounded" placeholder="Địa chỉ email"
                                value="<?php echo $user['email']; ?>">
                            <?php echo form_error($errors, 'email', '<span class="error text-red-600">', '</span>'); ?>
                        </div>
                        <div class="flex flex-col">
                            <label>Số điện thoại</label>
                            <input name="phone" type="number" class="form-control mt-1 p-2 border rounded" placeholder="Điện thoại"
                                value="<?php echo $user['phone']; ?>">
                            <?php echo form_error($errors, 'phone', '<span class="error text-red-600">', '</span>'); ?>
                        </div>
                        <div class="flex flex-col">
                            <label>Địa chỉ</label>
                            <input name="address" type="text" class="form-control mt-1 p-2 border rounded" placeholder="Địa chỉ"
                                value="<?php echo $user['address']; ?>">
                            <?php echo form_error($errors, 'address', '<span class="error text-red-600">', '</span>'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                Xác Nhận Thanh Toán
            </button>
        </form>
    </div>
</body>

</html>

<?php
layout('footer_custom', $data);
?>