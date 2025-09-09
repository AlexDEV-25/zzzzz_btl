<!DOCTYPE html>
<html lang="vi">
<?php
$data = ['pageTitle' => 'Trang chủ',];
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'lỗi rồi'; ?></title>
    <link rel="icon" href="/asset/images/favicon.ico" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/asset/css/style.css" />
</head>

<body class="bg-gray-100">
    <?php
    if (!defined('_CODE')) {
        die("truy cap that bai");
    }
    $filterAll = filter();
    if (!empty($filterAll['search'])) {
        $value = $filterAll['search'];
        $title = 'Sản phẩm bạn muốn tìm';
        if (getCountRows("SELECT * FROM products WHERE name_product LIKE '%$value%' OR DESCRIPTION LIKE '%$value%'") > 0) {
            $listProduct = selectAll("SELECT * FROM products WHERE name_product LIKE '%$value%' OR DESCRIPTION LIKE '%$value%'");
        } else {
            $title = 'Sản phẩm bạn muốn tìm';
            setFlashData('smg', "Sản phẩm bạn muốn tìm không có, đây là các sản phẩm khác");
            setFlashData('smg_type', "danger");
            $smg = getFlashData('smg');
            $smg_type = getFlashData('smg_type');
            $listProduct = selectAll("SELECT * FROM products");
        }
    } else {
        $title = 'Sản phẩm nổi bật';
        $listProduct = selectAll("SELECT * FROM products");
    }

    // Kiểm tra trạng thái người dùng
    if (!empty($filterAll['userId'])) {
        $userId = $filterAll['userId'];
        $cartCount = getCountCart($userId);
        if ($filterAll['userId'] == 1) {
            layout('header_admin', $data);
        } else {
            if (!empty($filterAll['count'])) {
                $count = $filterAll['count'];
            } else {
                $count = 0;
            }
            $data = [
                'pageTitle' => 'Trang chủ',
                'count' => $cartCount,
                'userId' => $userId
            ];
            layout('header_custom', $data);
        }
    } else {
        layout('header_dashboard', $data);
    }
    ?>

    <!-- Banner -->
    <div class="my-10 max-w-7xl mx-auto relative isolate bg-cover bg-bottom bg-[url('/zzzzz_btl/templates/image/banner/banner.jpg')] h-[400px] rounded-xl">

        <div class="bg-black/30 absolute inset-0 flex flex-col items-center justify-center w-full px-10 lg:px-80 rounded-xl">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                    Giảm Giá Đến 45% - Sản Phẩm Chính Hãng
                </h1>
                <p class="mt-6 text-lg leading-8 text-white">
                    Mua sắm trực tuyến tại META.vn - Giá tốt, Uy tín
                </p>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Danh mục bên trái -->
            <aside class="lg:col-span-1">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Danh mục sản phẩm</h2>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <ul class="space-y-2">
                        <?php
                        $listCategory = selectAll("SELECT * FROM categories");
                        foreach ($listCategory as $category):
                            $categoryId = $category['id'];
                        ?>
                            <li>
                                <a href="?module=home&action=listCategory&categoryId=<?php echo $categoryId; ?>&userId=<?php echo !empty($userId) ? $userId : 0; ?>"
                                    class="block text-gray-700 hover:text-rose-700 font-medium">
                                    <?php echo $category['name_category']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>

            <!-- Phần sản phẩm -->
            <main class="lg:col-span-3">
                <h2 class="text-3xl font-bold text-gray-900 mb-6"><?php echo $title; ?></h2>
                <?php
                if (!empty($smg)) {
                    $alertClass = ($smg_type === 'danger') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
                ?>
                    <div class="<?php echo $alertClass; ?> p-4 rounded-lg mb-6">
                        <?php echo $smg; ?>
                    </div>
                <?php } ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    foreach ($listProduct as $product):
                        $productId = $product['id'];
                        $productDetail = selectOne("SELECT * FROM products_detail WHERE id_product = $productId");
                    ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="?module=home&action=productDetail&productId=<?php echo $productId; ?>&userId=<?php echo !empty($userId) ? $userId : 0; ?>">
                                <img src="<?php echo _IMGP_ . $product['thumbnail']; ?>"
                                    alt="<?php echo $product['name_product']; ?>"
                                    class="w-full h-48 object-cover" />
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo $product['name_product']; ?></h3>
                                    <p class="text-rose-700 font-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                                    <p class="text-gray-600 text-sm"> Còn lại: <?php echo $productDetail['amount']; ?> sản phẩm </p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="mb-0">© 2025 META.vn - Mua sắm online chính hãng</p>
        </div>
    </footer>

    <?php
    if (!empty($filterAll['userId'])) {
        if ($filterAll['userId'] == 1) {
            layout('footer_admin', $data);
        } else {
            layout('footer_custom', $data);
        }
    } else {
        layout('footer_dashboard', $data);
    }
    ?>
</body>

</html>