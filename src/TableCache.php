<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.28.5
 * Time: 22:07
 */

namespace Application;

class TableCache
{
    protected $connection = null;

    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            throw new \RuntimeException('table has no connection present');
        }
        return $this->connection;
    }

    public function get($key)
    {
        $statement = $this->getConnection()->prepare("SELECT * FROM table_cache WHERE `key` = :key LIMIT 1");
        $statement->execute(array('key' => $key));
        $result = $statement->fetch();
        return $result ? $result['value'] : null;
    }

    public function set($key, $value)
    {
        $statement = $this->getConnection()->prepare("REPLACE INTO table_cache (`key`, `value`) VALUES (:key, :value)");
        return $statement->execute(array('key' => $key, 'value' => $value));
    }
}