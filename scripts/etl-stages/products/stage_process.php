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

$connection->query("INSERT INTO `final_products` SELECT * FROM `raw_products`");
$connection->query("DELETE FROM `raw_products`");