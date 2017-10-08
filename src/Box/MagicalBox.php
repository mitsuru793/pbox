<?php

namespace Pbox\Box;

class MagicalBox extends Box
{
    use MagicalAccessorTrait;

    protected $attributes = [];

    public function attributes(): array
    {
        return $this->attributes;
    }
}
