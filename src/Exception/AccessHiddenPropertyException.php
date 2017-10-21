<?php

namespace Pbox\Exception;

use OutOfRangeException;
use Throwable;

class AccessHiddenPropertyException extends OutOfRangeException
{
    public function __construct(string $propertyName, int $code = 0, Throwable $previous = null)
    {
        $message = "Cannot access hidden property: $propertyName";
        parent::__construct($message, $code, $previous);
    }
}