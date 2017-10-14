<?php

namespace Pbox\Box;

trait HasDynamicAttributes
{
    protected $attributes = [];

    public function attributes(): array
    {
        return $this->attributes;
    }
}