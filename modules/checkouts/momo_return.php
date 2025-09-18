<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

$filterAll = filter();
// Lấy dữ liệu checkout từ session
$checkoutData = getSession('checkout_data');
$data = getSession('momo'); // Lấy data MoMo từ session
$voucher = getSession('voucher');

// Kiểm tra kết quả thanh toán MoMo
// resultCode = 0 nghĩa là thành công, khác 0 là thất bại
$resultCode = isset($filterAll['resultCode']) ? intval($filterAll['resultCode']) : -1;

if ($resultCode == 0) {
    // THANH TOÁN THÀNH CÔNG
    if (!$checkoutData || empty($checkoutData['userId']) || empty($checkoutData['productCartIds'])) {
        setFlashData('smg', '❌ Không có sản phẩm để thanh toán!');
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
    if (!empty($voucher['voucher'])) {
        $voucherCode = trim($voucher['voucher']);
        $today = date('Y-m-d');
        $voucher_data = selectOne("SELECT * FROM vouchers WHERE code = '$voucherCode' AND start <= '$today' AND end >= '$today'");

        if ($voucher_data) {
            if ($voucher_data['unit'] == 0) {
                $discountValue = $total * $voucher_data['discount'] / 100;
            } else {
                $discountValue = $voucher_data['discount'];
            }
        } else {
            setFlashData('smg', '❌ Mã voucher không tồn tại hoặc đã hết hạn!');
            setFlashData('smg_type', 'danger');
        }
    }

    $finalTotal = max(0, $total - $discountValue);

    // Insert bill vào database
    $userId = $data['id_user'];
    $date = $data['date'];
    $insertStatusBill = insert('bills', $data);

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

            $deleteProductCart = delete('products_cart', "id = $productCartId");

            $success = $success && $insertStatusBillDetail && $deleteProductCart;
        }

        $count = $cartCount - count($productCartIds);
        $updateStatus = update('cart', ['count' => $count], "id_user = $userId");

        if ($success && $updateStatus) {
            // Xóa session sau khi hoàn thành
            removeSession('checkout_data');
            removeSession('momo');
            removeSession('voucher');

            setFlashData('smg', '✅ Thanh toán MoMo thành công! Đặt hàng hoàn tất.');
            setFlashData('smg_type', 'success');
            redirect('?module=checkouts&action=checkout_success&role=0&userId=' . $userId);
        } else {
            setFlashData('smg', '❌ Đặt hàng thất bại. Vui lòng thử lại!');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', '❌ Không thể tạo hóa đơn. Vui lòng thử lại!');
        setFlashData('smg_type', 'danger');
    }
} else {
    // THANH TOÁN THẤT BẠI
    $userId = $data['id_user'];

    // Xóa session khi thanh toán thất bại
    removeSession('momo');
    removeSession('voucher');

    setFlashData('smg', '❌ Thanh toán MoMo thất bại hoặc bị hủy!');
    setFlashData('smg_type', 'danger');
    redirect('?module=checkouts&action=checkout&role=0&userId=' . $userId);
}
