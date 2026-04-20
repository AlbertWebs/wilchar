<?php $__env->startSection('content'); ?>
    <!-- Products Page Hero -->
    <section class="hero-section hero--secondary banner" style="padding: 120px 0 80px; background-color: #03211B; position: relative;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8 mx-auto text-center">
                    <h1 class="hero--secondary__title wow fadeInUp" data-wow-duration="0.8s" style="color: #ffffff;">Our Products & Solutions</h1>
                    <p class="hero--secondary__text wow fadeInDown" data-wow-duration="0.8s" style="color: rgba(255, 255, 255, 0.9);">Discover our comprehensive range of loan products designed to meet your business needs</p>
                    <nav aria-label="breadcrumb" class="mt-4 wow fadeInDown" data-wow-duration="0.8s">
                        <ol class="breadcrumb justify-content-center" style="margin-bottom: 0;">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" style="color: #ffffff;">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #FCB650;">Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="section" style="padding: 80px 0;">
        <div class="container">
            <?php if($products->count() > 0): ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card card--custom wow fadeInUp" data-wow-duration="0.8s" style="height: 100%; transition: all 0.3s ease;">
                                <?php if($product->image): ?>
                                    <div class="card__thumb" style="height: 200px; overflow: hidden; border-radius: 12px 12px 0 0;">
                                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <div class="card__content" style="padding: 1.5rem;">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <?php if($product->icon): ?>
                                            <img src="<?php echo e(asset('storage/' . $product->icon)); ?>" alt="<?php echo e($product->name); ?>" style="width: 48px; height: 48px; object-fit: contain;">
                                        <?php endif; ?>
                                        <h4 class="card__title mb-0"><?php echo e($product->name); ?></h4>
                                    </div>
                                    
                                    <?php if($product->short_description): ?>
                                        <p class="fs-small mb-3" style="color: #64748b;"><?php echo e($product->short_description); ?></p>
                                    <?php endif; ?>

                                    <?php if($product->features && count($product->features) > 0): ?>
                                        <ul class="mb-3" style="list-style: none; padding: 0;">
                                            <?php $__currentLoopData = array_slice($product->features, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="mb-2" style="color: #475569;">
                                                    <i class="bi bi-check-circle-fill text-emerald-500 me-2"></i><?php echo e($feature); ?>

                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php endif; ?>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <?php if($product->interest_rate): ?>
                                            <span class="badge" style="background: #f0f9ff; color: #0ea5e9; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                                                <i class="bi bi-percent"></i> <?php echo e($product->interest_rate); ?>% Rate
                                            </span>
                                        <?php endif; ?>
                                        <?php if($product->loan_duration): ?>
                                            <span class="badge" style="background: #fef3c7; color: #f59e0b; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                                                <i class="bi bi-calendar-week"></i> <?php echo e($product->loan_duration); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if($product->min_amount || $product->max_amount): ?>
                                        <p class="mb-3" style="color: #1e293b; font-weight: 600;">
                                            <i class="bi bi-currency-exchange me-2"></i>
                                            KES <?php echo e(number_format($product->min_amount ?? 0, 0)); ?> - 
                                            KES <?php echo e(number_format($product->max_amount ?? 0, 0)); ?>

                                        </p>
                                    <?php endif; ?>

                                    <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="btn_theme btn_theme_active w-100 text-center">
                                        Learn More <i class="bi bi-arrow-up-right"></i><span></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-inbox" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h3 class="mb-2">No Products Available</h3>
                    <p class="text-slate-500">Products will be displayed here once they are added by the administrator.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); padding: 80px 0; border-radius: 0;">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto text-center text-white">
                    <h2 class="mb-3">Ready to Get Started?</h2>
                    <p class="mb-4">Apply for a loan today and get the financial support your business needs.</p>
                    <a href="<?php echo e(route('loan-application.create')); ?>" class="btn_theme" style="background: white; color: #0ea5e9;">
                        Apply Now <i class="bi bi-arrow-up-right"></i><span></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views\front\products\index.blade.php ENDPATH**/ ?>