<?php

namespace Application\Processing\Stages\Employee;

use Application\Database;
use Application\Processing\DataExportInterface;

class DataExport implements DataExportInterface
{

    /**
     * @param array $output
     */
    public function export(array $output)
    {
        // get connection
        $connection = Database::getInstance()->getConnection();

        // prepare statement
        $keys = array_keys($output);
        $statementKeys = array_map(function($item) {
            return ':' . $item;
        }, $keys);
        $statementKeysString = implode(',',$statementKeys);
        $statement = $connection->prepare("REPLACE INTO final_employees VALUES ({$statementKeysString})");
        $statement->execute($output);
    }
}