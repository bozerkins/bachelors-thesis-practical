<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.28.5
 * Time: 22:42
 */
require_once __DIR__ . '/../../../vendor/autoload.php';

\Application\Database::getInstance()->switchConnection('bachelors_etl');
$connection = \Application\Database::getInstance()->getConnection();

$processingManager = new \Application\Processing\ProcessingManager();
$processingManager->setDataImport(new \Application\Processing\Employee\DataImport());
$processingManager->setDataParser(new \Application\Processing\Employee\DataParser());
$processingManager->setDataExport(new \Application\Processing\Employee\DataExport());

// process employees
$employeeIds = $connection->query("SELECT emp_no FROM raw_employees");
foreach($employeeIds as $employeeId) {
    $processingManager->execute(array('emp_no' => $employeeId));
}