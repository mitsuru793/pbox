<?php

namespace Pbox\Box;

use OutOfRangeException;

class MagicalHardBox extends Box
{
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->attributes())) {
            $getter = "get$name";
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
            $setter = "set$name";
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

    public function attributes(): array
    {
        $allProps = get_object_vars($this);
        $metaProps = $this->metaAttributes();

        $publicProps = [];
        foreach ($allProps as $prop => $value) {
            if (!isset($metaProps[$prop])) {
                $publicProps[$prop] = $value;
            }
        }
        return $publicProps;
    }
}
