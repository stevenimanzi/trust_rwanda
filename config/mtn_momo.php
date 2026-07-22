<?php

return [
    'base_url' => env('MTN_MOMO_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
    'api_user' => env('MTN_MOMO_API_USER'),
    'api_key' => env('MTN_MOMO_API_KEY'),
    'subscription_key' => env('MTN_MOMO_SUBSCRIPTION_KEY'),
    'target_environment' => env('MTN_MOMO_TARGET_ENVIRONMENT', 'sandbox'),
    'currency' => env('MTN_MOMO_CURRENCY', 'EUR'),
    'callback_enabled' => env('MTN_MOMO_CALLBACK_ENABLED', false),
];
