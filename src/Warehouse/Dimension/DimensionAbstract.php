<?php

namespace Application\Warehouse\Dimension;

use Application\Database;

abstract class DimensionAbstract
{
    /**
     * @return string
     */
    abstract protected function getTable();

    protected function getConnection()
    {
        return Database::getInstance()->getConnection('bachelors_analytics');
    }

    public function link(array $condition, $autocreate = true)
    {
        $record = $this->get($condition);
        if (!$record && $autocreate) {
            $this->create($condition);
            $record = $this->get($condition);
        }
        return $record;
    }

    public function get(array $condition)
    {
        $where = array();
        foreach(array_keys($condition) as $key) {
            $where[] = $key . ' = :' . $key;
        }
        $whereString = implode(' AND ', $where);

        $statement = $this->getConnection()->prepare("
            SELECT * FROM {$this->getTable()} WHERE {$whereString}
        ");
        $statement->execute($condition);
        return $statement->fetch();
    }

    public function create(array $uniqueCondition)
    {
        $keys = array_keys($uniqueCondition);
        $statementKeys = array_map(function($item) {
            return ':' . $item;
        }, $keys);
        $statementKeysString = implode(',',$statementKeys);
        $keysString = implode(',', $keys);
        $statement = $this->getConnection()->prepare("REPLACE INTO {$this->getTable()} ({$keysString}) VALUES ({$statementKeysString})");
        $statement->execute($uniqueCondition);
    }
}