<?php
class Sortlib 
{
    /**
     * @param array $devList
     */
    public function __construct($devList)
    {
        $this->_devs = $devList;
    }

    /**
     * @param array    $devArray
     * @param DateTime $date
     *
     * @return array
     */
    protected function _shuffleArray($devArray, $date) 
    {
        $maxDevIndex  = count($devArray) - 1;
        $randomIndex  = rand(0, $maxDevIndex);
        $randomPick   = $devArray[array_rand($devArray)];
        $randomSecond = str_pad(rand(0, 59), 2, "0", STR_PAD_LEFT); 
        $second       = $date->format('s');
    
        shuffle($devArray);

        if ($devArray[$randomIndex] == $randomPick || $randomSecond == $second) {
            $devArray = $this->_shuffleArray($devArray, $date);
        }

        return $devArray;
    }

    /**
     * @param  DateTime $date
     * @return array
     */
    public function run($date) 
    {	 
        $sortedDevs = $this->_shuffleArray($this->_devs, $date);

        return $sortedDevs;
    }
}
