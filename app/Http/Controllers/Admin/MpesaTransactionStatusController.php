<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MpesaTransactionStatus;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MpesaTransactionStatusController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Display transaction status query history
     */
    public function index(Request $request)
    {
        $query = MpesaTransactionStatus::with('requester')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('transaction_id')) {
            $query->where('transaction_id', 'like', "%{$request->transaction_id}%");
        }

        $statuses = $query->paginate(15);

        return view('admin.mpesa.transaction-status.index', compact('statuses'));
    }

    /**
     * Show form to query transaction status
     */
    public function create()
    {
        return view('admin.mpesa.transaction-status.create');
    }

    /**
     * Query transaction status
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|string',
            'remarks' => 'nullable|string|max:255',
        ]);

        try {
            $accessToken = $this->mpesaService->getAccessToken();
            if (!$accessToken) {
                return back()->with('error', 'Failed to get M-Pesa access token.');
            }

            $shortcode = $this->mpesaService->getShortcode();
            $baseUrl = $this->mpesaService->getBaseUrl();

            $url = "{$baseUrl}/mpesa/transactionstatus/v1/query";

            $requestData = [
                'InitiatorName' => config('services.mpesa.initiator_name', 'testapi'),
                'SecurityCredential' => config('services.mpesa.security_credential', ''),
                'CommandID' => 'TransactionStatusQuery',
                'TransactionID' => $validated['transaction_id'],
                'PartyA' => $shortcode,
                'IdentifierType' => '4',
                'Remarks' => $validated['remarks'] ?? 'Transaction Status Query',
                'Occasion' => 'TransactionStatus',
                'QueueTimeOutURL' => route('mpesa.transaction-status.timeout'),
                'ResultURL' => route('mpesa.transaction-status.callback'),
            ];

            $response = Http::withToken($accessToken)->post($url, $requestData);
            $responseData = $response->json();

            DB::beginTransaction();
            try {
                $status = MpesaTransactionStatus::create([
                    'initiator_name' => config('services.mpesa.initiator_name', 'testapi'),
                    'transaction_id' => $validated['transaction_id'],
                    'party_a' => $shortcode,
                    'identifier_type' => '4',
                    'remarks' => $validated['remarks'] ?? 'Transaction Status Query',
                    'occasion' => 'TransactionStatus',
                    'queue_timeout_url' => route('mpesa.transaction-status.timeout'),
                    'result_url' => route('mpesa.transaction-status.callback'),
                    'originator_conversation_id' => $responseData['OriginatorConversationID'] ?? null,
                    'status' => isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0' ? 'pending' : 'failed',
                    'requested_by' => auth()->id(),
                ]);

                DB::commit();

                if (isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                    return redirect()->route('mpesa.transaction-status.index')
                        ->with('success', 'Transaction status query initiated successfully.');
                } else {
                    return back()->with('error', $responseData['ResponseDescription'] ?? 'Failed to query transaction status.');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Transaction Status Save Error: ' . $e->getMessage());
                return back()->with('error', 'Failed to save transaction status query.');
            }
        } catch (\Exception $e) {
            Log::error('Transaction Status Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to query transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Transaction status callback
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('Transaction Status Callback:', $data);

        $result = $data['Result'] ?? [];
        $originatorConversationId = $result['OriginatorConversationID'] ?? null;

        if (!$originatorConversationId) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid request'], 400);
        }

        $status = MpesaTransactionStatus::where('originator_conversation_id', $originatorConversationId)->first();

        if (!$status) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Query not found'], 404);
        }

        DB::beginTransaction();
        try {
            $resultCode = $result['ResultCode'] ?? null;
            $resultDesc = $result['ResultDesc'] ?? null;

            $status->update([
                'conversation_id' => $result['ConversationID'] ?? null,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
                'response_data' => $data,
            ]);

            if ($resultCode == 0) {
                $resultParameters = $result['ResultParameters']['ResultParameter'] ?? [];
                foreach ($resultParameters as $param) {
                    if (isset($param['Key'])) {
                        switch ($param['Key']) {
                            case 'TransactionAmount':
                                $status->transaction_amount = $param['Value'] ?? null;
                                break;
                            case 'TransactionReceipt':
                                $status->receipt_number = $param['Value'] ?? null;
                                break;
                            case 'TransactionDate':
                                $status->transaction_date = $param['Value'] ?? null;
                                break;
                            case 'TransactionType':
                                $status->transaction_type = $param['Value'] ?? null;
                                break;
                        }
                    }
                }
                $status->status = 'found';
            } else {
                $status->status = $resultCode == 1 ? 'not_found' : 'failed';
            }

            $status->save();
            DB::commit();

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Status Callback Error: ' . $e->getMessage());
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Error processing callback'], 500);
        }
    }

    /**
     * Timeout endpoint
     */
    public function timeout(Request $request)
    {
        Log::info('Transaction Status Timeout:', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}