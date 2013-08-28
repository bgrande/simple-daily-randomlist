<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

require_once("bootstrap.php");

$error = $sort = null;
$type = 'json';

if (php_sapi_name() == "cli") { 
    $type = "cli";    
}

try {
    $sort = dailySort\App\SortFactory::factory($type, APPLICATION_PATH . "/src/devlist.json");
    
    if ('json' == $type && $_GET['resetListAndGenerateNew'] == true) {
        $sort->resetList();
    } 
    
    echo $sort->getSortedList();
} catch (Exception $e) {
    $error = $e->getMessage();
    echo ($type == "cli") ? $error . "\n" : json_encode(array('error' => $error));
}