<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\B2bTransaction;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class B2bTransactionController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Display a listing of B2B transactions
     */
    public function index(Request $request)
    {
        $query = B2bTransaction::with('initiator')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('party_b')) {
            $query->where('party_b', 'like', "%{$request->party_b}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->paginate(15);

        return view('admin.mpesa.b2b.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new B2B transaction
     */
    public function create()
    {
        return view('admin.mpesa.b2b.create');
    }

    /**
     * Initiate B2B transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'party_b' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'account_reference' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        try {
            $accessToken = $this->mpesaService->getAccessToken();
            if (!$accessToken) {
                return back()->with('error', 'Failed to get M-Pesa access token.');
            }

            $shortcode = $this->mpesaService->getShortcode();
            $baseUrl = $this->mpesaService->getBaseUrl();

            $url = "{$baseUrl}/mpesa/b2b/v1/paymentrequest";

            $requestData = [
                'InitiatorName' => config('services.mpesa.initiator_name', 'testapi'),
                'SecurityCredential' => config('services.mpesa.security_credential', ''),
                'CommandID' => 'BusinessPayment',
                'Amount' => $validated['amount'],
                'PartyA' => $shortcode,
                'PartyB' => $validated['party_b'],
                'AccountReference' => $validated['account_reference'] ?? 'B2B-' . time(),
                'Remarks' => $validated['remarks'] ?? 'B2B Payment',
                'QueueTimeOutURL' => route('mpesa.b2b.timeout'),
                'ResultURL' => route('mpesa.b2b.callback'),
            ];

            $response = Http::withToken($accessToken)->post($url, $requestData);
            $responseData = $response->json();

            DB::beginTransaction();
            try {
                $transaction = B2bTransaction::create([
                    'initiator_name' => config('services.mpesa.initiator_name', 'testapi'),
                    'amount' => $validated['amount'],
                    'party_a' => $shortcode,
                    'party_b' => $validated['party_b'],
                    'account_reference' => $validated['account_reference'] ?? 'B2B-' . time(),
                    'remarks' => $validated['remarks'] ?? 'B2B Payment',
                    'queue_timeout_url' => route('mpesa.b2b.timeout'),
                    'result_url' => route('mpesa.b2b.callback'),
                    'originator_conversation_id' => $responseData['OriginatorConversationID'] ?? null,
                    'status' => isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0' ? 'pending' : 'failed',
                    'initiated_by' => auth()->id(),
                ]);

                DB::commit();

                if (isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                    return redirect()->route('mpesa.b2b.show', $transaction)
                        ->with('success', 'B2B transaction initiated successfully.');
                } else {
                    return back()->with('error', $responseData['ResponseDescription'] ?? 'Failed to initiate B2B transaction.');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('B2B Save Error: ' . $e->getMessage());
                return back()->with('error', 'Failed to save B2B transaction record.');
            }
        } catch (\Exception $e) {
            Log::error('B2B Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to initiate B2B transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display B2B transaction details
     */
    public function show(B2bTransaction $b2bTransaction)
    {
        $b2bTransaction->load('initiator');
        return view('admin.mpesa.b2b.show', compact('b2bTransaction'));
    }

    /**
     * B2B callback endpoint
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('B2B Callback:', $data);

        $result = $data['Result'] ?? [];
        $originatorConversationId = $result['OriginatorConversationID'] ?? null;

        if (!$originatorConversationId) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid request'], 400);
        }

        $transaction = B2bTransaction::where('originator_conversation_id', $originatorConversationId)->first();

        if (!$transaction) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();
        try {
            $resultCode = $result['ResultCode'] ?? null;
            $resultDesc = $result['ResultDesc'] ?? null;
            $conversationId = $result['ConversationID'] ?? null;

            $transaction->update([
                'conversation_id' => $conversationId,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
                'callback_data' => $data,
            ]);

            if ($resultCode == 0) {
                $resultParameters = $result['ResultParameters']['ResultParameter'] ?? [];
                foreach ($resultParameters as $param) {
                    if (isset($param['Key'])) {
                        switch ($param['Key']) {
                            case 'TransactionReceipt':
                                $transaction->transaction_receipt = $param['Value'] ?? null;
                                break;
                            case 'TransactionAmount':
                                $transaction->transaction_amount = $param['Value'] ?? null;
                                break;
                            case 'TransactionCompletedDateTime':
                                $transaction->transaction_date = $param['Value'] ?? null;
                                break;
                        }
                    }
                }
                $transaction->status = 'success';
            } else {
                $transaction->status = 'failed';
            }

            $transaction->save();
            DB::commit();

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('B2B Callback Processing Error: ' . $e->getMessage());
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Error processing callback'], 500);
        }
    }

    /**
     * B2B timeout endpoint
     */
    public function timeout(Request $request)
    {
        Log::info('B2B Timeout:', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}