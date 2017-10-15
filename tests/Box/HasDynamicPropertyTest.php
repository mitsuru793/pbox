<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class HasDynamicPropertyHardCodedTest extends TestCase
{
    public function testSetAndGetPropertyWithoutAccessor()
    {
        $object = new class extends MockHasDynamicPropertyHardCodedBase
        {
            protected $prop;
        };
        $object->prop = 'from outside';
        $this->assertSame('from outside', $object->prop);
    }

    public function testCallSetterWhenSetProperty()
    {
        $object = new class extends MockHasDynamicPropertyHardCodedBase
        {
            protected $prop;

            public function setPropAttribute($value)
            {
                $this->prop = 'from setter';
            }
        };
        $object->prop = 'from outside';
        $this->assertSame('from setter', $object->prop);
    }

    public function testCallGetterWhenSetProperty()
    {
        $object = new class extends MockHasDynamicPropertyHardCodedBase
        {
            protected $prop;

            public function getPropAttribute($value)
            {
                return 'from setter';
            }
        };
        $object->prop = 'from outside';
        $this->assertSame('from setter', $object->prop);
    }

    public function testNotCallAccessorWhenAccessPublicProperty()
    {
        $object = new class extends MockHasDynamicPropertyHardCodedBase
        {
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
        $object->prop = 'from outside';
        $this->assertSame('from outside', $object->prop);
    }

    public function testIsset()
    {
        $object = new MockHasDynamicPropertyHardCoded;
        $this->assertTrue(isset($object->pubProp));
        $this->assertTrue(isset($object->proProp));
        $this->assertTrue(isset($object->priProp));
        $this->assertFalse(isset($object->invalid));
    }
}

class MockHasDynamicPropertyHardCodedBase
{
    use HasDynamicPropertyHardCoded;

    public function attributes(): array
    {
        return get_object_vars($this);
    }
}

class MockHasDynamicPropertyHardCoded extends MockHasDynamicPropertyHardCodedBase
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
