<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class MagicalBoxTest extends TestCase
{
    public function testAttributes()
    {
        $box = new MockMagicalBox;
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
        ];
        $this->assertSame($expected, $box->attributes());
    }
}

class MockMagicalBox extends MagicalHardBox
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
