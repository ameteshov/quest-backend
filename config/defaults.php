<?php

return [
    'emails' => [
        'support' => env('SUPPORT_EMAIL', 'support@hr-tophunter.ru')
    ],
    'free_plan' => [
        'points' => env('FREE_PLAN_POINTS', 15)
    ],
    'checkout' => [
        'merchant_key' => env('MERCHANT_KEY'),
        'merchant_token' => env('MERCHANT_TOKEN'),
        'default_currency' => env('CHECKOUT_CURRENCY', 'RUB'),
        'default_locale' => env('CHECKOUT_LOCALE', 'ru'),
        'return_url' => env('CHECKOUT_RETURN_URL', env('FRONTEND_URL') . '/panel/payments/finished/')
    ],
    'forms' => [
        'ttl' => env('FORMS_TTL', 48) // in hours
    ],
    'subscription' => [
        'ttl' => env('SUBSCRIPTION_TTL', 1) //in months
    ],
    'frontend_url' => env('FRONTEND_URL')
];
