<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();

if (!empty($filterAll['id'])) {
    $productId = $filterAll['id'];

    // Kiểm tra xem userId nó tồn tại trong database không?
    // Nếu tồn tại => Lấy ra thông tin người dùng
    // Nếu không tồn tại => Chuyển hướng về trang list

    $productOld = selectOne("SELECT * FROM products WHERE id = $productId");
    if (!empty($productOld)) {
        // Tồn tại 

        // echo '<pre>';
        // print_r($productOld);
        // echo '</pre>';
        // die();
        setFlashData('productOld', $productOld);
    } else {
        redirect('?module=products&action=list');
    }
}

if (isPost()) {
    $filterAll = filter();

    $errors = []; // Mảng chữa các lỗi

    if (empty($filterAll['name_product'])) {
        $errors['name_product']['required'] = 'lỗi không nhập';
    } else {
        $name_product = $filterAll['name_product'];
        $sql = "SELECT id FROM products WHERE name_product = '$name_product' AND id <> '$productId' ";
        // id <> $productId tương tự với id khác $productId 
        if (getCountRows($sql) > 0) {
            $errors['name_product']['unique'] = 'sản phẩm đã tồn tại.';
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

        $dataUpdate = [
            'name_product' => $filterAll['name_product'],
            'price' => $filterAll['price'],
            'origin_price' => $filterAll['origin_price'],
            'material' => $filterAll['material'],
            'id_category' => $filterAll['id_category'],
            'description' => $filterAll['description'],
            'created_at' => date('Y-m-d H:i:s'),
            'thumbnail' => $thumbnail,
        ];

        $condition = "id = $productId";
        $UpdateStatus = update('products', $dataUpdate, $condition);
        if ($UpdateStatus) {
            // setFlashData('smg', 'Sửa sản phẩm dùng thành công!!');
            // setFlashData('smg_type', 'success');
            $name_product = $filterAll['name_product'];
            $nameQuery = selectOne("SELECT id FROM products WHERE name_product = '$name_product'");
            if (!empty($nameQuery)) {
                $productId = $nameQuery['id'];
                // setSession('productId', $productId); // quan trọng
                redirect('?module=products&action=editDetail&id=' . $productId);
            } else {
                redirect('?module=products&action=list');
            }
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

    redirect('?module=products&action=edit&id=' . $productId);
}


layout('header');


$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('old');
$productOld = getFlashData('productOld');
if (!empty($productOld)) {
    $oldData = $productOld;
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
                                        <div class="alert alert-danger text-center">Không có danh mục nào nào!!</div>
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
                            <div style=" padding-top: 50px;box-sizing: border-box;" class="col">
                                <h2 style="font-size: 25px;">Ảnh cũ</h2>
                                <div>
                                    <img style="width: 500px; height: 300px;box-sizing: border-box; margin-top: 30px;"
                                        src="<?php echo  _IMGP_ . $oldData['thumbnail']; ?>"
                                        alt="Ảnh cũ">
                                </div>
                            </div>
                            <div class="col">
                                <h2>Chọn ảnh mới</h2>
                                <div class="file-upload">
                                    <label for="file">Chọn ảnh:</label>
                                    <input type="file" id="file" accept="image/*" name="thumbnail">
                                </div>
                                <div id="preview-container" class="preview-container">
                                    <img id="preview" src="#" alt="Xem trước ảnh" style="display: none;">
                                </div>
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