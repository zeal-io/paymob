<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Zeal\PaymentFramework\Enums\ResponseStatusEnum;
use Zeal\PaymentFramework\Responses\PaymentResponse;

class CreateOrderResponse extends PaymobResponse
{
    private string $id;

    public function toResponseObject(): PaymentResponse
    {
        return $this->setOrderId($this->responseBody['id']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
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