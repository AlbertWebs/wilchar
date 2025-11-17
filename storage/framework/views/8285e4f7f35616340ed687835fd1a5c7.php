

<?php $__env->startSection('header'); ?>
    Create New Page
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Page Details','description' => 'Create a new page for your website.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Page Details','description' => 'Create a new page for your website.']); ?>
            <form action="<?php echo e(route('admin.website.pages.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-900">Page Title <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="<?php echo e(old('title')); ?>"
                                required
                                class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="Enter page title"
                            >
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-semibold text-slate-900">URL Slug</label>
                            <input
                                type="text"
                                name="slug"
                                id="slug"
                                value="<?php echo e(old('slug')); ?>"
                                class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="auto-generated-from-title"
                            >
                            <p class="mt-1 text-xs text-slate-500">Leave empty to auto-generate from title</p>
                            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="excerpt" class="block text-sm font-semibold text-slate-900">Excerpt</label>
                            <textarea
                                name="excerpt"
                                id="excerpt"
                                rows="3"
                                class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="Brief description of the page"
                            ><?php echo e(old('excerpt')); ?></textarea>
                            <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-semibold text-slate-900">Content</label>
                            <textarea
                                name="content"
                                id="content"
                                rows="15"
                                class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-mono"
                                placeholder="Enter page content (HTML supported)"
                            ><?php echo e(old('content')); ?></textarea>
                            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-xl border border-slate-200 bg-white p-5">
                            <h3 class="mb-4 text-sm font-semibold text-slate-900">Publish Settings</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-slate-900">Status <span class="text-rose-500">*</span></label>
                                    <select
                                        name="status"
                                        id="status"
                                        required
                                        class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    >
                                        <option value="draft" <?php echo e(old('status') === 'draft' ? 'selected' : ''); ?>>Draft</option>
                                        <option value="published" <?php echo e(old('status') === 'published' ? 'selected' : ''); ?>>Published</option>
                                        <option value="archived" <?php echo e(old('status') === 'archived' ? 'selected' : ''); ?>>Archived</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label for="order" class="block text-sm font-semibold text-slate-900">Menu Order</label>
                                    <input
                                        type="number"
                                        name="order"
                                        id="order"
                                        value="<?php echo e(old('order', 0)); ?>"
                                        min="0"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    >
                                    <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="is_homepage"
                                        id="is_homepage"
                                        value="1"
                                        <?php echo e(old('is_homepage') ? 'checked' : ''); ?>

                                        class="h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500"
                                    >
                                    <label for="is_homepage" class="text-sm font-semibold text-slate-900">Set as Homepage</label>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="show_in_menu"
                                        id="show_in_menu"
                                        value="1"
                                        <?php echo e(old('show_in_menu', true) ? 'checked' : ''); ?>

                                        class="h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500"
                                    >
                                    <label for="show_in_menu" class="text-sm font-semibold text-slate-900">Show in Menu</label>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-5">
                            <h3 class="mb-4 text-sm font-semibold text-slate-900">Featured Image</h3>
                            <input
                                type="file"
                                name="featured_image"
                                id="featured_image"
                                accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200"
                            >
                            <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-5">
                            <h3 class="mb-4 text-sm font-semibold text-slate-900">SEO Settings</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="meta_title" class="block text-sm font-semibold text-slate-900">Meta Title</label>
                                    <input
                                        type="text"
                                        name="meta_title"
                                        id="meta_title"
                                        value="<?php echo e(old('meta_title')); ?>"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    >
                                </div>

                                <div>
                                    <label for="meta_description" class="block text-sm font-semibold text-slate-900">Meta Description</label>
                                    <textarea
                                        name="meta_description"
                                        id="meta_description"
                                        rows="3"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    ><?php echo e(old('meta_description')); ?></textarea>
                                </div>

                                <div>
                                    <label for="meta_keywords" class="block text-sm font-semibold text-slate-900">Meta Keywords</label>
                                    <input
                                        type="text"
                                        name="meta_keywords"
                                        id="meta_keywords"
                                        value="<?php echo e(old('meta_keywords')); ?>"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="keyword1, keyword2, keyword3"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                    <a href="<?php echo e(route('admin.website.pages.index')); ?>" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Create Page
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
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Create Page'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wilchar\resources\views/admin/website/pages/create.blade.php ENDPATH**/ ?>