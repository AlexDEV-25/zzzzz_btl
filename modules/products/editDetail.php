<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
$productId = $filterAll['id'];
if (!empty($productId)) {
    $detaiOld = selectOne("SELECT * FROM products_detail WHERE id_product = $productId");
    if (!empty($detaiOld)) {
        // Tồn tại 

        // echo '<pre>';
        // print_r($detaiOld);
        // echo '</pre>';
        // die();
        setFlashData('detaiOld', $detaiOld);
    } else {
        // redirect('?module=products&action=list');
    }
}

if (isPost()) {
    $errors = []; // Mảng chữa các lỗi

    if (empty($filterAll['amount'])) {
        $errors['amount']['required'] = 'lỗi không nhập';
    } else {
        if ($filterAll['amount'] < 0) {
            $errors['amount']['min'] = 'số lượng không được nhỏ hơn 0';
        }
    }

    if (empty($filterAll['size'])) {
        $errors['size']['required'] = 'lỗi không nhập';
    }

    if (empty($filterAll['color'])) {
        $errors['color']['required'] = 'lỗi không nhập';
    }

    if (empty($errors)) {
        $result = move_uploaded_file(
            $_FILES['image']['tmp_name'], // đường đẫn gốc
            _WEB_PATH . '\\templates\\image\\products\\' . // đường đẫn  mới nhớ thêm \\ ở cuối
                $_FILES['image']['name']
        ); // file  muốn chuyển
        $anh_string = (string)$_FILES['image']['name'];
        $image = $anh_string;

        $dataUpdate = [
            'amount' => $filterAll['amount'],
            'code_color' => $filterAll['code_color'],
            'color' => $filterAll['color'],
            'image' => $image,
            'size' => $filterAll['size'],
            'id_product' => $productId,
        ];

        $sql = "SELECT id FROM products_detail WHERE id_product = '$productId'";
        if (getCountRows($sql) > 0) {
            $condition = "id_product = $productId";
            $UpdateStatus = update('products_detail', $dataUpdate, $condition);
        } else {
            $UpdateStatus = insert('products_detail', $dataUpdate);
        }
        if ($UpdateStatus) {
            setFlashData('smg', 'Sửa sản phẩm dùng thành công!!');
            setFlashData('smg_type', 'success');
            // removeSession('productId');
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

    redirect('?module=products&action=editDetail&id=' . $productId);
}


layout('header');


$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('old');
$oldData = getFlashData('detaiOld');


if (!empty($detailOld)) {
    $oldData = $detailOld;
}
?>

<div class="container">
    <div class="row" style="margin: 50px auto;">

        <h2 class="text-center text-uppercase">Update sản phẩm </h2>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }

        ?>
        <form action="" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Số lượng</label>
                        <input name="amount" type="number" class="form-control"
                            value="<?php echo oldData($oldData, 'amount'); ?>">
                        <?php echo form_error($errors, 'amount', '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group mg-form">
                        <label for="">Tên màu</label>
                        <input name="color" type="text" class="form-control"
                            value="<?php echo oldData($oldData, 'color'); ?>">
                        <?php echo form_error($errors, 'color', '<span class="error">', '</span>'); ?>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Kích thước</label>
                        <input name="size" type="text" class="form-control"
                            value="<?php echo oldData($oldData, 'size'); ?>">
                        <?php echo form_error($errors, 'size', '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group mg-form">
                        <div class="color-picker">
                            <div class="color-preview" id="colorPreview">#000000</div>
                            <div class="color-input">
                                <label for="colorInput">Chọn màu:</label>
                                <input type="color" id="colorInput" value="#000000" name="code_color">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="box-sizing: border-box; margin-left: 5px;" class="upload-container ">
                    <div id="uploadForm" class="row">
                        <div style=" padding-top: 50px;box-sizing: border-box;" class="col">
                            <h2 style="font-size: 25px;">Ảnh cũ</h2>
                            <div>
                                <img style="width: 500px; height: 300px;box-sizing: border-box; margin-top: 30px;"
                                    src="<?php echo _IMGP_ . $oldData['image']; ?>"
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


            <input type="hidden" name="id" value="<?php echo $productId ?>">

            <button type="submit" class="btn-user mg-btn btn btn-primary btn-block">Update sản phẩm</button>
            <a href="?module=products&action=list" class="btn-user mg-btn btn btn-success btn-block">Quay lại</a>
            <hr>
        </form>
    </div>
</div>


<?php
layout('footer');
?>