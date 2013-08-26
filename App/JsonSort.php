<?php

namespace dailySort\App;

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