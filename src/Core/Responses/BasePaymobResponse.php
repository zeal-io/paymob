<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Responses\BasePaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidAuthenticationException;

abstract class BasePaymobResponse extends BasePaymentResponse
{
    public function initializeErrorResponse(): static
    {
        $this->errorResponse = ErrorResponse::make();

        return $this;
    }

    public function validateErrors(): static
    {
        if ($this->response->status() === 422 || $this->response->status() === 400) {
            $this->hasErrors = true;
            $this->errorResponse
                ->setResponse($this->response)
                ->setResponseMessage('Invalid Payment Key');

            return $this;
        }
        if ($this->response->status() === 401) {
            $this->hasErrors = true;
            $this->errorResponse
                ->setResponse($this->response)
                ->setResponseMessage('UnAuthentication');

            return $this;
        }

        return $this;
    }
}
