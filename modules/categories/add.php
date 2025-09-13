<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

if (isPost()) {
    $filterAll = filter();
    $errors = []; // Mảng chữa các lỗi

    // validate name
    if (empty($filterAll['name_category'])) {
        $errors['name_category']['required'] = 'lỗi không nhập';
    }

    if (empty($errors)) {
        $result = move_uploaded_file(
            $_FILES['image']['tmp_name'], // đường đẫn gốc
            _WEB_PATH . '\\templates\\image\\categories\\' . // đường đẫn  mới nhớ thêm \\ ở cuối
                $_FILES['image']['name']
        ); // file  muốn chuyển
        $anh_string = (string)$_FILES['image']['name'];
        $image = $anh_string;
        $dataInsert = [
            'name_category' => $filterAll['name_category'],
            'image' => $image,
        ];
        $insertStatus = insert('categories', $dataInsert);
        if ($insertStatus) {
            setFlashData('smg', 'Thêm danh mục mới thành công!!');
            setFlashData('smg_type', 'success');
            redirect('?module=categories&action=list');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
            redirect('?module=categories&action=add');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('oldData', $filterAll);
        redirect('?module=categories&action=add');
    }
}
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('oldData');
$data = [
    'pageTitle' => 'Thêm danh mục'
];
layout('header', $data);
?>

<body>
    <div class="container">
        <div class="row" style="margin: 50px auto;">

            <h2 class="text-center text-uppercase">Thêm danh mục</h2>
            <?php
            if (!empty($smg)) {
                getSmg($smg, $smg_type);
            }

            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class=" row">
                    <div class="col">
                        <div class="form-group mg-form">
                            <label for="">Tên danh mục</label>
                            <input name="name_category" type="text" class="form-control"
                                value="<?php
                                        echo oldData($oldData, 'name_category');
                                        ?>">
                            <?php
                            echo form_error($errors, 'name_category', '<span class="error">', '</span>');
                            ?>
                        </div>

                        <div style="box-sizing: border-box; margin-left: 5px;" class="upload-container ">
                            <div id="uploadForm" class="row">
                                <h2>Chọn ảnh</h2>
                                <div class="file-upload col">
                                    <label for="file">Chọn ảnh:</label>
                                    <input type="file" id="file" accept="image/*" name="image">
                                </div>
                                <div id="preview-container" class="preview-container col">
                                    <img id="preview" src="#" alt="Xem trước ảnh" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <button type="submit" class="btn-user mg-btn btn btn-primary btn-block">Thêm danh mục</button>
                <a href="?module=categories&action=list" class="btn-user mg-btn btn btn-success btn-block">Quay lại</a>

                <hr>


            </form>
        </div>
    </div>
</body>
<?php layout('footer'); ?>