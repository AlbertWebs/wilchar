<?php
    use Illuminate\Support\Facades\Storage;
?>



<?php $__env->startSection('header'); ?>
    Site Settings
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'General Settings','description' => 'Update branding, contact info, and metadata.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'General Settings','description' => 'Update branding, contact info, and metadata.']); ?>
        <form action="<?php echo e(route('admin.site-settings.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Company Name</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a5 5 0 115-5v5z"/></svg>
                        </span>
                        <input type="text" name="site_name" value="<?php echo e(old('site_name', $defaultSettings['general']['site_name'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Site Tagline</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </span>
                        <input type="text" name="site_tagline" value="<?php echo e(old('site_tagline', $defaultSettings['general']['site_tagline'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. Your Trusted Financial Partner">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Support Email</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12H8m8 0l-4 4m4-4l-4-4"/></svg>
                        </span>
                        <input type="email" name="site_email" value="<?php echo e(old('site_email', $defaultSettings['general']['site_email'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Support Phone</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 5l5 1 2 5-3 3a14 14 0 006 6l3-3 5 2 1 5c-9 0-16-7-16-16z"/></svg>
                        </span>
                        <input type="text" name="site_phone" value="<?php echo e(old('site_phone', $defaultSettings['general']['site_phone'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Address</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21l-6-7a7 7 0 1112 0l-6 7z"/></svg>
                        </span>
                        <input type="text" name="site_address" value="<?php echo e(old('site_address', $defaultSettings['general']['site_address'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Alternate Address</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <input type="text" name="site_address_alt" value="<?php echo e(old('site_address_alt', $defaultSettings['general']['site_address_alt'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="Optional alternate address">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Location</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <input type="text" name="site_location" value="<?php echo e(old('site_location', $defaultSettings['general']['site_location'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. Nairobi, Kenya">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Alternate Phone</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 5l5 1 2 5-3 3a14 14 0 006 6l3-3 5 2 1 5c-9 0-16-7-16-16z"/></svg>
                        </span>
                        <input type="text" name="site_phone_alt" value="<?php echo e(old('site_phone_alt', $defaultSettings['general']['site_phone_alt'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. 0793793362">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">M-Pesa Paybill Number</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </span>
                        <input type="text" name="paybill_number" value="<?php echo e(old('paybill_number', $defaultSettings['general']['paybill_number'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. 4189755">
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Footer Description</label>
                    <textarea name="footer_description" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="Brief description shown in footer"><?php echo e(old('footer_description', $defaultSettings['general']['footer_description'])); ?></textarea>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Footer Copyright Text</label>
                    <textarea name="footer_text" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="Copyright text"><?php echo e(old('footer_text', $defaultSettings['general']['footer_text'])); ?></textarea>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Footer Powered By</label>
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                    <input type="text" name="footer_powered_by" value="<?php echo e(old('footer_powered_by', $defaultSettings['general']['footer_powered_by'])); ?>" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. Powered by Your Company Name">
                    <p class="mt-1 text-xs text-slate-500">This text appears after the copyright notice in the footer</p>
                </div>
            </div>

            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Branding & Logo','description' => 'Upload your company logo and favicon. Recommended: Logo (PNG, transparent background, max 2MB), Favicon (ICO or PNG, 32x32px, max 512KB).']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Branding & Logo','description' => 'Upload your company logo and favicon. Recommended: Logo (PNG, transparent background, max 2MB), Favicon (ICO or PNG, 32x32px, max 512KB).']); ?>
                <div class="grid gap-6 md:grid-cols-3">
                    <?php
                        $placeholders = [
                            'logo' => $defaultSettings['branding']['logo'] ? asset('storage/' . $defaultSettings['branding']['logo']) : asset('main/assets/images/logo.png'),
                            'logo_dark' => $defaultSettings['branding']['logo_dark'] ? asset('storage/' . $defaultSettings['branding']['logo_dark']) : asset('main/assets/images/logo.png'),
                            'favicon' => $defaultSettings['branding']['favicon'] ? asset('storage/' . $defaultSettings['branding']['favicon']) : asset('main/assets/images/favicon.png'),
                        ];
                    ?>
                    <?php $__currentLoopData = ['logo' => 'Main Logo', 'logo_dark' => 'Dark Mode Logo (Optional)', 'favicon' => 'Favicon']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700"><?php echo e($label); ?></label>
                            <div class="flex flex-col gap-3">
                                <div class="h-32 w-full overflow-hidden rounded-xl border border-slate-200 bg-white p-2 flex items-center justify-center">
                                    <img src="<?php echo e($placeholders[$key]); ?>" alt="<?php echo e($label); ?>" class="max-h-full max-w-full object-contain" id="preview-<?php echo e($key); ?>" onerror="this.src='<?php echo e(asset('main/assets/images/logo.png')); ?>'">
                                </div>
                                <label class="flex w-full cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-center text-xs font-medium text-slate-500 hover:border-emerald-400 hover:bg-emerald-50 hover:text-emerald-500 transition">
                                    <svg class="mb-1 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l6-6 4 4 5-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4h16v16H4z"/></svg>
                                    <span>Click to Upload</span>
                                    <input type="file" name="<?php echo e($key); ?>" class="hidden" accept="image/*" onchange="previewImage(this, 'preview-<?php echo e($key); ?>')">
                                </label>
                                <?php if($defaultSettings['branding'][$key]): ?>
                                    <p class="text-xs text-slate-500">Current: <?php echo e(basename($defaultSettings['branding'][$key])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $attributes = $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $component = $__componentOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
            
            <script>
                function previewImage(input, previewId) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById(previewId).src = e.target.result;
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>

            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Home Page Section Images','description' => 'Upload images for different sections on the home page. Recommended: PNG or JPG format, max 2MB each.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Home Page Section Images','description' => 'Upload images for different sections on the home page. Recommended: PNG or JPG format, max 2MB each.']); ?>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <?php
                        $homeImageFields = [
                            'home_hero_image' => ['label' => 'Hero Section Image', 'default' => 'main/assets/images/hero_img2.png'],
                            'home_why_choose_image' => ['label' => 'Why Choose Us Image', 'default' => 'main/assets/images/choose_us2.png'],
                            'home_how_works_image' => ['label' => 'How It Works Image', 'default' => 'main/assets/images/how_works.png'],
                            'home_about_image' => ['label' => 'About Us Image', 'default' => 'main/assets/images/about_guideline.png'],
                            'home_loan_solution_image' => ['label' => 'Loan Solution Image', 'default' => 'main/assets/images/loan_solution.png'],
                            'home_title_vector' => ['label' => 'Title Vector Icon', 'default' => 'main/assets/images/title_vector.png'],
                        ];
                    ?>
                    <?php $__currentLoopData = $homeImageFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700"><?php echo e($config['label']); ?></label>
                            <div class="flex flex-col gap-3">
                                <div class="h-32 w-full overflow-hidden rounded-xl border border-slate-200 bg-white p-2 flex items-center justify-center">
                                    <?php
                                        $currentImage = $defaultSettings['home_images'][$key] ?? '';
                                        $previewSrc = $currentImage ? asset('storage/' . $currentImage) : asset($config['default']);
                                    ?>
                                    <img src="<?php echo e($previewSrc); ?>" alt="<?php echo e($config['label']); ?>" class="max-h-full max-w-full object-contain" id="preview-<?php echo e($key); ?>" onerror="this.src='<?php echo e(asset($config['default'])); ?>'">
                                </div>
                                <label class="flex w-full cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-center text-xs font-medium text-slate-500 hover:border-emerald-400 hover:bg-emerald-50 hover:text-emerald-500 transition">
                                    <svg class="mb-1 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l6-6 4 4 5-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4h16v16H4z"/></svg>
                                    <span>Click to Upload</span>
                                    <input type="file" name="<?php echo e($key); ?>" class="hidden" accept="image/*" onchange="previewImage(this, 'preview-<?php echo e($key); ?>')">
                                </label>
                                <?php if($currentImage): ?>
                                    <p class="text-xs text-slate-500">Current: <?php echo e(basename($currentImage)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $attributes = $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $component = $__componentOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Social Media Links','description' => 'Add your social media profiles to display in the footer.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Social Media Links','description' => 'Add your social media profiles to display in the footer.']); ?>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Facebook URL</label>
                        <input type="url" name="facebook_url" value="<?php echo e(old('facebook_url', $defaultSettings['social']['facebook_url'])); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://facebook.com/yourpage">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Twitter URL</label>
                        <input type="url" name="twitter_url" value="<?php echo e(old('twitter_url', $defaultSettings['social']['twitter_url'])); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://twitter.com/yourhandle">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Instagram URL</label>
                        <input type="url" name="instagram_url" value="<?php echo e(old('instagram_url', $defaultSettings['social']['instagram_url'])); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://instagram.com/yourhandle">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" value="<?php echo e(old('linkedin_url', $defaultSettings['social']['linkedin_url'])); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://linkedin.com/company/yourcompany">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">YouTube URL</label>
                        <input type="url" name="youtube_url" value="<?php echo e(old('youtube_url', $defaultSettings['social']['youtube_url'])); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://youtube.com/@yourchannel">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" value="<?php echo e(old('whatsapp_number', $defaultSettings['social']['whatsapp_number'])); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. +254712345678">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Telegram URL</label>
                        <input type="url" name="telegram_url" value="<?php echo e(old('telegram_url', $defaultSettings['social']['telegram_url'])); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://t.me/yourchannel">
                    </div>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $attributes = $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $component = $__componentOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Title</label>
                    <input type="text" name="meta_title" value="<?php echo e(old('meta_title', $defaultSettings['seo']['meta_title'])); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Description</label>
                    <input type="text" name="meta_description" value="<?php echo e(old('meta_description', $defaultSettings['seo']['meta_description'])); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="<?php echo e(old('meta_keywords', $defaultSettings['seo']['meta_keywords'])); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                    Save Settings
                </button>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $attributes = $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $component = $__componentOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Site Settings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/site-settings/edit.blade.php ENDPATH**/ ?>