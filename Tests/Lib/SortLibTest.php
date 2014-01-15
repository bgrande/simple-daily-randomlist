<?php

namespace dailySort\Tests\Lib;

use dailySort\Lib;

class SortLibTest extends \PHPUnit_Framework_TestCase 
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
     * @var Lib\SortLib
     */
    protected $_sortLib;

    protected function setUp()
    {
        $this->_sortLib = new Lib\SortLib(
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
     * @expectedExceptionMessage Argument 1 passed to dailySort\Lib\SortLib::__construct() must be of the type array, null given
     */
    public function testSortWithoutSortData()
    {        
        $sortlib = new Lib\SortLib(
            null
        );
    }
}
