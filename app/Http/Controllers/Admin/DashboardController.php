<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\LoanApplication;
use App\Models\Collection;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use App\Models\Team;
use App\Models\Expense;
use App\Models\Asset;
use App\Models\Liability;
use App\Models\ShareholderContribution;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $loanApplications = LoanApplication::count();
        $approvedLoans = Loan::count();
        $pendingApprovals = LoanApplication::where('status', 'pending')->count();
        $totalDisbursed = Loan::sum('amount_approved');
        $totalCollections = Collection::sum('amount');

        // Monthly disbursements & collections for chart
        $monthExpression = $this->monthExpression();

        $monthlyDisbursements = Loan::selectRaw("{$monthExpression} as month, SUM(amount_approved) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyCollections = Collection::selectRaw("{$monthExpression} as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $disbursements = array_replace(array_fill(1, 12, 0), $monthlyDisbursements->toArray());
        $collections = array_replace(array_fill(1, 12, 0), $monthlyCollections->toArray());

        $months = collect(range(1, 12))->map(fn ($month) => Carbon::create()->startOfYear()->addMonths($month - 1)->format('M'))->toArray();

        $currentMonthApprovals = Loan::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $previousMonthApprovals = Loan::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $approvalGrowth = $previousMonthApprovals > 0
            ? (($currentMonthApprovals - $previousMonthApprovals) / $previousMonthApprovals) * 100
            : ($currentMonthApprovals > 0 ? 100 : 0);

        $pendingApprovalBreakdown = [
            'loan_officer' => LoanApplication::where('approval_stage', 'loan_officer')->count(),
            'credit_officer' => LoanApplication::where('approval_stage', 'credit_officer')->count(),
            'finance_officer' => LoanApplication::where('approval_stage', 'finance_officer')->count(),
            'director' => LoanApplication::where('approval_stage', 'director')->count(),
        ];

        $teamStats = Team::with(['loanApplications', 'loans.repayments'])->get()->map(function (Team $team) {
            $loanTotal = $team->loans->sum('total_amount');
            $collected = $team->loans->flatMap->repayments->sum('amount');

            return [
                'name' => $team->name,
                'onboardings' => $team->loanApplications->count(),
                'disbursements' => $team->loans->count(),
                'collection_rate' => $loanTotal > 0 ? round(($collected / $loanTotal) * 100, 1) : 0,
            ];
        });

        $overdueLoans = Loan::with('client')
            ->whereIn('status', ['approved', 'disbursed'])
            ->whereNotNull('next_due_date')
            ->where('outstanding_balance', '>', 0)
            ->whereDate('next_due_date', '<', now())
            ->orderBy('next_due_date')
            ->take(5)
            ->get()
            ->map(function (Loan $loan) {
                $loan->days_overdue = Carbon::parse($loan->next_due_date)->diffInDays(now());
                return $loan;
            });

        $financialSummary = [
            'expenses' => Expense::whereBetween('expense_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
            'assets' => Asset::sum('current_value'),
            'liabilities' => Liability::sum('outstanding_balance'),
            'shareholder_contributions' => ShareholderContribution::sum('amount'),
        ];

        return view('admin.dashboard', compact(
            'totalClients',
            'loanApplications',
            'approvedLoans',
            'pendingApprovals',
            'totalDisbursed',
            'totalCollections',
            'disbursements',
            'collections',
            'months',
            'approvalGrowth',
            'pendingApprovalBreakdown',
            'teamStats',
            'overdueLoans',
            'financialSummary'
        ));
    }

    private function monthExpression(): string
    {
        return match (DB::getDriverName()) {
            'sqlite' => "CAST(strftime('%m', created_at) AS INTEGER)",
            'pgsql' => "EXTRACT(MONTH FROM created_at)",
            default => "MONTH(created_at)",
        };
    }
}
