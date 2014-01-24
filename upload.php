<?php

require_once("bootstrap.php");

$listId = null;
$isAllowed = false;

try {
    if (empty($_FILES) || !isset($_FILES['list-upload-file'])) {
        throw new RuntimeException('No valid file provided!');
    }

    // @todo use own (static) class
    // @todo make session checking right!
    // @todo this is not save! we need another credential here (email with link) (&admin=session_id())
    if (isset($_GET['listid']) && session_id() == $_GET['listid']) {
        $hash = trim(preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $_GET['listid']));
        $isAllowed = true;
    } else if (!isset($_GET['listid'])) {
        $base = microtime(true) . '-' . $_SERVER['REMOTE_ADDR'] . '-' . mt_rand(mt_rand(1,10),999999);
        $hash = hash("sha256", $base);
        session_id();
        session_set_cookie_params(time() + 691200);
        session_start();
    } else {
        throw new RuntimeException('you are not allowed to upload files!');
    }

    $uploadDir = sprintf("src/%s", $hash);

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir);
    }

    $targetFilePath = $uploadDir . "/list.json";

    if ('application/octet-stream' == $_FILES['list-upload-file']['type']) {
        $uploadFile = $_FILES['list-upload-file'];
        move_uploaded_file($uploadFile['tmp_name'], $targetFilePath);
    } else {
        throw new RuntimeException('No valid file provided');
    }

    $targetUrl = 'mandatorindex.php?listid=' . $hash;
} catch (Exception $e) {
    $targetUrl = 'mandatorindex.php?error=' . urlencode($e->getMessage());
}

header('Location: ' . $targetUrl);