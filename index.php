<?php

session_start();
require_once('config.php');
require_once(_WEB_PATH . '\\includes\\connect.php');

// thư viện phpMailer
require_once(_WEB_PATH . '\\includes\\phpmailer\\Exception.php');
require_once(_WEB_PATH . '\\includes\\phpmailer\\PHPMailer.php');
require_once(_WEB_PATH . '\\includes\\phpmailer\\SMTP.php');

require_once(_WEB_PATH . '\\includes\\functions.php');
require_once(_WEB_PATH . '\\includes\\database.php');
require_once(_WEB_PATH . '\\includes\\session.php');
$module = _MODULE;
$action = _ACTION;

// $test_session = setSession('diachi', "giá trị");
// var_dump($test_session);

// echo getSession('diachi');

// removeSession('diachi');
// echo getSession('diachi');

// setFlashdata('diachimoi', 'success');
// echo getFlashdata('diachimoi');

// sendMail('22111061953@hunre.edu.vn', 'tieu de', 'helloooooooooooooooooo');



if (!empty($_GET['module'])) {
    if (is_string($_GET['module'])) {
        $module = trim($_GET['module']);
    }
}

if (!empty($_GET['action'])) {
    if (is_string($_GET['action'])) {
        $action = trim($_GET['action']);
    }
}
$path = "modules\\$module\\$action.php";
if (file_exists($path)) {
    require_once($path);
} else {
    require_once("modules\\error\\404.php");
}

// echo $module."<br>";
// echo $action."<br>";
// echo _WEB_HOST."<br>";
// echo _WEB_PATH."<br>";
// echo '<i class="fa-solid fa-house"></i>';
