@extends('layouts.admin', ['title' => 'Create Loan Application'])

@section('header')
    Create Loan Application
@endsection

@section('content')
    <x-admin.section title="Loan Application Onboarding">
        @include('admin.loan-applications.partials.form')
    </x-admin.section>
@endsection

