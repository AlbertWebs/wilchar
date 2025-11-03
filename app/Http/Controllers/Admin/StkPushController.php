<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StkPush;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class StkPushController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Display a listing of STK Push transactions
     */
    public function index(Request $request)
    {
        $query = StkPush::with('initiator')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('phone')) {
            $query->where('phone_number', 'like', "%{$request->phone}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $stkPushes = $query->paginate(15);

        return view('admin.mpesa.stk-push.index', compact('stkPushes'));
    }

    /**
     * Show the form for creating a new STK Push
     */
    public function create()
    {
        return view('admin.mpesa.stk-push.create');
    }

    /**
     * Initiate STK Push (Lipa na M-Pesa Online)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|regex:/^254[0-9]{9}$/',
            'amount' => 'required|numeric|min:1',
            'account_reference' => 'nullable|string|max:255',
            'transaction_desc' => 'nullable|string|max:255',
        ]);

        try {
            $accessToken = $this->mpesaService->getAccessToken();
            if (!$accessToken) {
                return back()->with('error', 'Failed to get M-Pesa access token.');
            }

            $timestamp = $this->mpesaService->getTimestamp();
            $password = $this->mpesaService->generatePassword();
            $shortcode = $this->mpesaService->getShortcode();
            $baseUrl = $this->mpesaService->getBaseUrl();

            $url = "{$baseUrl}/mpesa/stkpush/v1/processrequest";

            $requestData = [
                'BusinessShortCode' => $shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $validated['amount'],
                'PartyA' => $validated['phone_number'],
                'PartyB' => $shortcode,
                'PhoneNumber' => $validated['phone_number'],
                'CallBackURL' => route('mpesa.stk-callback'),
                'AccountReference' => $validated['account_reference'] ?? 'STK-' . time(),
                'TransactionDesc' => $validated['transaction_desc'] ?? 'STK Push Payment',
            ];

            $response = Http::withToken($accessToken)
                ->post($url, $requestData);

            $responseData = $response->json();

            DB::beginTransaction();
            try {
                $stkPush = StkPush::create([
                    'phone_number' => $validated['phone_number'],
                    'amount' => $validated['amount'],
                    'account_reference' => $validated['account_reference'] ?? 'STK-' . time(),
                    'transaction_desc' => $validated['transaction_desc'] ?? 'STK Push Payment',
                    'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                    'checkout_request_id' => $responseData['CheckoutRequestID'] ?? null,
                    'result_code' => $responseData['ResponseCode'] ?? null,
                    'result_desc' => $responseData['ResponseDescription'] ?? null,
                    'status' => isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0' ? 'pending' : 'failed',
                    'initiated_by' => auth()->id(),
                ]);

                DB::commit();

                if (isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                    return redirect()->route('mpesa.stk-push.show', $stkPush)
                        ->with('success', 'STK Push initiated successfully. Customer will receive a prompt on their phone.');
                } else {
                    return back()->with('error', $responseData['ResponseDescription'] ?? 'Failed to initiate STK Push.');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('STK Push Save Error: ' . $e->getMessage());
                return back()->with('error', 'Failed to save STK Push record.');
            }
        } catch (\Exception $e) {
            Log::error('STK Push Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to initiate STK Push: ' . $e->getMessage());
        }
    }

    /**
     * Display STK Push details
     */
    public function show(StkPush $stkPush)
    {
        $stkPush->load('initiator');
        return view('admin.mpesa.stk-push.show', compact('stkPush'));
    }

    /**
     * STK Push callback endpoint
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('STK Push Callback:', $data);

        $body = $data['Body'] ?? [];
        $stkCallback = $body['stkCallback'] ?? [];
        $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;

        if (!$checkoutRequestId) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid request'], 400);
        }

        $stkPush = StkPush::where('checkout_request_id', $checkoutRequestId)->first();

        if (!$stkPush) {
            Log::warning('STK Push not found for callback', ['CheckoutRequestID' => $checkoutRequestId]);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();
        try {
            $resultCode = $stkCallback['ResultCode'] ?? null;
            $resultDesc = $stkCallback['ResultDesc'] ?? null;
            $merchantRequestId = $stkCallback['MerchantRequestID'] ?? null;

            $stkPush->update([
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
                'callback_data' => $data,
            ]);

            if ($resultCode == 0) {
                $callbackMetadata = $stkCallback['CallbackMetadata'] ?? [];
                $items = $callbackMetadata['Item'] ?? [];

                $metadata = [];
                foreach ($items as $item) {
                    $metadata[$item['Name']] = $item['Value'] ?? null;
                }

                $stkPush->update([
                    'mpesa_receipt_number' => $metadata['MpesaReceiptNumber'] ?? null,
                    'result_type' => $metadata['TransactionType'] ?? null,
                    'balance' => $metadata['Balance'] ?? null,
                    'transaction_date' => $metadata['TransactionDate'] ?? null,
                    'status' => 'success',
                ]);
            } else {
                $stkPush->update(['status' => 'failed']);
            }

            DB::commit();

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('STK Push Callback Processing Error: ' . $e->getMessage());
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Error processing callback'], 500);
        }
    }
}