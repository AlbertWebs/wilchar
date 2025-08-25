@extends('adminlte::page')

@section('title', 'New Loan Application')

@section('content_header')
    <h1>Create Loan Application</h1>
@stop

@section('content')
    <form action="{{ route('loan-applications.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                @include('admin.loan-applications.form')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('loan-applications.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
@stop
