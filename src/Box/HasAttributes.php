<?php

namespace Pbox\Box;

trait HasAttributes
{
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