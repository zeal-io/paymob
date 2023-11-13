<?php

namespace Zeal\Paymob\Core\RequestBuilders;

use Zeal\PaymentFramework\RequestBuilders\BaseRequestBuilder;

class FetchTransactionRequestBuilder extends BaseRequestBuilder
{
    private string $orderId;
    private string $authToken;

    public function toArray(): array
    {
        return [
            'merchant_order_id' => $this->orderId,
            'auth_token'        => $this->authToken,
        ];
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): self
    {
        $this->authToken = $authToken;
        return $this;
    }
}
