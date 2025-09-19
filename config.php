<!-- hằng số  -->
<?php
const _MODULE = 'home';
const _ACTION = 'dashboard';

const _CODE = true;

// thiết lập host
define('_WEB_HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/project_finish');
define('_WEB_HOST_TEMPLATES', _WEB_HOST . '/templates');

// thiết lập path
define('_WEB_PATH', __DIR__);
define('_WEB_PATH_TEMPLATES', _WEB_PATH . '\\templates');

define('_IMGA_', '/project_finish/templates/image/avatars/');
define('_IMGB_', '/project_finish/templates/image/banner/');
define('_IMGC_', '/project_finish/templates/image/categories/');
define('_IMGG_', '/project_finish/templates/image/gift/');
define('_IMGP_', '/project_finish/templates/image/products/');
define('_IMGCS_', '/project_finish/templates/image/codes/');

// database
const _HOST = "localhost";
const _DB = "quanlyshop22";
const _USER = "root";
const _PASS = "";
