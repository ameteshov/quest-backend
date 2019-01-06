<?php

return [
    'emails' => [
        'support' => env('SUPPORT_EMAIL', 'support@hr-tophunter.ru')
    ],
    'free_plan' => [
        'points' => 100
    ],
    'checkout' => [
        'merchant_key' => env('MERCHANT_KEY'),
        'merchant_token' => env('MERCHANT_TOKEN'),
        'default_currency' => env('CHECKOUT_CURRENCY', 'RUB'),
        'default_locale' => env('CHECKOUT_LOCALE', 'ru')
    ]
];
