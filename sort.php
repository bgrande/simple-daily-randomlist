<?php

$error = $sort = null;
$type = "json";

if (php_sapi_name() == "cli") { 
    $type = "cli";    
}

try {
    $sort = sort\App\SortFactory::factory($type, $filePath);

    if ("json" == $type && ((isset($argv) && $argv['resetListAndGenerateNew'] == true) || (isset($_GET) && $_GET['resetListAndGenerateNew'] == true))) {
        $sort->resetList();
    }

    echo $sort->getSortedList();
} catch (Exception $e) {
    echo json_encode(
        array("error" =>  $e->getMessage())
    );
}