<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

if (isLogin()) {
    $token = getSession('tokenLogin');
    delete('tokenlogin', "token='$token'");
    removeSession('tokenLogin');
    redirect('?module=home&action=dashboard');
}
