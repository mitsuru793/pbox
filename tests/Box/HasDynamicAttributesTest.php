<?php

namespace Pbox\Box;

use Pbox\Exception\UndefinedAttributeException;
use PHPUnit\Framework\TestCase;

class HasDynamicAttributesTest extends TestCase implements HasAttributesTestInterface
{
    use HasAttributesTest_HasDynamicAttributes;
}

trait HasAttributesTest_HasDynamicAttributes
{
    public function testAttributeReturnsValueByName()
    {
        $o = new MockHasDynamicAttributes;
        $this->assertSame('v1', $o->attribute('p1'));
    }

    public function testAttributeThrowsExceptionWhenAccessUndefined()
    {
        $o = new MockHasDynamicAttributes;
        $this->expectException(UndefinedAttributeException::class);
        $o->attribute('invalid');
    }

    public function testAttributesReturnsAllValues()
    {
        $o = new MockHasDynamicAttributes;
        $this->assertSame(['p1' => 'v1', 'p2' => 'v2'], $o->attributes());
    }

    public function testSetAttributeSetsValueAsName()
    {
        $o = new MockHasDynamicAttributes;
        $o->setAttribute('p1', 'changed');
        $this->assertSame('changed', $o->attributes['p1']);
    }

    public function testSetAttributesRewritesAllValues()
    {
        $o = new MockHasDynamicAttributes;
        $o->setAttribute('p1', 'changed');

        $attributes = ['p2' => 'changed', 'p3' => 'changed'];
        $o->setAttributes($attributes);
        $this->assertSame($attributes, $o->attributes);
    }
}

class MockHasDynamicAttributes
{
    use HasDynamicAttributes;
    public $attributes = ['p1' => 'v1', 'p2' => 'v2'];
}
