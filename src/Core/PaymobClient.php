<?php

namespace Zeal\Paymob\Core;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Zeal\PaymentFramework\RequestBuilders\BaseRequestBuilder;
use Zeal\PaymentFramework\Responses\BasePaymentResponse;
use Zeal\Paymob\Core\Models\IntegrationKey;
use Zeal\Paymob\Core\Models\PaymentKey;
use Zeal\Paymob\Core\Models\PaymentOrder;
use Zeal\Paymob\Core\Responses\AuthenticationResponse;
use Zeal\Paymob\Core\Responses\ConnectExceptionResponse;
use Zeal\Paymob\Core\Responses\CreateOrderResponse;
use Zeal\Paymob\Core\Responses\FetchPaymentTransactionResponse;
use Zeal\Paymob\Core\Responses\PaymentKeyResponse;
use Zeal\Paymob\Core\Responses\PayWithSavedTokenResponse;

class PaymobClient
{
    const BASE_URL = 'https://accept.paymob.com/api/';

    public PendingRequest $client;
    private BaseRequestBuilder $requestBuilder;
    private IntegrationKey $integrationKey;
    private BasePaymentResponse $response;
    private string $token;

    public function __construct(IntegrationKey $integrationKey)
    {
        $this->client = new PendingRequest();
        $this->integrationKey = $integrationKey;

            $this
                ->setHeaders()
                ->authenticate();
    }

    private function setHeaders(): self
    {
        $this->client->withHeaders([
            'Content-Type' => 'application/json'
        ]);

        return $this;
    }
    public function createOrder(PaymentOrder $order): PaymobClient
    {
//[
//                'auth_token'        => $this->authToken,
//                'delivery_needed'   => $order->deliveryNeeded,
//                'amount_cents'      => $order->amount,
//                'currency'          => $order->currency,
//                'merchant_order_id' => $order->orderId,
//                'items'             => $order->items,
//            ]
        $response = $this->client->post(self::BASE_URL . 'ecommerce/orders', $this->requestBuilder->build());

        $this->response = new CreateOrderResponse($response);

        $this->orderId = $this->response->getOrderId();

        return $this;
    }

    /**
     * Check out an order
     *
     * @param array $data order details
     */
    public function createPaymentKey(PaymentKey $paymentKey): PaymobClient
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

    public function payWithSavedToken(string $cardToken): PaymobClient
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

    private function authenticate(): self
    {
        $response = $this->client
            ->post(self::BASE_URL . 'auth/tokens', [
                'api_key' => $this->integrationKey->api_key,
            ]);

        $this->response = AuthenticationResponse::make($response);

        $this->token = $this->response->getToken();

        return $this;
    }
}
