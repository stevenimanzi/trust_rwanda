<?php

return [
    'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
    'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
    'env' => env('PESAPAL_ENV', 'sandbox'),
    'live_api' => 'https://pay.pesapal.com/v3',
    'sandbox_api' => 'https://cybqa.pesapal.com/pesapalv3',
];
