<?php

namespace Pbox\Box;

trait RequiresAttributesImmutableAccessor
{
    use RequiresAttributesGetter;

    /**
     * @param string $name
     * @param mixed $value
     * @return object receiver
     */
    abstract function withAttribute(string $name, $value);

    /**
     * @param array $attributes
     * @return object receiver
     */
    abstract function withAttributes(array $attributes);
}