<?php

namespace Pbox\Box;

use ArrayIterator;
use IteratorAggregate;
use Pbox\Exception\AgainstTypehintException;
use PHPUnit\Framework\TestCase;

class AddsPropertyAccessWithTypehintToDynamicAttributesTest extends TestCase
{
    use TestDefaultTypehintWhenSetProperty;

    private $mock;

    public function setUp()
    {
        parent::setUp();

        $this->mock = new class
        {
            use AddsPropertyAccessWithTypehintToDynamicAttributes;

            private $typehints = [
                'pArray' => 'array',
                'pCallable' => 'callable',
                'pBool' => 'bool',
                'pFloat' => 'float',
                'pInt' => 'int',
                'pString' => 'string',
                'pIterable' => 'iterable',
            ];

            private $attributes = [];
        };
    }

    public function testTypehintCallsClosureWhenSetProperty()
    {
        $mock = new class
        {
            use AddsPropertyAccessWithTypehintToDynamicAttributes;
            private $typehints = ['prop' => 'startWithAtmark'];
            private $attributes = [];

            protected function typehints(string $typeName)
            {
                $typehints = [
                    'startWithAtmark' => function ($value) {
                        return is_string($value) && preg_match('/^@/', $value);
                    },
                ];
                return $typehints[$typeName];
            }

        };
        $mock->prop = '@str';
        $this->assertSame('@str', $mock->prop);

        $this->expectException(AgainstTypehintException::class);
        $mock->prop = 'noAtmark';
    }
}

trait TestDefaultTypehintWhenSetProperty
{
    public function testTypehintArray()
    {
        $this->mock->pArray = [1, 2];
        $this->assertSame([1, 2], $this->mock->pArray);

        $this->expectException(AgainstTypehintException::class);
        $this->mock->pArray = 'invalid';
    }

    public function testTypehintCallable()
    {
        $func = function () {
        };
        $this->mock->pCallable = $func;
        $this->assertSame($func, $this->mock->pCallable);

        $this->expectException(AgainstTypehintException::class);
        $this->mock->pCallable = 'invalid';
    }

    public function testTypehintBool()
    {
        $this->mock->pBool = true;
        $this->assertSame(true, $this->mock->pBool);

        $this->expectException(AgainstTypehintException::class);
        $this->mock->pBool = 'invalid';
    }

    public function testTypehintFloat()
    {
        $this->mock->pFloat = 0.1;
        $this->assertSame(0.1, $this->mock->pFloat);

        $this->expectException(AgainstTypehintException::class);
        $this->mock->pFloat = 1;
    }

    public function testTypehintInt()
    {
        $this->mock->pInt = 1;
        $this->assertSame(1, $this->mock->pInt);

        $this->expectException(AgainstTypehintException::class);
        $this->mock->pInt = 0.1;
    }

    public function testTypehintString()
    {
        $this->mock->pString = '1';
        $this->assertSame('1', $this->mock->pString);

        $this->expectException(AgainstTypehintException::class);
        $this->mock->pString = 1;
    }

    public function testTypehintIterable()
    {
        $this->mock->pIterable = [1, 2];
        $this->assertSame([1, 2], $this->mock->pIterable);

        $iterator = new class implements IteratorAggregate
        {
            public function getIterator()
            {
                return new ArrayIterator([3, 4]);
            }
        };
        $this->mock->pIterable = $iterator;
        $this->assertSame($iterator, $this->mock->pIterable);

        $this->expectException(AgainstTypehintException::class);
        $this->mock->pIterable = 'invalid';
    }
}

