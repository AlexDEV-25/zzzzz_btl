<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Luxury Furniture - Nội thất cao cấp'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Lato:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header class="luxury-header">
        <div class="header-container">
            <nav class="header-nav">
                <!-- Brand Logo -->
                <a href="?module=home&action=dashboard&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="brand-container">
                    <div class="brand-logo">LF</div>
                    <div>
                        <div class="brand-text">Luxury Furniture</div>
                        <div class="brand-subtitle">Premium Interior</div>
                    </div>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Navigation Menu (Desktop) -->
                <div class="nav-menu">
                    <a href="?module=home&action=dashboard&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="nav-link">
                        <i class="fas fa-home me-2"></i>Trang chủ
                    </a>
                    <a href="?module=billsCustom&action=historyBill&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="nav-link">
                        <i class="fas fa-receipt me-2"></i>Đơn hàng
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <form autocomplete="off" method="POST" class="search-form" action="?module=home&action=productsSearch">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm nội thất..." class="search-input">
                        <input type="hidden" name="role" value="0">
                        <?php if (!empty($data['userId'])): ?>
                            <input type="hidden" name="userId" value="<?php echo $data['userId']; ?>">
                        <?php endif; ?>
                        <?php if (!empty($data['categoryId'])): ?>
                            <input type="hidden" name="categoryId" value="<?php echo $data['categoryId']; ?>">
                        <?php endif; ?>
                        <?php if (!empty($data['productId'])): ?>
                            <input type="hidden" name="productId" value="<?php echo $data['productId']; ?>">
                        <?php endif; ?>
                        <button type="submit" class="search-button">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- User Actions -->
                <div class="user-actions">
                    <!-- Cart -->
                    <a href="?module=cart&action=cart&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="cart-link">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Giỏ hàng</span>
                        <span class="cart-count"><?php echo $data['count'] ?? '0'; ?></span>
                    </a>

                    <!-- User Menu -->
                    <div class="user-menu-container">
                        <button class="user-menu-button" onclick="toggleUserMenu()">
                            <img class="user-avatar" src="<?php
                                                            if (!empty($data['userId'])) {
                                                                $user = selectOne("SELECT * FROM users WHERE id = " . $data['userId']);
                                                                echo !empty($user['avatar']) ? _IMGA_ . $user['avatar'] : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=32&h=32&q=80';
                                                            } else {
                                                                echo 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=32&h=32&q=80';
                                                            }
                                                            ?>" alt="User avatar">
                        </button>

                        <div id="user-menu" class="user-menu">
                            <a href="?module=home&action=customEdit&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="user-menu-item">
                                <i class="fas fa-user"></i>Thông tin cá nhân
                            </a>
                            <a href="?module=billsCustom&action=historyBill&role=0&userId=<?php echo $data['userId'] ?? ''; ?>" class="user-menu-item">
                                <i class="fas fa-history"></i>Lịch sử mua hàng
                            </a>
                            <a href="?module=auth&action=logout" class="user-menu-item">
                                <i class="fas fa-sign-out-alt"></i>Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            // Add mobile menu functionality here
            console.log('Mobile menu toggle');
        }

        // User menu toggle
        function toggleUserMenu() {
            const userMenu = document.getElementById('user-menu');
            userMenu.classList.toggle('active');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', (e) => {
            const menu = document.getElementById('user-menu');
            const button = document.querySelector('.user-menu-button');

            if (!button.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('active');
            }
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.luxury-header');
            if (window.scrollY > 30) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Search input focus effects
        const searchInput = document.querySelector('.search-input');
        const searchForm = document.querySelector('.search-form');

        searchInput?.addEventListener('focus', () => {
            searchForm.style.transform = 'translateY(-1px)';
        });

        searchInput?.addEventListener('blur', () => {
            searchForm.style.transform = 'translateY(0)';
        });
    </script>
</body>

</html>