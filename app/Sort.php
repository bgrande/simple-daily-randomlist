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
     * @param string $sourceFile
     */
    public function __construct($sourceFile)
    {
        $this->_sourceFile = APPLICATION_PATH . '/' . $sourceFile;

        if (file_exists($sourceFile)) {
            $devListJson = file_get_contents($sourceFile);
        } else {
            $devListJson = '[ "dummy", "dummy2" ]';
        }

        $this->_date = new DateTime();
        $this->_devList = json_decode($devListJson);
    }

    /**
     * @return string
     */
    public function getListForCli()
    {
        $sortedList = $this->_sortList();
        $devs = "today's (" . $this->_date->format('Y-m-d') . ") almost completely random daily list: \n";

        return implode("\n", $sortedList) . "\n";
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
     * @return array
     */
    protected function _sortList()
    {
        $sortlib = new Sortlib($this->_devList);
        return $sortlib->run($this->_date);
    }

}