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
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        // check login
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        // câu lệnh truy vấn
        $userQuery = selectOne("SELECT password, id, role, is_deleted FROM users WHERE email = '$email'");
        if (!empty($userQuery) && $userQuery["is_deleted"] != 1) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            $role = $userQuery['role'];
            if (password_verify($password, $passwordHash)) {
                // tạo tokenLogin
                $tokenLogin = sha1(uniqid() . time());

                // insert vào bảng
                $dataInsert = [
                    'user_Id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];

                $insertStatus = insert('tokenlogin', $dataInsert);
                if ($insertStatus) {
                    // insert thành công
                    // lưu loginToken vào session
                    setSession('tokenLogin', $tokenLogin);
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
                    setFlashData('msg', 'khong the dang nhap vui long thu lai sau');
                    setFlashData('msg_type', 'danger');
                    redirect('?module=auth&action=login');
                }
            } else {
                setFlashData('msg', 'mat khau khong chinh xac');
                setFlashData('msg_type', 'danger');
                redirect('?module=auth&action=login');
            }
        } else {
            setFlashData('msg', 'email khong ton tai, hoac tai khoan da bi khoa');
            setFlashData('msg_type', 'danger');
            redirect('?module=auth&action=login');
        }
    } else {
        setFlashData('msg', 'vui long nhap email va mat khau');
        setFlashData('msg_type', 'danger');
        redirect('?module=auth&action=login');
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

$data = ['pageTitle' => 'Đăng nhập tài khoản'];
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

            <h2 class="text-center text-uppercase">đăng nhập quản lý users</h2>
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