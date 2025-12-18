<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\LoanApplicationController;
use App\Http\Controllers\Admin\LoanApprovalController;
use App\Http\Controllers\Admin\MpesaDisbursementController;
use App\Http\Controllers\Admin\DisbursementController;
use App\Http\Controllers\Admin\RepaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoanProductController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StkPushController;
use App\Http\Controllers\Admin\C2bTransactionController;
use App\Http\Controllers\Admin\B2bTransactionController;
use App\Http\Controllers\Admin\MpesaAccountBalanceController;
use App\Http\Controllers\Admin\MpesaTransactionStatusController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\LiabilityController;
use App\Http\Controllers\Admin\ShareholderController;
use App\Http\Controllers\Admin\TrialBalanceController;
use App\Http\Controllers\Admin\AccountBalanceController;
use App\Http\Controllers\Admin\FinanceDisbursementController;
use App\Http\Controllers\Admin\PaymentDashboardController;
use App\Http\Controllers\Admin\SandboxController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileSettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanCalculatorController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public page routes
Route::get('/page/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

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
// M-Pesa Callback Routes (Public)
// ===========================
// These routes need to be public for M-Pesa servers to call them
Route::prefix('admin/mpesa')->name('mpesa.')->group(function () {
    Route::post('stk-push/callback', [StkPushController::class, 'callback'])->name('stk-callback');
    Route::post('c2b/validate', [C2bTransactionController::class, 'validate'])->name('c2b.validate');
    Route::post('c2b/confirm', [C2bTransactionController::class, 'confirm'])->name('c2b.confirm');
    Route::post('b2b/callback', [B2bTransactionController::class, 'callback'])->name('b2b.callback');
    Route::post('b2b/timeout', [B2bTransactionController::class, 'timeout'])->name('b2b.timeout');
    Route::post('account-balance/callback', [MpesaAccountBalanceController::class, 'callback'])->name('account-balance.callback');
    Route::post('account-balance/timeout', [MpesaAccountBalanceController::class, 'timeout'])->name('account-balance.timeout');
    Route::post('transaction-status/callback', [MpesaTransactionStatusController::class, 'callback'])->name('transaction-status.callback');
    Route::post('transaction-status/timeout', [MpesaTransactionStatusController::class, 'timeout'])->name('transaction-status.timeout');
});

// ===========================
// Role-Based Dashboards
// ===========================

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Clients
    Route::resource('clients', ClientController::class)->names([
        'index' => 'admin.clients.index',
        'create' => 'admin.clients.create',
        'store' => 'admin.clients.store',
        'show' => 'admin.clients.show',
        'edit' => 'admin.clients.edit',
        'update' => 'admin.clients.update',
        'destroy' => 'admin.clients.destroy',
    ]);

    // Loan Applications (Admin can manage all)
    Route::resource('loan-applications', LoanApplicationController::class);
    Route::post('loan-applications/{loanApplication}/assign-officer', [LoanApplicationController::class, 'assignLoanOfficer'])->name('loan-applications.assign-officer');

    // Approvals
    Route::get('approvals', [LoanApprovalController::class, 'index'])->name('approvals.index');
    Route::get('approvals/{loanApplication}', [LoanApprovalController::class, 'show'])->name('approvals.show');
    Route::post('approvals/{loanApplication}/approve', [LoanApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{loanApplication}/reject', [LoanApprovalController::class, 'reject'])->name('approvals.reject');
    Route::post('approvals/{loanApplication}/send-email', [LoanApprovalController::class, 'sendEmail'])->name('approvals.send-email');

    // Teams & Members
    Route::resource('teams', TeamController::class);
    Route::post('teams/{team}/members', [TeamController::class, 'assignMember'])->name('teams.members.assign');
    Route::delete('teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('teams.members.remove');

    // Loans (approved loans)
    Route::resource('loans', LoanController::class);

    // Financial Management
    Route::resource('assets', AssetController::class)->except(['show', 'create', 'edit']);
    Route::resource('expenses', ExpenseController::class)->except(['show', 'create', 'edit']);
    Route::resource('liabilities', LiabilityController::class)->except(['show', 'create', 'edit']);
    Route::resource('shareholders', ShareholderController::class)->except(['create', 'edit']);
    Route::post('shareholders/{shareholder}/contributions', [ShareholderController::class, 'storeContribution'])->name('shareholders.contributions.store');
    Route::resource('trial-balances', TrialBalanceController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::resource('account-balances', AccountBalanceController::class)->only(['index', 'store']);

    // Disbursements (B2C - kept for backward compatibility)
    Route::get('disbursements', [MpesaDisbursementController::class, 'index'])->name('disbursements.index');
    Route::get('disbursements/create/{loanApplication}', [MpesaDisbursementController::class, 'create'])->name('disbursements.create');
    Route::post('disbursements/{loanApplication}', [MpesaDisbursementController::class, 'store'])->name('disbursements.store');
    Route::get('disbursements/{disbursement}', [MpesaDisbursementController::class, 'show'])->name('disbursements.show');
    Route::post('disbursements/{disbursement}/retry', [MpesaDisbursementController::class, 'retry'])->name('disbursements.retry');


    // Collections
    Route::resource('collections', RepaymentController::class);

    // M-Pesa Operations
    Route::prefix('mpesa')->name('mpesa.')->group(function () {
        // M-Pesa Dashboard
        Route::get('/', function() {
            return view('admin.mpesa.dashboard');
        })->name('dashboard');

        // STK Push (Lipa na M-Pesa Online)
        Route::get('stk-push', [StkPushController::class, 'index'])->name('stk-push.index');
        Route::get('stk-push/create', [StkPushController::class, 'create'])->name('stk-push.create');
        Route::post('stk-push', [StkPushController::class, 'store'])->name('stk-push.store');
        Route::get('stk-push/{stkPush}', [StkPushController::class, 'show'])->name('stk-push.show');

        // C2B Transactions
        Route::get('c2b', [C2bTransactionController::class, 'index'])->name('c2b.index');
        Route::get('c2b/{c2bTransaction}', [C2bTransactionController::class, 'show'])->name('c2b.show');

        // B2B Transactions
        Route::get('b2b', [B2bTransactionController::class, 'index'])->name('b2b.index');
        Route::get('b2b/create', [B2bTransactionController::class, 'create'])->name('b2b.create');
        Route::post('b2b', [B2bTransactionController::class, 'store'])->name('b2b.store');
        Route::get('b2b/{b2bTransaction}', [B2bTransactionController::class, 'show'])->name('b2b.show');

        // B2C Disbursements (existing)
        Route::get('b2c', [MpesaDisbursementController::class, 'index'])->name('b2c.index');
        Route::get('b2c/create/{loanApplication}', [MpesaDisbursementController::class, 'create'])->name('b2c.create');
        Route::post('b2c/{loanApplication}', [MpesaDisbursementController::class, 'store'])->name('b2c.store');
        Route::get('b2c/{disbursement}', [MpesaDisbursementController::class, 'show'])->name('b2c.show');

        // Account Balance
        Route::get('account-balance', [MpesaAccountBalanceController::class, 'index'])->name('account-balance.index');
        Route::post('account-balance', [MpesaAccountBalanceController::class, 'store'])->name('account-balance.store');

        // Transaction Status
        Route::get('transaction-status', [MpesaTransactionStatusController::class, 'index'])->name('transaction-status.index');
        Route::get('transaction-status/create', [MpesaTransactionStatusController::class, 'create'])->name('transaction-status.create');
        Route::post('transaction-status', [MpesaTransactionStatusController::class, 'store'])->name('transaction-status.store');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'dashboard'])->name('reports.dashboard');
        Route::get('/financial', [ReportController::class, 'financial'])->name('reports.financial');
        Route::get('/loan-applications', [ReportController::class, 'loanApplications'])->name('reports.loan-applications');
        Route::get('/disbursements', [ReportController::class, 'disbursements'])->name('reports.disbursements');
        Route::get('/clients', [ReportController::class, 'clients'])->name('reports.clients');
        Route::get('/users', [ReportController::class, 'users'])->name('reports.users');
        Route::get('/loans', [ReportController::class, 'loans'])->name('reports.loans');
    });

    // User Management
    Route::resource('users', UserController::class);

    // Loan Products
    Route::resource('loan-products', LoanProductController::class);

    // Notifications
    Route::resource('notifications', NotificationController::class);

    // Audit Logs
    Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
    Route::get('logs/{log}', [AuditLogController::class, 'show'])->name('logs.show');

    // Role Management
    Route::resource('roles', RoleController::class)->names([
        'index' => 'admin.roles.index',
        'create' => 'admin.roles.create',
        'store' => 'admin.roles.store',
        'show' => 'admin.roles.show',
        'edit' => 'admin.roles.edit',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy',
    ]);

    // Site Settings
    Route::get('site-settings', [SiteSettingController::class, 'edit'])->name('admin.site-settings.edit');
    Route::patch('site-settings', [SiteSettingController::class, 'update'])->name('admin.site-settings.update');

    // Website CMS
    Route::prefix('website')->name('admin.website.')->group(function () {
        Route::resource('pages', \App\Http\Controllers\Admin\WebsiteController::class);
    });

    // Admin Profile
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password.update');
    Route::delete('profile', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');
});

Route::prefix('admin/finance-disbursements')
    ->middleware(['auth', 'role:Finance|Director'])
    ->name('finance-disbursements.')
    ->group(function () {
        Route::get('/', [FinanceDisbursementController::class, 'index'])->name('index');
        Route::post('/prepare', [FinanceDisbursementController::class, 'prepare'])->name('prepare');
        Route::get('/confirm/{disbursement}', [FinanceDisbursementController::class, 'showConfirm'])->name('confirm.show');
        Route::post('/confirm/{disbursement}', [FinanceDisbursementController::class, 'confirm'])->name('confirm.store');
    });

Route::prefix('admin/payments')
    ->middleware(['auth', 'role:Admin|Finance|Director|Collection Officer'])
    ->name('payments.')
    ->group(function () {
        Route::get('/', [PaymentDashboardController::class, 'index'])->name('index');
        Route::post('/attach', [PaymentDashboardController::class, 'attach'])->name('attach');
    });

// Disbursement Initiation (with OTP) - Accessible to Admin, Finance Officer, and Director
Route::prefix('admin/disbursements')
    ->middleware(['auth', 'role:Admin|Finance Officer|Director'])
    ->group(function () {
        Route::post('{disbursement}/generate-otp', [\App\Http\Controllers\Admin\DisbursementInitiationController::class, 'generateOtp'])->name('disbursements.generate-otp');
        Route::post('{disbursement}/verify-otp', [\App\Http\Controllers\Admin\DisbursementInitiationController::class, 'verifyOtpAndDisburse'])->name('disbursements.verify-otp');
        Route::post('{disbursement}/abort', [\App\Http\Controllers\Admin\DisbursementInitiationController::class, 'abort'])->name('disbursements.abort');
        Route::get('{disbursement}/status', [\App\Http\Controllers\Admin\DisbursementInitiationController::class, 'getStatus'])->name('disbursements.status');
    });

Route::middleware(['auth'])->prefix('admin/sandbox')->name('sandbox.')->group(function () {
    Route::get('/purge', [SandboxController::class, 'index'])->name('purge.index');
    Route::post('/purge', [SandboxController::class, 'purge'])->name('purge.run');
});

Route::middleware(['auth'])->prefix('admin/profile')->name('admin.profile.')->group(function () {
    Route::get('/', [AdminProfileSettingsController::class, 'edit'])->name('edit');
    Route::put('/', [AdminProfileSettingsController::class, 'update'])->name('update');
});

// Loan Officer Routes
Route::middleware(['auth', 'role:Loan Officer'])->prefix('loan-officer')->group(function () {
    Route::get('/dashboard', function () {
        return view('loan_officer.dashboard');
    })->name('loanofficer.dashboard');

    // Loan Applications
    Route::get('/applications', [LoanApplicationController::class, 'index'])->name('loan-officer.applications.index');
    Route::get('/applications/{loanApplication}', [LoanApplicationController::class, 'show'])->name('loan-officer.applications.show');

    // Approvals
    Route::get('/approvals', [LoanApprovalController::class, 'index'])->name('loan-officer.approvals.index');
    Route::get('/approvals/{loanApplication}', [LoanApprovalController::class, 'show'])->name('loan-officer.approvals.show');
    Route::post('/approvals/{loanApplication}/approve', [LoanApprovalController::class, 'approve'])->name('loan-officer.approvals.approve');
    Route::post('/approvals/{loanApplication}/reject', [LoanApprovalController::class, 'reject'])->name('loan-officer.approvals.reject');
});

// Credit Officer Routes
Route::middleware(['auth', 'role:Credit Officer'])->prefix('credit-officer')->group(function () {
    Route::get('/dashboard', function () {
        return view('credit_officer.dashboard');
    })->name('credit-officer.dashboard');

    // Approvals
    Route::get('/approvals', [LoanApprovalController::class, 'index'])->name('credit-officer.approvals.index');
    Route::get('/approvals/{loanApplication}', [LoanApprovalController::class, 'show'])->name('credit-officer.approvals.show');
    Route::post('/approvals/{loanApplication}/approve', [LoanApprovalController::class, 'approve'])->name('credit-officer.approvals.approve');
    Route::post('/approvals/{loanApplication}/reject', [LoanApprovalController::class, 'reject'])->name('credit-officer.approvals.reject');
});

// Director Routes
Route::middleware(['auth', 'role:Director'])->prefix('director')->group(function () {
    Route::get('/dashboard', function () {
        return view('director.dashboard');
    })->name('director.dashboard');

    // Approvals
    Route::get('/approvals', [LoanApprovalController::class, 'index'])->name('director.approvals.index');
    Route::get('/approvals/{loanApplication}', [LoanApprovalController::class, 'show'])->name('director.approvals.show');
    Route::post('/approvals/{loanApplication}/approve', [LoanApprovalController::class, 'approve'])->name('director.approvals.approve');
    Route::post('/approvals/{loanApplication}/reject', [LoanApprovalController::class, 'reject'])->name('director.approvals.reject');
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

// M-Pesa B2C Callback (public route, no auth)
Route::post('/api/mpesa/b2c/callback', [MpesaDisbursementController::class, 'callback'])->name('disbursements.callback');

require __DIR__.'/auth.php';
