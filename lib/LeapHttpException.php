<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapHttpException extends \RuntimeException
{
    public int $statusCode;

    public function __construct(string $message = 'Internal Server Error', int $statusCode = 500)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }
}
