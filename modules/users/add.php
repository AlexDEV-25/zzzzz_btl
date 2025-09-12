<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

if (isPost()) {
    $filterAll = filter();

    $errors = []; // Mảng chữa các lỗi

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
    // validate role

    if (!isset($filterAll['role']) || !in_array($filterAll['role'], [0, 2, 3])) {
        $errors['role']['invalid'] = 'Role không hợp lệ';
    }


    if (empty($errors)) {

        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'status' => $filterAll['status'],
            'role' => $filterAll['role'], // thêm dòng này
            'create_at' => date('Y-m-d H:i:s')
        ];


        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {
            setFlashData('smg', 'Thêm người dùng mới thành công!!');
            setFlashData('smg_type', 'success');
            redirect('?module=users&action=list');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
            redirect('?module=users&action=add');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('oldData', $filterAll);
        redirect('?module=users&action=add');
    }
}
$title = [
    'pageTitle' => 'Thêm tài khoản'
];

layout('header', $title);


$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('oldData');



?>

<div class="container">
    <div class="row" style="margin: 50px auto;">

        <h2 class="text-center text-uppercase">Thêm người dùng </h2>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }

        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Họ tên</label>
                        <input name="fullname" type="text" class="form-control" placeholder="Họ tên"
                            value="<?php
                                    echo oldData($oldData, 'fullname');
                                    ?>">
                        <?php
                        echo form_error($errors, 'fullname', '<span class="error">', '</span>');
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Địa chỉ email"
                            value="<?php
                                    echo oldData($oldData, 'email');
                                    ?>">
                        <?php
                        echo form_error($errors, 'email', '<span class="error">', '</span>');
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Số điện thoại</label>
                        <input name="phone" type="number" class="form-control" placeholder="Địa chỉ email"
                            value="<?php
                                    echo oldData($oldData, 'phone');
                                    ?>">
                        <?php
                        echo form_error($errors, 'phone', '<span class="error">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Pasword</label>
                        <input name="password" type="text" class="form-control" placeholder="Mật khẩu">
                        <?php
                        echo form_error($errors, 'password', '<span class="error">', '</span>');
                        ?>
                    </div>
                    <div class="form-group  mg-form">
                        <label for="">Nhập lại Pasword</label>
                        <input name="re_password" type="password" class="form-control"
                            placeholdDataer="Nhập lại mật khẩu">
                        <?php
                        echo form_error($errors, 're_password', '<span class="error">', '</span>');
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (oldData($oldData, 'status') == 0) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                            <option value="1" <?php echo (oldData($oldData, 'status') == 1) ? 'selected' : false; ?>>Đã kích hoạt</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Phân quyền</label>
                        <select name="role" class="form-control">
                            <option value="0" <?php echo (oldData($oldData, 'role') == 0) ? 'selected' : false; ?>>Khách hàng</option>
                            <option value="2" <?php echo (oldData($oldData, 'role') == 2) ? 'selected' : false; ?>>Manager</option>
                            <option value="3" <?php echo (oldData($oldData, 'role') == 3) ? 'selected' : false; ?>>Employee</option>
                        </select>
                    </div>

                </div>
            </div>


            <button type="submit" class="btn-user mg-btn btn btn-primary btn-block">Thêm người dùng</button>
            <a href="?module=users&action=list" class="btn-user mg-btn btn btn-success btn-block">Quay lại</a>

            <hr>


        </form>
    </div>
</div>


<?php
layout('header');
?>