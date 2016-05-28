<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.26.5
 * Time: 15:56
 */


require_once __DIR__ . "/../../vendor/autoload.php";
$connection = \Application\Database::getInstance()->getConnection();
$statement = $connection->prepare("INSERT IGNORE INTO dim_date
            (`date`, `month`, `month_name`, `year`, `year_month`, `day_of_week_name`)
            VALUES (:date, :month, :month_name, :year, :year_month, :day_of_week_name)");

for($date = '1885-01-01'; $date <= '2016-01-01'; $date = date('Y-m-d', strtotime($date . ' +1day'))) {
    $record = array();
    $unix = strtotime($date);
    $record['date'] = $date;
    $record['month'] = (int) date('m', $unix);
    $record['month_name'] = (string) date('F', $unix);
    $record['year'] = (int) date('Y', $unix);
    $record['year_month'] = (string) date('Y-m', $unix);
    $record['day_of_week_name'] = (string) date('l', $unix);
    $statement->execute($record);
}