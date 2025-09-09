<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

$title = [
    'pageTitle' => 'Đăng nhập tài khoản'
];

layout('header_login', $title);

// kiểm tra trạng thái đăng nhập
if (isLogin()) {
    redirect('?module=home&action=dashboard');
}

if (isPost()) {
    $filterAll = filter();
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        // check login
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        // câu lệnh truy vấn
        $userQuery = selectOne("SELECT password, id, role FROM users WHERE email = '$email'");
        if (!empty($userQuery)) {
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

                $insertStatus = insert('tokenLogin', $dataInsert);
                if ($insertStatus) {
                    // insert thành công
                    // lưu loginToken vào session
                    setSession('tokenLogin', $tokenLogin);
                    if ($role == 1) {
                        // 
                        redirect('?module=home&action=admin&userId=' . $userId);
                    } else {

                        $selectCart = "SELECT * FROM cart WHERE id_user = $userId";
                        // $insertProductCart = "SELECT * FROM products_cart WHERE id_user = $userId";
                        if (getCountRows($selectCart) > 0 && $userId != 1) {
                        } else {
                            // tạo giỏ số lượng
                            $dataInsertCart = [
                                'id_user' => $userId,
                            ];
                            insert('cart', $dataInsertCart);
                        }
                        redirect('?module=home&action=dashboard&userId=' . $userId);
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
            setFlashData('msg', 'email khong ton tai');
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

// echo '<pre>';
// print_r($kq);
// echo '</pre>';

?>

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



<?php
layout('footer_login');;
?>