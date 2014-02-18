<?php

$error = $sort = null;
$type = "json";

if (php_sapi_name() == "cli") { 
    $type = "cli";    
}

try {
    $sort = sort\App\SortFactory::factory($type, $filePath);

    if ("json" == $type && ($argv['resetListAndGenerateNew'] == true || $_GET['resetListAndGenerateNew'] == true)) {
        $sort->resetList();
    }

    echo $sort->getSortedList();
} catch (Exception $e) {
    echo json_encode(
        array("error" =>  $e->getMessage())
    );
}