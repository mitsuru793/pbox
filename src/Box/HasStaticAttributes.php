<?php

namespace Pbox\Box;

use Pbox\Exception\AccessHiddenPropertyException;
use Pbox\Exception\UndefinedPropertyException;

trait HasStaticAttributes
{
    use RequiresAttributesAccessor;

    /**
     * {@inheritdoc}
     */
    function attribute(string $name)
    {
        if ($this->isHiddenProperty($name)) {
            throw new AccessHiddenPropertyException($name);
        }

        $allProps = get_object_vars($this);
        if (array_key_exists($name, $allProps)) {
            return $allProps[$name];
        }

        throw new UndefinedPropertyException($name);
    }

    /**
     * {@inheritdoc}
     */
    public function attributes(): array
    {
        $allProps = get_object_vars($this);

        $publicProps = [];
        foreach ($allProps as $name => $value) {
            if ($this->isHiddenProperty($name)) {
                throw new AccessHiddenPropertyException($name);
            }
            $publicProps[$name] = $value;
        }
        return $publicProps;
    }

    public function setAttribute(string $name, $value)
    {
        if ($this->isHiddenProperty($name)) {
            throw new AccessHiddenPropertyException($name);
        }
        if (!property_exists($this, $name)) {
            throw new UndefinedPropertyException($name);
        }
        $this->$name = $value;
        return $this;
    }

    function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            if ($this->isHiddenProperty($name)) {
                throw new AccessHiddenPropertyException($name);
            }
            if (!property_exists($this, $name)) {
                throw new UndefinedPropertyException($name);
            }
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * Checks if the name is hidden.
     * You can distinguish hidden properties even in not public one, so protected and private one.
     *
     * @param string $name property name
     * @return bool
     */
    abstract function isHiddenProperty(string $name): bool;
}
