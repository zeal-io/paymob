<?php

declare(strict_types=1);

namespace Zeal\Paymob\Response;

use Zeal\Paymob\Exceptions\InvalidAuthenticationException;

final class AuthenticationResponse
{
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

    public function __construct($response)
    {
        $this->response = $response;

        $this->body =(object)json_decode((string) $response->getBody());

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
     * Returns response status code
     */
    public function getStatusCode(): string
    {
        return $this->response->getStatusCode();
    }

    /**
     * Return card token
     */
    public function getAuthToken(): string
    {
        return $this->body->token;
    }

    private function handleResponseExceptions(): void
    {
        if ($this->response->getStatusCode() !== 201) {
            $this->failed = true;
            throw new InvalidAuthenticationException(
                json_encode($this->body),
                $this->response->getStatusCode()
            );
        }
    }
}
