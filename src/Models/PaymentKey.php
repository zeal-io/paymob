<?php

declare(strict_types=1);

namespace Zeal\Paymob\Models;

final class PaymentKey
{
    public string $currency;
    public float $amount;
    public int $integrationId;
    public string $orderId;
    public int $expiration;
    public ?string $provider = null;
    public ?string $secretKey = null;
    public ?int $motoIntegrationId = null;

    public function __construct(
        string $currency,
        float $amount,
        int $integrationId,
        string $orderId,
        int $expiration = 3600,
        ?string $provider = null,
        ?string $secretKey = null,
        ?int $motoIntegrationId = null
    ) {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->integrationId = $integrationId;
        $this->orderId = $orderId;
        $this->expiration = $expiration;
        $this->provider = $provider;
        $this->secretKey = $secretKey;
        $this->motoIntegrationId = $motoIntegrationId;
    }
}
