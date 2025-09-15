<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$filterAll = filter();
$role = $filterAll['role'];
$userId = $filterAll['userId'];
$userDetail = selectOne("SELECT * FROM users WHERE id = $userId");
if (!empty($userDetail)) {
    // Tồn tại 
    setFlashData('user-detail', $userDetail);
} else {
    redirect('?module=home&action=dashboard');
}

if (isPost()) {
    $errors = []; // Mảng chữa các lỗi

    // Validate fullname: bắt buộc phải nhập, min 5 ký tự
    if (empty($filterAll['fullname'])) {
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập.';
    } else {
        if (strlen($filterAll['fullname']) < 5) {
            $errors['fullname']['min'] = 'Họ tên phải có ít nhất 5 ký tự.';
        }
    }


    // Email Validate: bắt buộc phải nhập, đúng đinh dạng email, kiểm tra email đã tồn tại trong csdl chưa

    if (empty($filterAll['email'])) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập.';
    } else {
        $email = $filterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email' AND id <> '$userId' ";
        // id <> $userId tương tự với id khác $userId 
        if (getCountRows($sql) > 0) {
            $errors['email']['unique'] = 'Email đã tồn tại.';
        }
    }

    // Validate số điện thoại: bắt buộc phải nhập, số có đúng định dạng không
    if (empty($filterAll['phone'])) {
        $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập.';
    } else {
        if (!isPhone($filterAll['phone'])) {
            $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ.';
        }
    }

    if (!empty($filterAll['password'])) {
        // Validate pasword_confirm: bắt buộc phải nhập, giống password
        if (empty($filterAll['re_password'])) {
            $errors['re_password']['required'] = 'Bạn phải nhập lại mật khẩu.';
        } else {
            if (($filterAll['password']) != $filterAll['re_password']) {
                $errors['re_password']['match'] = 'Mật khẩu bạn nhập lại không đúng.';
            }
        }
    }


    if (empty($errors)) {
        $result = move_uploaded_file(
            $_FILES['avatar']['tmp_name'], // đường đẫn gốc
            _WEB_PATH . '\\templates\\image\\avatars\\' . // đường đẫn  mới nhớ thêm \\ ở cuối
                $_FILES['avatar']['name']
        ); // file  muốn chuyển
        $anh_string = (string)$_FILES['avatar']['name'];
        $avatar = $anh_string;

        $dataUpdate = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'address' => $filterAll['address'],
            'avatar' => $avatar,
            'update_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($filterAll['password'])) {
            $dataUpdate['password'] = password_hash($filterAll['password'], PASSWORD_DEFAULT);
        }

        $condition = "id = $userId";
        $UpdateStatus = update('users', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', '✅ Sửa thông tin thành công!!');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', '❌ Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', '❌ Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
    }

    redirect('?module=home&action=customEdit&role=0&userId=' . $userId);
}
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
//1
$oldData = getFlashData('old');
$userDetailll = getFlashData('user-detail');
if (!empty($userDetailll)) {
    //2
    $oldData = $userDetailll;
}
$data = [
    'pageTitle' => "thông tin người dùng",
];
layout('header', $data);
?>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-6xl bg-white shadow-2xl rounded-3xl p-12">
        <h2 class="text-3xl font-extrabold text-center uppercase text-gray-700 mb-10">Update người dùng</h2>

        <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <!-- Cột 1 -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-lg text-gray-700 mb-2">Họ tên</label>
                        <input name="fullname" type="text" placeholder="Họ tên"
                            value="<?php echo oldData($oldData, 'fullname'); ?>"
                            class="w-full text-lg rounded-xl border border-gray-300 px-5 py-3 focus:ring-4 focus:ring-blue-400 focus:outline-none">
                        <?php echo form_error($errors, 'fullname', '<span class="text-red-500 text-base">', '</span>'); ?>
                    </div>

                    <div>
                        <label class="block text-lg text-gray-700 mb-2">Email</label>
                        <input name="email" type="email" placeholder="Địa chỉ email"
                            value="<?php echo oldData($oldData, 'email'); ?>"
                            class="w-full text-lg rounded-xl border border-gray-300 px-5 py-3 focus:ring-4 focus:ring-blue-400 focus:outline-none">
                        <?php echo form_error($errors, 'email', '<span class="text-red-500 text-base">', '</span>'); ?>
                    </div>

                    <div>
                        <label class="block text-lg text-gray-700 mb-2">Số điện thoại</label>
                        <input name="phone" type="number" placeholder="Điện thoại"
                            value="<?php echo oldData($oldData, 'phone'); ?>"
                            class="w-full text-lg rounded-xl border border-gray-300 px-5 py-3 focus:ring-4 focus:ring-blue-400 focus:outline-none">
                        <?php echo form_error($errors, 'phone', '<span class="text-red-500 text-base">', '</span>'); ?>
                    </div>
                </div>

                <!-- Cột 2 -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-lg text-gray-700 mb-2">Địa chỉ</label>
                        <input name="address" type="text" placeholder="Nhập địa chỉ"
                            value="<?php echo oldData($oldData, 'address'); ?>"
                            class="w-full text-lg rounded-xl border border-gray-300 px-5 py-3 focus:ring-4 focus:ring-blue-400 focus:outline-none">
                        <?php echo form_error($errors, 'address', '<span class="text-red-500 text-base">', '</span>'); ?>
                    </div>

                    <div>
                        <label class="block text-lg text-gray-700 mb-2">Password</label>
                        <input name="password" type="text" placeholder="Mật khẩu (không nhập nếu không thay đổi)"
                            class="w-full text-lg rounded-xl border border-gray-300 px-5 py-3 focus:ring-4 focus:ring-blue-400 focus:outline-none">
                        <?php echo form_error($errors, 'password', '<span class="text-red-500 text-base">', '</span>'); ?>
                    </div>

                    <div>
                        <label class="block text-lg text-gray-700 mb-2">Nhập lại Password</label>
                        <input name="re_password" type="password" placeholder="Nhập lại mật khẩu (không nhập nếu không thay đổi)"
                            class="w-full text-lg rounded-xl border border-gray-300 px-5 py-3 focus:ring-4 focus:ring-blue-400 focus:outline-none">
                        <?php echo form_error($errors, 're_password', '<span class="text-red-500 text-base">', '</span>'); ?>
                    </div>
                </div>

                <!-- Cột 3 -->
                <div class="space-y-6">
                    <h4 class="font-bold text-xl text-gray-700">Chọn ảnh</h4>
                    <div class="p-6 border-2 border-dashed border-gray-400 rounded-2xl">
                        <label class="block text-lg text-gray-600 mb-3">Chọn avatar:</label>
                        <input type="file" id="file" accept="image/*" name="avatar"
                            class="block w-full text-lg text-gray-600 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition">

                        <div id="preview-container" class="mt-6">
                            <img id="preview" src="#" alt="Xem trước ảnh" class="hidden w-40 h-40 object-cover rounded-xl border border-gray-300">
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="userId" value="<?php echo $userId ?>">
            <input type="hidden" name="role" value="<?php echo $role ?>">

            <div class="flex flex-col md:flex-row gap-6">
                <button type="submit"
                    class="flex-1 text-lg bg-blue-600 text-white font-semibold py-4 px-6 rounded-xl hover:bg-blue-700 transition">Update thông tin</button>
                <a href="?module=home&action=dashboard&role=0&userId=<?php echo $userId; ?>"
                    class="flex-1 text-lg bg-green-600 text-white font-semibold py-4 px-6 rounded-xl text-center hover:bg-green-700 transition">Quay lại</a>
            </div>
        </form>
    </div>
</body>

<?php layout('footer'); ?>