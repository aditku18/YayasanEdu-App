<?php

return [
    'default_gateway' => env('PAYMENT_DEFAULT_GATEWAY', 'midtrans'),
    
    'encryption_key' => env('PAYMENT_ENCRYPTION_KEY'),
    
    'webhook_token' => env('WEBHOOK_TOKEN'),
    
    'gateways' => [
        'midtrans' => [
            'name' => 'Midtrans',
            'class' => 'App\\Services\\PaymentGateways\\MidtransGateway',
            'config' => [
                'server_key' => env('MIDTRANS_SERVER_KEY'),
                'client_key' => env('MIDTRANS_CLIENT_KEY'),
                'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
                'api_url' => env('APP_ENV') === 'local' 
                    ? 'https://api.sandbox.midtrans.com/v2' 
                    : 'https://api.midtrans.com/v2',
                'webhook_token' => env('MIDTRANS_WEBHOOK_TOKEN'),
            ],
            'supported_methods' => ['credit_card', 'bank_transfer', 'virtual_account', 'ewallet', 'qris'],
            'supports_recurring' => true,
            'supports_split_payment' => true,
            'admin_fee_rate' => 2.5,
            'fixed_admin_fee' => 0,
        ],
        
        'xendit' => [
            'name' => 'Xendit',
            'class' => 'App\\Services\\PaymentGateways\\XenditGateway',
            'config' => [
                'secret_key' => env('XENDIT_SECRET_KEY'),
                'api_url' => env('XENDIT_API_URL', 'https://api.xendit.co'),
                'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
            ],
            'supported_methods' => ['credit_card', 'bank_transfer', 'virtual_account', 'ewallet', 'qris'],
            'supports_recurring' => true,
            'supports_split_payment' => true,
            'admin_fee_rate' => 2.9,
            'fixed_admin_fee' => 0,
        ],
        
        'custom' => [
            'name' => 'Custom Gateway',
            'class' => 'App\\Services\\PaymentGateways\\CustomGateway',
            'config' => [
                'api_url' => env('CUSTOM_GATEWAY_API_URL'),
                'secret_key' => env('CUSTOM_GATEWAY_SECRET_KEY'),
                'webhook_token' => env('CUSTOM_GATEWAY_WEBHOOK_TOKEN'),
            ],
            'supported_methods' => ['bank_transfer', 'other'],
            'supports_recurring' => false,
            'supports_split_payment' => false,
            'admin_fee_rate' => 0,
            'fixed_admin_fee' => 5000,
        ],
    ],
    
    'recurring' => [
        'auto_charge' => true,
        'charge_before_days' => 3,
        'max_retry_attempts' => 3,
        'retry_interval_hours' => 24,
    ],
    
    'security' => [
        'allowed_origins' => explode(',', env('PAYMENT_ALLOWED_ORIGINS', '*')),
        'webhook_timeout' => 30,
        'max_payment_amount' => 100000000, // 100 juta
        'min_payment_amount' => 1000,
    ],
    
    'notifications' => [
        'payment_success' => true,
        'payment_failed' => true,
        'recurring_payment' => true,
        'webhook_received' => false,
    ],
];
