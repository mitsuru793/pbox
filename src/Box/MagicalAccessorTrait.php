<?php

namespace Pbox\Box;

use OutOfRangeException;

trait MagicalAccessorTrait
{
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->attributes())) {
            $getter = "get{$name}Attribute";
            $value = $this->$name;
            return method_exists($this, $getter) ? $this->$getter($value) : $value;
        }
        if (property_exists($this, $name)) {
            throw new OutOfRangeException("Cannot access static property: $name");
        }
        throw new OutOfRangeException("Undefined property: $name");
    }

    public function __set(string $name, $value)
    {
        if (array_key_exists($name, $this->attributes())) {
            $setter = "set{$name}Attribute";
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                $this->$name = $value;
            }
            return;
        }
        if (property_exists($this, $name)) {
            throw new OutOfRangeException("Cannot access static property: $name");
        }
        throw new OutOfRangeException("Undefined property: $name");
    }

    public function __isset($name): bool
    {
        return isset($this->$name);
    }

    abstract public function attributes(): array;
}