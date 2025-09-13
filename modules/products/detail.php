<?php
$previewProduct = getSession('previewProduct');
$filterAll = filter();
$role = $filterAll['role'];
if (empty($previewProduct)) {
    redirect('?module=products&action=add&role' . $role);
}

if (isPost()) {
    $errors = [];
    // Validate
    if (empty($filterAll['amount'])) {
        $errors['amount']['required'] = 'lỗi không nhập';
    } elseif ($filterAll['amount'] < 0) {
        $errors['amount']['min'] = 'số lượng không được nhỏ hơn 0';
    }

    if (empty($filterAll['size'])) {
        $errors['size']['required'] = 'lỗi không nhập';
    }

    if (empty($filterAll['color'])) {
        $errors['color']['required'] = 'lỗi không nhập';
    }

    if (empty($errors)) {
        // B1: insert vào products
        $productInserts = insert('products', $previewProduct);

        $productName = $previewProduct["name_product"];
        $product = selectOne("SELECT * FROM products WHERE name_product = '$productName'");
        $productId = $product["id"];
        if ($productId) {
            // B2: upload ảnh nếu có
            $image = '';
            if (!empty($_FILES['image']['name'])) {
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    _WEB_PATH . '\\templates\\image\\products\\' . $_FILES['image']['name']
                );
                $image = (string)$_FILES['image']['name'];
            }

            // B3: insert chi tiết
            $dataDetail = [
                'amount' => $filterAll['amount'],
                'code_color' => $filterAll['code_color'],
                'color' => $filterAll['color'],
                'image' => $image,
                'size' => $filterAll['size'],
                'id_product' => $productId, // ✅ luôn có giá trị
            ];

            $insertStatus = insert('products_detail', $dataDetail);

            if ($insertStatus) {
                removeSession('previewProduct');
                setFlashData('smg', '✅ Thêm sản phẩm mới thành công!!');
                setFlashData('smg_type', 'success');
                redirect('?module=products&action=list&role=' . $role);
            } else {
                setFlashData('smg', '❌ Không thể lưu chi tiết sản phẩm.');
                setFlashData('smg_type', 'danger');
                redirect('?module=products&action=detail&role=' . $role);
            }
        } else {
            setFlashData('smg', '❌ Không thể lưu sản phẩm.');
            setFlashData('smg_type', 'danger');
            redirect('?module=products&action=detail&role=' . $role);
        }
    } else {
        setFlashData('smg', '❌ Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('oldData', $filterAll);
        redirect('?module=products&action=detail&role=' . $role);
    }
}
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('oldData');

$data = ['pageTitle' => 'Chi tiết sản phẩm'];
layout('header', $data);
?>

<body>
    <div class="container">
        <div class="row" style="margin: 50px auto;">
            <h2 class="text-center text-uppercase">Thêm chi tiết sản phẩm</h2>
            <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>
            <form method="POST" enctype="multipart/form-data">
                <input name="role" type="hidden" value="<?php echo $role; ?>">
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

                    <div class="upload-container row" style="margin-left: 5px;">
                        <div id="uploadForm" class="row">
                            <h2>Upload và Xem Trước Ảnh</h2>
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

                <button type="submit" class="btn-user mg-btn btn btn-primary btn-block">Lưu và thêm mới</button>
                <a href="?module=products&action=list&role=<?php echo $role; ?>" class="btn-user mg-btn btn btn-success btn-block">Quay lại</a>
            </form>
            <hr style="margin-top: 25px;">
        </div>
    </div>
</body>
<?php layout('footer'); ?>