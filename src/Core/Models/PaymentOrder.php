<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Models;

final class PaymentOrder
{
    public string $currency;
    public float $amount;
    public string $orderId;
    public array $items = [];
    public bool $deliveryNeeded = false;

    public function __construct(string $currency, float $amount, string $orderId, array $items = [], bool $deliveryNeeded = false)
    {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->orderId = $orderId;
        $this->items = $items;
        $this->deliveryNeeded = $deliveryNeeded;
    }
}
