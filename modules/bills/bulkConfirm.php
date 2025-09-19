<?php
$filterAll = filter();
$role = isset($filterAll['role']) ? (int)$filterAll['role'] : -1;
$listBills = selectAll("SELECT * FROM bills WHERE status = 0 ORDER BY id");
if (empty($listBills)) {
    redirect('?module=bills&action=list&role=' . $role);
}

$allNotEnough = [];

foreach ($listBills as $bill) {
    $billId = (int)$bill['id'];
    $listBillDetail = selectAll("SELECT * FROM products_bill WHERE id_bill = $billId");

    $notEnough = [];
    $updates = [];

    // 1️⃣ Kiểm tra đủ hàng chưa
    foreach ($listBillDetail as $item) {
        $productDetailId = (int) $item['id_product_detail'];
        $productDetail = selectOne("SELECT amount, id_product FROM products_detail WHERE id = $productDetailId");

        if ($productDetail) {
            if ($productDetail['amount'] < $item['amount_buy']) {
                $product = selectOne("SELECT name_product FROM products WHERE id = " . (int)$productDetail['id_product']);
                $notEnough[] = $product['name_product'] . " (cần {$item['amount_buy']}, còn {$productDetail['amount']})";
            } else {
                $updates[] = [
                    'id' => $productDetailId,
                    'amount' => $productDetail['amount'] - $item['amount_buy']
                ];
            }
        }
    }

    // 2️⃣ Nếu đủ → trừ kho + update bill
    if (empty($notEnough)) {
        foreach ($updates as $u) {
            update('products_detail', ['amount' => $u['amount']], "id = " . $u['id']);
        }
        update('bills', ['status' => 1], "id = $billId");
    } else {
        $allNotEnough = array_merge($allNotEnough, $notEnough);
    }
}

// 3️⃣ Nếu có thiếu → báo lỗi
if (!empty($allNotEnough)) {
    $msg = "❌ Không đủ hàng cho các sản phẩm: <br>" . implode("<br>", $allNotEnough);
    setFlashData('smg', $msg);
    setFlashData('smg_type', 'danger');
}

redirect('?module=bills&action=list&role=' . $role);
