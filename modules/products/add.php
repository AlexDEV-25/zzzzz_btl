<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

if (isPost()) {
    $filterAll = filter();
    $errors = []; // Mảng chữa các lỗi

    // validate name

    if (empty($filterAll['name_product'])) {
        $errors['name_product']['required'] = 'lỗi không nhập';
    } else {
        if (!productUnique($filterAll['name_product'])) {
            $errors['name_product']['unique'] = 'sản phẩm đã tồn tại';
        }
    }

    if (empty($filterAll['price'])) {
        $errors['price']['required'] = 'lỗi không nhập';
    } else {
        if ($filterAll['price'] < 0) {
            $errors['price']['min'] = 'giá không thể âm ';
        }
    }

    if (empty($filterAll['origin_price'])) {
        $errors['origin_price']['required'] = 'lỗi không nhập';
    } else {
        if ($filterAll['origin_price'] < 0) {
            $errors['origin_price']['min'] = 'giá không thể âm ';
        }
    }

    if (empty($filterAll['material'])) {
        $errors['material']['required'] = 'lỗi không nhập';
    }

    if (empty($filterAll['description'])) {
        $errors['description']['required'] = 'lỗi không nhập';
    }

    if (empty($errors)) {

        $result = move_uploaded_file(
            $_FILES['thumbnail']['tmp_name'], // đường đẫn gốc
            _WEB_PATH . '\\templates\\image\\products\\' . // đường đẫn  mới nhớ thêm \\ ở cuối
                $_FILES['thumbnail']['name']
        ); // file  muốn chuyển
        $anh_string = (string)$_FILES['thumbnail']['name'];
        $thumbnail = $anh_string;
        $dataInsert = [
            'name_product' => $filterAll['name_product'],
            'price' => $filterAll['price'],
            'origin_price' => $filterAll['origin_price'],
            'material' => $filterAll['material'],
            'id_category' => $filterAll['id_category'],
            'description' => $filterAll['description'],
            'created_at' => date('Y-m-d H:i:s'),
            'thumbnail' => $thumbnail,
        ];

        $insertStatus = insert('products', $dataInsert);
        if ($insertStatus) {
            // setFlashData('smg', 'Thêm sản phẩm mới thành công!!');
            // setFlashData('smg_type', 'success');

            $name_product = $filterAll['name_product'];
            $nameQuery = selectOne("SELECT id FROM products WHERE name_product = '$name_product'");
            if (!empty($nameQuery)) {
                $productId = $nameQuery['id'];
                setSession('productId', $productId);
                redirect('?module=products&action=detail&id=' . $productId);
            } else {
                redirect('?module=products&action=list');
            }
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
            redirect('?module=products&action=add');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('oldData', $filterAll);
        redirect('?module=products&action=add');
    }
}
$title = [
    'pageTitle' => 'Thêm sản phẩm'
];

layout('header', $title);


$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('oldData');



?>

<div class="container">
    <div class="row" style="margin: 50px auto;">

        <h2 class="text-center text-uppercase">Thêm sản phẩm</h2>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }

        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Tên sản phẩm</label>
                        <input name="name_product" type="text" class="form-control"
                            value="<?php
                                    echo oldData($oldData, 'name_product');
                                    ?>">
                        <?php
                        echo form_error($errors, 'name_product', '<span class="error">', '</span>');
                        ?>
                    </div>

                    <div class="form-group mg-form">
                        <label for="">Giá gốc</label>
                        <input name="origin_price" type="number" class="form-control"
                            value="<?php
                                    echo oldData($oldData, 'origin_price');
                                    ?>">
                        <?php
                        echo form_error($errors, 'origin_price', '<span class="error">', '</span>');
                        ?>
                    </div>


                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select name="id_category" class="form-control">

                            <?php
                            $listCategories = selectAll("SELECT * FROM categories ORDER BY id");

                            if (!empty($listCategories)):
                                foreach ($listCategories as $item):
                            ?>
                                    <option value="<?php echo $item['id']; ?>
                                     <?php echo (oldData($oldData, 'id_category') == 0) ? 'selected' : false; ?>"><?php echo $item['name_category']; ?></option>

                                <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-danger text-center">Không có người dùng nào!!</div>
                                    </td>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">giá bán</label>
                        <input name="price" type="number" class="form-control"
                            value="<?php
                                    echo oldData($oldData, 'price');
                                    ?>">
                        <?php
                        echo form_error($errors, 'price', '<span class="error">', '</span>');
                        ?>
                    </div>

                    <div class="form-group mg-form">
                        <label for="">Vật liệu</label>
                        <input name="material" type="text" class="form-control"
                            value="<?php
                                    echo oldData($oldData, 'material');
                                    ?>">
                        <?php
                        echo form_error($errors, 'material', '<span class="error">', '</span>');
                        ?>
                    </div>
                </div>

                <div>
                    <div class="form-group mg-form">
                        <label for="">Mô tả</label>
                        <input name="description" type="text" class="form-control"
                            value="<?php
                                    echo oldData($oldData, 'description');
                                    ?>">
                        <?php
                        echo form_error($errors, 'description', '<span class="error">', '</span>');
                        ?>
                    </div>

                    <div style="box-sizing: border-box; margin-left: 5px;" class="upload-container ">
                        <div id="uploadForm" class="row">
                            <h2>Chọn ảnh</h2>
                            <div class="file-upload col">
                                <label for="file">Chọn ảnh:</label>
                                <input type="file" id="file" accept="image/*" name="thumbnail">
                            </div>
                            <div id="preview-container" class="preview-container col">
                                <img id="preview" src="#" alt="Xem trước ảnh" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-user mg-btn btn btn-primary btn-block">Tiếp tục</button>
            <a href="?module=products&action=list" class="btn-user mg-btn btn btn-success btn-block">Quay lại</a>
        </form>
        <hr style="margin-top: 25px;">
    </div>
</div>


<?php
layout('footer');
?>