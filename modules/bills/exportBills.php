<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
set_time_limit(0);

// Lấy tất cả đơn có status = 1 (đã xác nhận)
$listBills = selectAll("SELECT * FROM bills WHERE status = 1 ORDER BY id");
if (empty($listBills)) {
    echo "Không có đơn hàng nào để xuất.";
    exit;
}
foreach ($listBills as $bill) {
    $billId = isset($bill['id']) ? (int)$bill['id'] : 0;
    $dataUpdate = [
        'status' => 2,
    ];
    $ok = update('bills', $dataUpdate, "id = $billId");
}

// Xuất file Word
// header("Content-type: application/vnd.ms-word");
// header("Content-Disposition: attachment;Filename=don_hang_A7.doc");

echo "<html><head>
<style>
    @page {
        size: 74mm 105mm; /* Khổ A7 */
        margin: 0; /* Loại bỏ margin để vừa khổ giấy */
    }
    body {
        font-family: 'Arial', sans-serif;
        font-size: 10pt; /* Giảm cỡ chữ để vừa khổ */
        margin: 0;
        padding: 0;
        color: #333;
    }
    .label {
        width: 100%; /* Sử dụng 100% chiều rộng của @page */
        height: 100%; /* Sử dụng 100% chiều cao của @page */
        border: 2px solid #ff5500;
        padding: 4mm; /* Giảm padding để tiết kiệm không gian */
        box-sizing: border-box;
        background-color: #ffffff;
        border-radius: 0; /* Loại bỏ bo góc khi in */
    }
    .header {
        display: flex;
        justify-content: space-between; /* Phân chia hai bên */
        align-items: flex-start; /* Căn chỉnh theo đầu */
        border-bottom: 1px solid #ff5500;
        padding-bottom: 2mm;
        margin-bottom: 2mm;
    }
    .logo-container {
        display: flex;
        flex-direction: column; /* Sắp xếp logo và barcode theo cột */
        align-items: center;
    }
    .logo {
        display: flex;
        align-items: center;
        gap: 2mm;
        color: #ff5500;
    }
    .logo img {
        height: 6mm; /* Giảm kích thước logo */
        filter: brightness(0) saturate(100%) invert(17%) sepia(97%) saturate(3337%) hue-rotate(2deg) brightness(95%) contrast(105%);
    }
    .logo b {
        font-size: 10pt;
        font-weight: 600;
    }
.barcode {
    text-align: center;
    margin: 2mm 0;
    background-color: #f9f9f9;
    padding: 1mm;
    width: 60mm; /* Đặt chiều rộng cố định */
    height: 20mm; /* Chiều cao tự động điều chỉnh theo tỷ lệ */
    box-sizing: border-box; /* Bao gồm padding trong kích thước */
}

.barcode img {
    width: 60mm; /* Hình ảnh lấp đầy container */
    height: 20mm; /* Giữ tỷ lệ gốc */
    image-rendering: crisp-edges; /* Tối ưu hóa độ nét khi scale */
}
    .header-info {
        text-align: right; /* Căn phải cho thông tin */
    }
    .address-container {
        display: flex;
        justify-content: space-between;
        gap: 2mm; /* Khoảng cách giữa hai khối */
    }
    .section {
        border: 1px solid #ddd;
        padding: 2mm;
        background-color: #fafafa;
        flex: 1; /* Chia đều không gian */
    }
    .section p {
        margin: 1mm 0;
        line-height: 1.2;
    }
    .order-code {
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        background-color: #ff5500;
        color: #fff;
        padding: 2mm;
        margin: 2mm 0;
        text-transform: uppercase;
    }
    .items {
        border: 1px solid #ddd;
        padding: 2mm;
        margin-bottom: 2mm;
        background-color: #fafafa;
    }
    .items p {
        margin: 1mm 0;
    }
    .items ul {
        margin: 0;
        padding-left: 4mm;
    }
    .items ul li {
        margin-bottom: 1mm;
    }
    .flex {
        display: flex;
        justify-content: space-between;
        margin-top: 2mm;
    }
    .qr {
        text-align: center;
        margin-top: 2mm;
    }
    .qr img {
        width: 15mm; /* Giảm kích thước QR */
        height: 15mm;
        border: 1px solid #ff5500;
    }
    .footer {
        border-top: 1px dashed #ff5500;
        padding-top: 2mm;
        margin-top: 2mm;
    }
    .highlight {
        font-weight: bold;
        font-size: 12pt;
        color: #ff5500;
        margin: 2mm 0;
        text-align: center;
        background-color: #fff3e6;
        padding: 2mm;
    }
    .footer p {
        margin: 1mm 0;
    }
    .footer p:last-child {
        color: #ff0000;
        font-size: 9pt;
        text-align: center;
        background-color: #ffebee;
        padding: 1mm;
    }
    .page-break { page-break-after: always; }
