    <?php

declare(strict_types = 1);

namespace Zeal\Paymob\Response;

use GuzzleHttp\Psr7\Response;

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
     * @param Response $response json string response body
     */
    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
        $this->context = $this->exception->getHandlerContext();

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
        if (isset($this->context['errno']) && $this->context['errno'] = 28) {
            $this->timedout = true;
        }
    }
}
