<?php

declare(strict_types=1);

namespace Zeal\Paymob;

use Illuminate\Support\Facades\Http;
use Zeal\Paymob\Models\PaymentKey;
use Zeal\Paymob\Models\PaymentOrder;
use Zeal\Paymob\Response\AuthenticationResponse;
use Zeal\Paymob\Response\ConnectExceptionResponse;
use Zeal\Paymob\Response\CreateOrderResponse;
use Zeal\Paymob\Response\FetchPaymentTransactionResponse;
use Zeal\Paymob\Response\PayWithSavedTokenResponse;
use Zeal\Paymob\Response\PaymentKeyResponse;

final class Paymob
{
    /**
     * Order id returned from paymob
     *
     * @var int
     */
    public $orderId;

    /**
     * Base API Endpont
     *
     * @var string
     */
    private $api = 'https://accept.paymob.com/api/';

    private $response;

    private $paymentKeyToken;

    public function __construct(string $apiKey)
    {
        $this->authenticate($apiKey);

        return $this;
    }

    public function getPaymentKeyToken()
    {
        return $this->paymentKeyToken;
    }

    public function createOrder(PaymentOrder $order): Paymob
    {

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'ecommerce/orders', [
                'auth_token'        => $this->authToken,
                'delivery_needed'   => $order->deliveryNeeded,
                'amount_cents'      => $order->amount,
                'currency'          => $order->currency,
                'merchant_order_id' => $order->orderId,
                'items'             => $order->items,
            ]);

        $this->response = new CreateOrderResponse($response);
        $this->orderId = $this->response->getOrderId();

        return $this;
    }

    /**
     * Check out an order
     *
     * @param array $data order details
     */
    public function createPaymentKey(PaymentKey $paymentKey): Paymob
    {
        if ($paymentKey->provider === 'paymob_flash') {
            $this->createIntentionPaymentKey($paymentKey);
            return $this;
        }

        $this->createAcceptancePaymentKey($paymentKey);
        return $this;
    }
    public function createAcceptancePaymentKey(PaymentKey $paymentKey): Paymob
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'acceptance/payment_keys', [
                'auth_token'     => $this->authToken,
                'amount_cents'   => $paymentKey->amount,
                'expiration'     => $paymentKey->expiration,
                'order_id'       => $paymentKey->orderId,
                'currency'       => $paymentKey->currency,
                'integration_id' => $paymentKey->integrationId,
                'billing_data'   => [
                    'apartment'       => 'NA',
                    'email'           => 'NA',
                    'floor'           => 'NA',
                    'first_name'      => 'NA',
                    'street'          => 'NA',
                    'building'        => 'NA',
                    'phone_number'    => 'NA',
                    'shipping_method' => 'NA',
                    'postal_code'     => 'NA',
                    'city'            => 'NA',
                    'country'         => 'NA',
                    'last_name'       => 'NA',
                    'state'           => 'NA',
                ],
            ]);

        $this->response = new PaymentKeyResponse($response);
        $this->paymentKeyToken = $this->response->getPaymentKeyToken();

        return $this;
    }

    public function createIntentionPaymentKey(PaymentKey $paymentKey): Paymob
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $paymentKey->secretKey,
            'Content-Type' => 'application/json',
        ])->post($this->api . 'v1/intention/', [
                'amount' => $paymentKey->amount,
                'currency' => $paymentKey->currency,
                'payment_methods' => [$paymentKey->motoIntegrationId],
                'items' => [],
                'special_reference' => $paymentKey->orderId,
                'billing_data' => [
                    'apartment' => '',
                    'email' => '',
                    'floor' => '',
                    'first_name' => 'NA',
                    'last_name' => 'NA',
                    'street' => '',
                    'building' => '',
                    'phone_number' => 'NA',
                    'shipping_method' => '',
                    'postal_code' => '',
                    'city' => '',
                    'country' => '',
                    'state' => '',
                ],
            ]);

        $this->response = new PaymentKeyResponse($response);
        $this->paymentKeyToken = $this->response->getIntentionPaymentKeyToken();

        return $this;
    }

    public function payWithSavedToken(string $cardToken): Paymob
    {
        try {
            $response = Http::timeout(16)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->api . 'acceptance/payments/pay', [
                    'source'        => [
                        'identifier' => $cardToken,
                        'subtype'    => 'TOKEN',
                    ],
                    'payment_token' => $this->paymentKeyToken,
                ]);

            $this->response = new PayWithSavedTokenResponse($response);
        } catch (\Exception $e) {
            $this->response = new ConnectExceptionResponse($e);
        }

        return $this;
    }

    public function syncTransactionResponse($uuid)
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'ecommerce/orders/transaction_inquiry', [
                'merchant_order_id' => $uuid,
                'auth_token'        => $this->authToken,
            ]);

        $this->response = new FetchPaymentTransactionResponse($response);
        return $this;
    }

    public function refund(array $data)
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'acceptance/void_refund/refund', [
                'auth_token'     => $this->authToken,
                'amount_cents'   => $data['amount'],
                'transaction_id' => $data['transaction_id'],
            ]);

        $this->response = new FetchPaymentTransactionResponse($response);

        return $this;
    }

    public function voidRefund(string $transactionId)
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'acceptance/void_refund/void?token=' . $this->authToken, [
                'transaction_id' => $transactionId,
            ]);

        $this->response = new FetchPaymentTransactionResponse($response);
        return $this;
    }
    public function response()
    {
        return $this->response;
    }

    private function authenticate(string $apiKey): void
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json',])
            ->post($this->api . 'auth/tokens', ['api_key' => $apiKey]);

        $this->response = new AuthenticationResponse($response);
        $this->authToken = $this->response->getAuthToken();
    }
}
