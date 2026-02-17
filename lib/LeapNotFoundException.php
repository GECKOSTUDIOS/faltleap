<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapNotFoundException extends LeapHttpException
{
    public function __construct(string $message = 'Not Found')
    {
        parent::__construct($message, 404);
    }
}
