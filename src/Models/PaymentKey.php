<?php

namespace Zeal\Paymob\Models;

class PaymentKey
{
    public string $currency;
    public float $amount;
    public int $integrationId;
    public $orderId;
    public int $expiration;

    public function __construct(
        string $currency,
        float $amount,
        int $integrationId,
        $orderId = null,
        int $expiration = 3600
    ) {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->integrationId = $integrationId;
        $this->orderId = $orderId;
        $this->expiration = $expiration;
    }
}
