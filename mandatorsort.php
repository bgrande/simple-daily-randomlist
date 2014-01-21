<?php

require_once("bootstrap.php");

$listId = null;

// @todo use own class
try {
    if (isset($_GET['listid']) || isset($argv['listid'])) {
        $listId = $_GET['listid'] ? $_GET['listid'] : $argv['listid'];

        // @todo better use listid as subfolder and save date specific lists in subfolder
        $listId = trim(preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $_GET['listid']));

        $filePath = sprintf("src/list-%s.json", $listId);

        if (!file_exists($filePath)) {
            throw new RuntimeException('no valid listid provided!');
        }
    } else {
        throw new RuntimeException('no listid provided!');
    }
} catch (Exception $e) {
    echo json_encode(
        array("error" => $e->getMessage())
    );
    return;
}

include_once('sort.php');