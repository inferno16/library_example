<?php

namespace Models\Book;

use Models\BaseModel;

/**
 * @method string getId()
 * @method string getName()
 * @method string getAuthor()
 * @method string getDate()
 * @method void setId($val)
 * @method void setName($val)
 * @method void setAuthor($val)
 * @method void setDate($val)
 */
class BookModel extends BaseModel
{
    public const REQUIRED_FIELDS = ['name', 'author'];

    public static function dataValid($data): bool
    {
        return count(array_diff(self::REQUIRED_FIELDS, array_keys($data))) === 0;
    }

    public function save(): bool
    {
        return BookTable::getInstance()->upsert($this);
    }
}
