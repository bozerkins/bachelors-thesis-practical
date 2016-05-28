<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.26.5
 * Time: 15:56
 */


require_once __DIR__ . "/../../vendor/autoload.php";
$connection = \Application\Database::getInstance()->getConnection();
$statement = $connection->prepare("INSERT IGNORE INTO dim_amount
            (`amount`, `bin_amount`)
            VALUES (:amount, :bin_amount)");

for($amount = 0; $amount < 1000; $amount++) {
    $record = array();
    $record['amount'] = $amount;
    $record['bin_amount'] = ((int) floor($amount / 10)) * 10;
    $statement->execute($record);
}