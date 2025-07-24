<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Common Dashboard Route (fallback if role not handled)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes for All Authenticated Users
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===========================
// Role-Based Dashboards
// ===========================

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Add more admin-only routes here
});

Route::middleware(['auth', 'role:Loan Officer'])->prefix('loan-officer')->group(function () {
    Route::get('/dashboard', function () {
        return view('loan_officer.dashboard');
    })->name('loanofficer.dashboard');

    // Add more Loan Officer routes here
});

Route::middleware(['auth', 'role:Accountant'])->prefix('accountant')->group(function () {
    Route::get('/dashboard', function () {
        return view('accountant.dashboard');
    })->name('accountant.dashboard');

    // Add more accountant routes here
});

Route::middleware(['auth', 'role:Collections'])->prefix('collections')->group(function () {
    Route::get('/dashboard', function () {
        return view('collections.dashboard');
    })->name('collections.dashboard');

    // Add more collections routes here
});

require __DIR__.'/auth.php';
