<?php

declare(strict_types=1);

namespace Zeal\Paymob\Response;

use Zeal\Paymob\Exceptions\InvalidOrderException;
use Zeal\Paymob\Exceptions\UnauthenticatedException;

final class CreateOrderResponse
{
    /**
     * Hold encoded guzzle response
     *
     * @var object
     */
    private $response;

    /**
     * Holds guzzle response decoded body
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
     * Parses guzzle response body
     *
     * @param string $response json string response body
     */
    public function __construct($response)
    {
        $this->response = $response;

        $this->body = json_decode((string) $response->getBody());

        $this->handleResponseExceptions();
    }

    /**
     * Getter for failed flag
     *
     * @return bool
     */
    public function failed(): bool
    {
        return $this->failed;
    }

    /**
     * Returns response status code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Return card token
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->body->id;
    }

    private function handleResponseExceptions()
    {
        switch ($this->response->getStatusCode()) {
            case '422':
                $this->failed = true;
                throw new InvalidOrderException($this->body->message, $this->response->getStatusCode());
                break;
            case '400':
                throw new InvalidOrderException(json_encode($this->body), $this->response->getStatusCode());
                break;
            case '401':
                $this->failed = true;
                throw new UnauthenticatedException($this->body->detail, $this->response->getStatusCode());
            default:
                break;
        }
    }
}
