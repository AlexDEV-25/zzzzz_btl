<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
$billId = $filterAll['billId'] ?? null;
$userId = $filterAll['userId'] ?? null;

if ($billId) {
    $sql = "UPDATE bills SET status = -1 WHERE id = $billId AND status = 0";
    if (query($sql)) {
        setFlashData('msg', '✅ Hủy đơn thành công!');
        setFlashData('msg_type', 'success');
    } else {
        setFlashData('msg', '❌ Hủy đơn thất bại!');
        setFlashData('msg_type', 'danger');
    }
}
redirect("?module=billsCustom&action=customBillDetail&role=0&userId=$userId&billId=$billId");
