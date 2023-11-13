<?php

namespace Zeal\Paymob\Core\RequestBuilders;

use Zeal\PaymentFramework\RequestBuilders\BaseRequestBuilder;

class CreateOrderRequestBuilder extends BaseRequestBuilder
{
    private string $authToken;
    private bool $deliveryNeeded;
    private float $amountInCents;
    private string $currency;
    private string $orderId;
    private array $items;

    public function toArray(): array
    {
        return [
            'auth_token'        => $this->authToken,
            'delivery_needed'   => $this->deliveryNeeded,
            'amount_cents'      => $this->amountInCents,
            'currency'          => $this->currency,
            'merchant_order_id' => $this->orderId,
            'items'             => $this->items,
        ];
    }


    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): CreateOrderRequestBuilder
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function isDeliveryNeeded(): bool
    {
        return $this->deliveryNeeded;
    }

    public function setDeliveryNeeded(bool $deliveryNeeded): CreateOrderRequestBuilder
    {
        $this->deliveryNeeded = $deliveryNeeded;
        return $this;
    }

    public function getAmountInCents(): float
    {
        return $this->amountInCents;
    }

    public function setAmountInCents(float $amountInCents): CreateOrderRequestBuilder
    {
        $this->amountInCents = $amountInCents;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): CreateOrderRequestBuilder
    {
        $this->currency = $currency;
        return $this;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): CreateOrderRequestBuilder
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): CreateOrderRequestBuilder
    {
        $this->items = $items;
        return $this;
    }
}
