<?php

namespace Zeal\Paymob\Core\RequestBuilders;

use Zeal\PaymentFramework\RequestBuilders\BaseRequestBuilder;

class PaymentKeyRequestBuilder extends BaseRequestBuilder
{
    private string $authToken;
    private float $amountInCents;
    private string $expiration;
    private string $orderId;
    private string $currency;
    private string $integrationId;

    public function toArray(): array
    {
        return [
            'auth_token' => $this->authToken,
            'amount_cents' => $this->amountInCents,
            'expiration' => $this->expiration,
            'order_id' => $this->orderId,
            'currency' => $this->currency,
            'integration_id' => $this->integrationId,
            'billing_data' => [
                'apartment' => 'NA',
                'email' => 'NA',
                'floor' => 'NA',
                'first_name' => 'NA',
                'street' => 'NA',
                'building' => 'NA',
                'phone_number' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'NA',
                'country' => 'NA',
                'last_name' => 'NA',
                'state' => 'NA'
            ]
        ];
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

    public function getAmountInCents(): float
    {
        return $this->amountInCents;
    }

    public function setAmountInCents(float $amountInCents): self
    {
        $this->amountInCents = $amountInCents;
        return $this;
    }

    public function getExpiration(): string
    {
        return $this->expiration;
    }

    public function setExpiration(string $expiration): self
    {
        $this->expiration = $expiration;
        return $this;
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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getIntegrationId(): string
    {
        return $this->integrationId;
    }

    public function setIntegrationId(string $integrationId): self
    {
        $this->integrationId = $integrationId;
        return $this;
    }
}
