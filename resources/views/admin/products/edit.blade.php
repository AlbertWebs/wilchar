@extends('layouts.admin', ['title' => 'Edit Product'])

@section('header')
    Edit Product
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Product Details" description="Update the product information.">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @if($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <p class="font-semibold">We couldn't save the product yet.</p>
                        <ul class="mt-2 list-disc pl-5 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Product Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 w-full rounded-xl border-slate-200 @error('name') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" required>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">URL Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="mt-1 w-full rounded-xl border-slate-200">
                        <p class="mt-1 text-xs text-slate-500">Leave empty to auto-generate</p>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="mt-1 w-full rounded-xl border-slate-200" maxlength="500">
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Full Description</label>
                    <textarea name="description" rows="5" class="mt-1 w-full rounded-xl border-slate-200">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Interest Rate (%)</label>
                        <input type="number" name="interest_rate" value="{{ old('interest_rate', $product->interest_rate) }}" step="0.01" min="0" max="100" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Loan Duration</label>
                        <input type="text" name="loan_duration" value="{{ old('loan_duration', $product->loan_duration) }}" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Display Order</label>
                        <input type="number" name="display_order" value="{{ old('display_order', $product->display_order) }}" min="0" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Minimum Amount (KES)</label>
                        <input type="number" name="min_amount" value="{{ old('min_amount', $product->min_amount) }}" step="0.01" min="0" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Maximum Amount (KES)</label>
                        <input type="number" name="max_amount" value="{{ old('max_amount', $product->max_amount) }}" step="0.01" min="0" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Features</label>
                    <div id="features-container" class="mt-2 space-y-2">
                        @if(old('features', $product->features))
                            @foreach(old('features', $product->features) as $feature)
                                <div class="flex gap-2">
                                    <input type="text" name="features[]" value="{{ $feature }}" class="flex-1 rounded-xl border-slate-200">
                                    <button type="button" onclick="removeFeature(this)" class="rounded-xl bg-rose-50 px-3 py-2 text-rose-600 hover:bg-rose-100">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex gap-2">
                                <input type="text" name="features[]" class="flex-1 rounded-xl border-slate-200" placeholder="Enter a feature">
                                <button type="button" onclick="removeFeature(this)" class="rounded-xl bg-rose-50 px-3 py-2 text-rose-600 hover:bg-rose-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" onclick="addFeature()" class="mt-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        + Add Feature
                    </button>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Product Icon</label>
                        @if($product->icon)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->icon) }}" alt="{{ $product->name }}" class="h-16 w-16 rounded object-cover">
                                <p class="mt-1 text-xs text-slate-500">Current icon</p>
                            </div>
                        @endif
                        <input type="file" name="icon" accept="image/*" class="mt-1 w-full rounded-xl border-slate-200">
                        <p class="mt-1 text-xs text-slate-500">Leave empty to keep current icon</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Product Image</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-32 w-full rounded object-cover">
                                <p class="mt-1 text-xs text-slate-500">Current image</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded-xl border-slate-200">
                        <p class="mt-1 text-xs text-slate-500">Leave empty to keep current image</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="is_active" class="text-sm font-medium text-slate-700">Active (visible on website)</label>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.section>
    </div>

    <script>
        function addFeature() {
            const container = document.getElementById('features-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="features[]" class="flex-1 rounded-xl border-slate-200" placeholder="Enter a feature">
                <button type="button" onclick="removeFeature(this)" class="rounded-xl bg-rose-50 px-3 py-2 text-rose-600 hover:bg-rose-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            container.appendChild(div);
        }

        function removeFeature(button) {
            button.parentElement.remove();
        }
    </script>
@endsection
