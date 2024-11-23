<?php

namespace App\Exception;

use RuntimeException;

class AuthorizeException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}