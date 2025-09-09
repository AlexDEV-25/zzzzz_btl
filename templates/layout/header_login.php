<?php
if (!defined('_CODE')) {
    die('Access denied...');
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Quản lý người dùng'; ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

</body>

</html>

<header class="p-3 mb-3 border-bottom">
    <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow-sm">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard">
                    <img
                        class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"
                        src="https://th.bing.com/th/id/OIP.cdh5Bm1mwTTH7Q2GFTg1xwHaHq?rs=1&pid=ImgDetMain" alt="">

                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo _WEB_HOST; ?>?module=auth&action=login">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo _WEB_HOST; ?>?module=auth&action=register">Đăng ký</a></li>
                </ul>

            </div>
        </div>
    </nav>
</header>