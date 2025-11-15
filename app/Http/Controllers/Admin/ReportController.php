<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\Loan;
use App\Models\Disbursement;
use App\Models\Collection;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\User;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display dashboard reports
     */
    public function dashboard()
    {
        $stats = [
            'total_applications' => LoanApplication::count(),
            'pending_applications' => LoanApplication::where('status', 'submitted')->count(),
            'approved_applications' => LoanApplication::where('status', 'approved')->count(),
            'rejected_applications' => LoanApplication::where('status', 'rejected')->count(),
            'disbursed_loans' => LoanApplication::where('status', 'disbursed')->count(),
            'total_disbursed' => Disbursement::where('status', 'success')->sum('amount'),
            'total_collections' => Collection::sum('amount'),
            'active_clients' => Client::where('status', 'active')->count(),
            'pending_approvals' => LoanApplication::whereIn('approval_stage', ['loan_officer', 'credit_officer', 'finance_officer', 'director'])
                ->whereIn('status', ['submitted', 'under_review'])->count(),
        ];

        // Recent applications
        $recentApplications = LoanApplication::with('client')
            ->latest()
            ->limit(10)
            ->get();

        // Applications by stage
        $applicationsByStage = LoanApplication::select('approval_stage', DB::raw('count(*) as count'))
            ->whereIn('status', ['submitted', 'under_review'])
            ->groupBy('approval_stage')
            ->get();

        // Monthly disbursements (last 6 months)
        $driver = DB::connection()->getDriverName();
        $monthExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m', disbursement_date)"
            : "DATE_FORMAT(disbursement_date, '%Y-%m')";

        $monthlyDisbursements = Disbursement::select(
                DB::raw("$monthExpression as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'success')
            ->where('disbursement_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports.dashboard', compact('stats', 'recentApplications', 'applicationsByStage', 'monthlyDisbursements'));
    }

    /**
     * Financial reports
     */
    public function financial(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $financials = [
            'total_disbursed' => Disbursement::where('status', 'success')
                ->whereBetween('disbursement_date', [$startDate, $endDate])
                ->sum('amount'),
            'total_collections' => Collection::whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount'),
            'pending_disbursements' => Disbursement::where('status', 'pending')
                ->sum('amount'),
            'overdue_amount' => 0, // Calculate based on repayment schedule
        ];

        // Transactions summary
        $transactions = Transaction::with(['account', 'loan'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(20);

        return view('admin.reports.financial', compact('financials', 'transactions', 'startDate', 'endDate'));
    }

    /**
     * Loan applications report
     */
    public function loanApplications(Request $request)
    {
        $query = LoanApplication::with(['client', 'loanOfficer', 'creditOfficer', 'collectionOfficer', 'financeOfficer']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('stage') && $request->stage !== '') {
            $query->where('approval_stage', $request->stage);
        }

        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $applications = $query->latest()->paginate(20);

        return view('admin.reports.loan-applications', compact('applications'));
    }

    /**
     * Disbursements report
     */
    public function disbursements(Request $request)
    {
        $query = Disbursement::with(['loanApplication.client', 'disburser']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('disbursement_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('disbursement_date', '<=', $request->end_date);
        }

        $disbursements = $query->latest()->paginate(20);

        return view('admin.reports.disbursements', compact('disbursements'));
    }

    /**
     * Clients report
     */
    public function clients(Request $request)
    {
        $query = Client::withCount(['loanApplications', 'loans']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('kyc_status')) {
            if ($request->kyc_status === 'completed') {
                $query->where('kyc_completed', true);
            } else {
                $query->where('kyc_completed', false);
            }
        }

        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $clients = $query->latest()->paginate(20);

        // Client statistics
        $clientStats = [
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'inactive_clients' => Client::where('status', 'inactive')->count(),
            'blacklisted_clients' => Client::where('status', 'blacklisted')->count(),
            'kyc_completed' => Client::where('kyc_completed', true)->count(),
            'kyc_pending' => Client::where('kyc_completed', false)->count(),
            'clients_with_loans' => Client::has('loans')->count(),
            'new_clients_this_month' => Client::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
        ];

        return view('admin.reports.clients', compact('clients', 'clientStats'));
    }

    /**
     * Users/Staff report
     */
    public function users(Request $request)
    {
        $query = User::with('roles')->withCount(['approvals', 'disbursements', 'repayments']);

        if ($request->has('role') && $request->role !== '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $users = $query->latest()->paginate(20);

        // User statistics
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'by_role' => DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->select('roles.name as role_name', DB::raw('count(*) as count'))
                ->groupBy('roles.name')
                ->get(),
        ];

        return view('admin.reports.users', compact('users', 'userStats'));
    }

    /**
     * Loans report
     */
    public function loans(Request $request)
    {
        $query = Loan::with('client');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('loan_type') && $request->loan_type !== '') {
            $query->where('loan_type', $request->loan_type);
        }

        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $loans = $query->latest()->paginate(20);

        // Loan statistics
        $loanStats = [
            'total_loans' => Loan::count(),
            'approved_loans' => Loan::where('status', 'approved')->count(),
            'disbursed_loans' => Loan::where('status', 'disbursed')->count(),
            'closed_loans' => Loan::where('status', 'closed')->count(),
            'total_amount_disbursed' => Loan::where('status', 'disbursed')->sum('amount_approved'),
            'total_amount_pending' => Loan::where('status', 'approved')->sum('amount_approved'),
            'by_type' => Loan::select('loan_type', DB::raw('count(*) as count'))
                ->groupBy('loan_type')
                ->get(),
        ];

        return view('admin.reports.loans', compact('loans', 'loanStats'));
    }
}

