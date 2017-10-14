<?php

namespace Pbox\Box;

trait HasAttributesOfArray
{
    /** @var array */
    protected $attributes = [];

    public function attributes(): array
    {
        return $this->attributes;
    }
}