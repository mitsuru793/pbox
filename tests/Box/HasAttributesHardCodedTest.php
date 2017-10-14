<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class HasAttributesHardCodedTest extends TestCase
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
    use HasAttributesHardCoded;

    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';

    private $hiddenProperties;

    public function __construct(array $hiddenProperties = [])
    {
        $this->hiddenProperties = $hiddenProperties;
    }

    protected function hiddenProperties(): array
    {
        return $this->hiddenProperties;
    }
}