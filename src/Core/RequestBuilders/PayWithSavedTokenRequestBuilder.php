<?php

namespace Zeal\Paymob\Core\RequestBuilders;

use Zeal\PaymentFramework\RequestBuilders\BaseRequestBuilder;

class PayWithSavedTokenRequestBuilder extends BaseRequestBuilder
{
    private string $cardToken;
    private string $paymentKeyToken;

    public function toArray(): array
    {
        return [
            'source' => [
                'identifier' => $this->cardToken,
                'subtype' => 'TOKEN',
            ],
            'payment_token' => $this->paymentKeyToken,
        ];
    }

    public function getCardToken(): string
    {
        return $this->cardToken;
    }

    public function setCardToken(string $cardToken): PayWithSavedTokenRequestBuilder
    {
        $this->cardToken = $cardToken;
        return $this;
    }

    public function getPaymentKeyToken(): string
    {
        return $this->paymentKeyToken;
    }

    public function setPaymentKeyToken(string $paymentKeyToken): PayWithSavedTokenRequestBuilder
    {
        $this->paymentKeyToken = $paymentKeyToken;
        return $this;
    }
}
