@extends('adminlte::page')

@section('title', 'Edit Loan Application')

@section('content_header')
    <h1>Edit Loan Application</h1>
@stop

@section('content')
    <form action="{{ route('loan-applications.update', $loan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                @include('admin.loan-applications.form')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('loan-applications.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
@stop
