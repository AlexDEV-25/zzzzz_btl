<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
$token = !empty($filterAll['token']) ? $filterAll['token'] : null;

if (!empty($token)) {
    // Truy vấn database kiểm tra token
    $tokenQuery = selectOne(
        "SELECT id, fullname, email FROM users WHERE forgotToken = ?",
        [$token] // tránh SQL Injection
    );

    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];

        if (isPost()) {
            $filterPost = filter();
            $errors = [];

            // Validate password
            if (empty($filterPost['password'])) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập.';
            } elseif (strlen($filterPost['password']) < 8) {
                $errors['password']['min'] = 'Mật khẩu phải lớn hơn hoặc bằng 8 ký tự.';
            }

            // Validate password_confirm
            if (empty($filterPost['password_confirm'])) {
                $errors['password_confirm']['required'] = 'Bạn phải nhập lại mật khẩu.';
            } elseif ($filterPost['password'] !== $filterPost['password_confirm']) {
                $errors['password_confirm']['match'] = 'Mật khẩu nhập lại không đúng.';
            }

            if (empty($errors)) {
                $passwordHash = password_hash($filterPost['password'], PASSWORD_DEFAULT);

                $dataUpdate = [
                    'password'    => $passwordHash,
                    'forgotToken' => null,
                    'update_at'   => date('Y-m-d H:i:s')
                ];

                $updateStatus = update('users', $dataUpdate, "id = $userId");

                if ($updateStatus) {
                    setFlashData('smg', '✅ Thay đổi mật khẩu thành công!!');
                    setFlashData('smg_type', 'success');
                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('smg', '❌ Lỗi hệ thống, vui lòng thử lại sau!!');
                    setFlashData('smg_type', 'danger');
                }
            } else {
                setFlashData('smg', '❌ Vui lòng kiểm tra lại dữ liệu!!');
                setFlashData('smg_type', 'danger');
                setFlashData('errors', $errors);
                redirect('?module=auth&action=reset&token=' . $token);
            }
        }

        $smg      = getFlashData('smg');
        $smg_type = getFlashData('smg_type');
        $errors   = getFlashData('errors');

        $data = ['pageTitle' => 'Đặt lại mật khẩu'];
        layout('header_login', $data);
?>

        <body>
            <div class="row">
                <div class="col-4" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase">Đặt lại mật khẩu</h2>
                    <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>
                    <form action="" method="POST">
                        <div class="form-group mg-form">
                            <label for="password">Mật khẩu</label>
                            <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                            <?php echo form_error('password', '<span class="error">', '</span>', $errors); ?>
                        </div>

                        <div class="form-group mg-form">
                            <label for="password_confirm">Nhập lại mật khẩu</label>
                            <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
                            <?php echo form_error('password_confirm', '<span class="error">', '</span>', $errors); ?>
                        </div>

                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <button type="submit" class="mg-btn btn btn-primary btn-block">Gửi</button>
                        <hr>
                        <p class="text-center"><a href="?module=auth&action=login">Đăng nhập tài khoản</a></p>
                    </form>
                </div>
            </div>
        </body>
<?php
    } else {
        getSmg('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
    }
} else {
    getSmg('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
}

layout('footer');
