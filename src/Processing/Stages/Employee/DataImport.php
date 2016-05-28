<?php

namespace Application\Processing\Stages\Employee;

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
        $connection = Database::getInstance()->getConnection();

        // fetch employee record
        $statement = $connection->prepare("
            SELECT * FROM raw_employees WHERE emp_no = :emp_no
        ");
        $statement->execute($condition);
        $record = $statement->fetch();

        // fetch employee last department information
        $statement = $connection->prepare("
            SELECT * FROM raw_dept_emp WHERE emp_no = :emp_no ORDER BY from_date DESC LIMIT 1
        ");
        $statement->execute($condition);
        $record['department'] = $statement->fetch();

        // fetch department title
        $statement = $connection->prepare("
            SELECT * FROM raw_departments WHERE dept_no = :dept_no LIMIT 1
        ");
        $statement->execute(array('dept_no' => $record['department']['dept_no']));
        $record['department_title'] = $statement->fetch();

        // fetch employee management info
        $statement = $connection->prepare("
            SELECT * FROM raw_dept_manager WHERE emp_no = :emp_no AND to_date = '9999-01-01' ORDER BY from_date DESC LIMIT 1
        ");
        $statement->execute($condition);
        $record['management'] = $statement->fetch();

        return $record;
    }
}