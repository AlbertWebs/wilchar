@extends('layouts.admin', ['title' => 'Edit Testimonial'])

@section('header')
    Edit Testimonial
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Testimonial Details" description="Update the testimonial information.">
            <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @if($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <p class="font-semibold">We couldn't save the testimonial yet.</p>
                        <ul class="mt-2 list-disc pl-5 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Client Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" class="mt-1 w-full rounded-xl border-slate-200 @error('name') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" required>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Position / Title</label>
                        <input type="text" name="position" value="{{ old('position', $testimonial->position) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. Business Owner">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Company</label>
                        <input type="text" name="company" value="{{ old('company', $testimonial->company) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. ABC Enterprises">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rating <span class="text-rose-500">*</span></label>
                        <select name="rating" class="mt-1 w-full rounded-xl border-slate-200 @error('rating') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" required>
                            <option value="">Select Rating</option>
                            <option value="5" @selected(old('rating', $testimonial->rating) == 5)>5 Stars - Excellent</option>
                            <option value="4" @selected(old('rating', $testimonial->rating) == 4)>4 Stars - Very Good</option>
                            <option value="3" @selected(old('rating', $testimonial->rating) == 3)>3 Stars - Good</option>
                            <option value="2" @selected(old('rating', $testimonial->rating) == 2)>2 Stars - Fair</option>
                            <option value="1" @selected(old('rating', $testimonial->rating) == 1)>1 Star - Poor</option>
                        </select>
                        @error('rating')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Testimonial Content <span class="text-rose-500">*</span></label>
                    <textarea name="content" rows="4" class="mt-1 w-full rounded-xl border-slate-200 @error('content') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" required placeholder="Enter the client's testimonial...">{{ old('content', $testimonial->content) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Maximum 1000 characters</p>
                    @error('content')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Client Photo</label>
                        @if($testimonial->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="h-20 w-20 rounded-full object-cover">
                                <p class="mt-1 text-xs text-slate-500">Current photo</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded-xl border-slate-200 @error('image') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror">
                        <p class="mt-1 text-xs text-slate-500">Leave empty to keep current photo. Recommended: Square image, max 2MB</p>
                        @error('image')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Display Order</label>
                        <input type="number" name="display_order" value="{{ old('display_order', $testimonial->display_order) }}" min="0" class="mt-1 w-full rounded-xl border-slate-200" placeholder="0">
                        <p class="mt-1 text-xs text-slate-500">Lower numbers appear first</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="is_active" class="text-sm font-medium text-slate-700">Active (visible on home page)</label>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Update Testimonial
                    </button>
                    <a href="{{ route('admin.testimonials.index') }}" class="rounded-xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection
