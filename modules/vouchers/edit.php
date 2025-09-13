<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();

if (!empty($filterAll['id'])) {
    $voucherId = $filterAll['id'];

    // Kiểm tra tồn tại voucher
    $voucherOld = selectOne("SELECT * FROM vouchers WHERE id = $voucherId");
    if (!empty($voucherOld)) {
        setFlashData('voucherOld', $voucherOld);
    } else {
        redirect('?module=vouchers&action=list');
    }
}

if (isPost()) {
    $filterAll = filter();
    $errors = [];

    // Validate
    if (empty($filterAll['code'])) {
        $errors['code']['required'] = 'Vui lòng nhập mã voucher';
    }
    if (empty($filterAll['discount'])) {
        $errors['discount']['required'] = 'Vui lòng nhập giá trị giảm';
    }
    if (empty($filterAll['start'])) {
        $errors['start']['required'] = 'Vui lòng chọn ngày bắt đầu';
    }
    if (empty($filterAll['end'])) {
        $errors['end']['required'] = 'Vui lòng chọn ngày kết thúc';
    }

    if (empty($errors)) {
        $dataUpdate = [
            'code' => $filterAll['code'],
            'discount' => $filterAll['discount'],
            'unit' => $filterAll['unit'], // 0 = %, 1 = VNĐ
            'start' => $filterAll['start'],
            'end' => $filterAll['end'],
        ];

        $condition = "id = $voucherId";
        $UpdateStatus = update('vouchers', $dataUpdate, $condition);

        if ($UpdateStatus) {
            setFlashData('smg', 'Cập nhật voucher thành công!');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Hệ thống lỗi, vui lòng thử lại!');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu nhập!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
    }

    redirect('?module=vouchers&action=edit&id=' . $voucherId);
}

layout('header');

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('old');
$voucherOld = getFlashData('voucherOld');

if (!empty($voucherOld)) {
    $oldData = $voucherOld;
}
?>

<body>
    <div class="container">
        <div class="row" style="margin: 50px auto;">
            <h2 class="text-center text-uppercase">Update Voucher</h2>

            <?php
            if (!empty($smg)) {
                getSmg($smg, $smg_type);
            }
            ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="code">Mã voucher</label>
                    <input type="text" class="form-control" name="code"
                        value="<?php echo oldData($oldData, 'code'); ?>">
                    <?php echo form_error($errors, 'code', '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="discount">Giá trị giảm</label>
                    <input type="number" class="form-control" name="discount"
                        value="<?php echo oldData($oldData, 'discount'); ?>">
                    <?php echo form_error($errors, 'discount', '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="unit">Đơn vị</label>
                    <select name="unit" class="form-control">
                        <option value="0" <?php echo (oldData($oldData, 'unit') == 0) ? 'selected' : ''; ?>>%</option>
                        <option value="1" <?php echo (oldData($oldData, 'unit') == 1) ? 'selected' : ''; ?>>VNĐ</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start">Ngày bắt đầu</label>
                    <input type="date" class="form-control" name="start"
                        value="<?php echo oldData($oldData, 'start'); ?>">
                    <?php echo form_error($errors, 'start', '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="end">Ngày kết thúc</label>
                    <input type="date" class="form-control" name="end"
                        value="<?php echo oldData($oldData, 'end'); ?>">
                    <?php echo form_error($errors, 'end', '<span class="error">', '</span>'); ?>
                </div>

                <input type="hidden" name="id" value="<?php echo $voucherId; ?>">

                <button type="submit" class="btn btn-primary btn-block">Cập nhật voucher</button>
                <a href="?module=vouchers&action=list" class="btn btn-success btn-block">Quay lại</a>
            </form>
        </div>
    </div>
</body>
<?php layout('footer'); ?>