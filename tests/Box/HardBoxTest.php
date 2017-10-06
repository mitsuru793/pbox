<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class HardBoxTest extends TestCase
{
    use HardBoxTestConvertTest;
}

trait HardBoxTestConvertTest
{
    public function testPrivatePropertyDoesNotBeOutput()
    {
        $box = new MockHardBox();
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
        ];
        $this->assertSameConvert($expected, $box);
    }

    public function testHiddenPropertyDoesNotBeOutput()
    {
        $box = new class extends MockHardBox
        {
            public $hidden = ['pubProp'];
        };
        $expected = ['proProp' => 'proValue'];
        $this->assertSameConvert($expected, $box);

        $box = new class extends MockHardBox
        {
            public $hidden = ['pubProp', 'proProp'];
        };
        $this->assertSameConvert([], $box);
    }

    public function testNullPropertyIsOutput()
    {
        $box = new class extends MockHardBox
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
        $box = new class extends MockHardBox
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
        $box = new class extends MockHardBox
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
        $box = new class extends MockHardBox
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

        $box = new class ($object) extends MockHardBox
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
        $user = new class extends HardBox
        {
            public $name = 'mike';
            public $age = 13;
        };

        $post = new class($user) extends HardBox
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

        $box = new class($object) extends HardBox
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
        $box = new class extends HardBox
        {
            public $prop = [1, 2];
        };
        $expected = [
            'prop' => [1, 2],
        ];
        $this->assertSameConvert($expected, $box);
    }

    private function assertSameConvert(array $expectedArray, HardBox $box)
    {
        $this->assertSame($expectedArray, $box->toArray());
        $this->assertSame(json_encode($expectedArray), $box->toJson());
    }
}

class MockHardBox extends HardBox
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
