<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class MagicalHardBoxTest extends TestCase
{
    public function testSetAndGetPropertyWithoutAccessor()
    {
        $box = new class extends MagicalHardBox
        {
            protected $prop;
        };
        $box->prop = 'from outside';
        $this->assertSame('from outside', $box->prop);
    }

    public function testCallSetterWhenSetProperty()
    {
        $box = new class extends MagicalHardBox
        {
            protected $prop;

            public function setProp($value)
            {
                $this->prop = 'from setter';
            }
        };
        $box->prop = 'from outside';
        $this->assertSame('from setter', $box->prop);
    }

    public function testCallGetterWhenSetProperty()
    {
        $box = new class extends MagicalHardBox
        {
            protected $prop;

            public function getProp($value)
            {
                return 'from setter';
            }
        };
        $box->prop = 'from outside';
        $this->assertSame('from setter', $box->prop);
    }

    public function testNotCallAccessorWhenAccessPublicProperty()
    {
        $box = new class extends MagicalHardBox
        {
            public $prop;

            public function getProp($value)
            {
                return 'from getter';
            }

            public function setProp($value)
            {
                return 'from setter';
            }
        };
        $box->prop = 'from outside';
        $this->assertSame('from outside', $box->prop);
    }

    private function assertSameConvert(array $expectedArray, MagicalHardBox $box)
    {
        $this->assertSame($expectedArray, $box->toArray());
        $this->assertSame(json_encode($expectedArray), $box->toJson());
    }

    public function testAttributes()
    {
        $box = new MockMagicalHardBox;
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
        ];
        $this->assertSame($expected, $box->attributes());
    }
}

class MockMagicalHardBox extends MagicalHardBox
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
