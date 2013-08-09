<?php

require_once(APPLICATION_PATH . "/lib/Sortlib.php");

class Sort
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
     * @var DateTime
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
     * @param bool   $useCache
     */
    public function __construct($sourceFile, $useCache = true)
    {
        $this->_sourceFile = $sourceFile;

        if (file_exists($sourceFile)) {
            $devListJson = file_get_contents($sourceFile);
        } else {
            $devListJson = '[ "dummy", "dummy2" ]';
        }

        $this->_useCache = $useCache;
        $this->_date = new DateTime();
        $this->_devList = json_decode($devListJson);

        if ($this->_useCache) {
            $this->_initializeCacheFile();
        }
    }

    /**
     * @return string
     */
    public function getListForCli()
    {
        $sortedList = $this->_sortList();
        $title = "today's (" .
            $this->_date->format('Y-m-d') .
            ") almost completely random daily list: \n";

        return $title . implode("\n", $sortedList) . "\n";
    }

    /**
     * return json;
     */
    public function getListAsJson()
    {
        $devList = $this->_sortList();
        return json_encode($devList);
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
        // @todo error handling
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

        $sortlib = new Sortlib($this->_devList);
        $sortedList = $sortlib->run($this->_date);

        if ($this->_useCache) {
            file_put_contents($this->_cachedOutputFile, json_encode($sortedList));
        }

        return $sortedList;
    }

    protected function _initializeCacheFile()
    {
        $yesterday = new DateTime("-1 day");
        $basePath = dirname($this->_sourceFile) . '/' .
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