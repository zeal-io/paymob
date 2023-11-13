<?php

namespace Zeal\Paymob\Core\RequestBuilders;

use Zeal\PaymentFramework\RequestBuilders\BaseRequestBuilder;

class VoidRequestBuilder extends BaseRequestBuilder
{
    private int $transactionId;
    private string $authToken;

    public function toArray(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'token' => $this->authToken,
        ];
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(int $transactionId): VoidRequestBuilder
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): VoidRequestBuilder
    {
        $this->authToken = $authToken;
        return $this;
    }
}
