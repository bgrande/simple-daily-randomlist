<?php
// @todo bootstrapping!
$all = require_once("sort.php");

echo "today's (" . $date->format('Y-m-d') . ") almost completely random daily list: \n";
echo implode("\n", $all) . "\n";
