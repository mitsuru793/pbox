<?php

namespace Pbox\Box;

interface HasAttributesTestInterface
{
    public function testAttributeReturnsValueByName();

    public function testAttributeThrowsExceptionWhenAccessUndefined();

    public function testAttributesReturnsAllValues();

    public function testSetAttributeSetsValueAsName();

    public function testSetAttributesRewritesAllValues();
}