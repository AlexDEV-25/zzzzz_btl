<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}
$errors = [];
$oldData = [];
if (isPost()) {
    $filterAll = filter();
    // validate fullname
    if (empty($filterAll['fullname'])) {
        $errors['fullname']['required'] = 'lỗi không nhập';
    } else {
        if (strlen($filterAll['fullname']) < 5) {
            $errors['fullname']['min'] = 'lỗi tên quá ngắn';
        }
    }

    // validate email
    if (empty($filterAll['email'])) {
        $errors['email']['required'] = 'lỗi không nhập';
    } else {
        if (!emailUnique($filterAll['email'])) {
            $errors['email']['unique'] = 'email đã tồn tại';
        }
    }

    // validate phone
    if (empty($filterAll['phone'])) {
        $errors['phone']['required'] = 'lỗi không nhập';
    } else {
        if (!isPhone($filterAll['phone'])) {
            $errors['phone']['isPhone'] = 'số điện thoại không hợp lệ';
        }
    }

    // validate password
    if (empty($filterAll['password'])) {
        $errors['password']['required'] = 'lỗi không nhập';
    } else {
        if (strlen($filterAll['password']) < 8) {
            $errors['password']['min'] = 'mật khẩu phải nhiều hơn 8 kí tự ';
        }
    }

    // validate re_password
    if (empty($filterAll['re_password'])) {
        $errors['re_password']['required'] = 'lỗi không nhập';
    } else {
        if ($filterAll['re_password'] != $filterAll['password']) {
            $errors['re_password']['match'] = 'mật khẩu nhập lại  không đúng ';
        }
    }

    if (empty($errors)) {
        // xử lý insert
        $activeToken = sha1(uniqid() . time()); // thời gian đăng ký (độc nhất cho mỗi lần đăng kí)

        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users', $dataInsert);

        if ($insertStatus) {
            $email = $filterAll['email'];
            $userId = selectOne("SELECT id FROM users WHERE email = '$email'")['id'];
            $dataInsertCart = [
                'id_user' => $userId,
                'count' => 0
            ];
            $insertCartStatus = insert('cart', $dataInsertCart);
            if ($insertCartStatus) {
            } else {
                setFlashData('smg', "tạo giỏ không thành công");
                setFlashData('smg_type', "danger");
            }

            $linkActive = _WEB_HOST . "?module=auth&action=active&token=$activeToken";

            $subject = $filterAll['fullname'] . ' vui lòng kích hoạt tài khoản';
            $content =
                'Chào ' . $filterAll['fullname'] . '<br>' .
                'Vui lòng click vào link dưới đây' . '<br>' .
                $linkActive . '<br>' .
                'Trân trọng cảm ơn' . '<br>';

            $sendMail = sendMail($filterAll['email'], $subject, $content);
            if ($sendMail) {
                setFlashData('smg', "Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản");
                setFlashData('smg_type', "success");
            } else {
                setFlashData('smg', "Hệ thống gặp sự cố, vui lòng thử lại sau");
                setFlashData('smg_type', "danger");
            }
        } else {
            setFlashData('smg', "Đăng ký không thành công");
            setFlashData('smg_type', "danger");
        }
    } else {
        setFlashData('smg', "Kiểm tra lại");
        setFlashData('smg_type', "danger");
        setFlashData('errors', $errors);
        setFlashData('oldData', $filterAll);
    }

    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
    $errors = getFlashData('errors');
    $oldData = getFlashData('oldData');
}

$data = [
    'pageTitle' => 'Đăng ký tài khoản'
];
layout('header_login', $data);
?>

<body>
    <div class="row">
        <div class="col-4" style="margin: 100px auto;">
            <h2 class="text-center text-uppercase">đăng ký tài khoản users</h2>

            <?php
            if (!empty($smg)) {
                getSmg($smg, $smg_type);
            }
            ?>

            <form action="?module=auth&action=register" method="post">

                <div class="form-group mg-form">
                    <label for="fullname">Họ tên</label>
                    <input name="fullname" type="text" class="form-control" placeholder="Họ tên"
                        value="<?php echo oldData($oldData, 'fullname');  ?>">
                    <?php
                    echo form_error($errors, 'fullname', '<span class="error">', '</span>');
                    ?>
                </div>

                <div class="form-group mg-form">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Email"
                        value="<?php echo oldData($oldData, 'email');  ?>">
                    <?php
                    echo form_error($errors, 'email', '<span class="error">', '</span>');
                    ?>
                </div>

                <div class="form-group mg-form">
                    <label for="phone">Số điện thoại</label>
                    <input name="phone" type="number" class="form-control" placeholder="Số điện thoại"
                        value="<?php echo oldData($oldData, 'phone');  ?>">
                    <?php
                    echo form_error($errors, 'phone', '<span class="error">', '</span>');
                    ?>
                </div>
                <div class="form-group mg-form">
                    <label for="password">Password</label>
                    <input name="password" type="text" class="form-control" placeholder="Password"
                        value="<?php echo oldData($oldData, 'password');  ?>">
                    <?php
                    echo form_error($errors, 'password', '<span class="error">', '</span>');
                    ?>
                </div>
                <div class="form-group mg-form">
                    <label for="re_password">Nhập lại Password</label>
                    <input name="re_password" type="password" class="form-control" placeholder="Nhập lại Password"
                        value="<?php echo oldData($oldData, 're_password');  ?>">
                    <?php
                    echo form_error($errors, 're_password', '<span class="error">', '</span>');
                    ?>
                </div>
                <button class="btn btn-primary btn-block mg-form mg-btn" type="submit">Đăng ký</button>
                <p class="text-center"><a href="?module=auth&action=login">Đăng nhập tài khoản</a></p>
            </form>
        </div>
    </div>
</body>
<?php layout('footer_login');; ?>