<?php

namespace Pbox\Box;

trait HasAttributesOfArray
{
    protected $attributes = [];

    public function attributes(): array
    {
        return $this->attributes;
    }
}