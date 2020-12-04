<?php

namespace Adapters;

use PDO;
use RuntimeException;
use Traits\Singleton;

class PgSqlAdapter
{
    use Singleton;

    private $pdo;

    protected function __construct()
    {
        $this->pdo = new PDO($this->getConnString($this->getConfig()));
    }

    private function getConfig(): array
    {
        $config = parse_ini_file(ROOT_DIR . '/config.ini');

        return $config ?: [];
    }

    private function getConnString($config): string
    {
        if (!isset($config['host'], $config['port'], $config['dbname'], $config['dbuser'], $config['dbpass'])) {
            throw new RuntimeException('Missing config parameters');
        }

        return sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s',
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['dbuser'],
            $config['dbpass']
        );
    }

    /**
     * @param string $table
     * @param array $where
     * @param string|array $cols
     * @param null|string $class
     * @return array
     */
    public function fetch($table, $where = [], $cols = '*', $class = null)
    {
        $fetchMode = PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE;
        if (!$class) {
            $fetchMode = PDO::FETCH_ASSOC;
        }

        if(is_array($cols)) {
            $cols = implode(', ', $cols);
        }

        $bind = null;
        $whereSql = '';
        if(count($where) > 0) {
            $bind = array_filter(array_values($where));
            $whereSql = 'WHERE ' . implode(' AND ', array_keys($where));
        }

        $stmt = $this->pdo->prepare("SELECT $cols FROM $table $whereSql");
        $stmt->execute($bind);
        return $stmt->fetchAll($fetchMode, $class, [[], false]);
    }

    public function upsert($table, $fields, $data, $onConflict): bool
    {
        $fieldsCount = count($fields);
        if ($fieldsCount === 0) {
            return false;
        }
        $rowsCount = count($data) / $fieldsCount;
        if (!is_int($rowsCount) || $rowsCount === 0) {
            return false;
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES %s ON CONFLICT %s',
            $table,
            implode(', ', $fields),
            $this->getPlaceholderRows($fieldsCount, $rowsCount),
            $onConflict
        );

        return $this->pdo->prepare($sql)->execute($data);
    }

    private function getPlaceholderRows($numInRow, $numOfRows): string
    {
        return rtrim(str_repeat("({$this->getPlaceholders($numInRow)}), ", $numOfRows), ', ');
    }

    private function getPlaceholders($count): string
    {
        return rtrim(str_repeat('?, ', $count), ', ');
    }
}
