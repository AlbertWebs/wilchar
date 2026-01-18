<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\C2bTransaction;
use App\Models\Loan;
use App\Services\LoanPaymentService;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class C2bTransactionController extends Controller
{
    public function __construct(
        private LoanPaymentService $loanPaymentService,
        private MpesaService $mpesaService
    ) {
    }

    /**
     * Display a listing of C2B transactions
     */
    public function index(Request $request)
    {
        $query = C2bTransaction::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('phone')) {
            $query->where('msisdn', 'like', "%{$request->phone}%");
        }

        if ($request->filled('trans_id')) {
            $query->where('trans_id', 'like', "%{$request->trans_id}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('trans_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('trans_time', '<=', $request->end_date);
        }

        $transactions = $query->paginate(15);

        $stats = [
            'total' => C2bTransaction::count(),
            'completed' => C2bTransaction::where('status', 'completed')->count(),
            'total_amount' => C2bTransaction::where('status', 'completed')->sum('trans_amount'),
        ];

        return view('admin.mpesa.c2b.index', compact('transactions', 'stats'));
    }

    /**
     * Display C2B transaction details
     */
    public function show(C2bTransaction $c2bTransaction)
    {
        return view('admin.mpesa.c2b.show', compact('c2bTransaction'));
    }

    /**
     * C2B validation endpoint (PayBill/Buy Goods)
     */
    public function validate(Request $request)
    {
        $data = $request->all();
        Log::info('C2B Validation:', $data);

        // Return validation result
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted'
        ]);
    }

    /**
     * C2B confirmation callback endpoint
     */
    public function confirm(Request $request)
    {
        $data = $request->all();
        Log::info('C2B Confirmation:', $data);

        DB::beginTransaction();
        try {
            $transaction = C2bTransaction::updateOrCreate(
                ['trans_id' => $data['TransID'] ?? null],
                [
                    'transaction_type' => $data['TransactionType'] ?? null,
                    'trans_time' => $data['TransTime'] ?? now()->format('YmdHis'),
                    'trans_amount' => $data['TransAmount'] ?? 0,
                    'business_short_code' => $data['BusinessShortCode'] ?? null,
                    'bill_ref_number' => $data['BillRefNumber'] ?? null,
                    'invoice_number' => $data['InvoiceNumber'] ?? null,
                    'org_account_balance' => $data['OrgAccountBalance'] ?? null,
                    'third_party_trans_id' => $data['ThirdPartyTransID'] ?? null,
                    'msisdn' => $data['MSISDN'] ?? null,
                    'first_name' => $data['FirstName'] ?? null,
                    'middle_name' => $data['MiddleName'] ?? null,
                    'last_name' => $data['LastName'] ?? null,
                    'status' => 'completed',
                    'callback_data' => $data,
                ]
            );

            if (!$transaction->loan_id) {
                $loan = $this->resolveLoanFromReference($transaction->bill_ref_number);
                if ($loan) {
                    $transaction->loan()->associate($loan);
                    $transaction->save();
                }
            } else {
                $loan = $transaction->loan;
            }

            if (($loan ?? null) && !$transaction->applied_at) {
                $this->loanPaymentService->applyPaymentToLoan($loan, (float) $transaction->trans_amount, 'c2b', $transaction->trans_id);
                $transaction->update(['applied_at' => now()]);
            }

            DB::commit();

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('C2B Confirmation Error: ' . $e->getMessage());
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Error processing confirmation'
            ], 500);
        }
    }

    /**
     * Register C2B URLs with Safaricom
     */
    public function registerUrls(Request $request)
    {
        try {
            $result = $this->mpesaService->registerC2bUrls();
            
            if ($request->expectsJson()) {
                return response()->json($result);
            }

            if ($result['success']) {
                return redirect()->route('mpesa.c2b.index')
                    ->with('success', $result['message']);
            }

            return redirect()->route('mpesa.c2b.index')
                ->with('error', $result['message']);
        } catch (\Exception $e) {
            Log::error('C2B URL Registration Controller Error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to register URLs: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('mpesa.c2b.index')
                ->with('error', 'Failed to register URLs: ' . $e->getMessage());
        }
    }

    private function resolveLoanFromReference(?string $reference): ?Loan
    {
        if (!$reference) {
            return null;
        }

        if (is_numeric($reference)) {
            return Loan::find((int) $reference);
        }

        return Loan::whereHas('application', function ($query) use ($reference) {
            $query->where('application_number', $reference);
        })->first();
    }
}