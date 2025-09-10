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
        $dataUpdate = [
            'is_deleted' => 1
        ];
        $condition = "id = $userId";
        $UpdateStatus = update('users', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', 'Ẩn người dùng thành công');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Người dùng không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }

    $userDetail = getCountRows("SELECT * FROM users WHERE id =$userId");
    if ($userDetail > 0) {
        // Thực hiện xoá
        $deleteToken = delete('tokenlogin', "user_Id = $userId");
    } else {
        setFlashData('smg', 'Người dùng không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('smg_type', 'danger');
}

redirect('?module=users&action=list');
