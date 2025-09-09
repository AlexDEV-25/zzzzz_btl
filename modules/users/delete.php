<?php
if (!defined('_CODE')) {
    die('Access denied...');
}


//  Kiểm tra id trong datase -> tồn tại -> tiến hành xoá
// Xoá dữ liệu bảng tokenlogin -> Xoá dữ liệu bảng users

$filterAll = filter();
if (!empty($filterAll['id'])) {
    $userId = $filterAll['id'];
    $rowUser = getCountRows("SELECT * FROM users WHERE id =$userId");

    if ($rowUser > 0) {
        $listCart = selectAll("SELECT * FROM cart WHERE id_user = $userId ");
        foreach ($listCart as $item):
            $cartId = $item['id'];
            $rowProductCart = getCountRows("SELECT * FROM products_cart WHERE id_cart =$cartId");
            $rowCart = getCountRows("SELECT * FROM cart WHERE id =$cartId");
            if ($rowProductCart + $rowCart > 0) {
                // Thực hiện xoá
                $deleteProductCart = delete('products_cart', "id_cart =$cartId");
                $deleteCart = delete('cart', "id=$cartId");
                if ($deleteCart) {
                    setFlashData('smg', 'Xoá giỏ thành công.');
                    setFlashData('smg_type', 'success');
                } else {
                    setFlashData('smg', 'Lỗi hệ thống.');
                    setFlashData('smg_type', 'danger');
                }
            } else {
                setFlashData('smg', 'giỏ không tồn tại trong hệ thống.');
                setFlashData('smg_type', 'danger');
            }
        endforeach;
    } else {
        setFlashData('smg', 'Danh mục không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }

    $userDetail = getCountRows("SELECT * FROM users WHERE id =$userId");
    if ($userDetail > 0) {
        // Thực hiện xoá
        $deleteToken = delete('tokenlogin', "user_Id = $userId");

        if ($deleteToken) {
            // Xoá user
            $deleteUser = delete('users', "id=$userId");
            if ($deleteUser) {
                setFlashData('smg', 'Xoá người dùng thành công.');
                setFlashData('smg_type', 'success');
            } else {
                setFlashData('smg', 'Lỗi hệ thống.');
                setFlashData('smg_type', 'danger');
            }
        }
    } else {
        setFlashData('smg', 'Người dùng không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('smg_type', 'danger');
}

redirect('?module=users&action=list');
