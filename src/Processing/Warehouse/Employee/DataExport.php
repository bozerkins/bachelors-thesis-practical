<?php

namespace Application\Processing\Warehouse\Employee;

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
        $connection = Database::getInstance()->getConnection('bachelors_analytics');

        // prepare statement
        $keys = array_keys($output);
        $statementKeys = array_map(function($item) {
            return ':' . $item;
        }, $keys);
        $statementKeysString = implode(',',$statementKeys);
        $statement = $connection->prepare("REPLACE INTO fact_det_employees VALUES ({$statementKeysString})");
        $statement->execute($output);
    }
}