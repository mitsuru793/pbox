<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class HasIssetFroAttributesTest extends TestCase
{
    public function testIsset()
    {
        $mock = new MockHasIssetFroAttributes;

        $this->assertTrue(isset($mock->prop1));
        $this->assertTrue(isset($mock->prop2));
        $this->assertFalse(isset($mock->invalid));
    }
}

class MockHasIssetFroAttributes
{
    use HasIssetForAttributes;

    public function attributes(): array
    {
        return [
            'prop1' => 'value1',
            'prop2' => 'value2',
        ];
    }
}
