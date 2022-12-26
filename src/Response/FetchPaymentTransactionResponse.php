<?php

declare(strict_types=1);

namespace Zeal\Paymob\Response;

use Illuminate\Http\Client\Response;
use Zeal\Paymob\Exceptions\InvalidPaymentException;
use Zeal\Paymob\Exceptions\UnauthenticatedException;

final class FetchPaymentTransactionResponse
{
    /**
     * Hold encoded guzzle response
     *
     * @var object
     */
    private $response;

    /**
     * Holds guzzle response decoded body
     *
     * @var object
     */
    private $body;

    /**
     * flag is response failed or not
     *
     * @var bool
     */
    private $failed = false;

    /**
     * flag is response timed out or not
     *
     * @var bool
     */
    private $timedout = false;

    /**
     * flag is response timed out or not
     *
     * @var bool
     */
    private $requestFailed = false;

    public function __construct(Response $response)
    {
        $this->response = $response;

        $this->body = (object)json_decode((string)$response->getBody());

        $this->handleResponseExceptions();
    }

    /**
     * Getter for failed flag
     */
    public function failed(): bool
    {
        return $this->failed;
    }

    /**
     * Getter for timeout flag
     */
    public function timedout(): bool
    {
        return $this->timedout;
    }

    /**
     * Getter for requestFailed flag
     */
    public function requestFailed(): bool
    {
        return $this->requestFailed;
    }

    /**
     * Returns response status code
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Return card token
     */
    public function getPaymentKeyToken(): string
    {
        return $this->body->token;
    }

    private function handleResponseExceptions(): void
    {
        // Hot patch
        if (!(property_exists($this->body, 'success') && $this->body->success === true)) {
            $this->failed = true;
        }
    }

    public function getTransactionId()
    {
        return ($this->body) ? ($this->body->id ?? $this->body) : $this->body;
    }

    public function getOrderReference()
    {
        return ($this->body) ?? $this->body->order ?? $this->body->order;
    }
}
