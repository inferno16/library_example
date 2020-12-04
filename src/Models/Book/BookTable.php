<?php


namespace Models\Book;


use Adapters\PgSqlAdapter;
use RuntimeException;
use Traits\Singleton;

class BookTable
{
    use Singleton;

    public const NAME = 'books';

    /** @var PgSqlAdapter */
    private $adapter;
    private $model = BookModel::class;

    protected function __construct()
    {
        $this->adapter = PgSqlAdapter::getInstance();
    }

    public function upsert($data): bool
    {
        if ($data instanceof BookModel) {
            $data = $data->getData();
            ksort($data);
        } else if ($data instanceof BookCollection) {
            foreach ($data as $book) {
                $result = $this->upsert($book);
                if (!$result) {
                    return false;
                }
            }
            return true;
        } else {
            throw new RuntimeException('Unsupported data format');
        }

        return $this->adapter->upsert(
            self::NAME,
            array_keys($data),
            array_values($data),
            'ON CONSTRAINT books_name_author_key DO UPDATE SET "date"=NOW()'
        );
    }

    public function fetch($where): array
    {
        return $this->adapter->fetch(self::NAME, $where, '*', $this->model);
    }
}
