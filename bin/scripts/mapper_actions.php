#!/usr/bin/php
<?php

// input comes from STDIN (standard input)
while (($row = fgetcsv(STDIN)) !== false) {
    // fix for dev testing
    if (count($row) < 2) {
        continue;
    }
    $record = array();
    $record['idlink_va'] = $row[0];
    $record['visitor_id'] = $row[1];
    $record['time_spent'] = $row[2];
    $record['datetime'] = $row[3];

    fputcsv(STDOUT, array(
        date('Y-m-d', strtotime($record['datetime'])),
        $record['visitor_id'],
        (int) $record['time_spent'],
        1
    ));
}