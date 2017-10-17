<?php

namespace Pbox\Box;

use Pbox\Exception\AccessStaticPropertyException;
use Pbox\Exception\UndefinedPropertyException;

trait AddsPropertyAccessToAttributes
{
    use RequiresAttributesAccessor;

    public function __get(string $name)
    {
        if ($this->hasAttribute($name)) {
            $getter = "get{$name}Attribute";
            $value = $this->attribute($name);
            return method_exists($this, $getter) ? $this->$getter($value) : $value;
        }
        if (property_exists($this, $name)) {
            throw new AccessStaticPropertyException($name);
        }
        throw new UndefinedPropertyException($name);
    }

    public function __set(string $name, $value)
    {
        if ($this->hasAttribute($name)) {
            $setter = "set{$name}Attribute";
            if (method_exists($this, $setter)) {
                $value = $this->$setter($value);
            }
            $this->setAttribute($name, $value);
            return;
        }
        if (property_exists($this, $name)) {
            throw new AccessStaticPropertyException($name);
        }
        throw new UndefinedPropertyException($name);
    }

    /**
     * If this returns false, you will get a exception when access a property
     *
     * @param string $name
     * @return bool
     */
    abstract function hasAttribute(string $name): bool;
}