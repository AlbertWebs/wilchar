<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private $consumerKey;
    private $consumerSecret;
    private $shortcode;
    private $passkey;
    private $environment;
    private $baseUrl;

    public function __construct()
    {
        $this->consumerKey = config('services.mpesa.consumer_key', env('MPESA_CONSUMER_KEY'));
        $this->consumerSecret = config('services.mpesa.consumer_secret', env('MPESA_CONSUMER_SECRET'));
        $this->shortcode = config('services.mpesa.shortcode', env('MPESA_SHORTCODE'));
        $this->passkey = config('services.mpesa.passkey', env('MPESA_PASSKEY'));
        $this->environment = config('services.mpesa.environment', env('MPESA_ENVIRONMENT', 'sandbox'));
        $this->baseUrl = $this->environment === 'production' 
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Get M-Pesa OAuth access token
     */
    public function getAccessToken(): ?string
    {
        try {
            $url = "{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials";
            $credentials = base64_encode("{$this->consumerKey}:{$this->consumerSecret}");

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $credentials,
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            Log::error('M-Pesa Access Token Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('M-Pesa Access Token Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate security credentials (password)
     */
    public function generatePassword(): string
    {
        $timestamp = now()->format('YmdHis');
        return base64_encode($this->shortcode . $this->passkey . $timestamp);
    }

    /**
     * Generate timestamp
     */
    public function getTimestamp(): string
    {
        return now()->format('YmdHis');
    }

    /**
     * Get base URL
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get shortcode
     */
    public function getShortcode(): string
    {
        return $this->shortcode;
    }

    /**
     * Register C2B URLs with Safaricom
     */
    public function registerC2bUrls(): array
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return [
                    'success' => false,
                    'message' => 'Failed to get access token'
                ];
            }

            $validationUrl = config('mpesa.c2b.validation_url', url('/admin/mpesa/c2b/validate'));
            $confirmationUrl = config('mpesa.c2b.confirmation_url', url('/admin/mpesa/c2b/confirm'));

            $url = "{$this->baseUrl}/mpesa/c2b/v1/registerurl";
            
            $payload = [
                'ShortCode' => $this->shortcode,
                'ResponseType' => 'Completed',
                'ConfirmationURL' => $confirmationUrl,
                'ValidationURL' => $validationUrl,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('C2B URL Registration Success:', $data);
                return [
                    'success' => true,
                    'message' => 'C2B URLs registered successfully',
                    'data' => $data
                ];
            }

            $error = $response->json() ?? $response->body();
            Log::error('C2B URL Registration Error:', [
                'status' => $response->status(),
                'error' => $error
            ]);

            return [
                'success' => false,
                'message' => $error['errorMessage'] ?? 'Failed to register C2B URLs',
                'data' => $error
            ];
        } catch (\Exception $e) {
            Log::error('C2B URL Registration Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}

