<?php

namespace sort\App;

use sort\Lib\Sort;

class AbstractSort
{
    /**
     * @var string
     */
    protected $_sourceFile;

    /**
     * @var array
     */
    protected $_devList = array();

    /**
     * @var \DateTime
     */
    protected $_date;

    /**
     * bool
     */
    protected $_useCache;

    /**
     * string
     */
    protected $_cachedOutputFile;

    /**
     * @param string $sourceFile
     * @param bool $useCache
     *
     * @throws \RuntimeException
     */
    public function __construct($sourceFile, $useCache = true)
    {
        $this->_sourceFile = $sourceFile;

        if (file_exists($sourceFile)) {
            $devListJson = file_get_contents($sourceFile);
        } else {
            throw new \RuntimeException(
                sprintf(
                    'Could not read list input file %s',
                    basename($sourceFile)
                )
            );
        }

        $this->_useCache = $useCache;
        $this->_date = new \DateTime();
        $this->_devList = json_decode($devListJson);

        if ($this->_useCache) {
            $this->_initializeCacheFile();
        }
    }    

    /**
     * empty cached list     
     */
    public function resetList()
    {
        $this->_deleteOutputFile($this->_cachedOutputFile);
    }

    /**
     * @return bool|string
     */
    protected function _getCachedOutputFileContent()
    {
        if (file_exists($this->_cachedOutputFile)) {
            return file_get_contents($this->_cachedOutputFile);
        }

        return false;
    }

    /**    
     * @param string $fileToDelete    
     */
    protected function _deleteOutputFile($fileToDelete)
    {
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
    }

    /**
     * @return array
     */
    protected function _sortList()
    {
        $cachedList = $this->_getCachedOutputFileContent();
        if ($this->_useCache && false != $cachedList) {
            return json_decode($cachedList);
        }

        /** @var Sort\Random $sortLib */
        $sortLib = Sort\SortFactory::factory(Sort\SortFactory::TYPE_RANDOM, $this->_devList);
        $sortedList = $sortLib->sort($this->_date);

        if ($this->_useCache && !file_exists($this->_cachedOutputFile)) {
            file_put_contents($this->_cachedOutputFile, json_encode($sortedList));
        }

        return $sortedList;
    }

    /**
     * initialize sorted cache file
     */
    protected function _initializeCacheFile()
    {
        $yesterday = new \DateTime("-1 day");
        $basePath = dirname($this->_sourceFile) . DIRECTORY_SEPARATOR .
            basename($this->_sourceFile, '.json') . '_';

        $fileToDelete = $basePath .
            $yesterday->format("Y-m-d") .
            ".json";

        $this->_deleteOutputFile(
            $fileToDelete
        );

        $this->_cachedOutputFile = $basePath .
            $this->_date->format("Y-m-d") .
            ".json";
    }
}