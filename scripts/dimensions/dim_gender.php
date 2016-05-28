<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.26.5
 * Time: 15:56
 */


require_once __DIR__ . "/../../vendor/autoload.php";
$connection = \Application\Database::getInstance()->getConnection();
$statement = $connection->prepare("INSERT IGNORE INTO dim_gender
            (`gender_code`, `gender_title`)
            VALUES (:gender_code, :gender_title)");

$genders = array();
$genders[] = array('code' => 'M', 'title' => 'Male');
$genders[] = array('code' => 'F', 'title' => 'Female');
foreach($genders as $gender) {
    $record = array();
    $record['gender_code'] = $gender['code'];
    $record['gender_title'] = $gender['title'];
    $statement->execute($record);
}