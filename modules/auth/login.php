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

$data = ['pageTitle' => 'Đăng nhập - Luxury Furniture'];
layout('header_login', $data);
?>

<body>
    <div class="main-content">
        <div class="furniture-showcase">
            <div class="showcase-content">
                <h1 class="showcase-title">Luxury Interior</h1>
                <p class="showcase-subtitle">
                    Khám phá bộ sưu tập nội thất cao cấp được tuyển chọn kỹ lưỡng,
                    mang đến không gian sống đẳng cấp và tinh tế cho ngôi nhà của bạn.
                </p>
                <div class="showcase-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="feature-text">Cao Cấp</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <div class="feature-text">Tinh Tế</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="feature-text">Chất Lượng</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-section">
            <div class="login-container">
                <div class="login-header">
                    <div class="brand-logo">LF</div>
                    <h1 class="login-title">Chào mừng trở lại</h1>
                    <p class="login-subtitle">Đăng nhập để trải nghiệm thế giới nội thất đẳng cấp</p>
                </div>

                <?php if (!empty($smg)) : ?>
                    <div class="alert alert-<?php echo $smg_type == 'danger' ? 'danger' : 'success'; ?>">
                        <?php echo $smg; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="email" class="form-label">Địa chỉ email</label>
                        <input name="email" type="email" class="form-control" placeholder="your@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input name="password" type="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-login">
                        Đăng nhập
                    </button>
                </form>

                <div class="divider">
                    <span class="divider-text">Tùy chọn khác</span>
                </div>

                <div class="link-group">
                    <a href="?module=auth&action=forgot" class="auth-link">Quên mật khẩu?</a>
                </div>

                <div class="link-group">
                    <a href="?module=auth&action=register" class="auth-link">Tạo tài khoản mới</a>
                </div>
            </div>
        </div>
    </div>
</body>

<?php layout('footer'); ?>