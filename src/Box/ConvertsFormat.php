<?php

namespace Pbox\Box;

trait ConvertsFormat
{
    /** @var array always hidden property names */
    public $hidden = [];

    /** @var array hidden property names when it's null */
    public $hiddenIfNull = [];

    /** @var bool hidden all property when it's null */
    public $hiddenAllIfNull = false;

    /** @var array change property name when convert to json and so on */
    public $alias = [];

    /**
     * convert into JSON
     * @return string
     * @throws \LogicException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * convert into array
     * @return array
     * @throws \LogicException
     */
    public function toArray(): array
    {
        $vars = $this->attributes();
        $array = [];
        foreach ($vars as $key => $val) {
            if (in_array($key, $this->hidden)) {
                continue;
            } else if (is_null($val) && ($this->hiddenAllIfNull || in_array($key, $this->hiddenIfNull))) {
                continue;
            }

            if (array_key_exists($key, $this->alias)) {
                $key = $this->alias[$key];
            }

            if ($val instanceof \JsonSerializable) {
                $array[$key] = $val->jsonSerialize();
            } else if ($val instanceof self) {
                $array[$key] = $val->toArray();
            } else if (is_scalar($val) || is_array($val) || is_null($val)) {
                $array[$key] = $val;
            } else {
                throw new \LogicException("Property value of box must extend " . self::class . ", implements JsonSerializable or scala.");
            }
        }
        return $array;
    }

    abstract function attributes(): array;
}
