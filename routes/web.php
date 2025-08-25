<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\LoanApprovalController;
use App\Http\Controllers\Admin\DisbursementController;
use App\Http\Controllers\Admin\RepaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanCalculatorController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/calculate-loan', [LoanCalculatorController::class, 'calculate'])->name('loan.calculate');

// Common Dashboard Route (fallback if role not handled)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('loan-applications', LoanController::class);
    Route::resource('approvals', LoanApprovalController::class);
    Route::resource('loans', LoanController::class); // or separate ApprovedLoansController
    Route::resource('disbursements', DisbursementController::class);
    Route::resource('collections', RepaymentController::class);
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
