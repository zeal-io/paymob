<?php

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Enums\ResponseStatusEnum;
use Zeal\PaymentFramework\Responses\BasePaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidPaymentKeyException;
use Zeal\Paymob\Core\Exceptions\UnauthenticatedException;

class PaymentKeyResponse extends BasePaymobResponse
{
    private string $paymentKeyToken;

    public function toResponseObject(): BasePaymentResponse
    {
        return $this->setPaymentKeyToken($this->responseBody['token']);
    }

    public function toArray(): array
    {
        return [
            'token' => $this->paymentKeyToken,
        ];
    }

    public function setStatus(): BasePaymentResponse
    {
        $this->status = $this->hasErrors ? ResponseStatusEnum::SUCCESS : ResponseStatusEnum::FAILED;

        return $this;
    }

    private function setPaymentKeyToken(string $token): static
    {
        $this->paymentKeyToken = $token;

        return $this;
    }

    public function getPaymentKeyToken(): string
    {
        return $this->paymentKeyToken;
    }
}
