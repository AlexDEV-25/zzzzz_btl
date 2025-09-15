<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$filterAll = filter();
if (!empty($filterAll['productCartId']) && !empty($filterAll['userId'])) {
    $userId = $filterAll['userId'];
    $productCartId = $filterAll['productCartId'];
    $amount = $filterAll['amount'];
    $rowProductCart = getCountRows("SELECT * FROM products_cart WHERE id =$productCartId");
    if ($rowProductCart > 0) {
        $dataUpdate = [
            'amount_buy' => $amount,
        ];
        $condition = "id = $productCartId";
        $UpdateStatus = update('products_cart', $dataUpdate, $condition);
    } else {
        setFlashData('smg', '❌ Sản phẩm không không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
}
redirect("?module=cart&action=cart&role=0&userId=$userId");
