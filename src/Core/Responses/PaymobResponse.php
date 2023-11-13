<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Enums\ResponseStatusEnum;
use Zeal\PaymentFramework\Responses\PaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidAuthenticationException;

abstract class PaymobResponse extends PaymentResponse
{
    public function setErrorResponse(): ErrorResponse
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

    public function getGatewayOrderId(): ?int
    {
        return $this->responseBody['id'] ?? $this->responseBody['obj']['id'];
    }
}
