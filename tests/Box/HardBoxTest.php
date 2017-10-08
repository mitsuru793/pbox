<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class HardBoxTest extends TestCase
{
}

class MockHardBox extends HardBox
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
