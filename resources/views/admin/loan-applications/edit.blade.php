@extends('layouts.admin', ['title' => 'Edit Loan Application'])

@section('header')
    Edit {{ $application->application_number }}
@endsection

@section('content')
    <x-admin.section title="Update Loan Application Details">
        @include('admin.loan-applications.partials.form')
    </x-admin.section>
@endsection

