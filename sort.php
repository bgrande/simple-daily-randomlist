<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

require_once(APPLICATION_PATH . "/app/Sort.php");

$sort = new Sort(APPLICATION_PATH . "/src/devlist.json");

if (php_sapi_name() == "cli") {
    try {
        echo $sort->getListForCli();
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }    
} else {      
    if ($_GET['resetListAndGenerateNew'] == true) {
        $sort->resetList();
    }

    try {
        echo $sort->getListAsJson();
    } catch (Exception $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }    
}
