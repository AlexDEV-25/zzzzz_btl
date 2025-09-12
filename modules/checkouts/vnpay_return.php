<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

$filterAll = filter();
// Lấy dữ liệu checkout từ session
$checkoutData = getSession('checkout_data');
$data = getSession('vnpay');
$voucher = getSession('voucher');
if ($filterAll['vnp_TransactionNo'] != 0) {
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
    if (!empty($voucher['voucher'])) {
        $voucherCode = trim($voucher['voucher']);
        $today = date('Y-m-d');
        $voucher = selectOne("SELECT * FROM vouchers WHERE code = '$voucherCode' AND start <= '$today' AND end >= '$today'");

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
    $userId = $data['id_user'];
    redirect('?module=checkouts&action=checkout&role=0&userId=' . $userId);
}
