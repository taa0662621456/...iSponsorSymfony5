<?php

namespace App\Exception;

class ResourceNotFoundException extends \RuntimeException
{
    public function __construct(string $message = null, \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct($message, $previous, $code, $headers);
    }
}