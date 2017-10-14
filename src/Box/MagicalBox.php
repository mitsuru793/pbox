<?php

namespace Pbox\Box;

class MagicalBox
{
    use MagicalAccessorTrait;

    protected $attributes = [];

    public function attributes(): array
    {
        return $this->attributes;
    }
}
