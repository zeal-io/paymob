<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Models;

final class PaymentKey
{
    public string $currency;
    public float $amount;
    public int $integrationId;
    public string $orderId;
    public int $expiration;

    public function __construct(
        string $currency,
        float $amount,
        int $integrationId,
        string $orderId,
        int $expiration = 3600
    ) {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->integrationId = $integrationId;
        $this->orderId = $orderId;
        $this->expiration = $expiration;
    }
}
