<?php

return [
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'shortcode' => env('MPESA_SHORTCODE'),
    'passkey' => env('MPESA_PASSKEY'),

    'initiator_name' => env('MPESA_INITIATOR_NAME'),
    'security_credential' => env('MPESA_SECURITY_CREDENTIAL'),

    'b2c' => [
        'result_url' => env('MPESA_B2C_RESULT_URL', route('disbursements.callback')),
        'timeout_url' => env('MPESA_B2C_TIMEOUT_URL', url('/api/mpesa/b2c/timeout')),
    ],

    'stk' => [
        'callback_url' => env('MPESA_STK_CALLBACK_URL', route('mpesa.stk-callback')),
    ],
];

