<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\Disbursement;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\AuditLog;
use App\Services\B2cPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaDisbursementController extends Controller
{
    public function __construct(private B2cPaymentService $b2cPaymentService)
    {
    }

    /**
     * Display disbursements
     */
    public function index(Request $request)
    {
        $query = Disbursement::with(['loanApplication.client', 'disburser'])
            ->latest();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('method') && $request->method !== '') {
            $query->where('method', $request->method);
        }

        $disbursements = $query->paginate(15);

        return view('admin.disbursements.index', compact('disbursements'));
    }

    /**
     * Show disbursement form for approved loan application
     */
    public function create(LoanApplication $loanApplication)
    {
        if (!$loanApplication->isApproved()) {
            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('error', 'Only approved loan applications can be disbursed.');
        }

        $loanApplication->load('client');
        
        return view('admin.disbursements.create', compact('loanApplication'));
    }

    /**
     * Process M-Pesa B2C disbursement
     */
    public function store(Request $request, LoanApplication $loanApplication)
    {
        if (!$loanApplication->isApproved()) {
            return back()->with('error', 'Only approved loan applications can be disbursed.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'recipient_phone' => 'required|string|regex:/^254[0-9]{9}$/',
            'remarks' => 'nullable|string|max:255',
        ]);

        // Check if already disbursed
        $existingDisbursement = Disbursement::where('loan_application_id', $loanApplication->id)
            ->where('status', 'success')
            ->first();

        if ($existingDisbursement) {
            return back()->with('error', 'This loan has already been disbursed.');
        }

        DB::beginTransaction();
        try {
            // Create disbursement record
            $disbursement = Disbursement::create([
                'loan_application_id' => $loanApplication->id,
                'disbursed_by' => auth()->id(),
                'amount' => $validated['amount'],
                'method' => 'mpesa_b2c',
                'disbursement_date' => now(),
                'recipient_phone' => $validated['recipient_phone'],
                'status' => 'pending',
            ]);

            // Generate M-Pesa request
            $result = $this->b2cPaymentService->initiate($disbursement, $validated['remarks'] ?? '');

            if ($result['success']) {
                $disbursement->update([
                    'mpesa_request_id' => $result['request_id'] ?? null,
                    'mpesa_response_code' => $result['response_code'] ?? null,
                    'mpesa_response_description' => $result['response_description'] ?? null,
                    'mpesa_originator_conversation_id' => $result['originator_conversation_id'] ?? null,
                ]);

                DB::commit();

                return redirect()->route('disbursements.show', $disbursement)
                    ->with('success', 'M-Pesa B2C disbursement initiated successfully.');
            } else {
                $disbursement->update([
                    'status' => 'failed',
                    'mpesa_response_description' => $result['error'] ?? 'Failed to initiate payment',
                ]);

                DB::rollBack();

                return back()->with('error', 'Failed to initiate M-Pesa B2C: ' . ($result['error'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('M-Pesa Disbursement Error: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to process disbursement: ' . $e->getMessage());
        }
    }

    /**
     * Show disbursement details
     */
    public function show(Disbursement $disbursement)
    {
        $disbursement->load(['loanApplication.client', 'disburser']);
        
        return view('admin.disbursements.show', compact('disbursement'));
    }

    /**
     * M-Pesa B2C callback endpoint
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        
        Log::info('M-Pesa B2C Callback:', $data);

        // Find disbursement by conversation ID or request ID
        $disbursement = Disbursement::where('mpesa_originator_conversation_id', $data['OriginatorConversationID'] ?? null)
            ->orWhere('mpesa_request_id', $data['RequestID'] ?? null)
            ->first();

        if (!$disbursement) {
            Log::warning('Disbursement not found for callback', $data);
            return response()->json(['error' => 'Disbursement not found'], 404);
        }

        DB::beginTransaction();
        try {
            // Update disbursement with callback data
            $result = $data['Result'] ?? [];
            
            $disbursement->update([
                'mpesa_result_code' => $result['ResultCode'] ?? null,
                'mpesa_result_description' => $result['ResultDesc'] ?? null,
                'mpesa_conversation_id' => $result['ConversationID'] ?? null,
                'mpesa_callback_data' => $data,
            ]);

            // Check if transaction was successful
            if (isset($result['ResultCode']) && $result['ResultCode'] == 0) {
                $resultParameters = $result['ResultParameters']['ResultParameter'] ?? [];
                
                foreach ($resultParameters as $param) {
                    if (isset($param['Key'])) {
                        switch ($param['Key']) {
                            case 'TransactionReceipt':
                                $disbursement->transaction_receipt = $param['Value'] ?? null;
                                break;
                            case 'TransactionAmount':
                                $disbursement->transaction_amount = $param['Value'] ?? null;
                                break;
                            case 'B2CWorkingAccountAvailableFunds':
                                // Working account balance
                                break;
                            case 'B2CUtilityAccountAvailableFunds':
                                // Utility account balance
                                break;
                            case 'B2CChargesPaidAccountAvailableFunds':
                                // Charges account balance
                                break;
                            case 'ReceiverPartyPublicName':
                                // Recipient name
                                break;
                            case 'TransactionCompletedDateTime':
                                // Transaction date
                                break;
                        }
                    }
                }

                $disbursement->status = 'success';
                $disbursement->save();

                // Create transaction record
                $cashAccount = Account::where('type', 'cash')->first();
                if ($cashAccount) {
                    Transaction::create([
                        'account_id' => $cashAccount->id,
                        'loan_id' => $disbursement->loanApplication->loan_id ?? null,
                        'amount' => $disbursement->amount,
                        'type' => 'debit',
                        'description' => "Loan disbursement via M-Pesa B2C - {$disbursement->loanApplication->application_number}",
                        'reference' => $disbursement->transaction_receipt,
                    ]);

                    // Update account balance
                    $cashAccount->decrement('balance', $disbursement->amount);
                }

                // Update loan application status
                $disbursement->loanApplication->update(['status' => 'disbursed']);
                
                // Update loan status
                if ($disbursement->loanApplication->loan) {
                    $disbursement->loanApplication->loan->update(['status' => 'disbursed']);
                }

                AuditLog::log(
                    Disbursement::class,
                    $disbursement->id,
                    'disbursement_successful',
                    "M-Pesa B2C disbursement successful. Receipt: {$disbursement->transaction_receipt}"
                );

            } else {
                $disbursement->status = 'failed';
                $disbursement->save();

                AuditLog::log(
                    Disbursement::class,
                    $disbursement->id,
                    'disbursement_failed',
                    "M-Pesa B2C disbursement failed: " . ($result['ResultDesc'] ?? 'Unknown error')
                );
            }

            DB::commit();

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('M-Pesa Callback Processing Error: ' . $e->getMessage());
            
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Error processing callback'
            ], 500);
        }
    }

    /**
     * Retry failed disbursement
     */
    public function retry(Disbursement $disbursement)
    {
        if ($disbursement->status !== 'failed') {
            return back()->with('error', 'Only failed disbursements can be retried.');
        }

        if ($disbursement->retry_count >= 3) {
            return back()->with('error', 'Maximum retry attempts reached.');
        }

        try {
            $disbursement->incrementRetry();
            
            $result = $this->b2cPaymentService->initiate($disbursement);

            if ($result['success']) {
                $disbursement->update([
                    'status' => 'pending',
                    'mpesa_request_id' => $result['request_id'] ?? null,
                    'mpesa_response_code' => $result['response_code'] ?? null,
                    'mpesa_response_description' => $result['response_description'] ?? null,
                ]);

                return back()->with('success', 'Disbursement retry initiated successfully.');
            } else {
                return back()->with('error', 'Failed to retry disbursement: ' . ($result['error'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retry disbursement: ' . $e->getMessage());
        }
    }

}


