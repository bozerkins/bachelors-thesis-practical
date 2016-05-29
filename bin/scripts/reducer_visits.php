#!/usr/bin/php
<?php

$counter = array();

// input comes from STDIN
while (($row = fgetcsv(STDIN)) !== false) {
    $row = array_map('trim', $row);

    // fix for dev testing
    if (count($row) < 2) {
        continue;
    }
    $key = $row[0];
    if (!array_key_exists($key, $counter)) {
        $counter[$key] = $visitors[$key] = array();
        $counter[$key]['date'] = $row[0];
        $counter[$key]['visitors'] = array();
    }
    if (!array_key_exists($row[1], $counter[$key]['visitors'])) {
        $counter[$key]['visitors'][$row[1]] = 0;
    }

    $counter[$key]['visitors'][$row[1]] += $row[2];
}
foreach($counter as $info) {
    $record = array();
    $record[] = $info['date']; // date
    $record[] = array_sum($info['visitors']); // visits
    $record[] = count($info['visitors']); // visitors
    echo implode(',', $record).PHP_EOL;
}
