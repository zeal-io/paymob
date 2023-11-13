<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Enums\ResponseStatusEnum;
use Zeal\PaymentFramework\Responses\PaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidAuthenticationException;

class AuthenticationResponse extends PaymobResponse
{
    private string $token;

    public function toResponseObject(): PaymentResponse
    {
        return $this->setToken();
    }

    public function toArray(): array
    {
        return [];
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
