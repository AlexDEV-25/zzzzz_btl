<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
$billId = $filterAll['billId'] ?? null;
$userId = $filterAll['userId'] ?? null;
$billStatus = selectOne("SELECT * FROM bills WHERE id = $billId")['status'];

if ($billId) {
    if ($status == 0) {
        $sql = "UPDATE bills 
        SET status = -1 
        WHERE id = $billId AND status IN (0)";
        if (query($sql)) {
            setFlashData('msg', '✅ Hủy đơn thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', '❌ Hủy đơn thất bại!');
            setFlashData('msg_type', 'danger');
        }
    } elseif ($status == 1 || $status == 2) {
        $sql = "UPDATE bills 
        SET status = -1 
        WHERE id = $billId AND status IN (1,2)";
        if (query($sql)) {
            setFlashData('msg', '✅ Hủy đơn thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', '❌ Hủy đơn thất bại!');
            setFlashData('msg_type', 'danger');
        }

        // ✅ Hủy đơn → cộng lại số lượng
        $listBillDetail = selectAll("SELECT * FROM products_bill WHERE id_bill = $billId");

        foreach ($listBillDetail as $item) {
            $productDetailId = (int) $item['id_product_detail'];
            $productDetail   = selectOne("SELECT amount FROM products_detail WHERE id = $productDetailId");

            if ($productDetail) {
                $newAmount = $productDetail['amount'] + $item['amount_buy'];
                update('products_detail', ['amount' => $newAmount], "id = $productDetailId");
            }
        }
    }
}
redirect("?module=billsCustom&action=customBillDetail&role=0&userId=$userId&billId=$billId");
