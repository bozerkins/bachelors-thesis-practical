<?php

namespace Application\Processing\Warehouse\Visit;

use Application\Database;
use Application\Processing\DataImportInterface;

class DataImport implements DataImportInterface
{

    /**
     * @param array $condition
     * @return array $input
     */
    public function import(array $condition)
    {
        // get connection
        $connection = Database::getInstance()->getConnection('bachelors_etl');

        // fetch employee record
        $statement = $connection->prepare("
            SELECT * FROM final_visits WHERE date = :date
        ");
        $statement->execute($condition);
        $record = $statement->fetch();

        return $record;
    }
}