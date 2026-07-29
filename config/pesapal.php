<?php

return [
    'consumer_key' => env('PESAPAL_CONSUMER_KEY', ''),
    'consumer_secret' => env('PESAPAL_CONSUMER_SECRET', ''),
    
    // Set to 'production' to use live URLs
    'env' => env('PESAPAL_ENV', 'production'),
    
    'live_api' => 'https://pay.pesapal.com/v3',
    'sandbox_api' => 'https://cybqa.pesapal.com/pesapalv3',
    'callback_url' => env('PESAPAL_CALLBACK_URL'),
    'ipn_url' => env('PESAPAL_IPN_URL'),
];
