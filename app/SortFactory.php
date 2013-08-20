<?php

require_once(APPLICATION_PATH . "/app/CliSort.php");
require_once(APPLICATION_PATH . "/app/JsonSort.php");

class SortFactory
{
    const TYPE_CLI = 'cli'; 
    
    const TYPE_JSON = 'json';
    
    private static $_typeMapper = array(
        self::TYPE_CLI => 'CliSort',
        self::TYPE_JSON => 'JsonSort',        
    );

    /**
     * the parametrized function to get create an instance
     *
     * @static
     * @param string $type
     * @param string $sourceFile
     * 
     * @throws InvalidArgumentException
     * @return JsonSort|CliSort
     */
    public static function factory($type, $sourceFile = null)
    {
        switch ($type) {
            case self::TYPE_CLI: $className = self::$_typeMapper[self::TYPE_CLI];
                break;
            case self::TYPE_JSON: $className = self::$_typeMapper[self::TYPE_JSON];
                break;
            default: $className = null;
            
        }
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Missing sort class!');
        }

        return new $className($sourceFile);
    }

}
 