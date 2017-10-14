<?php

namespace Pbox\Box;

abstract class Box
{
    use ConvertsFormat;

    abstract public function attributes(): array;
}
