<?php

namespace Pbox\Box;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class HasAttributesOfArrayTest extends TestCase
{
    public function testAttributesReturnsProperty()
    {
        $attributes = [
            'prop1' => 'value1',
            'prop2' => 'value2',
        ];

        $double = m::mock(HasAttributesOfArray::class);
        $this->setProp($double, 'attributes', $attributes);
        $this->assertSame($attributes, $double->attributes());
    }

    private function setProp($object, string $prop, $value)
    {
        $reflectionObject = new ReflectionClass($object);
        $reflectionProperty = $reflectionObject->getProperty($prop);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }
}
