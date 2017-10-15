<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class AddsIssetToAttributesTest extends TestCase
{
    public function testIsset()
    {
        $mock = new MockAddsIssetToAttributes;

        $this->assertTrue(isset($mock->prop1));
        $this->assertTrue(isset($mock->prop2));
        $this->assertFalse(isset($mock->invalid));
    }
}

class MockAddsIssetToAttributes
{
    use AddsIssetToAttributes;

    public function attributes(): array
    {
        return [
            'prop1' => 'value1',
            'prop2' => 'value2',
        ];
    }
}
