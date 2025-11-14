@extends('layouts.admin', ['title' => 'Edit Loan Product'])

@section('header')
    Edit Loan Product
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Update Product" description="Modify rates, fees or availability for this lending product.">
            <form action="{{ route('loan-products.update', $loanProduct) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.loan-products.partials.form', ['loanProduct' => $loanProduct])
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('loan-products.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                        ← Back to products
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                            Update Product
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

