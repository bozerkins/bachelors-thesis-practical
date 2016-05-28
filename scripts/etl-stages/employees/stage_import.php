<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.28.5
 * Time: 22:42
 */
require_once __DIR__ . '/../../../vendor/autoload.php';

\Application\Database::getInstance()->switchConnection('employees');
$sourceConnection = \Application\Database::getInstance()->getConnection();
\Application\Database::getInstance()->switchConnection('bachelors_etl');
$destinationConnection = \Application\Database::getInstance()->getConnection();

$cache = new \Application\TableCache();
$cache->setConnection($destinationConnection);

// import employees
$lastImportDate = $cache->get('employees_last_import_date');// ?: '';
$currentImportDate = date('Y-m-d', strtotime('yesterday'));
if ($lastImportDate) {
    $lastImportDateUnix = strtotime($lastImportDate);
    if ($lastImportDateUnix > strtotime('-2days')) {
        echo 'too soon to fetch the data' . PHP_EOL;
        exit(1); // error
    }
    $currentImportDate = date('Y-m-d', strtotime('+1day', $lastImportDateUnix));
}

$employees = $sourceConnection->query("SELECT * FROM employees WHERE hire_date = '{$currentImportDate}';")->fetchAll();
foreach($employees as $record) {
    $keys = array_keys($record);
    $statementKeys = array_map(function($item) {
        return ':' . $item;
    }, $keys);
    $statementKeysString = implode(',',$statementKeys);
    $statement = $destinationConnection->prepare("REPLACE INTO raw_employees VALUES ({$statementKeysString})");
    $statement->execute($record);
}

// update department managers
$departmentManagers = $sourceConnection->query("SELECT * FROM dept_manager WHERE from_date = '{$currentImportDate}' OR to_date = '{$currentImportDate}';")->fetchAll();
foreach($departmentManagers as $record) {
    $keys = array_keys($record);
    $statementKeys = array_map(function($item) {
        return ':' . $item;
    }, $keys);
    $statementKeysString = implode(',',$statementKeys);
    $statement = $destinationConnection->prepare("REPLACE INTO raw_dept_manager VALUES ({$statementKeysString})");
    $statement->execute($record);
}

// update active department employees
$departmentEmployees = $sourceConnection->query("SELECT * FROM dept_emp WHERE from_date = '{$currentImportDate}' OR to_date = '{$currentImportDate}';")->fetchAll();
foreach($departmentEmployees as $record) {
    $keys = array_keys($record);
    $statementKeys = array_map(function($item) {
        return ':' . $item;
    }, $keys);
    $statementKeysString = implode(',',$statementKeys);
    $statement = $destinationConnection->prepare("REPLACE INTO raw_dept_emp VALUES ({$statementKeysString})");
    $statement->execute($record);
}

// update departments
$departments = $sourceConnection->query("SELECT * FROM departments;")->fetchAll();
foreach($departments as $record) {
    $keys = array_keys($record);
    $statementKeys = array_map(function($item) {
        return ':' . $item;
    }, $keys);
    $statementKeysString = implode(',',$statementKeys);
    $statement = $destinationConnection->prepare("REPLACE INTO raw_departments VALUES ({$statementKeysString})");
    $statement->execute($record);
}

// import salaries
$twoMonthAgo = date('Y-m-d', strtotime($currentImportDate . ' -2months'));
$salaries = $sourceConnection->query("SELECT * FROM salaries WHERE from_date >= '{$currentImportDate}';")->fetchAll();
foreach($salaries as $record) {
    $keys = array_keys($record);
    $statementKeys = array_map(function($item) {
        return ':' . $item;
    }, $keys);
    $statementKeysString = implode(',',$statementKeys);
    $statement = $destinationConnection->prepare("REPLACE INTO raw_salaries VALUES ({$statementKeysString})");
    $statement->execute($record);
}

$cache->set('employees_last_import_date', $currentImportDate);
exit(0); // all ok