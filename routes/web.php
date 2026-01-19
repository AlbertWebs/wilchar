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
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanCalculatorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicLoanApplicationController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public page routes
Route::get('/page/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

// Products routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::post('/calculate-loan', [LoanCalculatorController::class, 'calculate'])->name('loan.calculate');

// Public Loan Application Routes
Route::get('/apply', [PublicLoanApplicationController::class, 'create'])->name('loan-application.create');
Route::post('/apply', [PublicLoanApplicationController::class, 'store'])->name('loan-application.store');
Route::get('/apply/thank-you', [PublicLoanApplicationController::class, 'thankYou'])->name('loan-application.thank-you');

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

// Admin Routes - Using Permission-Based Access Control
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard - requires dashboard.view permission
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('admin.dashboard');

    // Clients - each action requires specific permission
    Route::get('clients', [ClientController::class, 'index'])
        ->middleware('permission:clients.view')
        ->name('admin.clients.index');
    Route::get('clients/create', [ClientController::class, 'create'])
        ->middleware('permission:clients.create')
        ->name('admin.clients.create');
    Route::post('clients', [ClientController::class, 'store'])
        ->middleware('permission:clients.create')
        ->name('admin.clients.store');
    Route::get('clients/{client}', [ClientController::class, 'show'])
        ->middleware('permission:clients.view')
        ->name('admin.clients.show');
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])
        ->middleware('permission:clients.edit')
        ->name('admin.clients.edit');
    Route::patch('clients/{client}', [ClientController::class, 'update'])
        ->middleware('permission:clients.edit')
        ->name('admin.clients.update');
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])
        ->middleware('permission:clients.delete')
        ->name('admin.clients.destroy');

    // Loan Applications - Permission-based
    Route::get('loan-applications', [LoanApplicationController::class, 'index'])
        ->middleware('permission:loan-applications.view')
        ->name('loan-applications.index');
    Route::get('loan-applications/create', [LoanApplicationController::class, 'create'])
        ->middleware('permission:loan-applications.create')
        ->name('loan-applications.create');
    Route::post('loan-applications', [LoanApplicationController::class, 'store'])
        ->middleware('permission:loan-applications.create')
        ->name('loan-applications.store');
    Route::get('loan-applications/{loanApplication}', [LoanApplicationController::class, 'show'])
        ->middleware('permission:loan-applications.view')
        ->name('loan-applications.show');
    Route::get('loan-applications/{loanApplication}/edit', [LoanApplicationController::class, 'edit'])
        ->middleware('permission:loan-applications.edit')
        ->name('loan-applications.edit');
    Route::patch('loan-applications/{loanApplication}', [LoanApplicationController::class, 'update'])
        ->middleware('permission:loan-applications.edit')
        ->name('loan-applications.update');
    Route::delete('loan-applications/{loanApplication}', [LoanApplicationController::class, 'destroy'])
        ->middleware('permission:loan-applications.delete')
        ->name('loan-applications.destroy');
    Route::post('loan-applications/{loanApplication}/assign-officer', [LoanApplicationController::class, 'assignLoanOfficer'])
        ->middleware('permission:loan-applications.assign')
        ->name('loan-applications.assign-officer');

    // Approvals - Permission-based
    Route::get('approvals', [LoanApprovalController::class, 'index'])
        ->middleware('permission:approvals.view')
        ->name('approvals.index');
    Route::get('approvals/{loanApplication}', [LoanApprovalController::class, 'show'])
        ->middleware('permission:approvals.view')
        ->name('approvals.show');
    Route::post('approvals/{loanApplication}/approve', [LoanApprovalController::class, 'approve'])
        ->middleware('permission:approvals.approve')
        ->name('approvals.approve');
    Route::post('approvals/{loanApplication}/reject', [LoanApprovalController::class, 'reject'])
        ->middleware('permission:approvals.reject')
        ->name('approvals.reject');
    Route::post('approvals/{loanApplication}/send-email', [LoanApprovalController::class, 'sendEmail'])
        ->middleware('permission:approvals.view')
        ->name('approvals.send-email');

    // Teams & Members - Permission-based
    Route::get('teams', [TeamController::class, 'index'])->middleware('permission:teams.view')->name('teams.index');
    Route::get('teams/create', [TeamController::class, 'create'])->middleware('permission:teams.create')->name('teams.create');
    Route::post('teams', [TeamController::class, 'store'])->middleware('permission:teams.create')->name('teams.store');
    Route::get('teams/{team}', [TeamController::class, 'show'])->middleware('permission:teams.view')->name('teams.show');
    Route::get('teams/{team}/edit', [TeamController::class, 'edit'])->middleware('permission:teams.edit')->name('teams.edit');
    Route::patch('teams/{team}', [TeamController::class, 'update'])->middleware('permission:teams.edit')->name('teams.update');
    Route::delete('teams/{team}', [TeamController::class, 'destroy'])->middleware('permission:teams.delete')->name('teams.destroy');
    Route::post('teams/{team}/members', [TeamController::class, 'assignMember'])->middleware('permission:teams.assign')->name('teams.members.assign');
    Route::delete('teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->middleware('permission:teams.assign')->name('teams.members.remove');

    // Loans (approved loans) - Permission-based
    Route::get('loans', [LoanController::class, 'index'])
        ->middleware('permission:loans.view')
        ->name('loans.index');
    Route::get('loans/create', [LoanController::class, 'create'])
        ->middleware('permission:loans.create')
        ->name('loans.create');
    Route::post('loans', [LoanController::class, 'store'])
        ->middleware('permission:loans.create')
        ->name('loans.store');
    Route::get('loans/{loan}', [LoanController::class, 'show'])
        ->middleware('permission:loans.view')
        ->name('loans.show');
    Route::get('loans/{loan}/edit', [LoanController::class, 'edit'])
        ->middleware('permission:loans.edit')
        ->name('loans.edit');
    Route::patch('loans/{loan}', [LoanController::class, 'update'])
        ->middleware('permission:loans.edit')
        ->name('loans.update');
    Route::delete('loans/{loan}', [LoanController::class, 'destroy'])
        ->middleware('permission:loans.delete')
        ->name('loans.destroy');

    // Financial Management - Permission-based
    // Assets
    Route::get('assets', [AssetController::class, 'index'])->middleware('permission:assets.view')->name('assets.index');
    Route::post('assets', [AssetController::class, 'store'])->middleware('permission:assets.create')->name('assets.store');
    Route::patch('assets/{asset}', [AssetController::class, 'update'])->middleware('permission:assets.edit')->name('assets.update');
    Route::delete('assets/{asset}', [AssetController::class, 'destroy'])->middleware('permission:assets.delete')->name('assets.destroy');
    
    // Expenses
    Route::get('expenses', [ExpenseController::class, 'index'])->middleware('permission:expenses.view')->name('expenses.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->middleware('permission:expenses.create')->name('expenses.store');
    Route::patch('expenses/{expense}', [ExpenseController::class, 'update'])->middleware('permission:expenses.edit')->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->middleware('permission:expenses.delete')->name('expenses.destroy');
    
    // Liabilities
    Route::get('liabilities', [LiabilityController::class, 'index'])->middleware('permission:liabilities.view')->name('liabilities.index');
    Route::post('liabilities', [LiabilityController::class, 'store'])->middleware('permission:liabilities.create')->name('liabilities.store');
    Route::patch('liabilities/{liability}', [LiabilityController::class, 'update'])->middleware('permission:liabilities.edit')->name('liabilities.update');
    Route::delete('liabilities/{liability}', [LiabilityController::class, 'destroy'])->middleware('permission:liabilities.delete')->name('liabilities.destroy');
    
    // Shareholders
    Route::get('shareholders', [ShareholderController::class, 'index'])->middleware('permission:shareholders.view')->name('shareholders.index');
    Route::get('shareholders/{shareholder}', [ShareholderController::class, 'show'])->middleware('permission:shareholders.view')->name('shareholders.show');
    Route::post('shareholders', [ShareholderController::class, 'store'])->middleware('permission:shareholders.create')->name('shareholders.store');
    Route::patch('shareholders/{shareholder}', [ShareholderController::class, 'update'])->middleware('permission:shareholders.edit')->name('shareholders.update');
    Route::delete('shareholders/{shareholder}', [ShareholderController::class, 'destroy'])->middleware('permission:shareholders.delete')->name('shareholders.destroy');
    Route::post('shareholders/{shareholder}/contributions', [ShareholderController::class, 'storeContribution'])->middleware('permission:shareholders.contributions')->name('shareholders.contributions.store');
    
    // Trial Balances
    Route::get('trial-balances', [TrialBalanceController::class, 'index'])->middleware('permission:trial-balances.view')->name('trial-balances.index');
    Route::get('trial-balances/{trialBalance}', [TrialBalanceController::class, 'show'])->middleware('permission:trial-balances.view')->name('trial-balances.show');
    Route::post('trial-balances', [TrialBalanceController::class, 'store'])->middleware('permission:trial-balances.generate')->name('trial-balances.store');
    Route::delete('trial-balances/{trialBalance}', [TrialBalanceController::class, 'destroy'])->middleware('permission:trial-balances.delete')->name('trial-balances.destroy');
    
    // Account Balances
    Route::get('account-balances', [AccountBalanceController::class, 'index'])->middleware('permission:account-balances.view')->name('account-balances.index');
    Route::post('account-balances', [AccountBalanceController::class, 'store'])->middleware('permission:account-balances.create')->name('account-balances.store');

    // Disbursements (B2C - kept for backward compatibility) - Permission-based
    Route::get('disbursements', [MpesaDisbursementController::class, 'index'])
        ->middleware('permission:disbursements.view')
        ->name('disbursements.index');
    Route::get('disbursements/create/{loanApplication}', [MpesaDisbursementController::class, 'create'])
        ->middleware('permission:disbursements.create')
        ->name('disbursements.create');
    Route::post('disbursements/{loanApplication}', [MpesaDisbursementController::class, 'store'])
        ->middleware('permission:disbursements.create')
        ->name('disbursements.store');
    Route::get('disbursements/{disbursement}', [MpesaDisbursementController::class, 'show'])
        ->middleware('permission:disbursements.view')
        ->name('disbursements.show');
    Route::post('disbursements/{disbursement}/retry', [MpesaDisbursementController::class, 'retry'])
        ->middleware('permission:disbursements.retry')
        ->name('disbursements.retry');

    // Collections - Permission-based
    Route::get('collections', [RepaymentController::class, 'index'])
        ->middleware('permission:collections.view')
        ->name('collections.index');
    Route::get('collections/create', [RepaymentController::class, 'create'])
        ->middleware('permission:collections.create')
        ->name('collections.create');
    Route::post('collections', [RepaymentController::class, 'store'])
        ->middleware('permission:collections.create')
        ->name('collections.store');
    Route::get('collections/{collection}', [RepaymentController::class, 'show'])
        ->middleware('permission:collections.view')
        ->name('collections.show');
    Route::get('collections/{collection}/edit', [RepaymentController::class, 'edit'])
        ->middleware('permission:collections.edit')
        ->name('collections.edit');
    Route::patch('collections/{collection}', [RepaymentController::class, 'update'])
        ->middleware('permission:collections.edit')
        ->name('collections.update');
    Route::delete('collections/{collection}', [RepaymentController::class, 'destroy'])
        ->middleware('permission:collections.delete')
        ->name('collections.destroy');

    // M-Pesa Operations - Permission-based
    Route::prefix('mpesa')->name('mpesa.')->middleware('permission:mpesa.view')->group(function () {
        // M-Pesa Dashboard
        Route::get('/', function() {
            return view('admin.mpesa.dashboard');
        })->name('dashboard');

        // STK Push (Lipa na M-Pesa Online)
        Route::get('stk-push', [StkPushController::class, 'index'])->middleware('permission:mpesa.stk-push')->name('stk-push.index');
        Route::get('stk-push/create', [StkPushController::class, 'create'])->middleware('permission:mpesa.stk-push')->name('stk-push.create');
        Route::post('stk-push', [StkPushController::class, 'store'])->middleware('permission:mpesa.stk-push')->name('stk-push.store');
        Route::get('stk-push/{stkPush}', [StkPushController::class, 'show'])->middleware('permission:mpesa.stk-push')->name('stk-push.show');

        // C2B Transactions
        Route::get('c2b', [C2bTransactionController::class, 'index'])->middleware('permission:mpesa.c2b')->name('c2b.index');
        Route::post('c2b/register-urls', [C2bTransactionController::class, 'registerUrls'])->middleware('permission:mpesa.c2b')->name('c2b.register-urls');
        Route::get('c2b/{c2bTransaction}', [C2bTransactionController::class, 'show'])->middleware('permission:mpesa.c2b')->name('c2b.show');

        // B2B Transactions
        Route::get('b2b', [B2bTransactionController::class, 'index'])->middleware('permission:mpesa.b2b')->name('b2b.index');
        Route::get('b2b/create', [B2bTransactionController::class, 'create'])->middleware('permission:mpesa.b2b')->name('b2b.create');
        Route::post('b2b', [B2bTransactionController::class, 'store'])->middleware('permission:mpesa.b2b')->name('b2b.store');
        Route::get('b2b/{b2bTransaction}', [B2bTransactionController::class, 'show'])->middleware('permission:mpesa.b2b')->name('b2b.show');

        // B2C Disbursements (existing)
        Route::get('b2c', [MpesaDisbursementController::class, 'index'])->middleware('permission:mpesa.b2c')->name('b2c.index');
        Route::get('b2c/create/{loanApplication}', [MpesaDisbursementController::class, 'create'])->middleware('permission:mpesa.b2c')->name('b2c.create');
        Route::post('b2c/{loanApplication}', [MpesaDisbursementController::class, 'store'])->middleware('permission:mpesa.b2c')->name('b2c.store');
        Route::get('b2c/{disbursement}', [MpesaDisbursementController::class, 'show'])->middleware('permission:mpesa.b2c')->name('b2c.show');

        // Account Balance
        Route::get('account-balance', [MpesaAccountBalanceController::class, 'index'])->middleware('permission:mpesa.account-balance')->name('account-balance.index');
        Route::post('account-balance', [MpesaAccountBalanceController::class, 'store'])->middleware('permission:mpesa.account-balance')->name('account-balance.store');

        // Transaction Status
        Route::get('transaction-status', [MpesaTransactionStatusController::class, 'index'])->middleware('permission:mpesa.transaction-status')->name('transaction-status.index');
        Route::get('transaction-status/create', [MpesaTransactionStatusController::class, 'create'])->middleware('permission:mpesa.transaction-status')->name('transaction-status.create');
        Route::post('transaction-status', [MpesaTransactionStatusController::class, 'store'])->middleware('permission:mpesa.transaction-status')->name('transaction-status.store');
    });

    // Reports - Permission-based
    Route::prefix('reports')->middleware('permission:reports.view')->group(function () {
        Route::get('/', [ReportController::class, 'dashboard'])->name('reports.dashboard');
        Route::get('/financial', [ReportController::class, 'financial'])->middleware('permission:reports.financial')->name('reports.financial');
        Route::get('/loan-applications', [ReportController::class, 'loanApplications'])->middleware('permission:reports.loan-applications')->name('reports.loan-applications');
        Route::get('/disbursements', [ReportController::class, 'disbursements'])->middleware('permission:reports.disbursements')->name('reports.disbursements');
        Route::get('/clients', [ReportController::class, 'clients'])->middleware('permission:reports.clients')->name('reports.clients');
        Route::get('/users', [ReportController::class, 'users'])->middleware('permission:reports.users')->name('reports.users');
        Route::get('/loans', [ReportController::class, 'loans'])->middleware('permission:reports.loans')->name('reports.loans');
    });

    // User Management - Permission-based
    Route::get('users', [UserController::class, 'index'])->middleware('permission:users.view')->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->middleware('permission:users.create')->name('users.create');
    Route::post('users', [UserController::class, 'store'])->middleware('permission:users.create')->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('permission:users.view')->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users.edit')->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->middleware('permission:users.edit')->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('users.destroy');

    // Loan Products - Permission-based
    Route::get('loan-products', [LoanProductController::class, 'index'])->middleware('permission:loan-products.view')->name('loan-products.index');
    Route::get('loan-products/create', [LoanProductController::class, 'create'])->middleware('permission:loan-products.create')->name('loan-products.create');
    Route::post('loan-products', [LoanProductController::class, 'store'])->middleware('permission:loan-products.create')->name('loan-products.store');
    Route::get('loan-products/{loanProduct}', [LoanProductController::class, 'show'])->middleware('permission:loan-products.view')->name('loan-products.show');
    Route::get('loan-products/{loanProduct}/edit', [LoanProductController::class, 'edit'])->middleware('permission:loan-products.edit')->name('loan-products.edit');
    Route::patch('loan-products/{loanProduct}', [LoanProductController::class, 'update'])->middleware('permission:loan-products.edit')->name('loan-products.update');
    Route::delete('loan-products/{loanProduct}', [LoanProductController::class, 'destroy'])->middleware('permission:loan-products.delete')->name('loan-products.destroy');

    // Testimonials - Permission-based
    Route::get('testimonials', [TestimonialController::class, 'index'])->middleware('permission:testimonials.view')->name('admin.testimonials.index');
    Route::get('testimonials/create', [TestimonialController::class, 'create'])->middleware('permission:testimonials.create')->name('admin.testimonials.create');
    Route::post('testimonials', [TestimonialController::class, 'store'])->middleware('permission:testimonials.create')->name('admin.testimonials.store');
    Route::get('testimonials/{testimonial}', [TestimonialController::class, 'show'])->middleware('permission:testimonials.view')->name('admin.testimonials.show');
    Route::get('testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->middleware('permission:testimonials.edit')->name('admin.testimonials.edit');
    Route::patch('testimonials/{testimonial}', [TestimonialController::class, 'update'])->middleware('permission:testimonials.edit')->name('admin.testimonials.update');
    Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->middleware('permission:testimonials.delete')->name('admin.testimonials.destroy');

    // Products - Permission-based
    Route::get('products', [AdminProductController::class, 'index'])->middleware('permission:products.view')->name('admin.products.index');
    Route::get('products/create', [AdminProductController::class, 'create'])->middleware('permission:products.create')->name('admin.products.create');
    Route::post('products', [AdminProductController::class, 'store'])->middleware('permission:products.create')->name('admin.products.store');
    Route::get('products/{product}', [AdminProductController::class, 'show'])->middleware('permission:products.view')->name('admin.products.show');
    Route::get('products/{product}/edit', [AdminProductController::class, 'edit'])->middleware('permission:products.edit')->name('admin.products.edit');
    Route::patch('products/{product}', [AdminProductController::class, 'update'])->middleware('permission:products.edit')->name('admin.products.update');
    Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->middleware('permission:products.delete')->name('admin.products.destroy');

    // Notifications - Permission-based
    Route::get('notifications', [NotificationController::class, 'index'])->middleware('permission:notifications.view')->name('notifications.index');
    Route::get('notifications/create', [NotificationController::class, 'create'])->middleware('permission:notifications.create')->name('notifications.create');
    Route::post('notifications', [NotificationController::class, 'store'])->middleware('permission:notifications.create')->name('notifications.store');
    Route::get('notifications/{notification}', [NotificationController::class, 'show'])->middleware('permission:notifications.view')->name('notifications.show');
    Route::get('notifications/{notification}/edit', [NotificationController::class, 'edit'])->middleware('permission:notifications.edit')->name('notifications.edit');
    Route::patch('notifications/{notification}', [NotificationController::class, 'update'])->middleware('permission:notifications.edit')->name('notifications.update');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->middleware('permission:notifications.delete')->name('notifications.destroy');

    // Audit Logs - Permission-based
    Route::get('logs', [AuditLogController::class, 'index'])->middleware('permission:audit-logs.view')->name('logs.index');
    Route::get('logs/{log}', [AuditLogController::class, 'show'])->middleware('permission:audit-logs.show')->name('logs.show');

    // Role Management - Permission-based
    Route::get('roles', [RoleController::class, 'index'])->middleware('permission:roles.view')->name('admin.roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->middleware('permission:roles.create')->name('admin.roles.create');
    Route::post('roles', [RoleController::class, 'store'])->middleware('permission:roles.create')->name('admin.roles.store');
    Route::get('roles/{role}', [RoleController::class, 'show'])->middleware('permission:roles.view')->name('admin.roles.show');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles.edit')->name('admin.roles.edit');
    Route::patch('roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('admin.roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('admin.roles.destroy');

    // Site Settings - Permission-based
    Route::get('site-settings', [SiteSettingController::class, 'edit'])->middleware('permission:site-settings.view')->name('admin.site-settings.edit');
    Route::patch('site-settings', [SiteSettingController::class, 'update'])->middleware('permission:site-settings.edit')->name('admin.site-settings.update');

    // Website CMS
    Route::prefix('website')->name('admin.website.')->group(function () {
        Route::resource('pages', \App\Http\Controllers\Admin\WebsiteController::class);
    });

    // Legal Pages
    Route::get('legal-pages', [\App\Http\Controllers\Admin\LegalPageController::class, 'index'])->middleware('permission:pages.view')->name('admin.legal-pages.index');
    Route::get('legal-pages/{type}/edit', [\App\Http\Controllers\Admin\LegalPageController::class, 'edit'])->middleware('permission:pages.edit')->name('admin.legal-pages.edit');
    Route::patch('legal-pages/{type}', [\App\Http\Controllers\Admin\LegalPageController::class, 'update'])->middleware('permission:pages.edit')->name('admin.legal-pages.update');

    // Admin Profile
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password.update');
    Route::delete('profile', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');
});

Route::prefix('admin/finance-disbursements')
    ->middleware(['auth', 'role:Admin|Finance|Director'])
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

// Disbursement Initiation (with OTP) - Accessible to Admin, Finance, and Director
Route::prefix('admin/disbursements')
    ->middleware(['auth', 'role:Admin|Finance|Director'])
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

// M-Pesa B2C Callbacks (public routes, no auth)
Route::post('/api/mpesa/b2c/callback', [MpesaDisbursementController::class, 'callback'])->name('disbursements.callback');
Route::post('/api/mpesa/b2c/timeout', [MpesaDisbursementController::class, 'timeout'])->name('disbursements.timeout');

require __DIR__.'/auth.php';
