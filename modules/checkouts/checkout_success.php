<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$filterAll = filter();
$userId =  $filterAll['userId'];
$cartCount = getCountCart($userId);
// Lấy thông tin flash
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$data = [
    'pageTitle' => "Đặt hàng thành công",
    'role' => 0,
    'count' => $cartCount,
    'userId' => $userId
];
layout('header_custom', $data);
?>

<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-100 to-blue-200 p-6">
        <div class="bg-white shadow-2xl rounded-3xl max-w-xl w-full p-8 text-center">
            <div class="mb-6">
                <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m2 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Đặt hàng thành công!</h1>
            <?php if (!empty($smg)):
                getSmg($smg, $smg_type); ?>
            <?php else: ?>
                <p class="text-lg text-gray-600 mb-6">Cảm ơn bạn đã mua sắm. Đơn hàng của bạn đang được xử lý.</p>
            <?php endif; ?>
            <div class="flex justify-center gap-4 mt-6">
                <a href="?module=home&action=dashboard&role=0&userId=<?php echo $userId;  ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</body>
<?php layout('footer'); ?>