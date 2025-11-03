<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\Disbursement;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaDisbursementController extends Controller
{
    private $mpesaConsumerKey;
    private $mpesaConsumerSecret;
    private $mpesaShortcode;
    private $mpesaPasskey;
    private $mpesaEnvironment; // sandbox or production

    public function __construct()
    {
        $this->mpesaConsumerKey = config('services.mpesa.consumer_key', env('MPESA_CONSUMER_KEY'));
        $this->mpesaConsumerSecret = config('services.mpesa.consumer_secret', env('MPESA_CONSUMER_SECRET'));
        $this->mpesaShortcode = config('services.mpesa.shortcode', env('MPESA_SHORTCODE'));
        $this->mpesaPasskey = config('services.mpesa.passkey', env('MPESA_PASSKEY'));
        $this->mpesaEnvironment = config('services.mpesa.environment', env('MPESA_ENVIRONMENT', 'sandbox'));
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
            $result = $this->initiateB2C($disbursement, $validated['remarks'] ?? '');

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
            
            $result = $this->initiateB2C($disbursement);

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

    /**
     * Initiate M-Pesa B2C payment
     */
    private function initiateB2C(Disbursement $disbursement, string $remarks = ''): array
    {
        try {
            // Get access token
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                return ['success' => false, 'error' => 'Failed to get access token'];
            }

            // Generate security credentials
            $timestamp = now()->format('YmdHis');
            $password = base64_encode($this->mpesaShortcode . $this->mpesaPasskey . $timestamp);

            // Prepare B2C request
            $url = $this->mpesaEnvironment === 'production' 
                ? 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest'
                : 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';

            $requestData = [
                'InitiatorName' => config('services.mpesa.initiator_name', 'testapi'),
                'SecurityCredential' => config('services.mpesa.security_credential', ''),
                'CommandID' => 'BusinessPayment',
                'Amount' => $disbursement->amount,
                'PartyA' => $this->mpesaShortcode,
                'PartyB' => $disbursement->recipient_phone,
                'Remarks' => $remarks ?: "Loan disbursement for {$disbursement->loanApplication->application_number}",
                'QueueTimeOutURL' => config('services.mpesa.queue_timeout_url', url('/api/mpesa/b2c/timeout')),
                'ResultURL' => route('disbursements.callback'),
                'Occasion' => 'Loan Disbursement',
            ];

            $response = Http::withToken($accessToken)
                ->post($url, $requestData);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                return [
                    'success' => true,
                    'request_id' => $responseData['RequestID'] ?? null,
                    'response_code' => $responseData['ResponseCode'] ?? null,
                    'response_description' => $responseData['ResponseDescription'] ?? null,
                    'originator_conversation_id' => $responseData['OriginatorConversationID'] ?? null,
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['errorMessage'] ?? $responseData['ResponseDescription'] ?? 'Unknown error',
                    'response_code' => $responseData['ResponseCode'] ?? null,
                ];
            }

        } catch (\Exception $e) {
            Log::error('M-Pesa B2C Initiation Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get M-Pesa access token
     */
    private function getAccessToken(): ?string
    {
        try {
            $url = $this->mpesaEnvironment === 'production'
                ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
                : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

            $credentials = base64_encode($this->mpesaConsumerKey . ':' . $this->mpesaConsumerSecret);

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $credentials,
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('M-Pesa Access Token Error: ' . $e->getMessage());
            return null;
        }
    }
}