</style>
</head><body>";

foreach ($listBills as $index => $bill) {
    $billId = (int)$bill['id'];
    update('bills', ['status' => 2], "id = $billId");

    // user
    $user = ['fullname' => 'Khách hàng không xác định', 'address' => 'Địa chỉ không xác định'];
    if (!empty($bill['id_user'])) {
        $tmp = selectOne("SELECT * FROM users WHERE id = " . (int)$bill['id_user']);
        if ($tmp) $user = $tmp;
    }

    // voucher
    $voucher = ['code' => ''];
    if (!empty($bill['id_voucher'])) {
        $tmp = selectOne("SELECT * FROM vouchers WHERE id = " . (int)$bill['id_voucher']);
        if ($tmp) $voucher = $tmp;
    }

    // sản phẩm
    $products_bill_list = selectAll("SELECT * FROM products_bill WHERE id_bill = $billId");
    echo "
    <div class='label'>
        <!-- Header -->
        <div class='header'>
            <div class='logo-container'>
                <div class='logo'>
                    <img src='https://upload.wikimedia.org/wikipedia/commons/1/1f/Shopee_logo.svg' alt='Shopee'>
                    <b>Shopee Xpress</b>
                </div>
                <div class='barcode'>
                  <img src='https://barcode.tec-it.com/barcode.ashx?data=ABC-abc-1234&code=Code128&translate-esc=on' alt='barcode'>
                </div>
            </div>
            <div class='header-info'>
                <p>Mã vận đơn: <b>SPXVN023856935$billId</b></p>
                <p>Mã đơn hàng: <b>221116BM5W6$billId</b></p>
            </div>
        </div>

        <!-- Người gửi và Người nhận -->
        <div class='address-container'>
            <div class='section'>
                <p><b>Từ:</b></p>
                <p>NỘI THẤT HOÀNG ĐỨC</p>
                <p>Số 2, TDP 4, Xuân Phương,Hà Nội</p>
                <p>SDT: 8438074</p>
            </div>
            <div class='section'>
                <p><b>Đến (Chỉ giao cho chính chủ):</b></p>
                <p>" . htmlspecialchars($user['fullname']) . "</p>
                <p>" . htmlspecialchars($user['address']) . "</p>
            </div>
        </div>

        <!-- Nội dung hàng -->
        <div class='items'>
            <p><b>Nội dung hàng (Tổng SL sản phẩm: " . (empty($products_bill_list) ? 0 : count($products_bill_list)) . "):</b></p>";
    if (!empty($products_bill_list)) {
        echo "<ul>";
        foreach ($products_bill_list as $products_bill) {
            $pd_id = (int)$products_bill['id_product_detail'];
            $productsDetail = selectOne("SELECT * FROM products_detail WHERE id = $pd_id");
            $product_id = (int)$productsDetail['id_product'];
            $product = selectOne("SELECT * FROM products WHERE id = $product_id");
            $amount_buy = (int)$products_bill['amount_buy'];
            $productName = $product['name_product'] ?? $product['name'] ?? 'SP';
            echo "<li>" . htmlspecialchars($productName) . " SL: $amount_buy</li>";
        }
        echo "</ul>";
    }
    echo "</div>";

    echo "<!-- QR và ngày đặt -->
        <div class='flex'>
            <div class='qr'>
                <img src='" . _IMGCS_ . "qrcode.png' alt='QR Code'>
            </div>
            <div>
                <p><b>Ngày đặt hàng:</b></p>
                <p>" . htmlspecialchars($bill['date']) . "</p>
            </div>
        </div>

        <!-- Tổng tiền -->
        <p class='highlight'>" . number_format($bill['total'] ?? 0, 0, '.', '.') . " VND</p>

        <!-- Footer -->
        <div class='footer'>
            <p>Khối lượng tịnh: <b>100kg</b></p>
            <p>Chữ ký người nhận: ____________________</p>
            <p>Xác nhận hàng nguyên vẹn, không móp méo, bể vỡ</p>
            <p style='color:red; font-size:9pt;'>Tuyển dụng Tài xế/Điều phối kho SPX - Thu nhập 8-20tr. Gọi 1900 6885</p>
        </div>
    </div>";

    // ngắt trang A7 cho đơn tiếp theo
    if ($index < count($listBills) - 1) {
        echo "<div class='page-break'></div>";
    }
}

echo "</body></html>";
