@extends('layouts.admin', ['title' => 'Create Loan Product'])

@section('header')
    Create Loan Product
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Product Configuration" description="Define interest rates, fees and tenure for this lending product.">
            <form action="{{ route('loan-products.store') }}" method="POST" class="space-y-6">
                @csrf
                @include('admin.loan-products.partials.form')
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('loan-products.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Save Product
                    </button>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

