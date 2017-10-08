<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class MagicalHardBoxTest extends TestCase
{
    public function testAttributes()
    {
        $box = new MockMagicalHardBox;
        $expected = [
            'pubProp' => 'pubValue',
            'proProp' => 'proValue',
        ];
        $this->assertSame($expected, $box->attributes());
    }
}

class MockMagicalHardBox extends MagicalHardBox
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
