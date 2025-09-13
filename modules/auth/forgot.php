<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

// // kiểm tra trạng thái đăng nhập
// if (isLogin()) {
//     redirect('?module=home&action=dashboard');
// }

if (isPost()) {
    $filterAll = filter();
    if (!empty($filterAll['email'])) {
        $email = $filterAll['email'];
        // echo $email;

        $queryUser = selectOne("SELECT id FROM users WHERE email = '$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];

            // Tạo forrgot token
            $forgotToken = sha1(uniqid() . time());

            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                // Tạo cái link reset, khôi phục mk
                $linkReset = _WEB_HOST . '?module=auth&action=reset&token=' . $forgotToken;

                // gửi mail cho người dùng
                $subject = 'Yêu cầu khôi phục mật khẩu.';
                $content = 'Chào bạn.</br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn. 
                Vui lòng click vào link sau để đổi lại mật khẩu: </br>';
                $content .= $linkReset . '</br>';
                $content .= 'Trân trọng cảm ơn!';

                $sendEmail = sendMail($email, $subject, $content);

                if ($sendEmail) {
                    setFlashData('msg', 'Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu!');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống vui lòng thử lại sau!(email)');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống vui lòng thử lại sau!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'gia tri email khong ton tai trong he thong');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'vui long nhap email');
        setFlashData('msg_type', 'danger');
    }
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

$data = ['pageTitle' => 'Quên mật khẩu'];
layout('header_login', $data);
?>

<body>
    <div class="row">
        <div class="col-4" style="margin: 100px auto;">

            <?php
            if (!empty($msg)) {
                getSmg($msg, $msg_type);
            }
            ?>

            <h2 class="text-center text-uppercase">Quên mật khẩu</h2>
            <form action="" method="post">
                <div class="form-group mg-form">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Email">
                </div>
                <button class="btn btn-primary btn-block mg-form mg-btn" type="submit">Gửi</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
                <p class="text-center"><a href="?module=auth&action=register">Đăng ký</a></p>
            </form>
        </div>
    </div>
</body>
<?php layout('footer'); ?>