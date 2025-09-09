<?php

if (!defined('_CODE')) {
    die("truy cap that bai");
}

try {
    if (class_exists("PDO")) {
        $dns = "mysql:host=" . _HOST . ";dbname=" . _DB;

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // hỗ trợ utf8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // thông báo khi có lỗi
        ];

        $conn = new PDO($dns, _USER, _PASS, $options);
    }
} catch (Exception $e) {
    echo "<div style='color:red; pandding: 5px 15px;border:1px solid red;'> ";
    echo $e->getMessage() . "<br>";
    echo $e->getLine();
    echo "</div>";
    die();
}
