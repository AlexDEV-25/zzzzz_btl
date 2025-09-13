<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$filterAll = filter();
if (!empty($filterAll['productCartId']) && !empty($filterAll['userId'])) {
    $productCartId = $filterAll['productCartId'];
    $userId = $filterAll['userId'];
    $rowProductCart = getCountRows("SELECT * FROM products_cart WHERE id =$productCartId");

    if ($rowProductCart > 0) {
        $deleteCart = delete('products_cart', "id=$productCartId");
        setFlashData('smg', '❌ Sản phẩm không không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    } else {
        setFlashData('smg', '❌ Sản phẩm không không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }

    $cart = getCountRows("SELECT * FROM cart WHERE id_user =$userId");
    if ($cart > 0) {
        $Cart = selectOne("SELECT * FROM cart WHERE id_user = $userId");
        $count = $Cart['count'] - 1;
        $dataUpdate = [
            'count' => $count,
        ];
        $condition = "id_user = $userId";
        $UpdateStatus = update('cart', $dataUpdate, $condition);

        if ($UpdateStatus) {
            setFlashData('smg', '✅ Xoá sản phẩm thành công.');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', '❌ Lỗi hệ thống.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', '❌ Sản phẩm không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', '❌ Liên kết không tồn tại.');
    setFlashData('smg_type', 'danger');
}
redirect("?module=cart&action=cart&role=0&userId=$userId");
