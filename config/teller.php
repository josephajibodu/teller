<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default gateway that will be used by Teller
    | when no specific gateway is requested.
    |
    */

    'default_gateway' => env('TELLER_DEFAULT_GATEWAY', 'paystack'),

    /*
    |--------------------------------------------------------------------------
    | Gateway Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each payment gateway that
    | Teller supports. You can add as many gateways as you need.
    |
    */

    'gateways' => [
        'paystack' => [
            'secret_key' => env('PAYSTACK_SECRET_KEY'),
            'public_key' => env('PAYSTACK_PUBLIC_KEY'),
            'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),
        ],

        'flutterwave' => [
            'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
            'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
            'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Proration Settings
    |--------------------------------------------------------------------------
    |
    | These options control how proration calculations are handled when
    | upgrading or downgrading subscriptions.
    |
    */

    'proration' => [
        'enabled' => env('TELLER_PRORATION_ENABLED', true),
        'rounding' => env('TELLER_PRORATION_ROUNDING', 'up'), // up, down, nearest
        'cutoff_day' => env('TELLER_PRORATION_CUTOFF_DAY', 25), // Day of month to defer upgrades
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Settings
    |--------------------------------------------------------------------------
    |
    | These options control webhook handling and verification.
    |
    */

    'webhooks' => [
        'verify_signature' => env('TELLER_VERIFY_WEBHOOK_SIGNATURE', true),
        'timeout' => env('TELLER_WEBHOOK_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    |
    | Default currency and formatting options.
    |
    */

    'currency' => [
        'default' => env('TELLER_DEFAULT_CURRENCY', 'NGN'),
        'format' => [
            'symbol' => '₦',
            'decimal_places' => 2,
        ],
    ],
];
