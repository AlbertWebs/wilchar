<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\LoanApplication;
use App\Models\Collection;
use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Support\Facades\DB;

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
        $monthlyDisbursements = Loan::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("SUM(amount_approved) as total")
        )->groupBy('month')->pluck('total', 'month');

        $monthlyCollections = Collection::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("SUM(amount) as total")
        )->groupBy('month')->pluck('total', 'month');
        $disbursements = array_replace(array_fill(0, 12, 0), $monthlyDisbursements->toArray());
        $collections = array_replace(array_fill(0, 12, 0), $monthlyCollections->toArray());

        // return view('admin.dashboard', compact('disbursements', 'collections'));

        return view('admin.dashboard', compact(
            'totalClients', 'loanApplications', 'approvedLoans',
            'pendingApprovals', 'totalDisbursed', 'totalCollections',
            'monthlyDisbursements', 'monthlyCollections','disbursements','collections'
        ));
    }
}
