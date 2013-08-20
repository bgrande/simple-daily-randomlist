<?php

class Sortlib 
{
    /**
     * @var array
     */
    protected $_sortList;
    
    /**
     * @param array $sortList
     */
    public function __construct($sortList)
    {
        $this->_sortList = $sortList;
    }

    /**
     * Completely nonsense random sorting
     * 
     * @param array    $sortList
     * @param DateTime $date
     *
     * @return array
     */
    protected function _shuffleArray($sortList, $date)
    {
        $maxDevIndex  = count($sortList) - 1;
        $randomIndex  = rand(0, $maxDevIndex);
        $randomPick   = array_rand($sortList);
        $randomSecond = str_pad(rand(0, 59), 2, "0", STR_PAD_LEFT); 
        $second       = $date->format('s');
    
        shuffle($sortList);

        if ($randomIndex == $randomPick || $randomSecond == $second) {
            $sortList = $this->_shuffleArray($sortList, $date);
        }

        return $sortList;
    }

    /**
     * @param  DateTime $date
     * @return array
     */
    public function run($date) 
    {	 
        $sortedDevs = $this->_shuffleArray($this->_sortList, $date);

        return $sortedDevs;
    }
}
