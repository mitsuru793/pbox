<?php

namespace Pbox\Exception;

use OutOfRangeException;
use Throwable;

class UndefinedAttributeException extends OutOfRangeException
{
    public function __construct(string $propertyName, int $code = 0, Throwable $previous = null)
    {
        $message = "Attributes don't has key: $propertyName";
        parent::__construct($message, $code, $previous);
    }
}