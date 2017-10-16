<?php

namespace Pbox\Box;

trait RequiresAttributesAccessor
{
    use RequiresAttributesGetter;

    /**
     * @param string $name
     * @param mixed $value
     * @return object receiver
     */
    abstract function setAttribute(string $name, $value);

    /**
     * @param array $attributes
     * @return object receiver
     */
    abstract function setAttributes(array $attributes);
}