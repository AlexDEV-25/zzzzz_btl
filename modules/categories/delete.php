<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$filterAll = filter();
if (!empty($filterAll['id'])) {
    $categoryId = $filterAll['id'];
    $rowCategory = getCountRows("SELECT * FROM categories WHERE id =$categoryId");
    if ($rowCategory > 0) {
        $dataUpdate = [
            'is_deleted' => 1
        ];
        $condition = "id = $categoryId";
        $UpdateStatus = update('categories', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', '✅ Ẩn danh mục thành công');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', '❌ Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', '❌ Danh mục không tồn tại trong hệ thống.');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', '❌ Liên kết không tồn tại.');
    setFlashData('smg_type', 'danger');
}

redirect('?module=categories&action=list');
