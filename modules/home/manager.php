<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$amount_user = getCountRows("SELECT * FROM users ");
$amount_bill = getCountRows("SELECT * FROM bills ");
$amount_product = getCountRows("SELECT * FROM products ");
$statistical = 0;
$listBills = selectAll("SELECT * FROM bills  WHERE status = 2 ");

foreach ($listBills as $item):
    $statistical += $item['total'];
endforeach;
$data = [
    'pageTitle' => 'Trang manager',
    'role' => 2
];
layout('header_manager', $data);

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
            </div>
        </main>
    </div>
</body>

<?php
layout('footer_manager', $data);
?>