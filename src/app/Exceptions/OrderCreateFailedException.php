<?php

namespace App\Exceptions;

use Exception;

class OrderCreateFailedException extends Exception
{
    public function __construct(string $message = "Failed to create the order.", int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
