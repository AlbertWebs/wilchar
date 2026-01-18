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
        'result_url' => env('MPESA_B2C_RESULT_URL', env('APP_URL', 'http://localhost') . '/api/mpesa/b2c/callback'),
        'timeout_url' => env('MPESA_B2C_TIMEOUT_URL', env('APP_URL', 'http://localhost') . '/api/mpesa/b2c/timeout'),
        'queue_timeout_url' => env('MPESA_B2C_QUEUE_TIMEOUT_URL', env('MPESA_B2C_TIMEOUT_URL', env('APP_URL', 'http://localhost') . '/api/mpesa/b2c/timeout')),
    ],

    'stk' => [
        'callback_url' => env('MPESA_STK_CALLBACK_URL', env('APP_URL', 'http://localhost') . '/admin/mpesa/stk-push/callback'),
    ],

    'c2b' => [
        'validation_url' => env('MPESA_C2B_VALIDATION_URL', env('APP_URL', 'http://localhost') . '/admin/mpesa/c2b/validate'),
        'confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL', env('APP_URL', 'http://localhost') . '/admin/mpesa/c2b/confirm'),
    ],
];

