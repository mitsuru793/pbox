<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class HasStaticAttributesTest extends TestCase
{
    public function testAttributes()
    {
        $mock = new MockObject;
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
            'priProp' => 'priValue',
            'hiddenProperties' => [],
        ];
        $this->assertSame($expected, $mock->attributes());

        $mock = new MockObject(['hiddenProperties']);
        unset($expected['hiddenProperties']);
        $this->assertSame($expected, $mock->attributes());

        $mock = new MockObject(['pubProp', 'proProp', 'priProp', 'hiddenProperties']);
        $this->assertSame([], $mock->attributes());
    }
}

class MockObject
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

    protected function hiddenProperties(): array
    {
        return $this->hiddenProperties;
    }
}