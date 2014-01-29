<?php

require_once("bootstrap.php");

$listId = null;
$isAllowed = false;

try {
    if (empty($_FILES) || !isset($_FILES['list-upload-file'])) {
        throw new RuntimeException('No valid file provided!');
    }

    // @todo make session checking right!
    // @todo this is not save! we need another credential here (email with link) (&admin=session_id())
    if (isset($_GET['listid']) && session_id() == $_GET['listid']) {
        $hash = \dailySort\App\Helper\File::sanatizeInput($_GET['listid']);
        $isAllowed = true;
    } else if (!isset($_GET['listid'])) {
        $hash = \dailySort\App\Helper\Hash::createListHash();
        session_id();
        session_set_cookie_params(time() + 691200);
        session_start();
    } else {
        throw new RuntimeException('you are not allowed to upload files!');
    }

    $targetFilePath = \dailySort\App\Helper\File::getUploadPath($hash);
    $success = \dailySort\App\Helper\File::uploadFile($targetFilePath, $_FILES['list-upload-file']);

    $targetUrl = 'mandatorindex.php?listid=' . $hash;
} catch (Exception $e) {
    $targetUrl = 'mandatorindex.php?error=' . urlencode($e->getMessage());
}

header('Location: ' . $targetUrl);