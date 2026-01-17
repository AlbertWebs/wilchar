<!DOCTYPE html>
<html lang="en">


<head>
    <!-- required meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- #favicon -->
    <link rel="shortcut icon" href="<?php echo e(!empty($settings['favicon']) ? asset('storage/' . $settings['favicon']) : asset('main/assets/images/favicon.png')); ?>" type="image/x-icon">
    
    <?php
        $siteName = $settings['site_name'] ?? 'Nuru Wilchar SME Capital Limited';
        $siteTagline = $settings['site_tagline'] ?? 'Empowering Small Businesses Across Western Region';
        $metaTitle = $metaTitle ?? ($settings['meta_title'] ?? $siteName . ' - ' . $siteTagline);
        $metaDescription = $metaDescription ?? ($settings['meta_description'] ?? 'A supportive company focused on empowering small businesses in rural and peri-urban areas. We provide financial support through cash advances to meet your business needs, enabling consistent cash flow, business continuity, and growth for grassroots entrepreneurs.');
        $metaKeywords = $metaKeywords ?? ($settings['meta_keywords'] ?? 'business loans, SME loans, cash advances, small business financing, Kenya loans, Western Region loans, quick loans, business capital, Nuru Wilchar, SME Capital');
        $ogImage = $ogImage ?? (!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('main/assets/images/logo.png'));
        $canonicalUrl = $canonicalUrl ?? url()->current();
        $siteUrl = config('app.url');
    ?>

    <!-- Primary Meta Tags -->
    <title><?php echo e($metaTitle); ?></title>
    <meta name="title" content="<?php echo e($metaTitle); ?>">
    <meta name="description" content="<?php echo e($metaDescription); ?>">
    <meta name="keywords" content="<?php echo e($metaKeywords); ?>">
    <meta name="author" content="<?php echo e($siteName); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    <meta name="theme-color" content="#0ea5e9">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:title" content="<?php echo e($metaTitle); ?>">
    <meta property="og:description" content="<?php echo e($metaDescription); ?>">
    <meta property="og:image" content="<?php echo e($ogImage); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?php echo e($siteName); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo e($canonicalUrl); ?>">
    <meta name="twitter:title" content="<?php echo e($metaTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($metaDescription); ?>">
    <meta name="twitter:image" content="<?php echo e($ogImage); ?>">
    <meta name="twitter:image:alt" content="<?php echo e($siteName); ?>">
    <?php if(!empty($settings['twitter_url'])): ?>
        <meta name="twitter:site" content="<?php echo e($settings['twitter_url']); ?>">
        <meta name="twitter:creator" content="<?php echo e($settings['twitter_url']); ?>">
    <?php endif; ?>
    
    <!-- Additional SEO Meta Tags -->
    <meta name="geo.region" content="KE">
    <meta name="geo.placename" content="Kenya">
    <?php if(!empty($settings['site_location'])): ?>
        <meta name="geo.position" content="<?php echo e($settings['site_location']); ?>">
    <?php endif; ?>
    
    <!-- Business/Organization Schema -->
    <meta name="contact" content="<?php echo e($settings['site_email'] ?? ''); ?>">
    <meta name="copyright" content="<?php echo e($siteName); ?>">
    
    <!-- Mobile Web App -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php echo e($siteName); ?>">
    
    <!-- Structured Data (JSON-LD) -->
    <?php
        $socialLinks = array_filter([
            $settings['facebook_url'] ?? null,
            $settings['twitter_url'] ?? null,
            $settings['instagram_url'] ?? null,
            $settings['linkedin_url'] ?? null,
            $settings['youtube_url'] ?? null,
        ]);
        
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'FinancialService',
            'name' => $siteName,
            'alternateName' => $siteTagline,
            'url' => $siteUrl,
            'logo' => $ogImage,
            'description' => $metaDescription,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings['site_address'] ?? '',
                'addressLocality' => $settings['site_location'] ?? 'Kenya',
                'addressCountry' => 'KE'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $settings['site_phone'] ?? '',
                'contactType' => 'Customer Service',
                'email' => $settings['site_email'] ?? '',
                'areaServed' => 'KE',
                'availableLanguage' => ['en', 'sw']
            ],
            'sameAs' => array_values($socialLinks),
            'priceRange' => 'KES 1,000 - KES 100,000',
            'serviceType' => 'Business Loans, Cash Advances, SME Financing',
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Kenya'
            ]
        ];
        $jsonLd = json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    ?>
    <script type="application/ld+json">
    <?php echo $jsonLd; ?>

    </script>
    
    <?php if(isset($product)): ?>
    <!-- Product Structured Data -->
    <?php
        $productData = [
            '@context' => 'https://schema.org',
            '@type' => 'FinancialProduct',
            'name' => $product->name,
            'description' => $product->description ?? $product->short_description ?? '',
            'provider' => [
                '@type' => 'FinancialService',
                'name' => $siteName,
                'url' => $siteUrl
            ],
            'url' => $canonicalUrl,
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Kenya'
            ]
        ];
        
        if ($product->min_amount || $product->max_amount) {
            $productData['loanAmount'] = [
                '@type' => 'MonetaryAmount',
                'currency' => 'KES',
                'minValue' => $product->min_amount ?? 0,
                'maxValue' => $product->max_amount ?? 0
            ];
        }
        
        if ($product->interest_rate) {
            $productData['interestRate'] = $product->interest_rate;
        }
        
        if ($product->image) {
            $productData['image'] = asset('storage/' . $product->image);
        }
    ?>
    <script type="application/ld+json">
    <?php echo json_encode($productData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>

    </script>
    <?php endif; ?>

    <!--  css dependencies start  -->

    <!-- bootstrap five css -->
    <link rel="stylesheet" href="<?php echo e(asset('main/assets/vendor/bootstrap/css/bootstrap.min.css')); ?>">
    <!-- bootstrap-icons css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- nice select css -->
    <link rel="stylesheet" href="<?php echo e(asset('main/assets/vendor/nice-select/css/nice-select.css')); ?>">
    <!-- magnific popup css -->
    <link rel="stylesheet" href="<?php echo e(asset('main/assets/vendor/magnific-popup/css/magnific-popup.css')); ?>">
    <!-- slick css -->
    <link rel="stylesheet" href="<?php echo e(asset('main/assets/vendor/slick/css/slick.css')); ?>">
    <!-- odometer css -->
    <link rel="stylesheet" href="<?php echo e(asset('main/assets/vendor/odometer/css/odometer.css')); ?>">
    <!-- animate css -->
    <link rel="stylesheet" href="<?php echo e(asset('main/assets/vendor/animate/animate.css')); ?>">
    <!--  / css dependencies end  -->

    <!-- main css -->
    <link rel="stylesheet" href="<?php echo e(asset('main/assets/css/style.css')); ?>">
    
    <!-- Custom Elegant Styling -->
    <style>
        /* Enhanced Menu Styling */
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            color: #1e293b !important;
            padding: 0.6rem 1.2rem !important;
            transition: all 0.3s ease;
            position: relative;
            letter-spacing: 0.3px;
            border: 1px solid rgba(14, 165, 233, 0.1);
            border-radius: 8px;
            margin: 0 0.25rem;
            background: rgba(255, 255, 255, 0.5);
        }
        
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus {
            color: #0ea5e9 !important;
            transform: translateY(-2px);
            border-color: rgba(14, 165, 233, 0.3);
            background: rgba(240, 249, 255, 0.8);
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.15);
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #0ea5e9, #0284c7);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 70%;
        }
        
        .navbar-nav .dropdown-toggle::after {
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }
        
        .navbar-nav .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            background: white;
            animation: fadeInDown 0.3s ease;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            color: #475569;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(90deg, #f0f9ff, #e0f2fe);
            color: #0ea5e9;
            padding-left: 2rem;
        }
        
        /* Enhanced Button Styling */
        .btn_theme {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn_theme:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(14, 165, 233, 0.3);
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Enhanced Section Spacing */
        .section {
            padding: 80px 0;
        }
        
        /* Card Hover Effects */
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        /* Improved Typography */
        h1, h2, h3, h4, h5, h6 {
            letter-spacing: -0.5px;
            line-height: 1.3;
        }
        
        /* Enhanced Form Inputs */
        .form-control {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        /* Hide Mobile Menu - Only Show Bottom Nav */
        @media (max-width: 991.98px) {
            /* Hide the main navbar menu */
            .navbar-collapse,
            .navbar-nav,
            .main-menu {
                display: none !important;
            }
            
            /* Hide navbar toggler since we're using bottom nav */
            .navbar-toggler {
                display: none !important;
            }
            
            /* Hide offcanvas menu on mobile */
            .offcanvas {
                display: none !important;
            }
            
            /* Keep navbar brand visible but smaller */
            .navbar-brand {
                margin-right: auto;
            }
            
            /* Make navbar more compact */
            .navbar {
                padding: 0.75rem 0 !important;
            }
        }
        
        /* Logo Animation */
        .navbar-brand {
            transition: transform 0.3s ease;
            padding: 0 !important;
            margin: 0 !important;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand img.logo {
            padding: 0 !important;
            margin: 0 !important;
            display: block;
            height: auto;
        }

        /* Elegant Mobile Bottom Navigation - Enhanced Design */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.12), 0 -2px 8px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            padding: 0.75rem 0 0.5rem;
            border-top: 1px solid rgba(14, 165, 233, 0.15);
        }

        .mobile-bottom-nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.3), transparent);
        }

        .mobile-bottom-nav__container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 0.5rem;
        }

        .mobile-bottom-nav__item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            text-decoration: none;
            color: #64748b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 16px;
            min-width: 64px;
            position: relative;
            flex: 1;
            max-width: 80px;
        }

        .mobile-bottom-nav__item i {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
        }

        .mobile-bottom-nav__item span {
            font-size: 0.7rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.3px;
            position: relative;
            z-index: 2;
        }

        .mobile-bottom-nav__item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(2, 132, 199, 0.15) 100%);
            border-radius: 16px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
        }

        .mobile-bottom-nav__item.active,
        .mobile-bottom-nav__item:hover {
            color: #0ea5e9;
        }

        .mobile-bottom-nav__item.active::before,
        .mobile-bottom-nav__item:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        .mobile-bottom-nav__item.active i,
        .mobile-bottom-nav__item:hover i {
            transform: translateY(-3px) scale(1.1);
            color: #0ea5e9;
        }

        .mobile-bottom-nav__item.active span,
        .mobile-bottom-nav__item:hover span {
            color: #0ea5e9;
            transform: translateY(-1px);
        }

        .mobile-bottom-nav__item.active::after {
            content: '';
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: linear-gradient(90deg, #0ea5e9, #0284c7);
            border-radius: 0 0 3px 3px;
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.4);
        }

        /* Show bottom nav only on mobile */
        @media (max-width: 991.98px) {
            .mobile-bottom-nav {
                display: block;
            }
            
            /* Add padding to body to prevent content from being hidden behind bottom nav */
            body {
                padding-bottom: 75px;
            }
        }

        /* Hide bottom nav on larger screens */
        @media (min-width: 992px) {
            .mobile-bottom-nav {
                display: none !important;
            }
            
            body {
                padding-bottom: 0;
            }
        }

        /* Improve touch targets on mobile */
        @media (max-width: 991.98px) {
            .btn_theme,
            .nav-link,
            .dropdown-item {
                min-height: 44px;
                display: flex;
                align-items: center;
            }
        }

        /* Better spacing for mobile cards */
        @media (max-width: 767.98px) {
            .card--custom {
                margin-bottom: 1.5rem;
            }
            
            .row.g-4 > * {
                margin-bottom: 1rem;
            }
        }

        /* Mobile Responsiveness Improvements */
        @media (max-width: 991.98px) {
            .navbar-brand img {
                max-height: 40px;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem !important;
                font-size: 0.95rem;
            }
            
            .hero-section {
                padding: 80px 0 60px !important;
            }
            
            .hero--secondary__title {
                font-size: 2rem !important;
            }
            
            .card--custom {
                margin-bottom: 1.5rem;
            }
            
            .section {
                padding-top: 60px !important;
                padding-bottom: 60px !important;
            }
            
            .btn_theme {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
            
            /* Calculator form mobile */
            .calculate__form {
                padding: 1.5rem;
            }
            
            .input-single {
                margin-bottom: 1.25rem;
            }
        }
    </style>
</head>

<body>

    <!--  Preloader  -->
    <div class="preloader">
        <span class="loader"></span>
    </div>

    <!--header-section start-->
    <header class="header-section ">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-xl nav-shadow" id="#navbar">
                        <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                            <img width="100" src="<?php echo e(!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('main/assets/images/logo.png')); ?>" class="logo" alt="<?php echo e($settings['site_name'] ?? 'Nuru Wilchar'); ?> Logo">
                        </a>
                        <a class="navbar-toggler d-xl-block d-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="bi bi-list"></i>
                        </a>

                        <div class="collapse navbar-collapse ms-auto d-none d-xl-block" id="navbar-content">
                            <div class="main-menu">
                                <ul class="navbar-nav mb-lg-0 mx-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('home')); ?>">Home</a>
                                    </li>
                                     <li class="nav-item">
                                        <a class="nav-link" href="#how-it-works">About Us</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="<?php echo e(route('products.index')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products & Solutions</a>
                                        <ul class="dropdown-menu">
                                            <?php
                                                $menuProducts = \App\Models\Product::active()->ordered()->limit(6)->get();
                                            ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $menuProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <li><a class="dropdown-item" href="<?php echo e(route('products.show', $product->slug)); ?>"><?php echo e($product->name); ?></a></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <li><a class="dropdown-item" href="#calculator">Business Loans</a></li>
                                                <li><a class="dropdown-item" href="#calculator">Payslip Loans</a></li>
                                                <li><a class="dropdown-item" href="#calculator">Logbook Loans</a></li>
                                            <?php endif; ?>
                                            <?php if($menuProducts->count() > 0): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('products.index')); ?>"><strong>View All Products</strong></a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#contact">Contact Us</a>
                                    </li>
                                </ul>
                                <div class="nav-right d-none d-xl-block">
                                    <div class="nav-right__search">
                                        <a href="javascript:void(0)" class="nav-right__search-icon btn_theme icon_box btn_bg_white"> <i class="bi bi-search"></i> <span></span> </a>    
                                        <a href="<?php echo e(route('loan-application.create')); ?>" class="btn_theme btn_theme_active">Apply Now <i class="bi bi-arrow-up-right"></i><span></span></a>
                                    </div>
                                    <div class="nav-right__search-inner">
                                        <div class="nav-search-inner__form">
                                            <form method="POST" id="search" class="inner__form">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="Search" required>
                                                    <button type="submit" class="search_icon"><i class="bi bi-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Offcanvas More info-->
    <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight">
        <div class="offcanvas-body custom-nevbar">
            <div class="row">
                <div class="col-md-7 col-xl-8">
                    <div class="custom-nevbar__left">
                        <button type="button" class="close-icon d-md-none ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button>
                        <ul class="custom-nevbar__nav mb-lg-0">
                             <li class="menu_item">
                                <a class="nav-link" href="<?php echo e(route('home')); ?>">Home</a>
                            </li>
                            <li class="menu_item">
                                <a class="nav-link" href="#how-it-works">About Us</a>
                            </li>
                            <li class="menu_item dropdown">
                                <a class="nav-link dropdown-toggle" href="<?php echo e(route('products.index')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products & Solutions</a>
                                <ul class="dropdown-menu">
                                    <?php
                                        $menuProducts = \App\Models\Product::active()->ordered()->limit(6)->get();
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $menuProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li><a class="dropdown-item" href="<?php echo e(route('products.show', $product->slug)); ?>"><?php echo e($product->name); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li><a class="dropdown-item" href="#calculator">Business Loans</a></li>
                                        <li><a class="dropdown-item" href="#calculator">Payslip Loans</a></li>
                                        <li><a class="dropdown-item" href="#calculator">Logbook Loans</a></li>
                                    <?php endif; ?>
                                    <?php if($menuProducts->count() > 0): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('products.index')); ?>"><strong>View All Products</strong></a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <li class="menu_item">
                                <a class="nav-link" href="#contact">Contact Us</a>
                            </li>
                           
                            
                        </ul>
                    </div>
                </div>
                <div class="col-md-5 col-xl-4">
                    <div class="custom-nevbar__right">
                        <div class="custom-nevbar__top d-none d-md-block">
                            <button type="button" class="close-icon ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button>
                            <div class="custom-nevbar__right-thumb mb-auto">
                                <img src="<?php echo e(!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('main/assets/images/logo.png')); ?>" alt="<?php echo e($settings['site_name'] ?? 'Nuru Wilchar'); ?> Logo">
                            </div>
                        </div>
                        <ul class="custom-nevbar__right-location">
                            <li>
                                <p class="mb-2">Phone: </p>
                                <a href="tel:<?php echo e(str_replace(' ', '', $settings['site_phone'] ?? '+254787666661')); ?>" class="fs-4 contact"><?php echo e($settings['site_phone'] ?? '+254787666661 / 0793793362'); ?></a>
                            </li>
                            <li class="location">
                                <p class="mb-2">Email: </p>
                                <a href="mailto:<?php echo e($settings['site_email'] ?? 'willingtonochieng92@gmail.com'); ?>" class="fs-4 contact"><?php echo e($settings['site_email'] ?? 'willingtonochieng92@gmail.com'); ?></a>
                            </li>
                            <li class="location">
                                <p class="mb-2">Location: </p>
                                <p class="fs-4 contact"><?php echo e($settings['site_address'] ?? 'Zulmac Insurance Building, 4th Floor, Room 318 (Next to Equity Bank)'); ?></p>
                            </li>
                            <?php if(!empty($settings['paybill_number'])): ?>
                            <li class="location">
                                <p class="mb-2">Paybill: </p>
                                <p class="fs-4 contact"><strong><?php echo e($settings['paybill_number']); ?></strong></p>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header-section end -->

    <?php echo $__env->yieldContent('content'); ?>

    <!-- Footer Area Start -->
    <footer class="footer">
        <div class="container">
            <div class="row section gy-5 gy-xl-0">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="about-company wow fadeInLeft" data-wow-duration="0.8s">
                        <div class="footer__logo mb-4">
                            <a href="<?php echo e(route('home')); ?>">
                                <img src="<?php echo e(!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('main/assets/images/logo.png')); ?>" alt="<?php echo e($settings['site_name'] ?? 'Nuru Wilchar'); ?> Logo">
                            </a>
                        </div>
                        <p><?php echo e($settings['footer_description'] ?? 'Welcome to Nuru SME Solutions, your trusted resource for financial support.'); ?></p>
                        <div class="social mt_32">
                            <?php if(!empty($settings['facebook_url'])): ?>
                                <a href="<?php echo e($settings['facebook_url']); ?>" target="_blank" class="btn_theme social_box"><i class="bi bi-facebook"></i><span></span></a>
                            <?php endif; ?>
                            <?php if(!empty($settings['twitter_url'])): ?>
                                <a href="<?php echo e($settings['twitter_url']); ?>" target="_blank" class="btn_theme social_box"><i class="bi bi-twitter"></i><span></span></a>
                            <?php endif; ?>
                            <?php if(!empty($settings['instagram_url'])): ?>
                                <a href="<?php echo e($settings['instagram_url']); ?>" target="_blank" class="btn_theme social_box"><i class="bi bi-instagram"></i><span></span></a>
                            <?php endif; ?>
                            <?php if(!empty($settings['whatsapp_number'])): ?>
                                <a href="https://wa.me/<?php echo e(str_replace(['+', ' ', '-'], '', $settings['whatsapp_number'])); ?>" target="_blank" class="btn_theme social_box"><i class="bi bi-whatsapp"></i><span></span></a>
                            <?php endif; ?>
                            <?php if(!empty($settings['telegram_url'])): ?>
                                <a href="<?php echo e($settings['telegram_url']); ?>" target="_blank" class="btn_theme social_box"><i class="bi bi-telegram"></i><span></span></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="footer__contact ms-sm-4 ms-xl-0 wow fadeInUp" data-wow-duration="0.8s">
                        <h4 class="footer__title mb-4">Contact</h4>
                        <div class="footer__content">
                            <a href="tel:<?php echo e(str_replace(' ', '', $settings['site_phone'] ?? '+254787666661')); ?>"> <span class="btn_theme social_box"> <i class="bi bi-telephone-plus"></i> </span> <?php echo e($settings['site_phone'] ?? '+254787666661 / 0793793362'); ?> <span></span> </a> 
                            <a href="mailto:<?php echo e($settings['site_email'] ?? 'willingtonochieng92@gmail.com'); ?>"> <span class="btn_theme social_box"> <i class="bi bi-envelope-open"></i> </span> <?php echo e($settings['site_email'] ?? 'willingtonochieng92@gmail.com'); ?> <span></span> </a> 
                            <a href="#"> <span class="btn_theme social_box"> <i class="bi bi-geo-alt"></i> </span> <?php echo e($settings['site_address'] ?? 'Zulmac Insurance Building, 4th Floor, Room 318 (Next to Equity Bank)'); ?> <span></span> </a>
                            <?php if(!empty($settings['paybill_number'])): ?>
                            <a href="#" class="mt-2"> <span class="btn_theme social_box"> <i class="bi bi-credit-card"></i> </span> <strong>Paybill: <?php echo e($settings['paybill_number']); ?></strong> <span></span> </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="quick-link ms-sm-4 ms-xl-0 wow fadeInRight" data-wow-duration="0.8s">
                        <h4 class="footer__title mb-4">Quick Link</h4>
                        <ul>
                            <li><a href="#calculator">Payslip Loans</a></li>
                            <li><a href="#calculator">Business Loans</a></li>
                            <li><a href="#calculator">Logbook Loans</a></li>
                            <li><a href="#calculator">Title Deed Loans</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="quick-link ms-sm-4 ms-xl-0 wow fadeInRight" data-wow-duration="0.8s">
                        <h4 class="footer__title mb-4">Legal</h4>
                        <ul>
                            <li><a href="#contact">Terms and Conditions</a></li>
                            <li><a href="#contact">CBK Disclaimer</a></li>
                            <li><a href="#contact">Privacy Policy</a></li>
                            <li><a href="#contact">Copyright Statement</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="footer__copyright">
                        <p class="copyright text-center">Copyright © <span id="copyYear"></span> <a href="#" class="secondary_color"><?php echo e($settings['site_name'] ?? 'Nuru Wilchar SME Solutions'); ?></a><?php if(!empty($settings['footer_powered_by'])): ?>. Powered By <a href="#" class="secondary_color"><?php echo e($settings['footer_powered_by']); ?></a><?php endif; ?></p>
                        <ul class="footer__copyright-conditions">
                            <li><a href="#contact">Help & Support</a></li>
                            <li><a href="#contact">Sitemap</a></li>
                            <li><a href="#contact">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Area End -->

    <!-- scroll to top -->
    <a href="#" class="scrollToTop"><i class="bi bi-chevron-double-up"></i></a>

    <!-- Elegant Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <div class="mobile-bottom-nav__container">
            <a href="<?php echo e(route('home')); ?>" class="mobile-bottom-nav__item <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" title="Home">
                <i class="bi bi-house-door-fill"></i>
                <span>Home</span>
            </a>
            <a href="<?php echo e(route('products.index')); ?>" class="mobile-bottom-nav__item <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" title="Products">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Products</span>
            </a>
            <a href="#calculator" class="mobile-bottom-nav__item" title="Calculator">
                <i class="bi bi-calculator-fill"></i>
                <span>Calculate</span>
            </a>
            <a href="<?php echo e(route('loan-application.create')); ?>" class="mobile-bottom-nav__item <?php echo e(request()->routeIs('loan-application.*') ? 'active' : ''); ?>" title="Apply">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Apply</span>
            </a>
            <a href="#contact" class="mobile-bottom-nav__item" title="Contact">
                <i class="bi bi-telephone-fill"></i>
                <span>Contact</span>
            </a>
        </div>
    </nav>

    <!--  js dependencies start  -->

    <!-- jquery -->
    <script data-cfasync="false" src="https://pixner.net/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="<?php echo e(asset('main/assets/vendor/jquery/jquery-3.6.3.min.js')); ?>"></script>
    <!-- bootstrap five js -->
    <script src="<?php echo e(asset('main/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- nice select js -->
    <script src="<?php echo e(asset('main/assets/vendor/nice-select/js/jquery.nice-select.min.js')); ?>"></script>
    <!-- magnific popup js -->
    <script src="<?php echo e(asset('main/assets/vendor/magnific-popup/js/jquery.magnific-popup.min.js')); ?>"></script>
    <!-- circular-progress-bar -->
    <script
        src="https://cdn.jsdelivr.net/gh/tomik23/circular-progress-bar@latest/docs/circularProgressBar.min.js"></script>
    <!-- slick js -->
    <script src="<?php echo e(asset('main/assets/vendor/slick/js/slick.min.js')); ?>"></script>
    <!-- odometer js -->
    <script src="<?php echo e(asset('main/assets/vendor/odometer/js/odometer.min.js')); ?>"></script>
    <!-- viewport js -->
    <script src="<?php echo e(asset('main/assets/vendor/viewport/viewport.jquery.js')); ?>"></script>
    <!-- jquery ui js -->
    <script src="<?php echo e(asset('main/assets/vendor/jquery-ui/jquery-ui.min.js')); ?>"></script>
    <!-- wow js -->
    <script src="<?php echo e(asset('main/assets/vendor/wow/wow.min.js')); ?>"></script>

    <script src="<?php echo e(asset('main/assets/vendor/jquery-validate/jquery.validate.min.js')); ?>"></script>

    <!--  / js dependencies end  -->
    <!-- plugins js -->
    <script src="<?php echo e(asset('main/assets/js/plugins.js')); ?>"></script>
    <!-- main js -->
    <script src="<?php echo e(asset('main/assets/js/main.js')); ?>"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $("#frmCalculate").on("submit", function (e) {
            e.preventDefault(); // Prevent normal form submission

            $.ajax({
                url: "<?php echo e(route('loan.calculate')); ?>", // Laravel route
                method: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                success: function (response) {
                    let resultHtml = `
                        <div class="alert alert-success mt-3" style="background: #f0f9ff; border: 2px solid #0ea5e9; border-radius: 8px; padding: 20px;">
                            <h5 class="mb-3" style="color: #0c4a6e; font-weight: 600;">
                                <i class="bi bi-calculator"></i> Loan Calculation Results
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div style="background: white; padding: 15px; border-radius: 6px; margin-bottom: 10px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span style="color: #64748b; font-size: 14px;">Loan Applied:</span>
                                            <strong style="color: #0c4a6e; font-size: 16px;">KES ${response.loan_amount}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span style="color: #64748b; font-size: 14px;">Application Fee (${response.loan_cycle_label}):</span>
                                            <strong style="color: #dc2626; font-size: 16px;">- KES ${response.application_fee}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span style="color: #64748b; font-size: 14px;">Amount You Receive:</span>
                                            <strong style="color: #059669; font-size: 16px;">KES ${response.disbursed_amount}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div style="background: white; padding: 15px; border-radius: 6px; margin-bottom: 10px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span style="color: #64748b; font-size: 14px;">Interest Rate:</span>
                                            <strong style="color: #0c4a6e; font-size: 16px;">${response.interest_rate}% ${response.duration == 1 ? '' : 'per week'}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span style="color: #64748b; font-size: 14px;">Total Interest:</span>
                                            <strong style="color: #0c4a6e; font-size: 16px;">KES ${response.interest}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span style="color: #64748b; font-size: 14px;">Total Payable:</span>
                                            <strong style="color: #0c4a6e; font-size: 18px;">KES ${response.total_payable}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); padding: 15px; border-radius: 6px; color: white;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="font-size: 14px; opacity: 0.9;">Weekly Installment (${response.duration} week${response.duration > 1 ? 's' : ''}):</span>
                                            <strong style="font-size: 20px;">KES ${response.weekly_installment}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div style="background: #fef3c7; border-left: 3px solid #f59e0b; padding: 12px 15px; border-radius: 4px; line-height: 1.6;">
                                        <small style="color: #78350f; font-size: 12px; display: block;">
                                            <i class="bi bi-info-circle"></i> 
                                            <strong>Note:</strong> Repayment is based on the full loan amount (KES ${response.loan_amount}), not the disbursed amount.
                                        </small>
                                        <small style="color: #78350f; font-size: 12px; display: block; margin-top: 6px;">
                                            All loans are paid weekly and enter arrears 1 day after the weekly due date.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $("#CalcMsg").html(resultHtml);
                    
                    // Scroll to results
                    $('html, body').animate({
                        scrollTop: $("#CalcMsg").offset().top - 100
                    }, 500);
                },
                error: function (xhr) {
                    let errorMsg = "Something went wrong. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = [];
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errors.push(value[0]);
                        });
                        errorMsg = errors.join('<br>');
                    }
                    $("#CalcMsg").html(
                        `<div class="alert alert-danger mt-3">${errorMsg}</div>`
                    );
                }
            });
        });

        // Mobile Bottom Nav Active State Script
        document.addEventListener('DOMContentLoaded', function() {
            // Update active state based on current page
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.mobile-bottom-nav__item');
            
            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPath || (href === '<?php echo e(route('home')); ?>' && currentPath === '/')) {
                    item.classList.add('active');
                }
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href !== '#' && href.startsWith('#')) {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            const offset = 80; // Account for fixed navbar
                            const targetPosition = target.offsetTop - offset;
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        });
    });
    </script>

    

</body>


</html><?php /**PATH C:\projects\wilchar\resources\views/front/master.blade.php ENDPATH**/ ?>