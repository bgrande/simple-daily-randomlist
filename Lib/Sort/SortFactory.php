<?php

namespace sort\Lib\Sort;

class SortFactory
{
    const TYPE_RANDOM = 'random';

    private static $_typeMapper = array(
        self::TYPE_RANDOM => 'sort\Lib\Sort\Random',
    );

    /**
     * the parametrized function to get create an instance
     *
     * @static
     *
     * @param string $type
     * @param array $list
     *
     * @throws \InvalidArgumentException
     *
     * @return Random
     */
    public static function factory($type, $list = array())
    {
        if (empty($list)) {
            throw new \InvalidArgumentException("The list you provided ist empty!");
        }

        switch ($type) {
            case self::TYPE_RANDOM: $className = self::$_typeMapper[self::TYPE_RANDOM];
                break;
            default: $className = null;

        }

        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Missing sort class $className!");
        }

        return new $className($list);
    }

}
 