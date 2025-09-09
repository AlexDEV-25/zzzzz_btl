<?php

if (!defined('_CODE')) {
    die("truy cap that bai");
}
$title = [
    'pageTitle' => 'active'
];

layout('header', $title);

$token = filter()['token'];
if (!empty($token)) {
    // truy vấn để kiểm tra token với database
    $tokenQuery = selectOne("SELECT id FROM users WHERE activeToken = '$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null,
        ];
        $updateStatus = update('users', $dataUpdate, "id=$userId");
        if ($updateStatus) {
            setFlashData('msg', "kich hoat thanh cong, ban co the dang nhap ngay bay gio");
            setFlashData('msg_type', "success");
        } else {
            setFlashData('msg', "kich hoat khong thanh cong, vui long lien he quan tri vien");
            setFlashData('msg_type', "danger");
        }
        redirect('?module=auth&action=login');
    } else {
        getSmg('lien ket khong ton tai hoac da het han', 'danger');
    }
} else {
    getSmg('ket noi khong ton tai hoac da het han', 'danger');
}
?>

<?php
layout('footer');
?>