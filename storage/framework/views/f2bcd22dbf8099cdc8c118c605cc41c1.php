<!DOCTYPE html>
<?php
    use Illuminate\Support\Str;
?>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? config('app.name', 'Admin Panel')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js']); ?>

    <script>
        window.csrfToken = '<?php echo e(csrf_token()); ?>';
    </script>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="h-full font-sans antialiased text-slate-900">
    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="flex min-h-screen overflow-hidden bg-slate-100">
        <!-- Sidebar -->
        <aside
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col bg-slate-900 text-slate-100 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
            x-cloak
        >
            <div class="flex items-center gap-2 px-6 py-5">
                <button
                    class="rounded-full bg-slate-800 p-2 text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 lg:hidden"
                    @click="sidebarOpen = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-lg font-semibold text-emerald-50">AD</span>
                    <div>
                        <p class="text-base font-semibold text-white">Admin Dashboard</p>
                        <p class="text-xs text-slate-400"><?php echo e(config('app.name')); ?></p>
                    </div>
                </a>
            </div>
            <nav class="flex-1 overflow-y-auto px-4 pb-6">
                <p class="px-2 pt-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Overview</p>
                <ul class="mt-3 space-y-1 text-sm">
                    <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-emerald-400 group-hover:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('loan-applications.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('loan-applications.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-indigo-400 group-hover:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 3h-9A1.5 1.5 0 006 4.5v15L12 16l6 3.5v-15A1.5 1.5 0 0016.5 3z" />
                            </svg>
                            <span>Loan Applications</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('loans.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('loans.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-amber-400 group-hover:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.886 0-3.628.93-4.748 2.401L3 12l4.252 1.599C8.372 15.07 10.114 16 12 16s3.628-.93 4.748-2.401L21 12l-4.252-1.599C15.628 8.93 13.886 8 12 8z" />
                            </svg>
                            <span>Loans Portfolio</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('disbursements.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('disbursements.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-rose-400 group-hover:text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m4-4H8" />
                            </svg>
                            <span>Disbursements</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('collections.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('collections.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-cyan-400 group-hover:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h8m-8 5h16" />
                            </svg>
                            <span>Collections & Recovery</span>
                        </a>
                    </li>
                </ul>

                <p class="px-2 pt-6 text-xs font-semibold uppercase tracking-wide text-slate-500">Organization</p>
                <ul class="mt-3 space-y-1 text-sm">
                    <li>
                        <a href="<?php echo e(route('admin.roles.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('admin.roles.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-fuchsia-400 group-hover:text-fuchsia-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A4 4 0 018 17h8a4 4 0 013.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                            </svg>
                            <span>Roles & Permissions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('users.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('users.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-teal-400 group-hover:text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7a4 4 0 118 0 4 4 0 01-8 0zm12 13a8 8 0 10-16 0" />
                            </svg>
                            <span>Team Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('reports.dashboard')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('reports.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-lime-400 group-hover:text-lime-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h10m-10 6h16" />
                            </svg>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin.site-settings.edit')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('admin.site-settings.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 4.5l1-1 1 1m8 8l1 1-1 1m-15-8l-1 1 1 1m8 8l-1 1 1 1M12 9v3l2 2" />
                            </svg>
                            <span>System Settings</span>
                        </a>
                    </li>
                </ul>

                <p class="px-2 pt-6 text-xs font-semibold uppercase tracking-wide text-slate-500">Financial</p>
                <ul class="mt-3 space-y-1 text-sm">
                    <li>
                        <a href="<?php echo e(route('loan-products.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('loan-products.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-sky-400 group-hover:text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zM5 21h14l-3-8H8l-3 8z" />
                            </svg>
                            <span>Loan Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('expenses.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('expenses.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-orange-400 group-hover:text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9h18M3 15h18M9 3v18" />
                            </svg>
                            <span>Expense Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('assets.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('assets.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-emerald-400 group-hover:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M6 10v8m12-8v8M3 18h18" />
                            </svg>
                            <span>Assets</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('liabilities.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('liabilities.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-red-400 group-hover:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2" />
                            </svg>
                            <span>Liabilities</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('shareholders.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('shareholders.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-purple-400 group-hover:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m-4 4h10M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                            <span>Shareholders</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('trial-balances.index')); ?>"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 <?php echo e(request()->routeIs('trial-balances.*') ? 'bg-slate-800 text-white' : 'text-slate-300'); ?>">
                            <svg class="h-5 w-5 shrink-0 text-yellow-400 group-hover:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6h6v6m-7-8h8l-4-4-4 4z" />
                            </svg>
                            <span>Trial Balance</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="border-t border-slate-800/80 px-4 py-4">
                <div class="flex items-center gap-3 rounded-lg bg-slate-800/40 p-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-700 text-sm font-semibold">
                        <?php echo e(strtoupper(Str::substr(auth()->user()->name ?? 'A', 0, 1))); ?>

                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e(auth()->user()->roles->pluck('name')->implode(', ')); ?></p>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="rounded-full bg-slate-700 p-2 text-slate-300 transition hover:bg-slate-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12H3m0 0l4-4m-4 4l4 4m12-8v8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Content -->
        <div class="flex flex-1 flex-col">
            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 bg-white/70 px-6 py-4 backdrop-blur supports-backdrop-blur:bg-white/60">
                <div class="flex items-center gap-3">
                    <button
                        class="rounded-full border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 lg:hidden"
                        @click="sidebarOpen = true"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M4 16h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-slate-900">
                            <?php if (! empty(trim($__env->yieldContent('header')))): ?>
                                <?php echo $__env->yieldContent('header'); ?>
                            <?php else: ?>
                                <?php echo e($header ?? 'Dashboard'); ?>

                            <?php endif; ?>
                        </h1>
                        <?php if(isset($breadcrumbs)): ?>
                            <nav class="mt-1 flex items-center text-xs text-slate-500">
                                <?php echo $breadcrumbs; ?>

                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        x-on:click="$dispatch('toggle-theme')"
                        class="hidden rounded-full border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m8-9h1M3 12H2m15.364-6.364l.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    <div class="relative">
                        <button class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <span><?php echo e(auth()->user()->email); ?></span>
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
                    <?php echo $__env->yieldContent('content', $slot ?? ''); ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal -->
    <div
        x-cloak
        x-show="$store.modal.open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 px-4 py-10 backdrop-blur"
    >
        <div
            x-transition
            x-bind:class="{
                'max-w-lg': $store.modal.size === 'md',
                'max-w-2xl': $store.modal.size === 'lg',
                'max-w-4xl': $store.modal.size === 'xl',
            }"
            class="w-full overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900" x-text="$store.modal.title"></h2>
                <button class="rounded-full bg-slate-100 p-2 text-slate-500 hover:bg-slate-200" @click="$store.modal.close()">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="relative max-h-[70vh] overflow-y-auto px-6 py-5 text-sm text-slate-700" x-data>
                <template x-if="$store.modal.loading">
                    <div class="absolute inset-0 z-10 flex items-center justify-center bg-white/80">
                        <svg class="h-8 w-8 animate-spin text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </template>
                <div
                    data-modal-body
                    x-effect="
                        $el.innerHTML = $store.modal.body || '';
                        if ($store.modal.body && window.Alpine?.initTree) {
                            queueMicrotask(() => window.Alpine.initTree($el));
                        }
                    "
                ></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const success = <?php echo json_encode(session('success'), 15, 512) ?>;
            const error = <?php echo json_encode(session('error'), 15, 512) ?>;

            if (success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: success,
                    timer: 2800,
                    showConfirmButton: false,
                });
            }

            if (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error,
                });
            }
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH C:\projects\wilchar\resources\views/layouts/admin.blade.php ENDPATH**/ ?>