<?php

namespace sort\Lib\Sort;

class Random implements SortLibInterface
{
    /**
     * @var array
     */
    protected $_sortList;
    
    /**
     * @param array $sortList
     */
    public function __construct(Array $sortList)
    {
        $this->_sortList = $sortList;
    }

    /**
     * @param \DateTime $date
     *
     * @return array
     */
    public function sort($date = null)
    {
        // make sure we got a date
        if (!($date instanceof \DateTime) || empty($date)) {
            $date = new \DateTime();
        }

        $sortedDevs = $this->_shuffleArray($this->_sortList, $date);

        return $sortedDevs;
    }

    /**
     * Completely nonsense random sorting
     * 
     * @param array     $sortList
     * @param \DateTime $date
     *
     * @return array
     */
    protected function _shuffleArray($sortList, $date)
    {
        $maxDevIndex  = count($sortList) - 1;
        $randomIndex  = mt_rand(0, $maxDevIndex);
        $randomPick   = array_rand($sortList);
        $randomSecond = str_pad(mt_rand(0, 59), 2, "0", STR_PAD_LEFT);
        $second       = $date->format('s');
    
        shuffle($sortList);

        if ($randomIndex == $randomPick || $randomSecond == $second) {
            $sortList = $this->_shuffleArray($sortList, $date);
        }

        return $sortList;
    }
}
