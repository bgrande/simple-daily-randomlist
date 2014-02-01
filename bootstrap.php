<?php

if (!ini_get('date.timezone')) {
    date_default_timezone_set('Europe/Berlin');
}

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

spl_autoload_register(function ($class) {
    if (preg_match('#^dailySort\\\\(.+)$#', $class, $ret)) {
        $relPath = str_replace('dailySort', '', $ret[0]);
        $relPath = str_replace('\\', DIRECTORY_SEPARATOR, $relPath);
        require_once APPLICATION_PATH . DIRECTORY_SEPARATOR . $relPath . '.php';
    }
});