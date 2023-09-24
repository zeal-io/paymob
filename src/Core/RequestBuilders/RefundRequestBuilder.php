<?php

namespace Zeal\Paymob\Core\RequestBuilders;

use Zeal\PaymentFramework\RequestBuilders\BaseRequestBuilder;

class RefundRequestBuilder extends BaseRequestBuilder
{
    private string $authToken;
    private string $amountInCents;
    private string $transactionId;

    public function toArray(): array
    {
        return [
            'auth_token' => $this->authToken,
            'amount_cents' => $this->amountInCents,
            'transaction_id' => $this->transactionId,
        ];
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): RefundRequestBuilder
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function getAmountInCents(): string
    {
        return $this->amountInCents;
    }

    public function setAmountInCents(string $amountInCents): RefundRequestBuilder
    {
        $this->amountInCents = $amountInCents;
        return $this;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): RefundRequestBuilder
    {
        $this->transactionId = $transactionId;
        return $this;
    }
}
