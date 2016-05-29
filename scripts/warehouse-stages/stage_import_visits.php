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
$processingManager->setDataImport(new \Application\Processing\Warehouse\Visit\DataImport());
$processingManager->setDataParser(new \Application\Processing\Warehouse\Visit\DataParser());
$processingManager->setDataExport(new \Application\Processing\Warehouse\Visit\DataExport());

// process employees
$records = $connection->query("SELECT `date` FROM final_visits LIMIT 10000"); // limit 10k, just in case
foreach($records as $record) {
    $processingManager->execute($record);
    $connection->query("DELETE FROM final_visits WHERE `date` = '{$record['date']}' LIMIT 1");
}