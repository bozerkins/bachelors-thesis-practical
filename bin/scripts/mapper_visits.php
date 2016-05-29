#!/usr/bin/php
<?php

// input comes from STDIN (standard input)
while (($row = fgetcsv(STDIN)) !== false) {
    $record = array();
    $record['visit_id'] = $row[0];
    $record['visitor_id'] = $row[1];
    $record['datetime'] = $row[2];

    fputcsv(STDOUT, array(
        date('Y-m-d', strtotime($record['datetime'])),
        $record['visitor_id'],
        1
    ));
}

