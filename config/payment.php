<?php

return [
		'paymob' => [
				'api_key' => env('PAYMOB_API_KEY', ''),
				'zeal_auth_integration' => env('PAYMOB_AUTH_INTEGRATION', ''),
				'zeal_payment_integration' => env('PAYMOB_PAYMENT_INTEGRATION', ''),
		],
];
