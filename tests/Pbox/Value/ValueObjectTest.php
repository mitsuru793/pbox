<?php

namespace Pbox\Value;

use Error;
use PHPUnit\Framework\TestCase;

class ValueObjectTest extends TestCase
{
    public function testConstructIsNotPublic()
    {
        $this->expectException(Error::class);
        new class extends ValueObject
        {
        };
    }

    public function testOfReturnsInstanceOfSelf()
    {
        $this->assertInstanceOf(ValueMock::class, ValueMock::of('value'));
    }

    public function testJsonSerialize()
    {
        $value = ValueMock::of('hello');
        $this->assertSame('"hello"', json_encode($value));

        $value = ValueMock::of(null);
        $this->assertSame('null', json_encode($value));

        $value = ValueMock::of(1);
        $this->assertSame('1', json_encode($value));

        $value = ValueMock::of(true);
        $this->assertSame('true', json_encode($value));
    }

    public function testIsEmpty()
    {
        $values = [null, false, 0, [], ''];
        foreach ($values as $value) {
            $this->assertTrue(ValueMock::of($value)->isEmpty());
        }

        $values = [true, 1, -1, [null], 'a'];
        foreach ($values as $value) {
            $this->assertFalse(ValueMock::of($value)->isEmpty());
        }
    }

    public function testIsNotEmpty()
    {
        $values = [null, false, 0, [], ''];
        foreach ($values as $value) {
            $this->assertFalse(ValueMock::of($value)->isNotEmpty());
        }

        $values = [true, 1, -1, [null], 'a'];
        foreach ($values as $value) {
            $this->assertTrue(ValueMock::of($value)->isNotEmpty());
        }
    }
}

class ValueMock extends ValueObject
{
}
