<?php

namespace dailySort\Lib\Helper;

class Hash
{
    public static function createListHash()
    {
        $base = microtime(true) . '-' . $_SERVER['REMOTE_ADDR'] . '-' . mt_rand(mt_rand(1,10),999999);
        return hash("sha256", $base);
    }
}
 