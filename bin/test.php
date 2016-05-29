<?php
echo substr(sha1('21'), 0, 16);
echo PHP_EOL;
echo substr(sha1(''), 0, 16);
echo PHP_EOL;
echo 'F61A5D5D130680E3';
echo PHP_EOL;
$hex = hex2bin("F61A5D5D130680E3");
var_dump($hex);