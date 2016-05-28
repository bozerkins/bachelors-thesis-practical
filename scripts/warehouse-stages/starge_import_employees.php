<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.29.5
 * Time: 02:06
 */
require_once __DIR__ . '/../../vendor/autoload.php';

\Application\Database::getInstance()->switchConnection('bachelors_etl');
$connection = \Application\Database::getInstance()->getConnection();

$processingManager = new \Application\Processing\ProcessingManager();
$processingManager->setDataImport(new \Application\Processing\Warehouse\Employee\DataImport());
$processingManager->setDataParser(new \Application\Processing\Warehouse\Employee\DataParser());
$processingManager->setDataExport(new \Application\Processing\Warehouse\Employee\DataExport());

// process employees
$employees = $connection->query("SELECT emp_no FROM final_employees LIMIT 10000"); // limit 10k, just in case
foreach($employees as $employee) {
    $processingManager->execute($employee);
    $connection->query("DELETE FROM final_employees WHERE emp_no = {$employee['emp_no']} LIMIT 1");
}