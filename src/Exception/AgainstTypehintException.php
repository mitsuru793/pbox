<?php

namespace Pbox\Exception;

use InvalidArgumentException;
use Throwable;

class AgainstTypehintException extends InvalidArgumentException
{
    public function __construct(string $typeName, string $argumentName, int $code = 0, Throwable $previous = null)
    {
        $message = "Type must be $typeName: $argumentName";
        parent::__construct($message, $code, $previous);
    }
}