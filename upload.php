<?php

require_once("bootstrap.php");

$listId = null;
$initiatorSession = null;

try {
    // @todo use own (static) class
    // @todo make session checking right!
    if (isset($_COOKIE["initiatorsession"])) {
        $initiatorSession = $_COOKIE["initiatorsession"];
    } else {
        throw new RuntimeException('you are not allowed to upload files!');
    }

    // @todo use own (static) class
    if (isset($_GET['listid'])) {
        $hash = trim(preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $_GET['listid']));
    } else {
        $base = time() . '-' . $_SERVER['REMOTE_ADDR'];
        $hash = hash("sha256", $base);
    }

    $targetFilePath = sprintf("src/list-%s.json", $hash);

    if (!empty($_FILES) && isset($_FILES['list-upload-file']) && 'application/octet-stream' == $_FILES['list-upload-file']['type']) {
        $uploadFile = $_FILES['list-upload-file'];
        move_uploaded_file($uploadFile['tmp_name'], $targetFilePath);
    } else {
        throw new RuntimeException('No valid file provided');
    }

    $targetUrl = 'mandatorindex.php?listid=' . $hash;

    // @todo make session handling right -> see above
    session_id($hash);
    session_name('initiatorsession');
    session_set_cookie_params(time() + 691200);
    session_start();
} catch (Exception $e) {
    $targetUrl = 'mandatorindex.php?error=' . urlencode($e->getMessage());
}

header('Location: ' . $targetUrl);