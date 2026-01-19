@extends('layouts.admin', ['title' => 'Edit ' . $pageType['title']])

@section('header')
    Edit {{ $pageType['title'] }}
@endsection

@section('content')
    <x-admin.section title="{{ $pageType['title'] }}" description="Update the content for this legal page.">
        <form action="{{ route('admin.legal-pages.update', $type) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label class="text-sm font-medium text-slate-700">Content</label>
                <textarea name="content" id="content" rows="20" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" required>{{ old('content', $page->content) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">You can use HTML tags for formatting. The content will be displayed on the public page.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? $pageType['title']) }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="SEO title">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Description</label>
                    <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="SEO description">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="SEO keywords">
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-200 pt-4">
                <a href="{{ route('page.show', $pageType['slug']) }}" target="_blank" class="text-sm text-slate-600 hover:text-slate-900">
                    <i class="bi bi-eye me-1"></i> View Public Page
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('admin.legal-pages.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </x-admin.section>
@endsection
