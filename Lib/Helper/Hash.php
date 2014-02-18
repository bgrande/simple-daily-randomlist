<?php

namespace sort\Lib\Helper;

class Hash
{
    public static function createListHash($salt = null)
    {
        if (null == $salt) {
            throw new \BadMethodCallException('A salt is required!');
        }

        $base = sprintf(
            "%s-%s-%s",
            microtime(true),
            $salt,
            mt_rand(mt_rand(1,10),999999)
        );

        return hash("sha256", $base);
    }
}
 