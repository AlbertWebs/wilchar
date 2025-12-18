<!DOCTYPE html>
<html lang="en">


<head>
    <!-- required meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- #favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('main/assets/images/favicon.png')); ?>" type="image/x-icon">
    <!-- #title -->
    <title><?php echo e($settings['meta_title'] ?? 'FINVIEW - Financial Loan Review and Comparison Website Template'); ?></title>
    <!-- #keywords -->
    <meta name="keywords" content="<?php echo e($settings['meta_keywords'] ?? 'FINVIEW, Financial Loan, Financial Loan Review and Comparison'); ?>">
    <!-- #description -->
    <meta name="description" content="<?php echo e($settings['meta_description'] ?? 'FINVIEW HTML5 Template'); ?>">

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
                        <a class="navbar-brand" href="index.html"><img src="<?php echo e(asset('main/assets/images/logo.png')); ?>" class="logo" alt="logo"></a>
                        <a class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="bi bi-list"></i>
                        </a>

                        <div class="collapse navbar-collapse ms-auto " id="navbar-content">
                            <div class="main-menu">
                                <ul class="navbar-nav mb-lg-0 mx-auto">
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Home</a>
                                    </li>
                                     <li class="nav-item">
                                        <a class="nav-link" href="#">About Us</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Products & Solutions</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Business Loans</a></li>
                                            <li><a class="dropdown-item" href="#">Payslip Loans</a></li>
                                            <li><a class="dropdown-item" href="#">Logbook Loans</a></li>
                                            <li><a class="dropdown-item" href="#">Title Deed Loans</a></li>
                                            
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Contact us</a>
                                    </li>
                                </ul>
                                <div class="nav-right d-none d-xl-block">
                                    <div class="nav-right__search">
                                        <a href="javascript:void(0)" class="nav-right__search-icon btn_theme icon_box btn_bg_white"> <i class="bi bi-search"></i> <span></span> </a>    
                                        <a href="sign-in.html" class="btn_theme btn_theme_active">Apply Now <i class="bi bi-arrow-up-right"></i><span></span></a>
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
                                        <a class="nav-link" href="#">Home</a>
                            </li>
                            <li class="menu_item">
                                <a class="nav-link" href="#">About Us</a>
                            </li>
                            <li class="menu_item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Products & Solutions</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Business Loans</a></li>
                                    <li><a class="dropdown-item" href="#">Payslip Loans</a></li>
                                    <li><a class="dropdown-item" href="#">Logbook Loans</a></li>
                                    <li><a class="dropdown-item" href="#">Title Deed Loans</a></li>
                                    
                                </ul>
                            </li>
                            <li class="menu_item">
                                <a class="nav-link" href="#">Contact us</a>
                            </li>
                           
                            
                        </ul>
                    </div>
                </div>
                <div class="col-md-5 col-xl-4">
                    <div class="custom-nevbar__right">
                        <div class="custom-nevbar__top d-none d-md-block">
                            <button type="button" class="close-icon ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button>
                            <div class="custom-nevbar__right-thumb mb-auto">
                                <img src="<?php echo e(asset('main/assets/images/logo.png')); ?>" alt="logo">
                            </div>
                        </div>
                        <ul class="custom-nevbar__right-location">
                            <li>
                                <p class="mb-2">Phone: </p>
                                <a href="tel:<?php echo e(str_replace(' ', '', $settings['site_phone'] ?? '+123 456 789')); ?>" class="fs-4 contact"><?php echo e($settings['site_phone'] ?? '+123 456 789'); ?></a>
                            </li>
                            <li class="location">
                                <p class="mb-2">Email: </p>
                                <a href="mailto:<?php echo e($settings['site_email'] ?? 'info@example.com'); ?>" class="fs-4 contact"><?php echo e($settings['site_email'] ?? 'info@example.com'); ?></a>
                            </li>
                            <li class="location">
                                <p class="mb-2">Location: </p>
                                <p class="fs-4 contact"><?php echo e($settings['site_address'] ?? 'Quickmart Plaza, Kakamega'); ?></p>
                            </li>
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
                            <a href="index.html">
                                <img src="<?php echo e(asset('main/assets/images/logo.png')); ?>" alt="Logo">
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
                            <a href="tel:<?php echo e(str_replace(' ', '', $settings['site_phone'] ?? '+254704388759')); ?>"> <span class="btn_theme social_box"> <i class="bi bi-telephone-plus"></i> </span> <?php echo e($settings['site_phone'] ?? '+254 704 388 759'); ?> <span></span> </a> 
                            <a href="mailto:<?php echo e($settings['site_email'] ?? 'info@nurusmesolution.co.ke'); ?>"> <span class="btn_theme social_box"> <i class="bi bi-envelope-open"></i> </span> <?php echo e($settings['site_email'] ?? 'info@nurusmesolution.co.ke'); ?> <span></span> </a> 
                            <a href="#"> <span class="btn_theme social_box"> <i class="bi bi-geo-alt"></i> </span> <?php echo e($settings['site_address'] ?? 'Quickmart Plaza, Kakamega'); ?> <span></span> </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="quick-link ms-sm-4 ms-xl-0 wow fadeInRight" data-wow-duration="0.8s">
                        <h4 class="footer__title mb-4">Quick Link</h4>
                        <ul>
                            <li><a href="#">Payslip Loans</a></li>
                     
                            <li><a href="faq.html">Business Loans</a></li>
                            <li><a href="blog.html">Logbook Loans</a></li>
                            <li><a href="blog.html">Title Deed Loans</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="quick-link ms-sm-4 ms-xl-0 wow fadeInRight" data-wow-duration="0.8s">
                        <h4 class="footer__title mb-4">Legal</h4>
                        <ul>
                            <li><a href="#">Terms and Conditions</a></li>
                            <li><a href="#">CBK Disclaimer</a></li>
                            <li><a href="faq.html">Privacy Policy</a></li>
                            <li><a href="blog.html">Copyright Statement</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="footer__copyright">
                        <p class="copyright text-center">Copyright © <span id="copyYear"></span> <a href="#" class="secondary_color"><?php echo e($settings['site_name'] ?? 'Nuru Wilchar SME Solutions'); ?></a><?php if(!empty($settings['footer_powered_by'])): ?>. Powered By <a href="#" class="secondary_color"><?php echo e($settings['footer_powered_by']); ?></a><?php endif; ?></p>
                        <ul class="footer__copyright-conditions">
                            <li><a href="contact.html">Help & Support</a></li>
                            <li><a href="#">Sitemap</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Area End -->

    <!-- scroll to top -->
    <a href="#" class="scrollToTop"><i class="bi bi-chevron-double-up"></i></a>

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
                    $("#CalcMsg").html(
                        `<div class="alert alert-success">Monthly Payment: Ksh ${response.monthly_payment}</div>`
                    );
                },
                error: function (xhr) {
                    $("#CalcMsg").html(
                        `<div class="alert alert-danger">Something went wrong. Try again.</div>`
                    );
                }
            });
        });
    });
    </script>

    

</body>


</html><?php /**PATH C:\projects\wilchar\resources\views/front/master.blade.php ENDPATH**/ ?>