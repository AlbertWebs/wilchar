<?php $__env->startSection('header'); ?>
    Website Pages
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">Content Management</p>
                <p class="text-sm text-slate-500">Manage all website pages and content.</p>
            </div>
            <a
                href="<?php echo e(route('admin.website.pages.create')); ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Page
            </a>
        </div>

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'All Pages','description' => 'Manage your website content and pages.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'All Pages','description' => 'Manage your website content and pages.']); ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Slug</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Homepage</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Menu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Updated</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <?php if($page->featured_image): ?>
                                            <img src="<?php echo e(asset('storage/' . $page->featured_image)); ?>" alt="<?php echo e($page->title); ?>" class="h-10 w-10 rounded-lg object-cover">
                                        <?php else: ?>
                                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900"><?php echo e($page->title); ?></p>
                                            <?php if($page->excerpt): ?>
                                                <p class="text-xs text-slate-500"><?php echo e(\Illuminate\Support\Str::limit($page->excerpt, 50)); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <code class="text-xs text-slate-600">/<?php echo e($page->slug); ?></code>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        <?php echo e($page->status === 'published' ? 'bg-emerald-50 text-emerald-600' : ''); ?>

                                        <?php echo e($page->status === 'draft' ? 'bg-slate-100 text-slate-600' : ''); ?>

                                        <?php echo e($page->status === 'archived' ? 'bg-rose-50 text-rose-600' : ''); ?>

                                    ">
                                        <?php echo e(ucfirst($page->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if($page->is_homepage): ?>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Homepage
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if($page->show_in_menu): ?>
                                        <span class="text-xs text-emerald-600">✓ In Menu</span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400">Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500">
                                    <?php echo e($page->updated_at->diffForHumans()); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?php echo e(route('admin.website.pages.edit', $page)); ?>" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            Edit
                                        </a>
                                        <form
                                            action="<?php echo e(route('admin.website.pages.destroy', $page)); ?>"
                                            method="POST"
                                            class="inline-flex"
                                            x-data
                                            @submit.prevent="Admin.confirmAction({ title: 'Delete Page?', text: 'This cannot be undone.', icon: 'warning', confirmButtonText: 'Delete' }).then(confirmed => { if (confirmed) $el.submit(); })"
                                        >
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">
                                    No pages created yet. Create your first page to get started.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($pages->links()); ?>

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
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Website Pages'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/website/pages/index.blade.php ENDPATH**/ ?>