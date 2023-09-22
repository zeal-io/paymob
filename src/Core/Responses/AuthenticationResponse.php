<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Responses\BasePaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidAuthenticationException;

final class AuthenticationResponse extends BasePaymobResponse
{
    private string $token;

    public function validateErrors(): BasePaymentResponse
    {
        if ($this->response->status() !== 201) {
            $this->hasErrors = true;
        }

        return $this;
    }

    public function toResponseObject(): BasePaymentResponse
    {
        return $this->setToken();
    }

    public function toArray(): array
    {
        return [];
    }


    public function setStatus(): BasePaymentResponse
    {
        // TODO: Implement setStatus() method.
    }

    private function setToken(): self
    {
        $this->token = $this->responseBody['token'];

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
