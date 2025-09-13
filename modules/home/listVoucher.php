<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
$data = [
    'pageTitle' => 'Voucher khuyến mãi'
];
// header tuỳ theo user
if (!empty($filterAll['userId'])) {
    $userId = $filterAll['userId'];
    $cartCount = getCountCart($userId);
    $data = [
        'role' => 0,
        'count' => $cartCount,
        'userId' => $userId
    ];
    layout('header_custom', $data);
} else {
    layout('header_dashboard', $data);
}

// lấy dữ liệu voucher
if (!empty($filterAll['search'])) {
    $value = $filterAll['search'];
    $amount = getCountRows("SELECT * FROM vouchers WHERE code LIKE '%$value%'");
    if ($amount > 0) {
        $listVouchers = selectAll("SELECT * FROM vouchers WHERE code LIKE '%$value%'");
    } else {
        setFlashData('smg', 'Voucher không tồn tại');
        setFlashData('smg_type', 'danger');
    }
} else {
    $today = date("Y-m-d");
    $listVouchers = selectAll("SELECT * FROM vouchers WHERE start <= '$today' 
    AND end >= '$today' AND is_deleted = 0 ORDER BY id DESC");
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<body class="bg-gradient-to-r from-indigo-100 via-white to-pink-100 min-h-screen flex flex-col">

    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-4xl font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500 mb-4">
                VOUCHER MÃI CHẤT
            </h1>
            <p class="text-center text-lg text-gray-600 italic mb-10">Giảm deal chống deal 🔥</p>

            <?php if (!empty($smg)) {
                echo '<div class="mb-6 p-4 rounded-md text-center ' . ($smg_type == 'danger' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') . '">' . $smg . '</div>';
            } ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if (!empty($listVouchers)): ?>
                    <?php foreach ($listVouchers as $item): ?>
                        <div class="bg-white border-2 border-indigo-100 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
                            <div class="text-sm font-semibold text-gray-500 mb-2">Mã: <?php echo strtoupper($item['code']); ?></div>
                            <div class="text-3xl font-extrabold text-pink-600 mb-4">
                                <?php echo $item['unit'] == 0 ? $item['discount'] . "%" : number_format($item['discount'], 0, ',', '.') . "VND"; ?>
                            </div>
                            <button onclick="copyCode('<?php echo $item['code']; ?>')"
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-500 text-white text-sm font-bold rounded-lg shadow-md hover:from-pink-600 hover:to-purple-600">
                                Nhập mã ngay
                            </button>
                            <div class="text-xs text-gray-500 mt-3">
                                <?php echo date("d/m/Y", strtotime($item['start'])); ?> - <?php echo date("d/m/Y", strtotime($item['end'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="col-span-4 text-center text-red-500">Không có voucher nào khả dụng</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php
    if (!empty($filterAll['userId'])) {
        layout('footer_custom', $data);
    } else {
        layout('footer_dashboard', $data);
    }
    ?>

    <script>
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                alert("Đã copy mã: " + code);
            });
        }
    </script>
</body>
<?php
if (!empty($filterAll['userId'])) {
    $userId = $filterAll['userId'];
    $cartCount = getCountCart($userId);
    $data = [
        'role' => 0,
        'count' => $cartCount,
        'userId' => $userId
    ];
    layout('footer_custom', $data);
} else {
    layout('footer_dashboard', $data);
}
?>