<?php

namespace sort\App;

use sort\Lib\Sort;

class AbstractSort
{
    /**
     * @var string
     */
    protected $_repository;

    /**
     * @var \DateTime
     */
    protected $_date;

    /**
     * @var \sort\Lib\Sort\Random
     */
    protected $_sortLib;

    /**
     * @param \sort\App\Repository $repository
     * @param string $sortType
     */
    public function __construct($repository, $sortType = Sort\SortFactory::TYPE_RANDOM)
    {
        $this->_repository = $repository;
        $this->_sortLib = Sort\SortFactory::factory($sortType);

        $this->_date = new \DateTime();
    }    

    /**
     * empty cached list     
     */
    public function resetList()
    {
        $this->_repository->resetList();
    }

    /**
     * @return array
     */
    protected function _sortList()
    {
        $list = $this->_repository->getOutputList();

        if ($list) {
            return $list;
        }

        $this->_sortLib->setSortList($this->_repository->getOriginalList());
        $sortedList = $this->_sortLib->sort($this->_date);

        $this->_repository->setOutputFile($sortedList);

        return $sortedList;
    }
}