<?php
if (!defined('_CODE')) {
    die("Truy cập thất bại!");
}
if (isPost()) {
    $filterAll = filter();
    if (!empty($filterAll['email'])) {
        $email = $filterAll['email'];

        $queryUser = selectOne("SELECT id FROM users WHERE email = '$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];

            // Tạo forgot token
            $forgotToken = sha1(uniqid() . time());

            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                // Tạo link reset mật khẩu
                $linkReset = _WEB_HOST . '?module=auth&action=reset&token=' . $forgotToken;

                // Nội dung email
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content  = '<p>Chào bạn,</p>';
                $content .= '<p>Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn.</p>';
                $content .= '<p>Vui lòng click vào link sau để đặt lại mật khẩu:</p>';
                $content .= '<p><a href="' . $linkReset . '">' . $linkReset . '</a></p>';
                $content .= '<p>Trân trọng cảm ơn!</p>';

                $sendEmail = sendMail($email, $subject, $content);

                if ($sendEmail) {
                    setFlashData('smg', '✅ Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu.');
                    setFlashData('smg_type', 'success');
                } else {
                    setFlashData('smg', '❌ Lỗi hệ thống, vui lòng thử lại sau (gửi email thất bại).');
                    setFlashData('smg_type', 'danger');
                }
            } else {
                setFlashData('smg', '❌ Lỗi hệ thống, vui lòng thử lại sau!');
                setFlashData('smg_type', 'danger');
            }
        } else {
            setFlashData('smg', '❌ Email này không tồn tại trong hệ thống.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', '❌ Vui lòng nhập email.');
        setFlashData('smg_type', 'danger');
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

$data = ['pageTitle' => 'Quên mật khẩu'];
layout('header_login', $data);
?>

<body>
    <div class="row">
        <div class="col-4" style="margin: 100px auto;">
            <h2 class="text-center text-uppercase">Quên mật khẩu</h2>
            <?php if (!empty($smg)) getSmg($smg, $smg_type) ?>
            <form action="" method="post">
                <div class="form-group mg-form">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Nhập email của bạn">
                </div>
                <button class="btn btn-primary btn-block mg-form mg-btn" type="submit">Gửi yêu cầu</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
                <p class="text-center"><a href="?module=auth&action=register">Đăng ký</a></p>
            </form>
        </div>
    </div>
</body>
<?php layout('footer'); ?>