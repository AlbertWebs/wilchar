

<?php $__env->startSection('header'); ?>
    Edit <?php echo e($pageType['title']); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => ''.e($pageType['title']).'','description' => 'Update the content for this legal page.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($pageType['title']).'','description' => 'Update the content for this legal page.']); ?>
        <form action="<?php echo e(route('admin.legal-pages.update', $type)); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div>
                <label class="text-sm font-medium text-slate-700">Content</label>
                <textarea name="content" id="content" rows="20" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" required><?php echo e(old('content', $page->content)); ?></textarea>
                <p class="mt-1 text-xs text-slate-500">You can use HTML tags for formatting. The content will be displayed on the public page.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Title</label>
                    <input type="text" name="meta_title" value="<?php echo e(old('meta_title', $page->meta_title ?? $pageType['title'])); ?>" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="SEO title">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Description</label>
                    <input type="text" name="meta_description" value="<?php echo e(old('meta_description', $page->meta_description ?? '')); ?>" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="SEO description">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="<?php echo e(old('meta_keywords', $page->meta_keywords ?? '')); ?>" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="SEO keywords">
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-200 pt-4">
                <a href="<?php echo e(route('page.show', $pageType['slug'])); ?>" target="_blank" class="text-sm text-slate-600 hover:text-slate-900">
                    <i class="bi bi-eye me-1"></i> View Public Page
                </a>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('admin.legal-pages.index')); ?>" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Changes
                    </button>
                </div>
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

<?php echo $__env->make('layouts.admin', ['title' => 'Edit ' . $pageType['title']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views\admin\legal-pages\edit.blade.php ENDPATH**/ ?>