<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class AddsPropertyAccessToDynamicAttributesTest extends TestCase
{
    public function testSetAndGetPropertyWithoutAccessor()
    {
        $mock = new class
        {
            use AddsPropertyAccessToDynamicAttributes;
            private $attributes = ['prop' => null];
        };
        $mock->prop = 'from outside';
        $this->assertSame('from outside', $mock->prop);
    }

    public function testCallSetterWhenSetProperty()
    {
        $mock = new class
        {
            use AddsPropertyAccessToDynamicAttributes;
            private $attributes = ['prop' => null];

            public function setPropAttribute($value)
            {
                $this->prop = 'from setter';
            }
        };
        $mock->prop = 'from outside';
        $this->assertSame('from setter', $mock->prop);
    }

    public function testCallGetterWhenSetProperty()
    {
        $mock = new class
        {
            use AddsPropertyAccessToDynamicAttributes;
            private $attributes = ['prop' => null];

            public function getPropAttribute($value)
            {
                return 'from setter';
            }
        };
        $mock->prop = 'from outside';
        $this->assertSame('from setter', $mock->prop);
    }

    public function testNotCallAccessorWhenAccessPublicProperty()
    {
        $mock = new class
        {
            use AddsPropertyAccessToDynamicAttributes;
            public $prop;

            public function getPropAttribute($value)
            {
                return 'from getter';
            }

            public function setPropAttribute($value)
            {
                return 'from setter';
            }
        };
        $mock->prop = 'from outside';
        $this->assertSame('from outside', $mock->prop);
    }

    public function testAttributesGetter()
    {
        $mock = new class
        {
            use AddsPropertyAccessToDynamicAttributes;
            private $attributes = ['p1' => 'v1', 'p2' => 'v2'];
        };
        $expected = ['p1' => 'v1', 'p2' => 'v2'];
        $this->assertSame($expected, $mock->attributes());
    }

    public function testIsset()
    {
        $mock = new class
        {
            use AddsPropertyAccessToDynamicAttributes;
            private $attributes = ['p1' => 'v1', 'p2' => null];
        };
        $this->assertTrue(isset($mock->p1));
        $this->assertFalse(isset($mock->p2));
        $this->assertFalse(isset($mock->invalid));
    }
}
