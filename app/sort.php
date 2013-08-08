<?php
require_once("../lib/Sortlib.php");

$sourceFile = '../src/devlist.json';
$devList = array();

if (file_exists($sourceFile)) {
    $devList = file_get_contents($sourceFile);
} else {
    $devList = '[ "dummy", "dummy2" ]';
}

$devArray = json_decode($devList);

$date    = new DateTime();
$sortlib = new Sortlib($devArray);
$all     = $sortlib->run($date);

echo json_encode($all);
return;