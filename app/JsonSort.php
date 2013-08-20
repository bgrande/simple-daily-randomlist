<?php

require_once(APPLICATION_PATH . "/app/SortInterface.php");
require_once(APPLICATION_PATH . "/app/AbstractSort.php");

class JsonSort extends AbstractSort implements SortInterface
{     
    /**
     * @return string;
     */
    public function getSortedList()
    {
        $devList = $this->_sortList();
        return json_encode($devList);
    }    
}