<?php

require_once("bootstrap.php");

$listId = null;

try {
    if (isset($_GET['listid']) || isset($argv['listid'])) {
        $listId = $_GET['listid'] ? $_GET['listid'] : $argv['listid'];

        $fileHelper = new \sort\Lib\Helper\File();
        $filePath = $fileHelper->getFilePathById($listId);
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