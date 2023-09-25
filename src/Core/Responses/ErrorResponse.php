<?php

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Resources\MissingValue;

class ErrorResponse extends \Zeal\PaymentFramework\Responses\ErrorResponse
{

    public function toResponseObject(): self
    {
        return $this
            ->setResponseMessage($this->responseBody['responseMessage'] ?? null)
            ->setDetailedResponseMessage($this->responseBody['detailedResponseMessage'] ?? null)
            ->setStatusCode($this->responseBody['responseCode'])
            ->setDetailedStatusCode($this->responseBody['detailedResponseCode']);
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
