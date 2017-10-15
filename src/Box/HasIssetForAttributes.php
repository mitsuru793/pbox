<?php

namespace Pbox\Box;

trait HasIssetForAttributes
{
    public function __isset($name): bool
    {
        return isset($this->attributes()[$name]);
    }

    abstract public function attributes(): array;
}