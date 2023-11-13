<?php

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Enums\ResponseStatusEnum;
use Zeal\PaymentFramework\Responses\PaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidPaymentKeyException;
use Zeal\Paymob\Core\Exceptions\UnauthenticatedException;

class PaymentKeyResponse extends PaymobResponse
{
    private string $paymentKeyToken;

    public function toResponseObject(): PaymentResponse
    {
        return $this->setPaymentKeyToken($this->responseBody['token']);
    }

    public function toArray(): array
    {
        return [
            'token' => $this->paymentKeyToken,
        ];
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
