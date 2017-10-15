<?php

namespace Pbox\Box;

trait AddsIssetToAttributes
{
    public function __isset($name): bool
    {
        return isset($this->attributes()[$name]);
    }

    abstract public function attributes(): array;
}