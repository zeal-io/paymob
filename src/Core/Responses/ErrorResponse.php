<?php

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Resources\MissingValue;

class ErrorResponse extends \Zeal\PaymentFramework\Responses\ErrorResponse
{
    public function toResponseObject(): self
    {
        return $this
            ->setResponseMessage($this->responseBody['detail'] ?? null)
            ->setDetailedResponseMessage($this->responseBody['detail'] ?? null)
            ->setStatusCode($this->responseBody['status_code'] ?? $this->httpStatus())
            ->setDetailedStatusCode($this->responseBody['code'] ?? null);
    }

    public function toArray(): array
    {
        return [
            'status' => $this->statusCode,
            'detailed_status' => $this->detailedStatusCode,
            'message' => $this->responseMessage ?? new MissingValue(),
            'detailed_message' => $this->detailedResponseMessage ?? new MissingValue(),
        ];
    }
}
