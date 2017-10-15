<?php

namespace Pbox\Box;

use Pbox\Exception\AccessStaticPropertyException;
use Pbox\Exception\UndefinedPropertyException;

/**
 * Trait AddsPropertyAccessToDynamicAttributes
 *
 * Must define property $attributes as array.
 * example:
 *     protected $attributes = ['name' => 'initial value']
 *
 * @package Pbox\Box
 */
trait AddsPropertyAccessToDynamicAttributes
{
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            $getter = "get{$name}Attribute";
            $value = $this->attributes[$name];
            return method_exists($this, $getter) ? $this->$getter($value) : $value;
        }
        if (property_exists($this, $name)) {
            throw new AccessStaticPropertyException($name);
        }
        throw new UndefinedPropertyException($name);
    }

    public function __set(string $name, $value)
    {
        if (array_key_exists($name, $this->attributes)) {
            $setter = "set{$name}Attribute";
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                $this->attributes[$name] = $value;
            }
            return;
        }
        if (property_exists($this, $name)) {
            throw new AccessStaticPropertyException($name);
        }
        throw new UndefinedPropertyException($name);
    }

    public function __isset($name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function attributes(): array
    {
        return $this->attributes;
    }
}