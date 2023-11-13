<?php

namespace Zeal\Paymob\Core\Services\MockService;

use Zeal\PaymentFramework\Services\MockService\BaseMockService;

class MockPaymobService extends BaseMockService
{
    public function mockAll(): static
    {
        $this
            ->obtainToken()
            ->createOrder()
            ->createPaymentKey()
            ->pay()
            ->refund()
            ->void();

        return $this;
    }

    public function obtainToken(int $httpCode = 200, string $payloadName = null): self
    {
        $payload = $payloadName ?? $this->getPayloadUsingStatusCode($httpCode);

        return $this->addMock(
            'https://accept.paymob.com/api/auth/tokens',
            __DIR__ . '/Payloads/Responses/Checkout/Token/' . $payload,
            $httpCode
        );
    }

    public function createOrder(int $httpCode = 200, string $payloadName = null): self
    {
        $payload = $payloadName ?? $this->getPayloadUsingStatusCode($httpCode);

        return $this->addMock(
            'https://accept.paymob.com/api/ecommerce/orders',
            __DIR__ . '/Payloads/Responses/Checkout/Order/' . $payload,
            $httpCode
        );
    }

    public function createPaymentKey(int $httpCode = 200, string $payloadName = null): self
    {
        $payload = $payloadName ?? $this->getPayloadUsingStatusCode($httpCode);

        return $this->addMock(
            'https://accept.paymob.com/api/acceptance/payment_keys',
            __DIR__ . '/Payloads/Responses/Checkout/PaymentKey/' . $payload,
            $httpCode
        );
    }

    public function pay(int $httpCode = 200, string $payloadName = null): self
    {
        $payload = $payloadName ?? $this->getPayloadUsingStatusCode($httpCode);

        return $this->addMock(
            'https://accept.paymob.com/api/acceptance/payments/pay',
            __DIR__ . '/Payloads/Responses/Checkout/Payment/' . $payload,
            $httpCode
        );
    }

    public function refund(int $httpCode = 200, string $payloadName = null): self
    {
        $payload = $payloadName ?? $this->getPayloadUsingStatusCode($httpCode);

        return $this->addMock(
            'https://accept.paymob.com/api/acceptance/void_refund/refund',
            __DIR__ . '/Payloads/Responses/Refund/' . $payload,
            $httpCode
        );
    }

    public function void(int $httpCode = 200, string $payloadName = null): self
    {
        $payload = $payloadName ?? $this->getPayloadUsingStatusCode($httpCode);

        return $this->addMock(
            'https://accept.paymob.com/api/acceptance/void_refund/void',
            __DIR__ . '/Payloads/Responses/Refund/' . $payload,
            $httpCode
        );
    }
}
