<?php

namespace Zeal\Paymob\Core\DTOs;

use Illuminate\Support\Facades\Validator;
use Zeal\PaymentFramework\DTOs\PaymentDto;
use Zeal\PaymentFramework\Interfaces\GatewaySpecificationInterface;
use Zeal\PaymentFramework\Support\HandleExceptionSupport;

class GatewaySpecificationDTO implements GatewaySpecificationInterface
{
    public function __construct(
        public readonly string $merchantOrderId,
        public readonly string $integrationId,
        public ?string $paymentKeyToken = null,
        public ?string $token = null,
        public ?string $orderId = null,
    ) {
    }

    public function validated(): array
    {
        return Validator::make($this->toArray(), [
            'merchant_order_id' => ['required', 'string', 'max:255'],
            'integration_id' => ['required', 'string', 'max:255'],
            'payment_key_token' => ['nullable', 'string', 'max:255'],
            'token' => ['nullable', 'string', 'max:255'],
            'order_id' => ['nullable', 'string', 'max:255'],
        ])->validate();
    }

    public function toArray(): array
    {
        return [
            'merchant_order_id' => $this->merchantOrderId,
            'integration_id' => $this->integrationId,
            'payment_key_token' => $this->paymentKeyToken,
            'token' => $this->token,
            'order_id' => $this->orderId,
        ];
    }

    public function getGatewayOrderId(): ?string
    {
        return $this->orderId;
    }

    public static function buildGatewaySpecification(PaymentDto $paymentDTO): static
    {
        if (! $paymentDTO->paymentSettings->business->paymentServiceIntegrationKey) {
            HandleExceptionSupport::badRequest('Payment service integration key is not set');
        }

        if ($paymentDTO->paymentInfo->transaction->getGatewaySpecification()) {
            $specification = $paymentDTO->paymentInfo->transaction->getGatewaySpecification();
            return new self(
                merchantOrderId: $specification['merchant_order_id'],
                integrationId: $specification['integration_id'],
                paymentKeyToken: $specification['payment_key_token'],
                token: $specification['token'],
                orderId: $specification['order_id'],
            );
        }

        return new self(
            merchantOrderId: $paymentDTO->paymentInfo->billingItem->getIdentifier(),
            integrationId: $paymentDTO->paymentSettings->business->paymentServiceIntegrationKey->integration_id
        );
    }
}
