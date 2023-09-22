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
}
