<?php
if (!defined('_CODE')) {
    die('Access denied...');
} ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Luxury Furniture - Nội thất cao cấp'; ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Lato:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <header class="luxury-header">
        <div class="header-container">
            <div class="header-content">
                <a href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard" class="navbar-brand">
                    <div class="brand-logo">LF</div>
                    <div>
                        <div class="brand-text">Luxury Furniture</div>
                        <div class="brand-subtitle">Premium Interior</div>
                    </div>
                </a>

                <button class="mobile-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo _WEB_HOST; ?>?module=home&action=dashboard">
                            <i class="fas fa-home"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo _WEB_HOST; ?>?module=auth&action=login">
                            <i class="fas fa-sign-in-alt"></i>Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo _WEB_HOST; ?>?module=auth&action=register">
                            <i class="fas fa-user-plus"></i>Đăng ký
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            const toggleBtn = document.querySelector('.mobile-toggle i');

            navMenu.classList.toggle('active');

            if (navMenu.classList.contains('active')) {
                toggleBtn.classList.replace('fa-bars', 'fa-times');
            } else {
                toggleBtn.classList.replace('fa-times', 'fa-bars');
            }
        }

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.luxury-header');
            if (window.scrollY > 30) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const navMenu = document.getElementById('navMenu');
                const toggleBtn = document.querySelector('.mobile-toggle i');

                navMenu.classList.remove('active');
                toggleBtn.classList.replace('fa-times', 'fa-bars');
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const navMenu = document.getElementById('navMenu');
            const toggleBtn = document.querySelector('.mobile-toggle');

            if (!navMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
                navMenu.classList.remove('active');
                document.querySelector('.mobile-toggle i').classList.replace('fa-times', 'fa-bars');
            }
        });

        // Active link highlighting
        const currentPath = window.location.href;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === currentPath) {
                link.classList.add('active');
            }
        });
    </script>

</body>

</html>