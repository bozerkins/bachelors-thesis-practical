<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.24.5
 * Time: 14:46
 */

require_once __DIR__ . '/../../../vendor/autoload.php';

\Application\Database::getInstance()->switchConnection('employees');
$connection = \Application\Database::getInstance()->getConnection();

$statement = $connection->prepare("SELECT distinct(from_date) as from_date FROM salaries ORDER BY from_date ASC");
$statement->execute();
$fromDates = $statement->fetchAll();

// create csv dif
$dir = __DIR__ . '/csv';
if (!is_dir($dir)) {
    mkdir($dir);
}

if (count(scandir($dir)) > 2) {
    throw new \ErrorException($dir . ' not empty. please clear it before running the script');
}

foreach($fromDates as $fromDate) {

    dump('processing: ' . $fromDate['from_date']);
    // employees are 1985-01-01 - 2002-08-01
    $statement = $connection->prepare("SELECT * FROM salaries WHERE from_date = :from_date");
    $statement->execute(array('from_date' => $fromDate['from_date']));
    $salaries = $statement->fetchAll();

    dump('records found: ' . count($salaries));

    $faker = \Faker\Factory::create();

    // generate sales
    $sales = array();
    foreach($salaries as $salary) {
        $money = (int) $salary['salary'];
        $days = date_diff(new \DateTime($salary['from_date']), new \DateTime($salary['to_date']));
        if ($days->days > 365) {
            continue; // invalid record
        }
        if ($money > 0) {
            $products = rand(5,15);
            $avgMoneyPerProduct = $money / $products;
            for($i=0; $i < $products; $i++) {
                $product = array();
                $product['emp_no'] = $salary['emp_no'];
                $product['price_net'] = $avgMoneyPerProduct * (rand(700,1500) / 100);
                $product['markup'] = $product['price_net'] * (rand(25,35) / 100);
                $product['price'] = $product['price_net'] + $product['markup'];
                $product['client_country'] = $faker->countryCode;
                $product['client_city'] = $faker->city;
                $product['client_email'] = $faker->email;
                $product['client_company_email'] = $faker->companyEmail;
                $reservationDays = rand(5, $days->days);
                $purchaseDays = rand($reservationDays, $days->days);
                $product['reservation_date'] = date('Y-m-d', strtotime($salary['from_date'] . ' +' . $reservationDays . 'days'));
                $product['purchase_date'] = date('Y-m-d', strtotime($salary['from_date'] . ' +' . $purchaseDays . 'days'));
                $key = $product['purchase_date'];
                if (!array_key_exists($key, $sales)) {
                    $sales[$key] = array();
                }
                $sales[$key][] = $product;
            }
        }
    }

    // import sales
    foreach($sales as $ymd => $data) {
        $file = $dir . '/sales_' . $ymd . '.csv';

        dump('starting processing: ' . $file);

        $fp = fopen($file, 'a+');
        foreach($data as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);

        dump('finished processing: ' . $file);
    }
}



