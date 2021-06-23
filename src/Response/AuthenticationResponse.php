<?php

declare(strict_types=1);

namespace Zeal\Paymob\Response;

use Zeal\Paymob\Exceptions\InvalidAuthenticationException;

final class AuthenticationResponse
{
    /**
     * Hold json decoded guzzle response
     *
     * @var object
     */
    private $response;

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


        if ($this->response->getStatusCode() != 201) {
            $this->failed = true;
            throw new InvalidAuthenticationException($this->body->detail, $this->response->getStatusCode());
        }
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
    public function getAuthToken()
    {
        return $this->body->token;
    }
}
