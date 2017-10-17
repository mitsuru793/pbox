<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class AddsPropertyAccessToStaticAttributesTest extends TestCase
{
    public function testSetAndGetPropertyWithoutAccessor()
    {
        $object = new class extends MockAddsPropertyAccessToStaticAttributesBase
        {
            protected $prop;
        };
        $object->prop = 'from outside';
        $this->assertSame('from outside', $object->prop);
    }

    public function testCallSetterWhenSetProperty()
    {
        $object = new class extends MockAddsPropertyAccessToStaticAttributesBase
        {
            protected $prop;

            public function setPropAttribute($value)
            {
                $this->prop = "[$value]";
            }
        };
        $object->prop = 'from outside';
        $this->assertSame('[from outside]', $object->prop);
    }

    public function testCallGetterWhenSetProperty()
    {
        $object = new class extends MockAddsPropertyAccessToStaticAttributesBase
        {
            protected $prop;

            public function getPropAttribute($value)
            {
                return "[$value]";
            }
        };
        $object->prop = 'from outside';
        $this->assertSame('[from outside]', $object->prop);
    }

    public function testNotCallAccessorWhenAccessPublicProperty()
    {
        $object = new class extends MockAddsPropertyAccessToStaticAttributesBase
        {
            public $prop;

            public function getPropAttribute($value)
            {
                return "[$value]";
            }

            public function setPropAttribute($value)
            {
                return "[$value]";
            }
        };
        $object->prop = 'from outside';
        $this->assertSame('from outside', $object->prop);
    }
}

class MockAddsPropertyAccessToStaticAttributesBase
{
    use AddsPropertyAccessToStaticAttributes;

    public function attributes(): array
    {
        return get_object_vars($this);
    }
}

class MockAddsPropertyAccessToStaticAttributes extends MockAddsPropertyAccessToStaticAttributesBase
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
