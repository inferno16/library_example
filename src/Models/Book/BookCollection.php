<?php

namespace Models\Book;

use Models\BaseCollection;

class BookCollection extends BaseCollection
{
    public function save(): bool
    {
        return BookTable::getInstance()->upsert($this);
    }

    public function getByAuthor($author)
    {
        $this->data = BookTable::getInstance()->fetch(['author LIKE ?' => "%$author%"]);

        return $this;
    }
}
