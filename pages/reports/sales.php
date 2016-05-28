<?php


require_once __DIR__ . "/../../vendor/autoload.php";

$dateFrom = array_key_exists('date_from', $_REQUEST) ? $_REQUEST['date_from'] : null;
$dateTo = array_key_exists('date_to', $_REQUEST) ? $_REQUEST['date_to'] : null;
$sort = array_key_exists('sort', $_REQUEST) ? $_REQUEST['sort'] : null;

if (!$dateFrom || !$dateTo || !preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $dateFrom) || !preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $dateFrom)) {
    echo 'Invalid dates passed';
    return;
}
$sortSql = $sort ? " ORDER BY `{$sort}` DESC" : '';

$time = microtime(true);

$connection = \Application\Database::getInstance()->getConnection();
$data = $connection->query("
	SELECT SQL_NO_CACHE
      dim_date.month as `Month`,
      COUNT(*) as `Amount`
	FROM fact_products
	JOIN dim_date
		ON fact_products.dim_purchase_key = dim_date.date
	WHERE dim_date.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
	GROUP BY dim_date.month
	{$sortSql}
")->fetchAll();

$heads = $data ? array_keys($data[0]) : array();

?>
<center>
    <h1>Sales overview</h1>
    Dates: <i><?=$dateFrom; ?> - <?=$dateTo; ?></i>
</center><br>
<style>
    /*
    Generic Styling, for Desktops/Laptops
    */
    table.cont-table {
        border-collapse: collapse;
        font: 12px/1.4 Georgia, Serif;
        table-layout: fixed;
        margin: 0 auto;
    }
    /* Zebra striping */
    table.cont-table tr:nth-of-type(odd) {
        background: #eee;
    }
    table.cont-table tr {
        font-size: 11px;
    }
    table.cont-table th {
        background: #333;
        color: white;
        font-weight: bold;
    }
    table.cont-table td, table.cont-table th {
        padding: 6px;
        border: 1px solid #ccc;
        text-align: left;
        vertical-align: top;
    }
</style>
<table class="cont-table">
    <thead>
    <tr>
        <?php foreach($heads?:array() as $header) : ?>
            <td><a href="<?=$_SERVER['REQUEST_URI'];?>&sort=<?=$header;?>"><?=$header; ?></a></td>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data?:array() as $row) : ?>
        <tr>
            <?php foreach($row as $item) : ?>
                <td><?=preg_match("/^[0-9]+$/", $item) ? number_format($item) : $item; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br>
<center><?=round(microtime(true) - $time, 3) . ' sec'; ?></center>