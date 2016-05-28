<?php

namespace Application\Processing\Warehouse\Salary;

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
            SELECT * FROM final_employees WHERE emp_no = :emp_no
        ");
        $statement->execute($condition);
        $record = $statement->fetch();

        return $record;
    }
}