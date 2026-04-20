<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\StkPush;
use App\Services\LoanPaymentService;
use App\Services\MpesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StkPushController extends Controller
{
    protected MpesaService $mpesaService;

    public function __construct(
        MpesaService $mpesaService,
        private LoanPaymentService $loanPaymentService
    ) {
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
        $loans = Loan::query()
            ->with(['client', 'application'])
            ->whereIn('status', ['pending', 'approved', 'disbursed'])
            ->orderByDesc('id')
            ->limit(500)
            ->get();

        $loansPayload = $loans->map(function (Loan $loan) {
            $phone = $loan->client?->phone ?? '';
            if ($phone && ! str_starts_with($phone, '254')) {
                $digits = preg_replace('/\D/', '', $phone);
                if (str_starts_with($digits, '0') && strlen($digits) >= 9) {
                    $phone = '254' . substr($digits, 1);
                } elseif (strlen($digits) === 9) {
                    $phone = '254' . $digits;
                }
            }

            return [
                'id' => $loan->id,
                'label' => ($loan->application?->application_number ?? 'Loan #' . $loan->id) . ' · ' . ($loan->client?->full_name ?? '—'),
                'phone' => $phone,
                'application_number' => $loan->application?->application_number ?? (string) $loan->id,
                'outstanding' => max(1, round((float) $loan->outstanding_balance, 2)),
            ];
        })->values();

        $transactionDescriptions = [
            'Loan repayment',
            'Loan processing fee',
            'Registration fee',
            'STK Push Payment',
        ];

        return view('admin.mpesa.stk-push.create', compact('loans', 'loansPayload', 'transactionDescriptions'));
    }

    /**
     * Initiate STK Push (Lipa na M-Pesa Online)
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|regex:/^254[0-9]{9}$/',
            'amount' => 'required|numeric|min:1',
            'account_reference' => 'nullable|string|max:255',
            'transaction_desc' => 'required|string|max:255',
            'transaction_desc_custom' => 'nullable|required_if:transaction_desc,__custom__|string|max:255',
            'loan_id' => 'nullable|exists:loans,id',
        ]);

        $transactionDescFinal = $validated['transaction_desc'] === '__custom__'
            ? ($validated['transaction_desc_custom'] ?? 'STK Push Payment')
            : $validated['transaction_desc'];

        try {
            $accessToken = $this->mpesaService->getAccessToken();
            if (!$accessToken) {
                return $this->stkJsonOrBack($request, false, 'Failed to get M-Pesa access token.');
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
                'CallBackURL' => config('mpesa.stk.callback_url', route('mpesa.stk-callback')),
                'AccountReference' => $validated['account_reference'] ?? 'STK-' . time(),
                'TransactionDesc' => $transactionDescFinal,
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
                    'transaction_desc' => $transactionDescFinal,
                    'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                    'checkout_request_id' => $responseData['CheckoutRequestID'] ?? null,
                    'result_code' => $responseData['ResponseCode'] ?? null,
                    'result_desc' => $responseData['ResponseDescription'] ?? null,
                    'status' => isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0' ? 'pending' : 'failed',
                    'initiated_by' => auth()->id(),
                    'loan_id' => $validated['loan_id'] ?? null,
                ]);

                DB::commit();

                if (isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                    $message = 'STK Push initiated successfully. Customer will receive a prompt on their phone.';

                    return $this->stkJsonOrBack(
                        $request,
                        true,
                        $message,
                        route('mpesa.stk-push.show', $stkPush)
                    );
                } else {
                    return $this->stkJsonOrBack(
                        $request,
                        false,
                        $responseData['ResponseDescription'] ?? 'Failed to initiate STK Push.'
                    );
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('STK Push Save Error: ' . $e->getMessage());

                return $this->stkJsonOrBack($request, false, 'Failed to save STK Push record.');
            }
        } catch (\Exception $e) {
            Log::error('STK Push Error: ' . $e->getMessage());

            return $this->stkJsonOrBack($request, false, 'Failed to initiate STK Push: ' . $e->getMessage());
        }
    }

    private function stkJsonOrBack(Request $request, bool $success, string $message, ?string $redirectUrl = null): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'redirect' => $success ? $redirectUrl : null,
            ]);
        }

        if ($success && $redirectUrl) {
            return redirect()->to($redirectUrl)->with('success', $message);
        }

        return back()->with('error', $message);
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

                $loan = $stkPush->loan ?? $this->resolveLoanFromReference($stkPush->account_reference);
                if ($loan && !$stkPush->applied_at) {
                    $this->loanPaymentService->applyPaymentToLoan($loan, (float) $stkPush->amount, 'stk', $stkPush->mpesa_receipt_number);
                    $stkPush->update([
                        'loan_id' => $loan->id,
                        'applied_at' => now(),
                    ]);
                }
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