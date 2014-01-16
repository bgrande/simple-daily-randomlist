<?php

require_once("bootstrap.php");

$error = $sort = null;
$type = "json";
$initiatorSession = null;
$listId = null;

// @todo use own class
if ($_COOKIE["initiatorsession"]) {
    $initiatorSession = $_COOKIE["initiatorsession"];
} else {
    $base = time() . '-' . $_SERVER['REMOTE_ADDR'];
    $hash = hash("sha256", microtime() . '-' . hash("sha256", $base));
    setcookie("intiatorsession", $hash, time() + 691200);
    $initiatorSession = $hash;
}


// @todo use own class
if ($_GET['listid']) {
    // @todo better use listid as subfolder and save date specific lists in subfolder
    $listId = trim(preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $_GET['listid']));
    $filename = "list-" . $listId . ".json";
} else {
    $base = time() . '-' . $_SERVER['REMOTE_ADDR'];
    $hash = hash("sha256", $base);
    echo json_encode(array('listid' => $hash));
    return;
}

try {
    $sort = dailySort\App\SortFactory::factory($type, APPLICATION_PATH . "/src/" . $filename);
    
    if ($_GET['resetListAndGenerateNew'] == true) {
        $sort->resetList();
    } 
    
    echo $sort->getSortedList();
} catch (Exception $e) {
    $error = $e->getMessage();
    echo json_encode(array("error" => $error));
}