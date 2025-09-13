<?php
if (!defined('_CODE')) {
    die("Truy cập thất bại!");
}
if (isPost()) {
    $filterAll = filter();
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        // Lấy thông tin đăng nhập
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        // Câu lệnh truy vấn
        $userQuery = selectOne("SELECT password, id, role, is_deleted FROM users WHERE email = '$email'");
        if (!empty($userQuery) && $userQuery["is_deleted"] != 1) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            $role = $userQuery['role'];

            if (password_verify($password, $passwordHash)) {
                // Tạo token đăng nhập
                $tokenLogin = sha1(uniqid() . time());

                // Lưu vào bảng tokenlogin
                $dataInsert = [
                    'user_Id'   => $userId,
                    'token'     => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];

                $insertStatus = insert('tokenlogin', $dataInsert);
                if ($insertStatus) {
                    // Lưu loginToken vào session
                    setSession('tokenLogin', $tokenLogin);

                    // Điều hướng theo role
                    if ($role == 1) {
                        redirect('?module=home&action=admin&role=1');
                    } else if ($role == 2) {
                        redirect('?module=home&action=manager&role=2');
                    } else if ($role == 3) {
                        redirect('?module=home&action=employee&role=3');
                    } else {
                        redirect('?module=home&action=dashboard&role=0&userId=' . $userId);
                    }
                } else {
                    setFlashData('smg', '❌ Không thể đăng nhập. Vui lòng thử lại sau.');
                    setFlashData('smg_type', 'danger');
                    redirect('?module=auth&action=login');
                }
            } else {
                setFlashData('smg', '❌ Mật khẩu không chính xác.');
                setFlashData('smg_type', 'danger');
                redirect('?module=auth&action=login');
            }
        } else {
            setFlashData('smg', '❌ Email không tồn tại hoặc tài khoản đã bị khóa.');
            setFlashData('smg_type', 'danger');
            redirect('?module=auth&action=login');
        }
    } else {
        setFlashData('smg', '❌ Vui lòng nhập email và mật khẩu.');
        setFlashData('smg_type', 'danger');
        redirect('?module=auth&action=login');
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

$data = ['pageTitle' => 'Đăng nhập tài khoản'];
layout('header_login', $data);
?>

<body>
    <div class="row">
        <div class="col-4" style="margin: 100px auto;">
            <h2 class="text-center text-uppercase">Đăng nhập</h2>
            <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>
            <form action="" method="post">
                <div class="form-group mg-form">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Email">
                </div>
                <div class="form-group mg-form">
                    <label for="password">Password</label>
                    <input name="password" type="password" class="form-control" placeholder="Password">
                </div>
                <button class="btn btn-primary btn-block mg-form mg-btn" type="submit">Đăng nhập</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
                <p class="text-center"><a href="?module=auth&action=register">Đăng ký</a></p>
            </form>
        </div>
    </div>
</body>
<?php layout('footer'); ?>