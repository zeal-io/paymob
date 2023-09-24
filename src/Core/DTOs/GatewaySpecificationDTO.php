<?php

namespace Zeal\Paymob\Core\DTOs;

use Zeal\PaymentFramework\Interfaces\GatewaySpecificationInterface;

class GatewaySpecificationDTO implements GatewaySpecificationInterface
{
    public string $orderId;
    public string $paymentKeyToken;
    public string $token;
    public function __construct(
        public readonly string $merchantOrderId,
        public readonly string $integrationId
    ) {
    }
}
