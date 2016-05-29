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
$processingManager->setDataImport(new \Application\Processing\Warehouse\Product\DataImport());
$processingManager->setDataParser(new \Application\Processing\Warehouse\Product\DataParser());
$processingManager->setDataExport(new \Application\Processing\Warehouse\Product\DataExport());

// process employees
$products = $connection->query("SELECT id FROM final_products LIMIT 10000"); // limit 10k, just in case
foreach($products as $product) {
    $processingManager->execute($product);
    $connection->query("DELETE FROM final_products WHERE id = {$product['id']} LIMIT 1");
}