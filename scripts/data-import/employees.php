<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.28.5
 * Time: 22:12
 */
require_once __DIR__ . '/../../vendor/autoload.php';

\Application\Database::getInstance()->switchConnection('bachelors_etl');
$destinationConnection = \Application\Database::getInstance()->getConnection();

$tables = array('employees','dept_emp','dept_manager','salaries', 'departments');
foreach($tables as $table) {
    $command = 'mysqldump -u root employees ' . $table . ' | mysql -u root bachelors_etl';
    shell_exec($command);
    $destinationConnection->query('RENAME TABLE ' . $table . ' TO raw_' . $table);
}