<?php

namespace Pbox\Box;

use Mockery as m;
use PHPUnit\Framework\TestCase;

class ConvertsFormatTest extends TestCase
{
    private $double;

    public function setUp()
    {
        parent::setUp();
        $this->mockAttributes([
            'prop1' => 'value1',
            'prop2' => 'value2',
            'prop3' => 'value3',
        ]);
    }

    public function testHiddenPropertyDoesNotBeOutput()
    {
        $this->double->hidden = ['prop1'];
        $expected = ['prop2' => 'value2', 'prop3' => 'value3'];
        $this->assertSameConvert($expected);

        $this->double->hidden = ['prop1', 'prop2'];
        $expected = ['prop3' => 'value3'];
        $this->assertSameConvert($expected);
    }

    public function testNullPropertyIsOutput()
    {
        $this->mockAttributes([
            'prop1' => null,
            'prop2' => 'value2',
        ]);
        $expected = [
            'prop1' => null,
            'prop2' => 'value2',
        ];
        $this->assertSameConvert($expected);
    }

    public function testHiddenIfNullProperty()
    {
        $attributes = [
            'prop1' => 'value1',
            'prop2' => 'value2',
            'prop3' => 'value3',
        ];
        $this->mockAttributes($attributes);
        $this->double->hiddenIfNull = ['prop1'];
        $expected = [
            'prop1' => 'value1',
            'prop2' => 'value2',
            'prop3' => 'value3',
        ];
        $this->assertSameConvert($expected);

        // If values is null.
        $attributes['prop1'] =  null;
        $this->mockAttributes($attributes);
        $this->double->hiddenIfNull = ['prop1'];
        $expected = [
            'prop2' => 'value2',
            'prop3' => 'value3',
        ];
        $this->assertSameConvert($expected);
    }

    public function testHiddenAllIfNullProperty()
    {
        $attributes = [
            'prop1' => null,
            'prop2' => null,
            'prop3' => 'value3',
        ];

        // default is false
        $this->mockAttributes($attributes);
        $expected = [
            'prop1' => null,
            'prop2' => null,
            'prop3' => 'value3',
        ];
        $this->assertSameConvert($expected);

        $this->double->hiddenAllIfNull = true;
        $expected = [
            'prop3' => 'value3',
        ];
        $this->assertSameConvert($expected);
    }

    public function testAliasProperty()
    {
        $this->mockAttributes([
            'prop1' => 'value1',
            'prop2' => 'value2',
            'prop3' => 'value3',
        ]);
        $this->double->alias = ['prop1' => 'changed'];
        $expected = [
            'changed' => 'value1',
            'prop2' => 'value2',
            'prop3' => 'value3',
        ];
        $this->assertSameConvert($expected);
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

        $this->mockAttributes([
            'prop1' => $object,
            'prop2' => 'value2',
            'prop3' => 'value3',
        ]);

        $expected = [
            'prop1' => 'hello',
            'prop2' => 'value2',
            'prop3' => 'value3',
        ];
        $this->assertSameConvert($expected);
    }

    public function testRecursivelyConvert()
    {
        $user = new class implements \JsonSerializable
        {
            public function jsonSerialize()
            {
                return [
                    'name' => 'mike',
                    'age' => 13,
                ];
            }
        };

        $this->mockAttributes([
            'author' => $user,
        ]);

        $expected = [
            'author' => [
                'name' => 'mike',
                'age' => 13,
            ],
        ];
        $this->assertSameConvert($expected);
    }

    public function testPropertyIsNotJsonSerializableObject()
    {
        $this->expectException(\LogicException::class);

        $object = new class
        {
        };
        $this->mockAttributes(['prop' => $object]);
        $this->double->toArray();
    }

    public function testPropertyIsArray()
    {
        $this->mockAttributes(['prop' => [1, 2]]);
        $expected = [
            'prop' => [1, 2],
        ];
        $this->assertSameConvert($expected);
    }

    private function assertSameConvert(array $expectedArray): void
    {
        $this->assertSame($expectedArray, $this->double->toArray());
        $this->assertSame(json_encode($expectedArray), $this->double->toJson());
    }

    private function mockAttributes(array $returnValues): void
    {
        $this->double = m::mock(ConvertsFormat::class);
        $this->double->allows()->attributes()->andReturns($returnValues);
    }
}
