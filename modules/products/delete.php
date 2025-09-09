<?php
if (!defined('_CODE')) {
    die('Access denied...');
}


//  Kiểm tra id trong datase -> tồn tại -> tiến hành xoá
// Xoá dữ liệu bảng products_detail -> Xoá dữ liệu bảng products

$filterAll = filter();
if (!empty($filterAll['id'])) {
    $productId = $filterAll['id'];
    $rowDetail = getCountRows("SELECT * FROM products_detail WHERE id_product =$productId");
    $rowProduct = getCountRows("SELECT * FROM products WHERE id =$productId");
    if ($rowDetail + $rowProduct > 0) {
        // Thực hiện xoá
        $deleteDetail = delete('products_detail', "id_product =$productId");
        $deleteProduct = delete('products', "id=$productId");
        if ($deleteProduct) {
            setFlashData('smg', 'Xoá sản phẩm thành công.');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Lỗi hệ thống.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Sản phẩm không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('smg_type', 'danger');
}

redirect('?module=products&action=list');
