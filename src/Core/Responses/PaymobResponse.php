<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Enums\ResponseStatusEnum;
use Zeal\PaymentFramework\Responses\PaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidAuthenticationException;

abstract class PaymobResponse extends PaymentResponse
{
    protected function setStatus(): static
    {
        $this->status = match (true) {
            200 >= $this->response->status() && $this->response->status() < 300 => ResponseStatusEnum::SUCCESS,
            400 >= $this->response->status() && $this->response->status() < 600 => ResponseStatusEnum::FAILED,
        };
        return $this;
    }

    public function errorResponse(): ErrorResponse
    {
        return ErrorResponse::make($this->response);
    }

    public function validateErrors(): static
    {
        if ($this->response->status() === 422 || $this->response->status() === 400) {
            $this->errorResponse()->setResponseMessage('Invalid Payment Key');

            return $this;
        }
        if ($this->response->status() === 401) {
            $this->errorResponse()->setResponseMessage('UnAuthenticated');

            return $this;
        }

        return $this;
    }
}
