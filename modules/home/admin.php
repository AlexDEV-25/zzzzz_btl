<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
// Thống kê cơ bản
$amount_user = getCountRows("SELECT * FROM users ");
$amount_bill = getCountRows("SELECT * FROM bills ");
$amount_product = getCountRows("SELECT * FROM products ");
$statistical = 0;
$listBills = selectAll("SELECT * FROM bills WHERE status = 2 ");

foreach ($listBills as $item) {
    $statistical += $item['total'];
}

// Dữ liệu cho biểu đồ doanh thu theo ngày
$revenueData = selectAll("SELECT DATE(date) as sale_date, SUM(total) as total_revenue 
                         FROM bills 
                         WHERE status = 2 
                         GROUP BY DATE(date) 
                         ORDER BY sale_date ASC");
$revenueDates = [];
$revenueValues = [];
foreach ($revenueData as $row) {
    $revenueDates[] = $row['sale_date'];
    $revenueValues[] = (float)$row['total_revenue'];
}

// Dữ liệu cho biểu đồ sản phẩm bán chạy (chỉ tính hóa đơn status = 2)
$topProducts = selectAll("SELECT p.name_product, SUM(pb.amount_buy) as total_sold 
                         FROM products_bill pb 
                         JOIN products_detail pd ON pb.id_product_detail = pd.id 
                         JOIN products p ON pd.id_product = p.id 
                         JOIN bills b ON pb.id_bill = b.id 
                         WHERE b.status = 2 
                         GROUP BY p.id, p.name_product 
                         ORDER BY total_sold DESC 
                         LIMIT 5");
$productNames = [];
$productSold = [];
foreach ($topProducts as $row) {
    $productNames[] = $row['name_product'];
    $productSold[] = (int)$row['total_sold'];
}
$data = [
    'pageTitle' => 'Trang admin',
    'role' => 1
];
layout('header_admin', $data);
?>

<body>
    <div class="min-h-full">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Thống kê</h1>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <!-- Thống kê cơ bản -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4">
                    <div class="rounded-xl border-2 bg-red-50 p-6 text-red-600 border-red-600">
                        <h4 class="capitalize font-medium">Tổng đơn hàng</h4>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-semibold"><?php echo $amount_bill; ?></p>
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                        d="M10.5 11H17M7 11h.5M7 7.5h.5m-.5 7h.5m9.5 0h-1m-5.5 0h3m3.5-7h-3m-3.5 0h1M21 7v-.63c0-1.193 0-1.79-.158-2.27a3.045 3.045 0 0 0-1.881-1.937C18.493 2 17.914 2 16.755 2h-9.51c-1.159 0-1.738 0-2.206.163a3.046 3.046 0 0 0-1.881 1.936C3 4.581 3 5.177 3 6.37V15m18-4v9.374c0 .858-.985 1.314-1.608.744a.946.946 0 0 0-1.284 0l-.483.442a1.657 1.657 0 0 1-2.25 0a1.657 1.657 0 0 0-2.25 0a1.657 1.657 0 0 1-2.25 0a1.657 1.657 0 0 0-2.25 0a1.657 1.657 0 0 1-2.25 0l-.483-.442a.946.946 0 0 0-1.284 0c-.623.57-1.608.114-1.608-.744V19" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl border-2 bg-indigo-50 p-6 text-indigo-600 border-indigo-600">
                        <h4 class="capitalize font-medium">Doanh thu</h4>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-semibold"><?php echo number_format($statistical, 0, '.', '.'); ?> đ</p>
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 2048 2048">
                                    <path fill="currentColor"
                                        d="m1344 2l704 352v785l-128-64V497l-512 256v258l-128 64V753L768 497v227l-128-64V354L1344 2zm0 640l177-89l-463-265l-211 106l497 248zm315-157l182-91l-497-249l-149 75l464 265zm-507 654l-128 64v-1l-384 192v455l384-193v144l-448 224L0 1735v-676l576-288l576 288v80zm-640 710v-455l-384-192v454l384 193zm64-566l369-184l-369-185l-369 185l369 184zm576-1l448-224l448 224v527l-448 224l-448-224v-527zm384 576v-305l-256-128v305l256 128zm384-128v-305l-256 128v305l256-128zm-320-288l241-121l-241-120l-241 120l241 121z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl border-2 bg-green-50 p-6 text-green-600 border-green-600">
                        <h4 class="capitalize font-medium">Khách hàng</h4>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-semibold"><?php echo $amount_user - 1; ?></p>
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                                    <path fill="currentColor"
                                        d="M18 17a7 7 0 1 0-7-7a7 7 0 0 0 7 7Zm0-12a5 5 0 1 1-5 5a5 5 0 0 1 5-5Z"
                                        class="clr-i-outline clr-i-outline-path-1" />
                                    <path fill="currentColor"
                                        d="M30.47 24.37a17.16 17.16 0 0 0-24.93 0A2 2 0 0 0 5 25.74V31a2 2 0 0 0 2 2h22a2 2 0 0 0 2-2v-5.26a2 2 0 0 0-.53-1.37ZM29 31H7v-5.27a15.17 15.17 0 0 1 22 0Z"
                                        class="clr-i-outline clr-i-outline-path-2" />
                                    <path fill="none" d="M0 0h36v36H0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl border-2 bg-orange-50 p-6 text-orange-600 border-orange-600">
                        <h4 class="capitalize font-medium">Số lượng sản phẩm</h4>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-semibold"><?php echo $amount_product; ?></p>
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 15 15">
                                    <path fill="none" stroke="currentColor"
                                        d="M5 5.5h1m3 4h1M10 5l-5 5M6.801.79L5.672 1.917a.988.988 0 0 1-.698.29H3.196a.988.988 0 0 0-.988.988v1.778a.988.988 0 0 1-.29.698L.79 6.802a.988.988 0 0 0 0 1.397l1.13 1.129a.987.987 0 0 1 .289.698v1.778c0 .546.442.988.988.988h1.778c.262 0 .513.104.698.29l1.13 1.129a.988.988 0 0 0 1.397 0l1.129-1.13a.988.988 0 0 1 .698-.289h1.778a.988.988 0 0 0 .988-.988v-1.778c0-.262.104-.513.29-.698l1.129-1.13a.988.988 0 0 0 0-1.397l-1.13-1.129a.988.988 0 0 1-.289-.698V3.196a.988.988 0 0 0-.988-.988h-1.778a.988.988 0 0 1-.698-.29L8.198.79a.988.988 0 0 0-1.397 0Z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biểu đồ -->
                <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Biểu đồ đường doanh thu theo ngày -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-xl font-semibold mb-4">Doanh thu theo ngày</h4>
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                    <!-- Biểu đồ cột sản phẩm bán chạy -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-xl font-semibold mb-4">Top sản phẩm bán chạy</h4>
                        <canvas id="productChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Thêm Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ đường doanh thu theo ngày
        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($revenueDates); ?>,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: <?php echo json_encode($revenueValues); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VND)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Ngày'
                        }
                    }
                }
            }
        });

        // Biểu đồ cột sản phẩm bán chạy
        const productChart = new Chart(document.getElementById('productChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($productNames); ?>,
                datasets: [{
                    label: 'Số lượng bán',
                    data: <?php echo json_encode($productSold); ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.8)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Sản phẩm'
                        }
                    }
                }
            }
        });
    </script>

    <?php layout('footer_admin'); ?>
</body>