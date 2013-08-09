<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

require_once(APPLICATION_PATH . "/app/Sort.php");

$sort = new Sort(APPLICATION_PATH . "/src/devlist.json");

if (php_sapi_name() == "cli") {
    echo $sort->getListForCli();
} else {
    if ($_GET['resetListAndGenerateNew'] == true) {
        $sort->resetList();
    }
    echo $sort->getListAsJson();
}
