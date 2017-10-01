<?php

namespace Pbox\Value;

abstract class ValueObject implements \JsonSerializable
{
    protected $value;

    /**
     * Value constructor.
     * @param $value
     */
    protected function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * construct an instance of self
     * @param $value
     * @return mixed object extends Value
     */
    public static function of($value)
    {
        return new static($value);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    /**
     * If values is empty, this returns true.
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    /**
     * If values isn't empty, this returns true.
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !empty($this->value);
    }
}
