<?php

require_once(APPLICATION_PATH . "/app/SortInterface.php");
require_once(APPLICATION_PATH . "/app/AbstractSort.php");

class CliSort extends AbstractSort implements SortInterface
{   
    /**
     * @return string
     */
    public function getSortedList()
    {
        $sortedList = $this->_sortList();
        $title = "today's (" .
            $this->_date->format('Y-m-d') .
            ") almost completely random daily list: \n";

        return $title . implode("\n", $sortedList) . "\n";
    }    
}