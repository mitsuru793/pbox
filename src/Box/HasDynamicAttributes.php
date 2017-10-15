<?php

namespace Pbox\Box;

trait HasDynamicAttributes
{
    /** @var array */
    protected $attributes = [];

    public function attributes(): array
    {
        return $this->attributes;
    }
}