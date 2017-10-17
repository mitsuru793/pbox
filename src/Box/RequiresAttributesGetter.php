<?php

namespace Pbox\Box;

trait RequiresAttributesGetter
{
    /**
     * @param $name
     * @return mixed
     */
    abstract function attribute(string $name);

    /**
     * @return array of attributes
     */
    abstract function attributes(): array;

}