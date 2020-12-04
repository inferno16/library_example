<?php

namespace Models;

use Helpers\StringHelper;
use InvalidArgumentException;
use RuntimeException;
use Traits\ArrayAccess;
use Traits\Countable;
use Traits\Iterator;

abstract class BaseCollection implements Savable, \Iterator, \ArrayAccess, \Countable
{
    use Iterator;
    use Countable;
    use ArrayAccess {
        offsetSet as traitOffsetSet;
    }

    protected $data;
    protected $modelClass;
    protected $validate = true;

    /**
     * BaseModel constructor.
     * @param array $data
     * @param bool $validate
     * @throws InvalidArgumentException
     */
    public function __construct(array $data = [], $validate = true)
    {
        $this->modelClass = self::getModelClass($this->modelClass);
        $this->setValidate($validate);
        $this->setDataFromArray($data);

    }

    public function setDataFromArray(array $data)
    {
        $this->data = array_map(function ($row) {
            return new $this->modelClass($row, $this->validate);
        }, $data);
    }

    private static function getModelClass($modelClass = null)
    {
        // strlen('collection') = 10
        $modelClass = $modelClass ?? StringHelper::removeLastNChars(static::class, 10) . 'Model';

        if (!class_exists($modelClass)) {
            throw new RuntimeException("Invalid class $modelClass");
        }

        return $modelClass;
    }

    /**
     * @return bool
     */
    public function getValidate(): bool
    {
        return $this->validate;
    }

    /**
     * @param bool $validate
     */
    public function setValidate(bool $validate): void
    {
        $this->validate = $validate;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_array($value)) {
            $value = new $this->modelClass($value, $this->validate);
        }

        if (
            ($offset !== null && !($value instanceof $this->modelClass)) &&
            ($offset === null && !($value instanceof self))
        ) {
            throw new RuntimeException('Trying to assign unsupported value' . gettype($value));
        }

        $this->traitOffsetSet($offset, $value);
    }

    public function toArray($ksort = false): array
    {
        $data = $this->data;
        /** @var BaseModel $row */
        foreach ($data as &$row) {
            $row = $row->getData();
            if ($ksort) {
                ksort($row);
            }
        }

        return $data;
    }
}
