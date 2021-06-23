<?php

declare(strict_types=1);

namespace Zeal\Paymob;

use GuzzleHttp\Client;
use Zeal\Paymob\Models\PaymentKey;
use Zeal\Paymob\Models\PaymentOrder;
use Zeal\Paymob\Response\AuthenticationResponse;
use Zeal\Paymob\Response\CheckoutResponse;
use Zeal\Paymob\Response\CreateOrderResponse;
use Zeal\Paymob\Response\PayWithSavedTokenResponse;
use Zeal\Paymob\Response\PaymentKeyResponse;
use Zeal\Paymob\Response\RefundResponse;

final class Paymob
{
    public $orderId;
    /**
     * Paymob API Credentails
     *
     * @var array
     */
    private $credentials;

    /**
     * Base API Endpont
     *
     * @var string
     */
    private $api = "https://accept.paymob.com/api/";

    /**
     * Message Send Response
     *
     * @var CheckoutResponse|TokenizationResponse
     */
    private $response;

    /**
     * Guzzle Client for Iframe APIs
     *
     * @var Client
     */
    private $http;

    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->http = new Client([
            'base_uri' => $this->api,
            'http_errors' => false,
            'defaults' => [
                'headers' => ['Content-Type' => 'application/json'],
            ]
        ]);

        $this->authenticate($apiKey);

        return $this;
    }

    public function createOrder(PaymentOrder $order): Paymob
    {
        $response = $this->http->request('POST', 'ecommerce/orders', [
            'json' => [
                "auth_token" => $this->authToken,
                "delivery_needed" => $order->deliveryNeeded,
                "amount_cents" => $order->amount,
                "currency" => $order->currency,
                "merchant_order_id" => $order->orderId,
                "items" => $order->items
            ]
        ]);

        $this->response = new CreateOrderResponse($response);
        $this->orderId = $this->response->getOrderId();

        return $this;
    }

    /**
     * Check out an order
     *
     * @param array  $data order details
     * @return Paymob
     */
    public function createPaymentKey(PaymentKey $paymentKey)
    {
        $response = $this->http->request('POST', "acceptance/payment_keys", [
            'json' => [
                "auth_token" => $this->authToken,
                "amount_cents" => $paymentKey->amount,
                "expiration" => $paymentKey->expiration,
                "order_id" => $paymentKey->orderId,
                "currency" => $paymentKey->currency,
                "integration_id" => $paymentKey->integrationId,
                "billing_data" => [
                    "apartment" => "NA",
                    "email" => "NA",
                    "floor" => "NA",
                    "first_name" => "NA",
                    "street" => "NA",
                    "building" => "NA",
                    "phone_number" => "NA",
                    "shipping_method" => "NA",
                    "postal_code" => "NA",
                    "city" => "NA",
                    "country" => "NA",
                    "last_name" => "NA",
                    "state" => "NA"
                ],
            ],
        ]);

        $this->response = new PaymentKeyResponse($response);
        $this->paymentKeyToken = $this->response->getPaymentKeyToken();

        return $this;
    }

    public function payWithSavedToken(string $cardToken)
    {
        $response = $this->http->request('POST', "acceptance/payments/pay", [
            'json' => [
                "source" => [
                    "identifier" => $cardToken,
                    "subtype" => "TOKEN"
                ],
                "payment_token" => $this->paymentKeyToken,
            ]
        ]);

        $this->response = new PayWithSavedTokenResponse($response);

        return $this;
    }


    /**
     * Response getter
     *
     * @return CheckoutResponse|TokenizationResponse
     */
    public function response()
    {
        return $this->response;
    }

    private function authenticate(string $apiKey)
    {
        $response = $this->http->request('POST', "auth/tokens", [
            'json' => [
                "api_key" => $apiKey,
            ],
        ]);

        $this->response = new AuthenticationResponse($response);
        $this->authToken = $this->response->getAuthToken();

        return $this;
    }
}
