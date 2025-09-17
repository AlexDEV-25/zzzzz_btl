<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

$filterAll = filter();
if (!empty($filterAll['userId'])) {
    $userId = $filterAll['userId'];
    $cartCount = getCountCart($userId);
    $data = [
        'pageTitle' => 'Voucher khuyến mãi',
        'role' => 0,
        'count' => $cartCount,
        'userId' => $userId
    ];
    layout('header_custom', $data);
}

// lấy dữ liệu voucher
$today = date("Y-m-d");
$listVouchers = selectAll("SELECT * FROM vouchers WHERE start <= '$today' 
    AND end >= '$today' AND is_deleted = 0 ORDER BY id DESC");

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<body class="bg-white font-body">
    <!-- Success Notification -->
    <div id="notification" class="success-notification">
        <span id="notification-text">Đã sao chép mã voucher!</span>
    </div>

    <!-- Elegant Hero Section -->
    <div class="voucher-hero py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="hero-content text-center">
                <!-- Breadcrumb -->
                <nav class="breadcrumb mb-6">
                    <a href="?module=home&action=dashboard&role=0&userId=<?php echo $userId; ?>">Trang chủ</a> /
                    <span>Voucher khuyến mãi</span>
                </nav>

                <h1 class="font-display text-4xl lg:text-5xl font-semibold text-gray-900 mb-4 tracking-tight">
                    Voucher khuyến mãi
                </h1>
                <p class="text-xl text-gray-600 font-light leading-relaxed max-w-2xl mx-auto">
                    Tận hưởng những ưu đãi đặc biệt dành cho khách hàng thân thiết
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (!empty($smg)) getSmg($smg, $smg_type); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php if (!empty($listVouchers)): ?>
                    <?php
                    $animationIndex = 1;
                    foreach ($listVouchers as $item):
                    ?>
                        <div class="voucher-card rounded-2xl p-8 animate-slide-up stagger-<?php echo min($animationIndex, 4); ?>">
                            <!-- Voucher Code -->
                            <div class="text-center mb-6">
                                <div class="text-xs font-medium text-gray-500 mb-3 uppercase tracking-wider">
                                    Mã voucher
                                </div>
                                <div class="voucher-code inline-block px-4 py-2 rounded-lg text-sm font-bold">
                                    <?php echo strtoupper($item['code']); ?>
                                </div>
                            </div>

                            <!-- Discount Value -->
                            <div class="text-center mb-6">
                                <div class="discount-text text-4xl font-bold mb-2">
                                    <?php echo $item['unit'] == 0
                                        ? $item['discount'] . "%"
                                        : number_format($item['discount'], 0, ',', '.') . "₫"; ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo $item['unit'] == 0 ? 'Giảm phần trăm' : 'Giảm trực tiếp'; ?>
                                </div>
                            </div>

                            <!-- Copy Button -->
                            <div class="text-center mb-6">
                                <button onclick="copyCode('<?php echo $item['code']; ?>')"
                                    class="copy-btn w-full py-3 px-6 rounded-xl text-sm font-medium transition-all duration-200">
                                    Sao chép mã
                                </button>
                            </div>

                            <!-- Validity Period -->
                            <div class="text-center">
                                <div class="validity-period inline-block">
                                    <span class="text-xs">Có hiệu lực:</span><br>
                                    <span class="font-medium">
                                        <?php echo date("d/m/Y", strtotime($item['start'])); ?> -
                                        <?php echo date("d/m/Y", strtotime($item['end'])); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Terms Notice -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs text-gray-500 text-center">
                                    * Áp dụng theo điều kiện sử dụng
                                </p>
                            </div>
                        </div>
                    <?php
                        $animationIndex++;
                    endforeach;
                    ?>
                <?php else: ?>
                    <div class="col-span-full">
                        <div class="empty-state rounded-2xl p-16 text-center">
                            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-gray-400 text-3xl">🎫</span>
                            </div>
                            <h3 class="font-display text-xl font-medium text-gray-900 mb-2">
                                Chưa có voucher khả dụng
                            </h3>
                            <p class="text-gray-500 mb-6">
                                Hiện tại chúng tôi chưa có voucher nào đang có hiệu lực. Hãy quay lại sau để không bỏ lỡ những ưu đãi hấp dẫn.
                            </p>
                            <a href="<?php echo _WEB_HOST; ?>"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Additional Info Section -->
            <?php if (!empty($listVouchers)): ?>
                <div class="mt-16 bg-gray-50 rounded-2xl p-8">
                    <div class="text-center">
                        <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">
                            Cách sử dụng voucher
                        </h2>
                        <div class="max-w-4xl mx-auto">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-blue-600 text-xl">1️⃣</span>
                                    </div>
                                    <h3 class="font-medium text-gray-900 mb-2">Sao chép mã</h3>
                                    <p class="text-sm text-gray-600">Nhấn vào nút "Sao chép mã" để copy voucher</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-blue-600 text-xl">2️⃣</span>
                                    </div>
                                    <h3 class="font-medium text-gray-900 mb-2">Thêm sản phẩm</h3>
                                    <p class="text-sm text-gray-600">Chọn sản phẩm yêu thích và thêm vào giỏ hàng</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-blue-600 text-xl">3️⃣</span>
                                    </div>
                                    <h3 class="font-medium text-gray-900 mb-2">Nhập mã giảm giá</h3>
                                    <p class="text-sm text-gray-600">Dán mã voucher khi thanh toán để được giảm giá</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Enhanced Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h3 class="font-display text-xl font-semibold text-white mb-4">META.vn</h3>
                <p class="text-gray-400 mb-6">Nội thất cao cấp - Ưu đãi tuyệt vời</p>
                <div class="text-sm">
                    © <?php echo date('Y'); ?> <span class="text-white font-medium">META.vn</span> - Mua sắm trực tuyến chính hãng
                </div>
            </div>
        </div>
    </footer>

    <!-- Enhanced Copy Script -->
    <script>
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                showNotification("Đã sao chép mã: " + code);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = code;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification("Đã sao chép mã: " + code);
            });
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notification-text');

            notificationText.textContent = message;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Trigger staggered animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.voucher-card');
            cards.forEach(card => {
                card.style.opacity = '1';
            });
        });
    </script>
</body>
<?php layout('footer'); ?>