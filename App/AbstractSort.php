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
     * @param \sort\App\Repository $repository
     */
    public function __construct($repository)
    {
        $this->_repository = $repository;

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

        /** @var Sort\Random $sortLib */
        $sortLib = Sort\SortFactory::factory(Sort\SortFactory::TYPE_RANDOM, $this->_repository->getOriginalList());
        $sortedList = $sortLib->sort($this->_date);

        $this->_repository->setOutputFile($sortedList);

        return $sortedList;
    }
}