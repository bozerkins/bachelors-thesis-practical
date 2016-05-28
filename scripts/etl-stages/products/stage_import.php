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

$importDir = __DIR__ . '/../../data-generation/sales-data/csv';

// filter dots
$dir = array_filter(scandir($importDir), function($item) { return $item !== '.' && $item !== '..'; });

$cache = new \Application\TableCache();
$cache->setConnection($connection);

// filter out already processed files
$lastDir = $cache->get('products_last_dir');
if ($lastDir) {
    $dir = array_filter(scandir($importDir), function($item) use ($lastDir) {
        return $item > $lastDir;
    });
}

foreach($dir as $folder) {

    // save last dir
    $cache->set('products_last_dir', $folder);

    // import folder
    dump('importing: ' . $folder);
    $counter = 0;

    $statement = $connection->prepare("INSERT INTO raw_products
            (emp_no,markup,price_net,price,client_city,client_country,client_email,client_company_email,reservation_date,purchase_date)
            VALUES (:emp_no,:markup,:price_net,:price,:client_city,:client_country,:client_email,:client_company_email,:reservation_date,:purchase_date)");

    if (($handle = fopen($importDir . '/' . $folder, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $counter++;
            $product = array();
            $product['emp_no'] = $data[0];
            $product['price_net'] = $data[1];
            $product['markup'] = $data[2];
            $product['price'] = $data[3];
            $product['client_country'] = $data[4];
            $product['client_city'] = $data[5];
            $product['client_email'] = $data[6];
            $product['client_company_email'] = $data[7];
            $product['reservation_date'] = $data[8];
            $product['purchase_date'] = $data[9];
            $statement->execute($product);
            if ($counter % 500 === 0) {
                dump('imported rows: ' . $counter);
            }
        }
        fclose($handle);
    }
    dump('done: ' . $folder);
}
