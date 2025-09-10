<?php
if (!defined('_CODE')) {
    die('Access denied...');
}


//  Kiểm tra id trong datase -> tồn tại -> tiến hành xoá
// Xoá dữ liệu bảng products_detail -> Xoá dữ liệu bảng products

$filterAll = filter();
if (!empty($filterAll['id'])) {
    $userId = $filterAll['id'];
    $rowUser = getCountRows("SELECT * FROM users WHERE id =$userId");
    if ($rowUser > 0) {
        $dataUpdate = [
            'is_deleted' => 0
        ];
        $condition = "id = $userId";
        $UpdateStatus = update('users', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', 'Khôi phục người dùng thành công');
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

redirect('?module=users&action=list');
