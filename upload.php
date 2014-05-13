<?php

require_once("bootstrap.php");

$listId = null;
$isAllowed = false;

try {
    if (empty($_FILES) || !isset($_FILES['list-upload-file'])) {
        throw new RuntimeException('No valid file provided!');
    }

    $fileHelper = new \sort\Lib\Helper\File();
    $fileHelper->setFile($_FILES['list-upload-file']);

    $email = isset($_POST['list-upload-email']) ? $_POST['list-upload-email'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;

    /* @todo merge duplicate code */
    if (isset($_GET['listid']) && isset($_GET['cid'])) {
        $hash = $fileHelper->sanatizeInput($_GET['listid']);
        $cHash = $fileHelper->sanatizeInput($_GET['cid']);

        $controllerPersistence = 'persistence_' . $salt . '_' . $cHash;
        $persistencePath = sprintf('src/%s', $controllerPersistence);
        $fileExists = file_exists($persistencePath);

        if (!$fileExists) {
            throw new RuntimeException('No valid controller id provided');
        }

        file_put_contents($persistencePath, time());
    } else if (!isset($_GET['listid'])) {
        $salt = $_SERVER['REMOTE_ADDR'] . $type;
        $hash = \sort\Lib\Helper\Hash::createListHash($salt);
        $cHash = \sort\Lib\Helper\Hash::createListHash($email);

        $controllerPersistence = 'persistence_' . $salt . '_' . $cHash;
        $persistencePath = sprintf('src/%s', $controllerPersistence);
        $fileExists = file_exists($persistencePath);
    } else {
        throw new RuntimeException('you are not allowed to upload files!');
    }

    $targetFilePath = $fileHelper->getUploadPath($hash);
    $success = $fileHelper->uploadFile($targetFilePath);

    if (!$fileExists) {
        /* @todo replace with real relative link */
        $link = sprintf('http://bgrande.de/sort/upload.php?listid=%s&cid=%s', $hash, $cHash);
        $subject = sprintf('Your sortlist admininistration link for list %s', $hash);
        $message = sprintf("Dear owner of address %s,\r\n
you are receiving this mail because you or somebody else created a list sorted by %s.
If you did not create this mail please ignore it!

The link to administrate this list is ", $email, $type, $link);

        $mailLib = new \sort\Lib\Mail();
        $mailLib->setFrom('no-reply@bgrande.de');
        $mailLib->setTo($email);
        $mailLib->setSubject($subject);
        $mailLib->setMessage($message);

        /* @todo install sendmail to test... */
        //$mailLib->sendMail();
    }

    $targetUrl = 'mandatorindex.php?listid=' . $hash;
} catch (Exception $e) {
    $targetUrl = 'mandatorindex.php?error=' . urlencode($e->getMessage());
}

header('Location: ' . $targetUrl);