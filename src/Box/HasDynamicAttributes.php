<?php

namespace Pbox\Box;

use Pbox\Exception\UndefinedAttributeException;

/**
 * Trait HasDynamicAttributes
 *
 * Must define property $attributes as array.
 * example:
 *     protected $attributes = ['name' => 'initial value']
 *
 * @package Pbox\Box
 */
trait HasDynamicAttributes
{
    use RequiresAttributesAccessor;

    /**
     * {@inheritdoc}
     */
    public function attribute(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        throw new UndefinedAttributeException($name);
    }

    /**
     * {@inheritdoc}
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }
}