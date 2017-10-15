<?php

namespace Pbox\Exception;

use OutOfRangeException;
use Throwable;

class UndefinedPropertyException extends OutOfRangeException
{
    public function __construct(string $propertyName, int $code = 0, Throwable $previous = null)
    {
        $message = "Undefined property: $propertyName";
        parent::__construct($message, $code, $previous);
    }
}