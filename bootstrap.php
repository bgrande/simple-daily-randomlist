<?php

spl_autoload_register(function ($class) {
    if (preg_match('#^dailySort\\\\(.+)$#', $class, $ret)) {        
        $relPath = str_replace('\\', DIRECTORY_SEPARATOR, $ret[0]);
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $relPath . '.php';
    }
});