@extends('layouts.admin', ['title' => 'Products & Solutions'])

@section('header')
    Products & Solutions
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">Products & Solutions</p>
                <p class="text-sm text-slate-500">Manage loan products and solutions displayed on the website.</p>
            </div>
            <a
                href="{{ route('admin.products.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Product
            </a>
        </div>

        <x-admin.section title="All Products">
            <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search products..."
                        class="rounded-xl border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                    >
                    <select name="status" class="rounded-xl border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                        <option value="">Status: All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Filter
                    </button>
                </form>
            </div>

            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-left">Details</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Order</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($product->icon)
                                            <img src="{{ asset('storage/' . $product->icon) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded object-cover">
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded bg-slate-200 text-slate-500">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                                            <p class="text-xs text-slate-500">{{ Str::limit($product->short_description ?? $product->description, 50) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    @if($product->interest_rate)
                                        <p class="text-xs"><strong>Rate:</strong> {{ $product->interest_rate }}%</p>
                                    @endif
                                    @if($product->loan_duration)
                                        <p class="text-xs"><strong>Duration:</strong> {{ $product->loan_duration }}</p>
                                    @endif
                                    @if($product->min_amount || $product->max_amount)
                                        <p class="text-xs"><strong>Amount:</strong> 
                                            KES {{ number_format($product->min_amount ?? 0, 2) }} - 
                                            KES {{ number_format($product->max_amount ?? 0, 2) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $product->display_order }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg bg-blue-50 p-2 text-blue-600 transition hover:bg-blue-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-rose-50 p-2 text-rose-600 transition hover:bg-rose-100">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                    No products found. <a href="{{ route('admin.products.create') }}" class="text-emerald-600 hover:underline">Create one</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </x-admin.section>
    </div>
@endsection
