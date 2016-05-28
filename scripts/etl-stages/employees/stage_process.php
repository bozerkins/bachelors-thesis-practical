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
$processingManager->setDataImport(new \Application\Processing\Stages\Employee\DataImport());
$processingManager->setDataParser(new \Application\Processing\Stages\Employee\DataParser());
$processingManager->setDataExport(new \Application\Processing\Stages\Employee\DataExport());

// process employees
$employees = $connection->query("SELECT emp_no FROM raw_employees");
foreach($employees as $employee) {
    $processingManager->execute($employee);
    $connection->query("DELETE FROM raw_employees WHERE emp_no = {$employee['emp_no']} LIMIT 1");
}