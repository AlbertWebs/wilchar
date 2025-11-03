<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MpesaAccountBalance;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MpesaAccountBalanceController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Display account balance history
     */
    public function index(Request $request)
    {
        $query = MpesaAccountBalance::with('requester')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $balances = $query->paginate(15);

        $latestBalance = MpesaAccountBalance::where('status', 'success')
            ->latest()
            ->first();

        return view('admin.mpesa.account-balance.index', compact('balances', 'latestBalance'));
    }

    /**
     * Request account balance
     */
    public function store(Request $request)
    {
        try {
            $accessToken = $this->mpesaService->getAccessToken();
            if (!$accessToken) {
                return back()->with('error', 'Failed to get M-Pesa access token.');
            }

            $shortcode = $this->mpesaService->getShortcode();
            $baseUrl = $this->mpesaService->getBaseUrl();

            $url = "{$baseUrl}/mpesa/accountbalance/v1/query";

            $requestData = [
                'InitiatorName' => config('services.mpesa.initiator_name', 'testapi'),
                'SecurityCredential' => config('services.mpesa.security_credential', ''),
                'CommandID' => 'AccountBalance',
                'PartyA' => $shortcode,
                'IdentifierType' => '4', // Organization
                'QueueTimeOutURL' => route('mpesa.account-balance.timeout'),
                'ResultURL' => route('mpesa.account-balance.callback'),
            ];

            $response = Http::withToken($accessToken)->post($url, $requestData);
            $responseData = $response->json();

            DB::beginTransaction();
            try {
                $balance = MpesaAccountBalance::create([
                    'initiator_name' => config('services.mpesa.initiator_name', 'testapi'),
                    'party_a' => $shortcode,
                    'identifier_type' => '4',
                    'queue_timeout_url' => route('mpesa.account-balance.timeout'),
                    'result_url' => route('mpesa.account-balance.callback'),
                    'originator_conversation_id' => $responseData['OriginatorConversationID'] ?? null,
                    'status' => isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0' ? 'pending' : 'failed',
                    'requested_by' => auth()->id(),
                ]);

                DB::commit();

                if (isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                    return redirect()->route('mpesa.account-balance.index')
                        ->with('success', 'Account balance request initiated successfully.');
                } else {
                    return back()->with('error', $responseData['ResponseDescription'] ?? 'Failed to request account balance.');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Account Balance Save Error: ' . $e->getMessage());
                return back()->with('error', 'Failed to save account balance request.');
            }
        } catch (\Exception $e) {
            Log::error('Account Balance Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to request account balance: ' . $e->getMessage());
        }
    }

    /**
     * Account balance callback
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('Account Balance Callback:', $data);

        $result = $data['Result'] ?? [];
        $originatorConversationId = $result['OriginatorConversationID'] ?? null;

        if (!$originatorConversationId) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid request'], 400);
        }

        $balance = MpesaAccountBalance::where('originator_conversation_id', $originatorConversationId)->first();

        if (!$balance) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Request not found'], 404);
        }

        DB::beginTransaction();
        try {
            $resultCode = $result['ResultCode'] ?? null;
            $resultDesc = $result['ResultDesc'] ?? null;

            $balance->update([
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
                            case 'AccountBalance':
                                // This will be split into different account types
                                break;
                            case 'WorkingAccountAvailableFunds':
                                $balance->working_account_balance = $param['Value'] ?? null;
                                break;
                            case 'UtilityAccountAvailableFunds':
                                $balance->utility_account_balance = $param['Value'] ?? null;
                                break;
                            case 'ChargesPaidAccountAvailableFunds':
                                $balance->charges_paid_account_balance = $param['Value'] ?? null;
                                break;
                            case 'DateTime':
                                $balance->account_balance_time = $param['Value'] ?? null;
                                break;
                        }
                    }
                }
                $balance->status = 'success';
            } else {
                $balance->status = 'failed';
            }

            $balance->save();
            DB::commit();

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Account Balance Callback Error: ' . $e->getMessage());
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Error processing callback'], 500);
        }
    }

    /**
     * Timeout endpoint
     */
    public function timeout(Request $request)
    {
        Log::info('Account Balance Timeout:', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}