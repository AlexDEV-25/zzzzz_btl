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
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=don_hang_A7.doc");

echo "<html><head>
<style>
    /* Khổ giấy A7: 74mm × 105mm */
    @page {
        size: 74mm 105mm;
        margin: 10mm;
    }

    body { 
        font-family: 'Times New Roman', serif; 
        font-size: 11pt; 
        line-height: 1.4; 
        margin: 0; 
        padding: 0;
    }
    h2 { text-align: center; font-size: 14pt; margin-bottom: 10px; }
    h3 { font-size: 12pt; margin: 8px 0; text-align:center; }
    .bill-info { border: 1px solid #333; padding: 5px 8px; margin-bottom: 5px; }
    .bill-info p { margin: 2px 0; font-size: 10pt; }
    table { border-collapse: collapse; width: 100%; font-size: 9pt; margin-top: 5px; }
    th, td { border: 1px solid #555; padding: 3px; text-align: center; }
    th { background: #333; color: white; font-size: 9pt; }
    .total { font-weight: bold; font-size: 11pt; margin-top: 6px; text-align: right; }
    .voucher { margin-top: 3px; font-size: 10pt; }
    .sign { margin-top: 15px; text-align: right; font-size: 10pt; font-style: italic; }
    .page-break { page-break-after: always; }
</style>
</head><body>";

foreach ($listBills as $index => $bill) {
    $billId = (int)$bill['id'];
    update('bills', ['status' => 2], "id = $billId");

    // user
    $user = ['fullname' => 'Khách hàng không xác định'];
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

    echo "<h3>Đơn hàng #{$billId}</h3>";
    echo "<div class='bill-info'>
            <p><b>KH:</b> " . htmlspecialchars($user['fullname']) . "</p>
            <p><b>Địa chỉ:</b> " . htmlspecialchars($user['address']) . "</p>
            <p><b>Số điện thoại:</b> " . htmlspecialchars($user['phone']) . "</p>
            <p><b>Ngày:</b> " . htmlspecialchars($bill['date']) . "</p>
          </div>";

    echo "<table>
            <tr>
                <th>SP</th>
                <th>SL</th>
            </tr>";

    if (!empty($products_bill_list)) {
        foreach ($products_bill_list as $products_bill) {
            $pd_id = (int)$products_bill['id_product_detail'];
            $productsDetail = selectOne("SELECT * FROM products_detail WHERE id = $pd_id");
            $product_id = (int)$productsDetail['id_product'];
            $product = selectOne("SELECT * FROM products WHERE id = $product_id");
            $amount_buy = (int)$products_bill['amount_buy'];
            $productName = $product['name_product'] ?? $product['name'] ?? 'SP';
            echo "<tr>
                    <td>" . htmlspecialchars($productName) . "</td>
                    <td>{$amount_buy}</td>
                  </tr>";
        }
    }

    echo "</table>";
    echo "<p class='total'>Tổng: " . number_format($bill['total'] ?? 0, 0, '.', '.') . " ₫</p>";

    // ngắt trang A7 cho đơn tiếp theo
    if ($index < count($listBills) - 1) {
        echo "<div class='page-break'></div>";
    }
}

echo "</body></html>";
