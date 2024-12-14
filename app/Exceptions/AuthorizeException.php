<?php

namespace App\Exceptions;

use RuntimeException;

class AuthorizeException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 200)
    {
        parent::__construct($message, $code);
    }
}