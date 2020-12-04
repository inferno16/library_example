<?php

namespace Models;

use InvalidArgumentException;
use Traits\MagicAccess;

abstract class BaseModel implements Savable
{
    use MagicAccess;

    public const REQUIRED_FIELDS = [];
    protected $data;

    /**
     * BaseModel constructor.
     * @param array $data
     * @param bool $validate
     */
    public function __construct(array $data = [], bool $validate = true)
    {
        if ($validate && !self::dataValid($data)) {
            throw new InvalidArgumentException('Data is not valid');
        }
        $this->setData($data);
    }

    abstract public static function dataValid($data): bool;
}
