<?php

namespace App\Services;

use App\Models\Disbursement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class B2cPaymentService
{
    public function __construct(private MpesaService $mpesaService)
    {
    }

    public function initiate(Disbursement $disbursement, string $remarks = ''): array
    {
        try {
            $accessToken = $this->mpesaService->getAccessToken();
            if (!$accessToken) {
                return ['success' => false, 'error' => 'Failed to get access token'];
            }

            $baseUrl = $this->mpesaService->getBaseUrl();
            $shortcode = $this->mpesaService->getShortcode();

            $url = "{$baseUrl}/mpesa/b2c/v1/paymentrequest";
            $requestData = [
                'InitiatorName' => config('services.mpesa.initiator_name', 'testapi'),
                'SecurityCredential' => config('services.mpesa.security_credential', ''),
                'CommandID' => 'BusinessPayment',
                'Amount' => $disbursement->amount,
                'PartyA' => $shortcode,
                'PartyB' => $disbursement->recipient_phone,
                'Remarks' => $remarks ?: "Loan disbursement for {$disbursement->loanApplication->application_number}",
                'QueueTimeOutURL' => config('services.mpesa.queue_timeout_url', url('/api/mpesa/b2c/timeout')),
                'ResultURL' => route('disbursements.callback'),
                'Occasion' => 'Loan Disbursement',
            ];

            $response = Http::withToken($accessToken)->post($url, $requestData);
            $responseData = $response->json();

            if ($response->successful() && isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                return [
                    'success' => true,
                    'request_id' => $responseData['RequestID'] ?? null,
                    'response_code' => $responseData['ResponseCode'] ?? null,
                    'response_description' => $responseData['ResponseDescription'] ?? null,
                    'originator_conversation_id' => $responseData['OriginatorConversationID'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => $responseData['errorMessage'] ?? $responseData['ResponseDescription'] ?? 'Unknown error',
                'response_code' => $responseData['ResponseCode'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('M-Pesa B2C Initiation Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

