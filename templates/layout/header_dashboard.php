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
    <?php if (!defined('_CODE')) die('Access denied...'); ?>

    <!-- Header -->
    <header class="guest-header">
        <div class="header-container">
            <nav class="header-nav" aria-label="Top Navigation">
                <!-- Brand Logo -->
                <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard&role=-1" class="brand-container">
                    <div class="brand-logo">
                        LF
                    </div>
                    <div class="brand-content">
                        <div class="brand-text">Luxury Furniture</div>
                        <div class="brand-subtitle">Premium Interior Collection</div>
                    </div>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Navigation Menu (Desktop) -->
                <div class="nav-menu">
                    <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard&role=-1" class="nav-link">
                        <i class="fas fa-home"></i>Trang chủ
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <form autocomplete="off" autocapitalize="off" method="POST" class="search-form" action="?module=home&action=productsSearch">
                        <input type="text" name="search" placeholder="Khám phá nội thất cao cấp..." class="search-input">
                        <input type="hidden" name="userId" value="-1">
                        <input type="hidden" name="role" value="-1">
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

                <!-- Authentication Links -->
                <div class="auth-container">
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=login" class="auth-link">
                        <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                    </a>
                    <div class="auth-divider"></div>
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=register" class="auth-link register">
                        <i class="fas fa-user-plus me-1"></i>Đăng ký
                    </a>
                </div>
            </nav>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu">
                <!-- Mobile Search -->
                <div class="mobile-search">
                    <form autocomplete="off" autocapitalize="off" method="POST" class="search-form" action="?module=home&action=productsSearch">
                        <input type="text" name="search" placeholder="Tìm kiếm nội thất..." class="search-input">
                        <input type="hidden" name="userId" value="-1">
                        <input type="hidden" name="role" value="-1">
                        <button type="submit" class="search-button">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Mobile Auth Links -->
                <div class="mobile-auth">
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=login" class="mobile-auth-link">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </a>
                    <a href="<?php echo _WEB_HOST; ?>?module=auth&action=register" class="mobile-auth-link register">
                        <i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản
                    </a>
                </div>
            </div>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const toggleBtn = document.querySelector('.mobile-toggle i');

            mobileMenu.classList.toggle('active');

            if (mobileMenu.classList.contains('active')) {
                toggleBtn.classList.replace('fa-bars', 'fa-times');
            } else {
                toggleBtn.classList.replace('fa-times', 'fa-bars');
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileToggle = document.querySelector('.mobile-toggle');

            if (!mobileToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('active');
                document.querySelector('.mobile-toggle i').classList.replace('fa-times', 'fa-bars');
            }
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.guest-header');
            if (window.scrollY > 30) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.backdropFilter = 'blur(25px)';
                header.style.boxShadow = '0 4px 35px rgba(26, 26, 26, 0.12)';
            } else {
                header.style.background = 'linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 246, 240, 0.98) 100%)';
                header.style.backdropFilter = 'blur(20px)';
                header.style.boxShadow = '0 2px 30px rgba(26, 26, 26, 0.08)';
            }
        });

        // Search input focus effects
        const searchInputs = document.querySelectorAll('.search-input');

        searchInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.closest('.search-form').style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                if (!this.closest('.search-form').matches(':focus-within')) {
                    this.closest('.search-form').style.transform = 'translateY(0)';
                }
            });
        });
    </script>
</body>

</html>