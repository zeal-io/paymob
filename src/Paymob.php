<?php

declare(strict_types=1);

namespace Zeal\Paymob;

use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Zeal\Paymob\Exceptions\InvalidPaymentException;
use Zeal\Paymob\Exceptions\UnauthenticatedException;
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
    public $orderId;

    private $api = 'https://accept.paymob.com/';
    private $response;
    private $paymentKeyToken;
    private $authToken;
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    // create a method to check response status code and throw exception if not 200
    public function checkResponseStatusCode(HttpResponse $response)
    {
        if ($response->failed()) {
            throw new \Exception(
                'Response status code is not 200. Body: ' . $response->body()
            );
        }
    }

    public function getPaymentKeyToken()
    {
        return $this->paymentKeyToken;
    }

    public function createOrder(PaymentOrder $order): Paymob
    {
        $this->ensureAuthToken();

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'api/ecommerce/orders', [
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

    public function createPaymentKey(PaymentKey $paymentKey): Paymob
    {
        if ($paymentKey->provider === 'paymob_flash') {
            return $this->createIntentionPaymentKey($paymentKey);
        }

        return $this->createAcceptancePaymentKey($paymentKey);
    }

    public function createAcceptancePaymentKey(PaymentKey $paymentKey): Paymob
    {
        $this->ensureAuthToken();

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'api/acceptance/payment_keys', [
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
        $this->checkResponseStatusCode($response);

        $this->response = new PaymentKeyResponse($response);
        $this->paymentKeyToken = $this->response->getPaymentKeyToken();

        return $this;
    }

    public function createIntentionPaymentKey(PaymentKey $paymentKey): Paymob
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $paymentKey->secretKey,
            'Content-Type' => 'application/json',
        ])->post($this->api . '/v1/intention/', [
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

        $this->checkResponseStatusCode($response);

        $this->response = new PaymentKeyResponse($response);
        $this->paymentKeyToken = $this->response->getIntentionPaymentKeyToken();

        return $this;
    }

    public function payWithSavedToken(string $cardToken): Paymob
    {
        try {
            $this->ensureAuthToken();

            $response = Http::timeout(16)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->api . 'api/acceptance/payments/pay', [
                    'source'        => [
                        'identifier' => $cardToken,
                        'subtype'    => 'TOKEN',
                    ],
                    'payment_token' => $this->paymentKeyToken,
                ]);

            $this->response = new PayWithSavedTokenResponse($response);
        } catch (InvalidPaymentException $e) {
            // Re-throw application exceptions - they should bubble up
            throw $e;
        } catch (UnauthenticatedException $e) {
            // Re-throw application exceptions - they should bubble up
            throw $e;
        } catch (\Exception $e) {
            // Only catch connection/timeout errors for ConnectExceptionResponse
            $this->response = new ConnectExceptionResponse($e);
        }

        return $this;
    }

    public function syncTransactionResponse($uuid)
    {
        $this->ensureAuthToken();

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'api/ecommerce/orders/transaction_inquiry', [
                'merchant_order_id' => $uuid,
                'auth_token'        => $this->authToken,
            ]);

        $this->response = new FetchPaymentTransactionResponse($response);
        return $this;
    }

    public function refund(array $data)
    {
        $this->ensureAuthToken();

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'api/acceptance/void_refund/refund', [
                'auth_token'     => $this->authToken,
                'amount_cents'   => $data['amount'],
                'transaction_id' => $data['transaction_id'],
            ]);

        $this->response = new FetchPaymentTransactionResponse($response);

        return $this;
    }

    public function voidRefund(string $transactionId)
    {
        $this->ensureAuthToken();

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'api/acceptance/void_refund/void?token=' . $this->authToken, [
                'transaction_id' => $transactionId,
            ]);

        $this->response = new FetchPaymentTransactionResponse($response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }

    private function authenticate(): string
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->api . 'api/auth/tokens', ['api_key' => $this->apiKey]);

        $this->response = new AuthenticationResponse($response);

        return $this->response->getAuthToken();
    }

    private function ensureAuthToken(): void
    {
        if (!$this->authToken) {
            $this->authToken = Cache::remember($this->getCacheKey(), 2700, function () {
                return $this->authenticate();
            });
        }
    }

    private function getCacheKey(): string
    {
        return 'paymob_auth_token_' . md5($this->apiKey);
    }
}
