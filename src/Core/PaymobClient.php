<?php

namespace Zeal\Paymob\Core;

use Zeal\PaymentFramework\Client\GatewayClient;
use Zeal\Paymob\Core\DTOs\GatewaySpecificationDTO;
use Zeal\Paymob\Core\Exceptions\InvalidAuthenticationException;
use Zeal\Paymob\Core\Models\IntegrationKey;
use Zeal\Paymob\Core\Models\PaymobIntegrationKey;
use Zeal\Paymob\Core\RequestBuilders\CreateOrderRequestBuilder;
use Zeal\Paymob\Core\RequestBuilders\FetchTransactionRequestBuilder;
use Zeal\Paymob\Core\RequestBuilders\PaymentKeyRequestBuilder;
use Zeal\Paymob\Core\RequestBuilders\PayWithSavedTokenRequestBuilder;
use Zeal\Paymob\Core\RequestBuilders\RefundRequestBuilder;
use Zeal\Paymob\Core\RequestBuilders\VoidRequestBuilder;
use Zeal\Paymob\Core\Responses\AuthenticationResponse;
use Zeal\Paymob\Core\Responses\CreateOrderResponse;
use Zeal\Paymob\Core\Responses\PaymentKeyResponse;
use Zeal\Paymob\Core\Responses\TransactionResponse;

class PaymobClient extends GatewayClient
{
    const BASE_URL = 'https://accept.paymob.com/api/';
    const PAY_WITH_SAVED_TOKEN_TIMEOUT = 14;
    private PaymobIntegrationKey $integrationKey;
    private GatewaySpecificationDTO $specificationDto;

    public function __construct(PaymobIntegrationKey $integrationKey)
    {
        $this->integrationKey = $integrationKey;
        parent::__construct();
    }

    public static function make(PaymobIntegrationKey $integrationKey): static
    {
        return new static($integrationKey);
    }

    protected function setHeaders(): static
    {
        $this->client->withHeaders([
            'Content-Type' => 'application/json'
        ]);

        return $this;
    }

    public function createPaymentOrder(CreateOrderRequestBuilder $requestBuilder): PaymobClient
    {
        $response = $this->post(PaymobClient::BASE_URL . 'ecommerce/orders', $requestBuilder);

        $this->response = CreateOrderResponse::make($response)
            ->then(fn(CreateOrderResponse $response) => $this->specificationDto->orderId = $response->getId());

        $this->specificationDto->orderId = $this->response->getId();

        return $this;
    }

    public function createPaymentKey(PaymentKeyRequestBuilder $requestBuilder): PaymobClient
    {
        $response = $this->post(PaymobClient::BASE_URL . 'acceptance/payment_keys', $requestBuilder);

        $this->response = PaymentKeyResponse::make($response);

        $this->specificationDto->paymentKeyToken = $this->response->getPaymentKeyToken();

        return $this;
    }

    public function payWithSavedToken(PayWithSavedTokenRequestBuilder $requestBuilder): PaymobClient
    {
        $this->client->timeout(PaymobClient::PAY_WITH_SAVED_TOKEN_TIMEOUT);

        $response = $this->post(PaymobClient::BASE_URL . 'acceptance/payments/pay', $requestBuilder);

        $this->response = TransactionResponse::make($response);

        return $this;
    }

    public function fetchTransactionByMerchantOrderId(FetchTransactionRequestBuilder $requestBuilder): self
    {
        $response = $this->post(PaymobClient::BASE_URL . 'ecommerce/orders/transaction_inquiry', $requestBuilder);

        $this->response = TransactionResponse::make($response);

        return $this;
    }

    public function refund(RefundRequestBuilder $requestBuilder): self
    {
        $response = $this->post(PaymobClient::BASE_URL . 'acceptance/void_refund/refund', $requestBuilder);

        $this->response = TransactionResponse::make($response);

        return $this;
    }

    public function void(VoidRequestBuilder $requestBuilder): self
    {
        $response = $this->post(PaymobClient::BASE_URL . 'acceptance/void_refund/void', $requestBuilder);

        $this->response = TransactionResponse::make($response);

        return $this;
    }

    public function authenticate(): self
    {
        $response = $this->post(PaymobClient::BASE_URL . 'auth/tokens', [
            'api_key' => $this->integrationKey->api_key,
        ]);

        $this->response = AuthenticationResponse::make($response)
            ->then(fn(AuthenticationResponse $response) => $this->specificationDto->token = $response->getToken());


        return $this;
    }

    public function setGatewaySpecification(GatewaySpecificationDTO $specificationDto): self
    {
        $this->specificationDto = $specificationDto;
        return $this;
    }
}
