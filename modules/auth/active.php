<?php

if (!defined('_CODE')) {
    die("Truy cập thất bại!");
}

$token = filter()['token'] ?? null;
if (!empty($token)) {
    // Truy vấn để kiểm tra token với database
    $tokenQuery = selectOne("SELECT id FROM users WHERE activeToken = '$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null,
        ];
        $updateStatus = update('users', $dataUpdate, "id=$userId");
        if ($updateStatus) {
            setFlashData('smg', "✅ Kích hoạt tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.");
            setFlashData('smg_type', "success");
        } else {
            setFlashData('smg', "❌ Kích hoạt không thành công. Vui lòng liên hệ quản trị viên.");
            setFlashData('smg_type', "danger");
        }
        redirect('?module=auth&action=login');
    } else {
        getSmg("❌ Liên kết không tồn tại hoặc đã hết hạn.", "danger");
    }
} else {
    getSmg("❌ Kết nối không tồn tại hoặc đã hết hạn.", "danger");
}
