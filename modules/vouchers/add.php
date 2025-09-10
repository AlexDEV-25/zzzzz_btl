<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

if (isPost()) {
    $filterAll = filter();

    $errors = []; // Mảng chứa lỗi

    // Validate code
    if (empty($filterAll['code'])) {
        $errors['code']['required'] = 'Vui lòng nhập mã voucher';
    }

    // Validate discount
    if (empty($filterAll['discount'])) {
        $errors['discount']['required'] = 'Vui lòng nhập mức giảm giá';
    } elseif (!is_numeric($filterAll['discount'])) {
        $errors['discount']['invalid'] = 'Giảm giá phải là số';
    }

    // Validate unit
    if (!isset($filterAll['unit'])) {
        $errors['unit']['required'] = 'Vui lòng chọn đơn vị giảm giá';
    }

    // Validate start date
    if (empty($filterAll['start'])) {
        $errors['start']['required'] = 'Vui lòng chọn ngày bắt đầu';
    }

    // Validate end date
    if (empty($filterAll['end'])) {
        $errors['end']['required'] = 'Vui lòng chọn ngày kết thúc';
    } elseif (!empty($filterAll['start']) && $filterAll['end'] < $filterAll['start']) {
        $errors['end']['invalid'] = 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu';
    }

    if (empty($errors)) {
        $dataInsert = [
            'code' => $filterAll['code'],
            'discount' => $filterAll['discount'],
            'unit' => $filterAll['unit'],
            'start' => $filterAll['start'],
            'end' => $filterAll['end'],
        ];

        $insertStatus = insert('vouchers', $dataInsert);
        if ($insertStatus) {
            setFlashData('smg', 'Thêm voucher mới thành công!!');
            setFlashData('smg_type', 'success');
            redirect('?module=vouchers&action=list');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
            redirect('?module=vouchers&action=add');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('oldData', $filterAll);
        redirect('?module=vouchers&action=add');
    }
}

$title = [
    'pageTitle' => 'Thêm Voucher'
];

layout('header', $title);

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('oldData');
?>

<div class="container">
    <div class="row" style="margin: 50px auto; max-width: 600px;">
        <h2 class="text-center text-uppercase">Thêm Voucher</h2>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Mã voucher</label>
                <input name="code" type="text" class="form-control"
                    value="<?php echo oldData($oldData, 'code'); ?>">
                <?php echo form_error($errors, 'code', '<span class="error">', '</span>'); ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Giảm giá</label>
                <input name="discount" type="number" class="form-control"
                    value="<?php echo oldData($oldData, 'discount'); ?>">
                <?php echo form_error($errors, 'discount', '<span class="error">', '</span>'); ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Đơn vị</label>
                <select name="unit" class="form-control">
                    <option value="">-- Chọn đơn vị --</option>
                    <option value="0" <?php echo oldData($oldData, 'unit') === '0' ? 'selected' : ''; ?>>%</option>
                    <option value="1" <?php echo oldData($oldData, 'unit') === '1' ? 'selected' : ''; ?>>VNĐ</option>
                </select>
                <?php echo form_error($errors, 'unit', '<span class="error">', '</span>'); ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Ngày bắt đầu</label>
                <input name="start" type="date" class="form-control"
                    value="<?php echo oldData($oldData, 'start'); ?>">
                <?php echo form_error($errors, 'start', '<span class="error">', '</span>'); ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Ngày kết thúc</label>
                <input name="end" type="date" class="form-control"
                    value="<?php echo oldData($oldData, 'end'); ?>">
                <?php echo form_error($errors, 'end', '<span class="error">', '</span>'); ?>
            </div>

            <button type="submit" class="btn-user mg-btn btn btn-primary btn-block">Thêm Voucher</button>
            <a href="?module=vouchers&action=list" class="btn-user mg-btn btn btn-success btn-block">Quay lại</a>
        </form>
    </div>
</div>

<?php
layout('footer');
?>