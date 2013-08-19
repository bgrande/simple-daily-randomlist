<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

require_once(APPLICATION_PATH . "/app/Sort.php");

$error = $sort = null;

try {
    $sort = new Sort(APPLICATION_PATH . "/src/devlist.json");
} catch (Exception $e) {
    $error = $e->getMessage();   
}

if (php_sapi_name() == "cli") { 
    if (null !== $error) {
        echo $error . "\n";
    } else {
        echo $sort->getListForCli();
    }
} else {      
    if (null !== $error) {
        echo json_encode(array('error' => $error));
    } else if ($_GET['resetListAndGenerateNew'] == true) {
        $sort->resetList();
    } else {
        echo $sort->getListAsJson();
    }
}
