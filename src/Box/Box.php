<?php

namespace Pbox\Box;

abstract class Box
{
    use ConvertsFormat;

    public function metaAttributes(): array
    {
        return get_class_vars(__CLASS__);
    }

    abstract public function attributes(): array;
}
