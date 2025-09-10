<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

// Kiểm tra id trong database -> tồn tại -> tiến hành xoá (hoặc ẩn)
$filterAll = filter();
if (!empty($filterAll['id'])) {
    $voucherId = $filterAll['id'];
    $rowVoucher = getCountRows("SELECT * FROM vouchers WHERE id = $voucherId");
    if ($rowVoucher > 0) {
        // Nếu bạn muốn xoá mềm (ẩn đi) thì dùng update
        $dataUpdate = [
            'is_deleted' => 1 // Cần thêm cột is_deleted vào bảng vouchers
        ];
        $condition = "id = $voucherId";
        $UpdateStatus = update('vouchers', $dataUpdate, $condition);

        // Nếu muốn xoá hẳn thì thay đoạn trên bằng:
        // $UpdateStatus = delete('vouchers', "id = $voucherId");

        if ($UpdateStatus) {
            setFlashData('smg', 'Ẩn voucher thành công');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi, vui lòng thử lại sau.');
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
