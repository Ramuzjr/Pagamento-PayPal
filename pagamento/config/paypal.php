<?php

return [

    'mode' => env('PAYPAL_MODE', 'sandbox'),

    'base_url' => env('PAYPAL_MODE', 'sandbox') === 'live'
        ? 'https://api-m.paypal.com'
        : 'https://api-m.sandbox.paypal.com',

    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret'    => env('PAYPAL_SECRET'),

    'currency'  => env('PAYPAL_CURRENCY', 'EUR'),
];
