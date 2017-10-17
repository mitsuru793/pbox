<?php

namespace Pbox\Box;

trait RequiresAttributesGetter
{
    /**
     * Returns an exposed property to outside.
     * You can expose a property to outside even if they are not public, so protected or private.
     *
     * @param string $name attribute name
     * @return mixed attribute
     */
    abstract function attribute(string $name);

    /**
     * Returns array of exposed properties to outside.
     * You can expose properties to outside even if they are not public, so protected or private.
     *
     * @return array attributes
     */
    abstract function attributes(): array;

}