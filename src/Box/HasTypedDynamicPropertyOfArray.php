<?php

namespace Pbox\Box;

use InvalidArgumentException;
use OutOfRangeException;
use Pbox\Exception\AccessStaticPropertyException;
use Pbox\Exception\AgainstTypehintException;
use Pbox\Exception\UndefinedPropertyException;

/**
 * Trait HasTypedDynamicPropertyOfArray
 *
 * Must define property $attributes as array.
 * The value of array is typehint.
 * example:
 *     protected $attributes = ['name' => 'string']
 *
 * You can use closure as typehint.
 * example:
 *     protected $attributes = [
 *         'startWithAtmark' => function ($value) {
 *             return is_string($value) && preg_match('~^@~', $value);
 *          }
 *     ];
 *
 * @package Pbox\Box
 */
trait HasTypedDynamicPropertyOfArray
{
    use HasDynamicPropertyOfArray;

    /**
     * @param string $name
     * @param $value
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     */
    public function __set(string $name, $value)
    {
        if (array_key_exists($name, $this->typehints)) {
            $setter = "set{$name}Attribute";
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                try {
                    $this->validTypehint($name, $value);
                } catch (InvalidArgumentException $e) {
                    throw $e;
                }
                $this->attributes[$name] = $value;
            }
            return;
        }
        if (property_exists($this, $name)) {
            throw new AccessStaticPropertyException($name);
        }
        throw new UndefinedPropertyException($name);
    }

    /**
     * @param string $name attribute name
     * @param mixed $value attribute value
     * @throws InvalidArgumentException
     */
    protected function validTypehint(string $name, $value): void
    {
        $typeName = $this->typehints[$name];
        $func = $this->typehints($typeName);
        if (is_callable($func)) {
            if (!$func($value)) {
                throw new AgainstTypehintException($typeName, $name);
            }
        } else {
            // $func is class name.
            if (!($value instanceof $func)) {
                throw new AgainstTypehintException($func, $name);
            }
        }
    }

    /**
     * @param string $typeName
     * @return string|callable
     */
    protected function typehints(string $typeName)
    {
        $typehints = [
            'array' => 'is_array',
            'callable' => 'is_callable',
            'bool' => 'is_bool',
            'float' => 'is_float',
            'int' => 'is_int',
            'string' => 'is_string',
            'iterable' => 'is_iterable',
        ];
        return $typehints[$typeName];
    }
}