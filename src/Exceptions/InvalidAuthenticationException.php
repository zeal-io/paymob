<?php

namespace Zeal\Paymob\Exceptions;

class InvalidAuthenticationException extends \Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
