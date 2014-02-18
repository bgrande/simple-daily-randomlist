<?php

namespace sort\Tests\Lib;

use sort\Lib;

class RandomSortLibTest extends \PHPUnit_Framework_TestCase
{
    protected $_testData = array(
        'Dev1',
        'Dev2',
        'Dev3',
        'Dev4',
        'Dev5',
        'Dev6',
        'Dev7',
    );

    /**
     * @var Lib\Sort\Random
     */
    protected $_sortLib;

    protected function setUp()
    {
        $this->_sortLib = new Lib\Sort\Random(
            $this->_testData
        );
    }
    
    public function testSortWithTodaysDate() 
    {       
        $today = new \DateTime();
        
        $sortResult = $this->_sortLib->sort($today);        
        $this->assertCount(7, $sortResult);        
    }

    public function testSortWithTomorrowsDate()
    {
        $tomorrow = new \DateTime();
        $tomorrow->modify('+1 day');

        $sortResult = $this->_sortLib->sort($tomorrow);
        $this->assertCount(7, $sortResult);
    }

    public function testSortWithYesterdaysDate()
    {
        $yesterday = new \DateTime();
        $yesterday->modify('-1 day');

        $sortResult = $this->_sortLib->sort($yesterday);
        $this->assertCount(7, $sortResult);
    }

    public function testSortWithoutDate()
    {   
        $sortResult = $this->_sortLib->sort(null);
        $this->assertCount(7, $sortResult);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage Argument 1 passed to sort\Lib\Sort\Random::__construct() must be of the type array, null given
     */
    public function testSortWithoutSortData()
    {        
        $sortlib = new Lib\Sort\Random(
            null
        );
    }
}
