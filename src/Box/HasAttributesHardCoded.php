<?php

namespace Pbox\Box;

trait HasAttributesHardCoded
{
    public function attributes(): array
    {
        $allProps = get_object_vars($this);
        $metaProps = $this->hiddenProperties();

        $publicProps = [];
        foreach ($allProps as $prop => $value) {
            if (!isset($metaProps[$prop])) {
                $publicProps[$prop] = $value;
            }
        }
        return $publicProps;
    }

    /**
     * Returns array of hidden properties from outside.
     * You can distinguish hidden properties even in not public one, so protected and private one.
     *
     * @return array
     */
    abstract protected function hiddenProperties(): array;
}
