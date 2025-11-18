<?php

declare(strict_types=1);

namespace Zeal\Paymob\Response;

use Illuminate\Http\Client\Response;

final class ConnectExceptionResponse
{
    /**
     * Hold Guzzle exception
     *
     * @var object
     */
    private $exception;

    /**
     * Hold Guzzle context
     *
     * @var object
     */
    private $context;

    /**
     * Holds guzzle response decoded body
     *
     * @var object
     */
    private $body;

    /**
     * flag is transaction failed or not
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
    private $requestFailed = true;

    /**
     * Parses guzzle response body
     *
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
        
        // Only Guzzle exceptions have getHandlerContext() method
        // Check if this is a connection/request exception (Guzzle exception)
        if (method_exists($exception, 'getHandlerContext')) {
            /** @var \GuzzleHttp\Exception\RequestException $exception */
            $this->context = $exception->getHandlerContext();
        } else {
            // Not a Guzzle exception - no handler context available
            $this->context = [];
            $this->failed = true;
        }

        $this->handleException();
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

    private function handleException(): void
    {
        // Check for timeout error (errno 28 = ETIMEDOUT)
        if (isset($this->context['errno']) && $this->context['errno'] == 28) {
            $this->timedout = true;
            $this->failed = true;
        }
    }
}
