#!/usr/bin/php
<?php

$counter = array();

// input comes from STDIN
while (($row = fgetcsv(STDIN)) !== false) {
    $row = array_map('trim', $row);

    $key = $row[0];
    if (!array_key_exists($key, $counter)) {
        $counter[$key] = $visitors[$key] = array();
        $counter[$key]['date'] = $row[0];
        $counter[$key]['visitors'] = array();
        $counter[$key]['visitors_time_spent'] = array();
    }
    if (!array_key_exists($row[1], $counter[$key]['visitors'])) {
        $counter[$key]['visitors'][$row[1]] = 0;
    }
    if (!array_key_exists($row[1], $counter[$key]['visitors_time_spent'])) {
        $counter[$key]['visitors_time_spent'][$row[1]] = 0;
    }

    $counter[$key]['visitors_time_spent'][$row[1]] += $row[2];
    $counter[$key]['visitors'][$row[1]] += $row[3];
}

foreach($counter as $info) {
    $record = array();
    $record[] = $info['date']; // date
    $record[] = array_sum($info['visitors']) / count($info['visitors']); // actions / visitors
    $record[] = array_sum($info['visitors_time_spent']) / count($info['visitors']); // time spent / visitors
    echo implode(',', $record).PHP_EOL;
}
