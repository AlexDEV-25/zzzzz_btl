<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['pageTitle'] ?? 'Admin Panel - Luxury Furniture'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Lato:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --luxury-gold: #C9A96E;
            --luxury-dark: #1a1a1a;
            --luxury-cream: #F8F6F0;
            --luxury-gray: #8B8680;
            --luxury-white: #FFFFFF;
            --luxury-shadow: rgba(201, 169, 110, 0.15);
            --luxury-gradient: linear-gradient(135deg, #C9A96E 0%, #B8956A 100%);
            --admin-gradient: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
            --luxury-light-gold: rgba(201, 169, 110, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background: linear-gradient(135deg, #f5f2eb 0%, #faf9f6 100%);
        }

        /* Header Styles */
        .admin-header {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.95) 0%, rgba(52, 73, 94, 0.98) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--luxury-gold);
            box-shadow: 0 4px 30px rgba(26, 26, 26, 0.15);
            position: sticky;
            top: 0;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .admin-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 10% 20%, rgba(201, 169, 110, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .header-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .header-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            position: relative;
        }

        /* Brand Logo */
        .brand-container {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: var(--luxury-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--luxury-white);
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            box-shadow: 0 4px 15px var(--luxury-shadow);
            margin-right: 1rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(201, 169, 110, 0.3);
        }

        .brand-text {
            color: var(--luxury-white);
            font-weight: 600;
            font-size: 1.4rem;
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            color: var(--luxury-gold);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: -2px;
        }

        .admin-badge {
            background: var(--luxury-gradient);
            color: var(--luxury-white);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 0.75rem;
            box-shadow: 0 2px 10px var(--luxury-shadow);
        }

        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            letter-spacing: 0.3px;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--luxury-gold);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: var(--luxury-white);
            background: rgba(201, 169, 110, 0.15);
            border-color: rgba(201, 169, 110, 0.3);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-link:hover::before {
            width: 60%;
        }

        .nav-link i {
            margin-right: 0.5rem;
            font-size: 0.85rem;
        }

        /* Search Bar */
        .search-container {
            flex: 1;
            max-width: 500px;
            margin: 0 2rem;
            position: relative;
        }

        .search-form {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(201, 169, 110, 0.3);
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .search-form:focus-within {
            border-color: var(--luxury-gold);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 20px rgba(201, 169, 110, 0.2);
            transform: translateY(-1px);
        }

        .search-input {
            flex: 1;
            padding: 0.875rem 1.125rem;
            border: none;
            outline: none;
            font-size: 0.9rem;
            color: var(--luxury-white);
            background: transparent;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
        }

        .search-button {
            padding: 0.875rem 1.25rem;
            background: var(--luxury-gradient);
            color: var(--luxury-white);
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .search-button:hover {
            background: linear-gradient(135deg, #B8956A 0%, #C9A96E 100%);
            box-shadow: 0 4px 15px var(--luxury-shadow);
        }

        /* User Menu */
        .user-menu-container {
            position: relative;
            margin-left: 1rem;
        }

        .user-menu-button {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(201, 169, 110, 0.3);
            border-radius: 50px;
            padding: 0.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu-button:hover {
            border-color: var(--luxury-gold);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 15px rgba(201, 169, 110, 0.2);
            transform: scale(1.05);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(201, 169, 110, 0.5);
        }

        .user-menu {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.75rem;
            background: var(--luxury-white);
            border: 1px solid rgba(201, 169, 110, 0.2);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(26, 26, 26, 0.15);
            padding: 0.5rem 0;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 60;
        }

        .user-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: var(--luxury-gray);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }

        .user-menu-item:hover {
            background: var(--luxury-light-gold);
            color: var(--luxury-dark);
        }

        .user-menu-item i {
            margin-right: 0.75rem;
            width: 1rem;
            text-align: center;
            color: var(--luxury-gold);
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(201, 169, 110, 0.3);
            color: var(--luxury-white);
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-toggle:hover {
            background: var(--luxury-gradient);
            border-color: var(--luxury-gold);
        }

        /* Mobile Menu */
        .mobile-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(44, 62, 80, 0.98);
            backdrop-filter: blur(25px);
            border-top: 1px solid rgba(201, 169, 110, 0.2);
            padding: 1.5rem;
            transform: translateY(-20px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .mobile-nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 1rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(201, 169, 110, 0.2);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .mobile-nav-link:hover {
            background: rgba(201, 169, 110, 0.15);
            border-color: var(--luxury-gold);
            color: var(--luxury-white);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .nav-menu {
                gap: 0.25rem;
            }

            .nav-link {
                font-size: 0.85rem;
                padding: 0.625rem 0.75rem;
            }

            .search-container {
                max-width: 400px;
                margin: 0 1rem;
            }
        }

        @media (max-width: 1024px) {
            .nav-menu {
                display: none;
            }

            .mobile-toggle {
                display: block;
            }

            .search-container {
                max-width: 300px;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 0 1rem;
            }

            .header-nav {
                padding: 0.75rem 0;
            }

            .search-container {
                margin: 0 0.5rem;
                max-width: 250px;
            }

            .brand-logo {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }

            .brand-text {
                font-size: 1.2rem;
            }

            .admin-badge {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .search-container {
                display: none;
            }

            .brand-text {
                display: none;
            }

            .brand-subtitle {
                display: none;
            }
        }

        /* Animation */
        @keyframes adminGlow {

            0%,
            100% {
                box-shadow: 0 4px 15px var(--luxury-shadow);
            }

            50% {
                box-shadow: 0 6px 25px rgba(201, 169, 110, 0.3);
            }
        }

        .brand-logo {
            animation: adminGlow 4s ease-in-out infinite;
        }

        .brand-container:hover .brand-logo {
            animation: none;
        }
    </style>
</head>

<body>
    <?php if (!defined('_CODE')) die('Access denied...'); ?>

    <!-- Header -->
    <header class="admin-header">
        <div class="header-container">
            <nav class="header-nav">
                <!-- Brand Logo -->
                <div class="brand-container">
                    <div class="brand-logo">LF</div>
                    <div>
                        <div class="brand-text">
                            Admin Panel
                            <span class="admin-badge">Administrator</span>
                        </div>
                        <div class="brand-subtitle">Luxury Furniture Management</div>
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Navigation Menu (Desktop) -->
                <div class="nav-menu">
                    <a href="<?php echo _WEB_HOST; ?>?module=home&action=admin&role=1" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>Dashboard
                    </a>
                    <a href="<?php echo _WEB_HOST; ?>?module=categories&action=list&role=1" class="nav-link">
                        <i class="fas fa-tags"></i>Danh mục
                    </a>
                    <a href="<?php echo _WEB_HOST; ?>?module=products&action=list&role=1" class="nav-link">
                        <i class="fas fa-couch"></i>Sản phẩm
                    </a>
                    <a href="<?php echo _WEB_HOST; ?>?module=users&action=list&role=1" class="nav-link">
                        <i class="fas fa-users"></i>Tài khoản
                    </a>
                    <a href="<?php echo _WEB_HOST; ?>?module=vouchers&action=list&role=1" class="nav-link">
                        <i class="fas fa-ticket-alt"></i>Voucher
                    </a>
                    <a href="<?php echo _WEB_HOST; ?>?module=bills&action=list&role=1" class="nav-link">
                        <i class="fas fa-receipt"></i>Đơn hàng
                    </a>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="mobile-menu">
                    <div class="mobile-nav-links">
                        <a href="<?php echo _WEB_HOST; ?>?module=home&action=admin&role=1" class="mobile-nav-link">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="<?php echo _WEB_HOST; ?>?module=categories&action=list&role=1" class="mobile-nav-link">
                            <i class="fas fa-tags me-2"></i>Danh mục
                        </a>
                        <a href="<?php echo _WEB_HOST; ?>?module=products&action=list&role=1" class="mobile-nav-link">
                            <i class="fas fa-couch me-2"></i>Sản phẩm
                        </a>
                        <a href="<?php echo _WEB_HOST; ?>?module=users&action=list&role=1" class="mobile-nav-link">
                            <i class="fas fa-users me-2"></i>Tài khoản
                        </a>
                        <a href="<?php echo _WEB_HOST; ?>?module=vouchers&action=list&role=1" class="mobile-nav-link">
                            <i class="fas fa-ticket-alt me-2"></i>Voucher
                        </a>
                        <a href="<?php echo _WEB_HOST; ?>?module=bills&action=list&role=1" class="mobile-nav-link">
                            <i class="fas fa-receipt me-2"></i>Đơn hàng
                        </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <form autocomplete="off" method="POST" class="search-form" action="?module=home&action=productsSearch">
                        <input type="text" name="search" placeholder="Tìm kiếm trong admin..." class="search-input">
                        <input type="hidden" name="role" value="<?php echo 1; ?>">
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

                <!-- User Menu -->
                <div class="user-menu-container">
                    <button class="user-menu-button" onclick="toggleUserMenu()">
                        <img class="user-avatar" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=36&h=36&q=80" alt="Admin avatar">
                    </button>

                    <div id="user-menu" class="user-menu">
                        <a href="<?php echo _WEB_HOST; ?>?module=home&action=admin&role=1" class="user-menu-item">
                            <i class="fas fa-tachometer-alt"></i>Trang Admin
                        </a>
                        <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard&role=1" class="user-menu-item">
                            <i class="fas fa-home"></i>Trang chủ
                        </a>
                        <a href="?module=auth&action=logout" class="user-menu-item">
                            <i class="fas fa-sign-out-alt"></i>Đăng xuất
                        </a>
                    </div>
                </div>
            </nav>
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

        // User menu toggle
        function toggleUserMenu() {
            const userMenu = document.getElementById('user-menu');
            userMenu.classList.toggle('active');
        }

        // Close menus when clicking outside
        document.addEventListener('click', (e) => {
            // Close user menu
            const userMenu = document.getElementById('user-menu');
            const userButton = document.querySelector('.user-menu-button');
            if (!userButton.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.remove('active');
            }

            // Close mobile menu
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileToggle = document.querySelector('.mobile-toggle');
            if (!mobileToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('active');
                document.querySelector('.mobile-toggle i').classList.replace('fa-times', 'fa-bars');
            }
        });

        // Active link highlighting
        const currentPath = window.location.href;
        document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
            if (link.href === currentPath || currentPath.includes(link.href.split('&action=')[1]?.split('&')[0])) {
                link.style.background = 'rgba(201, 169, 110, 0.2)';
                link.style.color = '#C9A96E';
                link.style.borderColor = 'rgba(201, 169, 110, 0.4)';
            }
        });
    </script>
</body>

</html>