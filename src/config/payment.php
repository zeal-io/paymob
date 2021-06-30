<?php

return [
    'paymob' => [
        'api_key' => env('PAYMOB_API_KEY', ''),
        'zeal_auth_integration' => (int) env('PAYMOB_AUTH_INTEGRATION', ''),
        'zeal_payment_integration' => (int) env('PAYMOB_PAYMENT_INTEGRATION', ''),
    ],
];
