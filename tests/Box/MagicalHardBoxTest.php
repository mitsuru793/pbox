<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class MagicalHardBoxTest extends TestCase
{
    use MagicalHardBoxConvertTest;

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
}

trait MagicalHardBoxConvertTest {
    public function testPrivatePropertyDoesNotBeOutput()
    {
        $box = new MockMagicalHardBox();
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
        ];
        $this->assertSameConvert($expected, $box);
    }

    private function assertSameConvert(array $expectedArray, MagicalHardBox $box)
    {
        $this->assertSame($expectedArray, $box->toArray());
        $this->assertSame(json_encode($expectedArray), $box->toJson());
    }

    public function testHiddenPropertyDoesNotBeOutput()
    {
        $box = new class extends MockMagicalHardBox
        {
            public $hidden = ['pubProp'];
        };
        $expected = ['proProp' => 'proValue'];
        $this->assertSameConvert($expected, $box);

        $box = new class extends MockMagicalHardBox
        {
            public $hidden = ['pubProp', 'proProp'];
        };
        $this->assertSameConvert([], $box);
    }

    public function testNullPropertyIsOutput()
    {
        $box = new class extends MockMagicalHardBox
        {
            public $pubProp = null;
        };
        $expected = [
            'pubProp' => null,
            'proProp' => 'proValue',
        ];
        $this->assertSameConvert($expected, $box);
    }

    public function testHiddenIfNullProperty()
    {
        $box = new class extends MockMagicalHardBox
        {
            public $hiddenIfNull = ['pubProp'];
            public $pubProp = 'pubValue';
        };
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
        ];
        $this->assertSameConvert($expected, $box);

        $expected = [
            'proProp' => 'proValue',
        ];
        $box->pubProp = null;
        $this->assertSameConvert($expected, $box);
    }

    public function testHiddenAllIfProperty()
    {
        $box = new class extends MockMagicalHardBox
        {
            public $pubProp = null;
            public $proProp = null;
        };
        $expected = [
            'pubProp' => null,
            'proProp' => null,
        ];
        $this->assertSameConvert($expected, $box);
    }

    public function testAliasProperty()
    {
        $box = new class extends MockMagicalHardBox
        {
            public $alias = ['pubProp' => 'changed'];
        };
        $expected = [
            'changed' => 'pubValue',
            'proProp' => 'proValue',
        ];
        $this->assertSameConvert($expected, $box);
    }

    public function testJsonSerializeReturnsInConvert()
    {
        $object = new class implements \JsonSerializable
        {
            function jsonSerialize()
            {
                return 'hello';
            }
        };

        $box = new class ($object) extends MockMagicalHardBox
        {
            function __construct($object)
            {
                $this->pubProp = $object;
            }
        };

        $expected = [
            'pubProp' => 'hello',
            'proProp' => 'proValue',
        ];
        $this->assertSameConvert($expected, $box);
    }

    public function testRecursivelyConvertBox()
    {
        $user = new class extends MagicalHardBox
        {
            public $name = 'mike';
            public $age = 13;
        };

        $post = new class($user) extends MagicalHardBox
        {
            public $author;

            function __construct($author)
            {
                $this->author = $author;
            }
        };

        $expected = [
            'author' => [
                'name' => 'mike',
                'age' => 13,
            ],
        ];
        $this->assertSameConvert($expected, $post);
    }

    public function testThrowExceptionWhenPropertyValueIsInvalid()
    {
        // property value must be ...
        // 1. implements JsonSerializable
        // 2. extends Box class
        // 3. be scala or array or null

        $this->expectException(\LogicException::class);

        $object = new class
        {
        };

        $box = new class($object) extends MagicalHardBox
        {
            public $prop;

            function __construct($value)
            {
                $this->prop = $value;
            }
        };

        $box->toArray();
    }

    public function testPropertyIsArray()
    {
        $box = new class extends MagicalHardBox
        {
            public $prop = [1, 2];
        };
        $expected = [
            'prop' => [1, 2],
        ];
        $this->assertSameConvert($expected, $box);
    }
}

class MockMagicalHardBox extends MagicalHardBox
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
