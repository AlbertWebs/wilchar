<?php $__env->startSection('content'); ?>
    <!-- Product Detail Hero -->
    <section class="hero-section hero--secondary banner" style="padding: 120px 0 80px; background-color: #03211B; position: relative;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8 mx-auto text-center">
                    <h1 class="hero--secondary__title wow fadeInUp" data-wow-duration="0.8s" style="color: #ffffff;"><?php echo e($product->name); ?></h1>
                    <?php if($product->short_description): ?>
                        <p class="hero--secondary__text wow fadeInDown" data-wow-duration="0.8s" style="color: rgba(255, 255, 255, 0.9);"><?php echo e($product->short_description); ?></p>
                    <?php endif; ?>
                    <nav aria-label="breadcrumb" class="mt-4 wow fadeInDown" data-wow-duration="0.8s">
                        <ol class="breadcrumb justify-content-center" style="margin-bottom: 0;">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" style="color: #ffffff;">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('products.index')); ?>" style="color: #ffffff;">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #FCB650;"><?php echo e($product->name); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Details -->
    <section class="section" style="padding: 80px 0;">
        <div class="container">
            <div class="row g-5">
                <div class="col-12 col-lg-8">
                    <?php if($product->image): ?>
                        <div class="mb-4 wow fadeInUp" data-wow-duration="0.8s">
                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-100 rounded-3" style="max-height: 400px; object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <div class="card card--custom wow fadeInUp" data-wow-duration="0.8s">
                        <div class="card__content" style="padding: 2rem;">
                            <h3 class="mb-4">About This Product</h3>
                            <div style="color: #475569; line-height: 1.8;">
                                <?php echo $product->description ?? '<p>No description available.</p>'; ?>

                            </div>
                        </div>
                    </div>

                    <?php if($product->features && count($product->features) > 0): ?>
                        <div class="card card--custom mt-4 wow fadeInUp" data-wow-duration="0.8s">
                            <div class="card__content" style="padding: 2rem;">
                                <h3 class="mb-4">Key Features</h3>
                                <div class="row g-3">
                                    <?php $__currentLoopData = $product->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-12 col-md-6">
                                            <div class="d-flex align-items-start gap-2">
                                                <i class="bi bi-check-circle-fill text-emerald-500 mt-1" style="font-size: 1.25rem;"></i>
                                                <span style="color: #475569;"><?php echo e($feature); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card card--custom wow fadeInDown" data-wow-duration="0.8s" style="position: sticky; top: 100px;">
                        <div class="card__content" style="padding: 2rem;">
                            <h4 class="mb-4">Product Details</h4>
                            
                            <?php if($product->interest_rate): ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <p class="mb-1" style="color: #64748b; font-size: 0.875rem;">Interest Rate</p>
                                    <p class="mb-0" style="color: #1e293b; font-size: 1.5rem; font-weight: 600;"><?php echo e($product->interest_rate); ?>%</p>
                                </div>
                            <?php endif; ?>

                            <?php if($product->loan_duration): ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <p class="mb-1" style="color: #64748b; font-size: 0.875rem;">Loan Duration</p>
                                    <p class="mb-0" style="color: #1e293b; font-size: 1.25rem; font-weight: 600;"><?php echo e($product->loan_duration); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if($product->min_amount || $product->max_amount): ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <p class="mb-1" style="color: #64748b; font-size: 0.875rem;">Loan Amount Range</p>
                                    <p class="mb-0" style="color: #1e293b; font-size: 1.25rem; font-weight: 600;">
                                        KES <?php echo e(number_format($product->min_amount ?? 0, 0)); ?> - 
                                        KES <?php echo e(number_format($product->max_amount ?? 0, 0)); ?>

                                    </p>
                                </div>
                            <?php endif; ?>

                            <a href="<?php echo e(route('loan-application.create')); ?>" class="btn_theme btn_theme_active w-100 text-center mt-4">
                                Apply for This Product <i class="bi bi-arrow-up-right"></i><span></span>
                            </a>
                            <a href="<?php echo e(route('products.index')); ?>" class="btn_theme w-100 text-center mt-2" style="background: transparent; border: 2px solid #0ea5e9; color: #0ea5e9;">
                                View All Products <i class="bi bi-arrow-left"></i><span></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($relatedProducts->count() > 0): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="mb-4">Related Products</h3>
                        <div class="row g-4">
                            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 col-md-4">
                                    <div class="card card--custom">
                                        <div class="card__content" style="padding: 1.5rem;">
                                            <h5 class="card__title mb-2"><?php echo e($related->name); ?></h5>
                                            <?php if($related->short_description): ?>
                                                <p class="fs-small mb-3" style="color: #64748b;"><?php echo e(Str::limit($related->short_description, 100)); ?></p>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('products.show', $related->slug)); ?>" class="btn_theme">
                                                Learn More <i class="bi bi-arrow-up-right"></i><span></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views\front\products\show.blade.php ENDPATH**/ ?>