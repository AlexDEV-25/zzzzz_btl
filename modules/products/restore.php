<?php
if (!defined('_CODE')) {
    die('Access denied...');
}


//  Kiểm tra id trong datase -> tồn tại -> tiến hành xoá
// Xoá dữ liệu bảng products_detail -> Xoá dữ liệu bảng products

$filterAll = filter();
$role = $filterAll['role'];
if (!empty($filterAll['id'])) {
    $productId = $filterAll['id'];
    $rowProduct = getCountRows("SELECT * FROM products WHERE id =$productId");
    if ($rowProduct > 0) {
        $dataUpdate = [
            'is_deleted' => 0
        ];
        $condition = "id = $productId";
        $UpdateStatus = update('products', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', 'Khôi phục sản phẩm thành công');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Danh mục không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('smg_type', 'danger');
}

redirect('?module=products&action=list&role=' . $role);
