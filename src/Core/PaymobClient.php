<?php

namespace Zeal\Paymob\Core;

use Illuminate\Support\Facades\Http;
use Zeal\PaymentFramework\Client\GatewayClient;
use Zeal\PaymentFramework\Interfaces\IntegrationKeyInterface;
use Zeal\Paymob\Core\DTOs\GatewaySpecificationDTO;
use Zeal\Paymob\Core\Models\IntegrationKey;
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
    private IntegrationKey $integrationKey;
    private GatewaySpecificationDTO $specificationDto;

    protected function setHeaders(): self
    {
        $this->client->withHeaders([
            'Content-Type' => 'application/json'
        ]);

        return $this;
    }

    public function createPaymentOrder(CreateOrderRequestBuilder $requestBuilder): PaymobClient
    {
        $response = $this->client->post(self::BASE_URL . 'ecommerce/orders', $requestBuilder->build());

        $this->response = CreateOrderResponse::make($response);

        $this->specificationDto->orderId = $this->response->getId();

        return $this;
    }

    public function createPaymentKey(PaymentKeyRequestBuilder $requestBuilder): PaymobClient
    {
        $response = $this->client->post(self::BASE_URL . 'acceptance/payment_keys', $requestBuilder->build());

        $this->response = PaymentKeyResponse::make($response);

        $this->specificationDto->paymentKeyToken = $this->response->getPaymentKeyToken();

        return $this;
    }

    public function payWithSavedToken(PayWithSavedTokenRequestBuilder $requestBuilder): PaymobClient
    {
        $response = $this->client
            ->timeout(self::PAY_WITH_SAVED_TOKEN_TIMEOUT)
            ->post(self::BASE_URL . 'acceptance/payments/pay', $requestBuilder->build());

        $this->response = TransactionResponse::make($response);

        return $this;
    }

    public function fetchTransactionByMerchantOrderId(FetchTransactionRequestBuilder $requestBuilder): self
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post(self::BASE_URL . 'ecommerce/orders/transaction_inquiry', $requestBuilder->build());

        $this->response = TransactionResponse::make($response);

        return $this;
    }

    public function refund(RefundRequestBuilder $requestBuilder): self
    {
        $response = $this->client
            ->post(self::BASE_URL . 'acceptance/void_refund/refund', $requestBuilder->build());

        $this->response = TransactionResponse::make($response);

        return $this;
    }

    public function voidRefund(VoidRequestBuilder $requestBuilder): self
    {
        $response = $this->client
            ->post(self::BASE_URL . 'acceptance/void_refund/void', $requestBuilder->build());

        $this->response = new TransactionResponse($response);

        return $this;
    }

    public function authenticate(): self
    {
        $response = $this->client->post(self::BASE_URL . 'auth/tokens', [
            'api_key' => $this->integrationKey->api_key,
        ]);

        $this->response = AuthenticationResponse::make($response);

        $this->specificationDto->token = $this->response->getToken();

        return $this;
    }

    public function setGatewaySpecification(GatewaySpecificationDTO $specificationDto): PaymobClient
    {
        $this->specificationDto = $specificationDto;
        return $this;
    }

    public function setIntegrationKey(IntegrationKeyInterface $integrationKey): self
    {
        $this->integrationKey = $integrationKey;
        return $this;
    }
}
