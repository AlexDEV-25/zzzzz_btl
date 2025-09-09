<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();

if (!empty($filterAll['id'])) {
    $categoryId = $filterAll['id'];

    // Kiểm tra xem userId nó tồn tại trong database không?
    // Nếu tồn tại => Lấy ra thông tin người dùng
    // Nếu không tồn tại => Chuyển hướng về trang list

    $categoryOld = selectOne("SELECT * FROM categories WHERE id = $categoryId");
    if (!empty($categoryOld)) {
        // Tồn tại 

        // echo '<pre>';
        // print_r($categoryOld);
        // echo '</pre>';
        // die();
        setFlashData('categoryOld', $categoryOld);
    } else {
        redirect('?module=categoies&action=list');
    }
}

if (isPost()) {
    $filterAll = filter();

    $errors = []; // Mảng chữa các lỗi

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

        $dataUpdate = [
            'name_category' => $filterAll['name_category'],
            'image' => $image,
        ];

        $condition = "id = $categoryId";
        $UpdateStatus = update('categories', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', 'Sửa danh mục dùng thành công!!');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
    }

    redirect('?module=categories&action=edit&id=' . $categoryId);
}


layout('header');


$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('old');
$categoryOld = getFlashData('categoryOld');
if (!empty($categoryOld)) {
    $oldData = $categoryOld;
}
?>

<div class="container">
    <div class="row" style="margin: 50px auto;">

        <h2 class="text-center text-uppercase">Update danh mục </h2>
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
                            value="<?php echo oldData($oldData, 'name_category'); ?>">
                        <?php echo form_error($errors, 'name_category', '<span class="error">', '</span>'); ?>
                    </div>

                    <div style="box-sizing: border-box; margin-left: 5px;" class="upload-container ">
                        <div id="uploadForm" class="row">
                            <div style=" padding-top: 50px;box-sizing: border-box;" class="col">
                                <h2 style="font-size: 25px;">Ảnh cũ</h2>
                                <div>
                                    <img style="width: 500px; height: 300px;box-sizing: border-box; margin-top: 30px;" src="<?php echo  _IMGC_ . $oldData['image']; ?>"
                                        alt="Ảnh cũ">
                                </div>
                            </div>
                            <div class="col">
                                <h2>Chọn ảnh mới</h2>
                                <div class="file-upload">
                                    <label for="file">Chọn ảnh:</label>
                                    <input type="file" id="file" accept="image/*" name="image">
                                </div>
                                <div id="preview-container" class="preview-container">
                                    <img id="preview" src="#" alt="Xem trước ảnh" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo $categoryId ?>">

            <button type="submit" class="btn-user mg-btn btn btn-primary btn-block">Update danh mục</button>
            <a href="?module=categories&action=list" class="btn-user mg-btn btn btn-success btn-block">Quay lại</a>

            <hr>


        </form>
    </div>
</div>


<?php
layout('footer');
?>