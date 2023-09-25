<?php

declare(strict_types=1);

namespace Zeal\Paymob\Core\Responses;

use Illuminate\Http\Client\Response;
use Zeal\PaymentFramework\Responses\PaymentResponse;
use Zeal\Paymob\Core\Exceptions\InvalidPaymentException;
use Zeal\Paymob\Core\Exceptions\UnauthenticatedException;

class TransactionResponse extends PaymobResponse
{
    private string $transactionId;
    private string $orderReference;

    public function toResponseObject(): PaymentResponse
    {
        return $this
            ->setTransactionId()
            ->setOrderReference();
    }

    public function toArray(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'order_reference' => $this->orderReference,
        ];
    }

    private function setTransactionId(): self
    {
        $this->transactionId = $this->responseBody['id'];

        return $this;
    }

    public function setOrderReference(): self
    {
        $this->orderReference = $this->responseBody['order'];
        return $this;
    }
}