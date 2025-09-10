<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

// Khôi phục dữ liệu bảng vouchers (set is_deleted = 0)

$filterAll = filter();
if (!empty($filterAll['id'])) {
    $voucherId = $filterAll['id'];
    $rowVoucher = getCountRows("SELECT * FROM vouchers WHERE id = $voucherId");
    if ($rowVoucher > 0) {
        $dataUpdate = [
            'is_deleted' => 0
        ];
        $condition = "id = $voucherId";
        $UpdateStatus = update('vouchers', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', 'Khôi phục voucher thành công');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Voucher không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('smg_type', 'danger');
}

redirect('?module=vouchers&action=list');
