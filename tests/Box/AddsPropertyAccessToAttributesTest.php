<?php

namespace Pbox\Box;

use Pbox\Exception\UndefinedPropertyException;
use PHPUnit\Framework\TestCase;

class AddsPropertyAccessToAttributesTest extends TestCase
{
    public function testGetPropertyWithoutAccessor()
    {
        $mock = new class extends Mock
        {
            protected $attributes = ['prop' => 'default'];
        };
        $this->assertSame('default', $mock->prop);
    }

    public function testSetPropertyWithoutAccessor()
    {
        $mock = new class extends Mock
        {
            protected $attributes = ['prop' => null];
        };
        $mock->prop = 'from outside';
        $this->assertSame('from outside', $mock->prop);
    }

    public function testCallGetterWhenGetProperty()
    {
        $mock = new class extends Mock
        {
            function getPropAttribute($value)
            {
                return "$value from getter";
            }
        };
        $mock->prop = 'val';
        $this->assertSame('val from getter', $mock->prop);

    }

    public function testCallSetterWhenSetProperty()
    {
        $mock = new class extends Mock
        {
            function setPropAttribute($value)
            {
                return "$value from setter";
            }
        };
        $mock->prop = 'val';
        $this->assertSame('val from setter', $mock->prop);
    }

    public function testSettingValueWithoutSetterThrowsExceptionWhenHasAttributeReturnsFalse()
    {
        $mock = new class extends Mock
        {
            function hasAttribute(string $name): bool
            {
                return false;
            }
        };

        $this->expectException(UndefinedPropertyException::class);
        $mock->prop = 'val';
    }

    public function testGettingValueWithoutGetterThrowsExceptionWhenHasAttributeReturnsFalse()
    {
        $mock = new class extends Mock
        {
            function hasAttribute(string $name): bool
            {
                return false;
            }
        };
        $this->expectException(UndefinedPropertyException::class);
        $mock->prop;
    }

    public function testSettingValueWithSetterThrowsExceptionWhenHasAttributeReturnsFalse()
    {
        $mock = new class extends Mock
        {
            function hasAttribute(string $name): bool
            {
                return false;
            }

            function setPropAttribute($value)
            {
                return "$value from setter";
            }
        };

        $this->expectException(UndefinedPropertyException::class);
        $mock->prop = 'val';
    }

    public function testGettingValueWithGetterThrowsExceptionWhenHasAttributeReturnsFalse()
    {
        $mock = new class extends Mock
        {
            function hasAttribute(string $name): bool
            {
                return false;
            }

            function getPropAttribute($value)
            {
                return "$value from getter";
            }
        };
        $this->expectException(UndefinedPropertyException::class);
        $mock->prop;
    }
}

class Mock
{
    use AddsPropertyAccessToAttributes;

    protected $attributes = ['prop' => null];

    function attribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    function attributes(): array
    {
        throw new \LogicException('Not use this method in test.');
    }

    function setAttribute(string $name, $value)
    {
        return $this->attributes[$name] = $value;
    }

    function setAttributes(array $attributes)
    {
        throw new \LogicException('Not use this method in test.');
    }

    function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }
}
