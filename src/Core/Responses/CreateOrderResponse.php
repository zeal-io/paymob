<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Zeal\PaymentFramework\Enums\ResponseStatusEnum;
use Zeal\PaymentFramework\Responses\BasePaymentResponse;

class CreateOrderResponse extends BasePaymobResponse
{
    private string $id;

    public function toResponseObject(): BasePaymentResponse
    {
        return $this->setOrderId($this->responseBody['id']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function setStatus(): BasePaymentResponse
    {
        $this->status = $this->hasErrors ? ResponseStatusEnum::SUCCESS : ResponseStatusEnum::FAILED;

        return $this;
    }

    private function setOrderId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
