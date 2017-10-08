<?php

namespace Pbox\Box;

use PHPUnit\Framework\TestCase;

class MagicalAccessorTraitTest extends TestCase
{
    public function testSetAndGetPropertyWithoutAccessor()
    {
        $box = new class extends MockMagicalAccessorTraitBase
        {
            protected $prop;
        };
        $box->prop = 'from outside';
        $this->assertSame('from outside', $box->prop);
    }

    public function testCallSetterWhenSetProperty()
    {
        $box = new class extends MockMagicalAccessorTraitBase
        {
            protected $prop;

            public function setPropAttribute($value)
            {
                $this->prop = 'from setter';
            }
        };
        $box->prop = 'from outside';
        $this->assertSame('from setter', $box->prop);
    }

    public function testCallGetterWhenSetProperty()
    {
        $box = new class extends MockMagicalAccessorTraitBase
        {
            protected $prop;

            public function getPropAttribute($value)
            {
                return 'from setter';
            }
        };
        $box->prop = 'from outside';
        $this->assertSame('from setter', $box->prop);
    }

    public function testNotCallAccessorWhenAccessPublicProperty()
    {
        $box = new class extends MockMagicalAccessorTraitBase
        {
            public $prop;

            public function getPropAttribute($value)
            {
                return 'from getter';
            }

            public function setPropAttribute($value)
            {
                return 'from setter';
            }
        };
        $box->prop = 'from outside';
        $this->assertSame('from outside', $box->prop);
    }
}

class MockMagicalAccessorTraitBase
{
    use MagicalAccessorTrait;

    public function metaAttributes(): array
    {
        return get_class_vars(__CLASS__);
    }

    public function attributes(): array
    {
        $allProps = get_object_vars($this);
        $metaProps = $this->metaAttributes();

        $publicProps = [];
        foreach ($allProps as $prop => $value) {
            if (!isset($metaProps[$prop])) {
                $publicProps[$prop] = $value;
            }
        }
        return $publicProps;
    }
}

class MockMagicalAccessorTrait extends MockMagicalAccessorTraitBase
{
    public $pubProp = 'pubValue';
    protected $proProp = 'proValue';
    private $priProp = 'priValue';
}
