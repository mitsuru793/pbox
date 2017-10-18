<?php

namespace Pbox\Box;

use Pbox\Exception\AccessHiddenPropertyException;
use Pbox\Exception\UndefinedPropertyException;
use PHPUnit\Framework\TestCase;

class HasStaticAttributesTest extends TestCase implements HasAttributesTestInterface
{
    use HasAttributesTestInterface_HasStaticAttributes;

    public function testAttributeThrowsExceptionWhenAccessHidden()
    {
        $o = new MockHasStaticAttributes(['pubProp']);
        $this->expectException(AccessHiddenPropertyException::class);
        $o->attribute('pubProp');
    }

    public function testAttributesThrowsExceptionWhenAccessHidden()
    {
        $o = new MockHasStaticAttributes(['pubProp']);
        $this->expectException(AccessHiddenPropertyException::class);
        $o->attributes();
    }

    public function testSetAttributeThrowsExceptionWhenAccessHidden()
    {
        $o = new MockHasStaticAttributes(['pubProp']);
        $this->expectException(AccessHiddenPropertyException::class);
        $o->setAttribute('pubProp', 'val');
    }

    public function testSetAttributeThrowsExceptionWhenAccessUndefined()
    {
        $o = new MockHasStaticAttributes;
        $this->expectException(UndefinedPropertyException::class);
        $o->setAttribute('invalid', 'val');
    }

    public function testSetAttributesThrowsExceptionWhenAccessHidden()
    {
        $o = new MockHasStaticAttributes(['pubProp']);
        $this->expectException(AccessHiddenPropertyException::class);
        $o->setAttributes(['pubProp' => 'val']);
    }

    public function testSetAttributesThrowsExceptionWhenAccessUndefined()
    {
        $o = new MockHasStaticAttributes;
        $this->expectException(UndefinedPropertyException::class);
        $o->setAttributes(['invalid' => 'val']);
    }
}
trait HasAttributesTestInterface_HasStaticAttributes
{
    public function testAttributeReturnsValueByName()
    {
        $o = new MockHasStaticAttributes;
        $this->assertSame('pubValue', $o->attribute('pubProp'));
        $this->assertSame('proValue', $o->attribute('proProp'));
        $this->assertSame('priValue', $o->attribute('priProp'));
    }

    public function testAttributeThrowsExceptionWhenAccessUndefined()
    {
        $o = new MockHasStaticAttributes;
        $this->expectException(UndefinedPropertyException::class);
        $o->attribute('invalid');
    }

    public function testAttributesReturnsAllValues()
    {
        $o = new MockHasStaticAttributes;
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
            'priProp' => 'priValue',
            'hiddenProperties' => [],
        ];
        $this->assertSame($expected, $o->attributes());
    }

    public function testSetAttributeSetsValueAsName()
    {
        $o = new MockHasStaticAttributes;
        $o->setAttribute('pubProp', 'changed');
        $this->assertSame('changed', $o->attribute('pubProp'));

        $o->setAttribute('proProp', 'changed');
        $this->assertSame('changed', $o->attribute('proProp'));

        $o->setAttribute('priProp', 'changed');
        $this->assertSame('changed', $o->attribute('proProp'));
    }


    public function testSetAttributesRewritesAllValues()
    {
        $o = new MockHasStaticAttributes;

        $attributes = ['pubProp' => 'changed', 'proProp' => 'changed'];
        $o->setAttributes($attributes);
        $expected = $attributes + ['priProp' => 'priValue', 'hiddenProperties' => []];
        $this->assertSame($expected, $o->attributes());
    }
}

class MockHasStaticAttributes
{
    use HasStaticAttributes;

    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';

    private $hiddenProperties;

    public function __construct(array $hiddenPropertyNames = [])
    {
        $this->hiddenProperties = [];
        foreach ($hiddenPropertyNames as $name) {
           $this->hiddenProperties[$name]  = $this->$name;
        }
    }

    private function isHiddenProperty(string $name): bool
    {
        return array_key_exists($name, $this->hiddenProperties);
    }
}