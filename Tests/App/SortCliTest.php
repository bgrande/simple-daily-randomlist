<?php

namespace sort\Tests\App;

use sort\App;

class SortCliTest extends SortAbstract
{

    protected $_formattedDate;

    protected function setUp()
    {
        parent::setUp();

        $date = new \DateTime();
        $this->_formattedDate = $date->format('Y-m-d');
    }

    public function testSortCliWithCacheEnabled()
    {
        $repository = $this->_getRepositoryMock();

        $repository->expects($this->any())
            ->method('getOutputList')
            ->will($this->returnValue($this->_firstContent));

        $cliSort = new App\CliSort($repository);
        $sortedList = $cliSort->getSortedList();

        $testResult = "today's ($this->_formattedDate) almost completely random daily list:\nDummy2\nDummy3\nDummy5\nDummy4\nDummy1\nDummy6";

        $this->assertEquals($testResult, $sortedList);

        $cliSort2 = new App\CliSort($repository);
        $sortedList2 = $cliSort2->getSortedList();

        $this->assertEquals($testResult, $sortedList2);

        $this->assertEquals($sortedList, $sortedList2);
    }

    public function testSortCliWithCacheDisabled()
    {
        $repository1 = $this->_getRepositoryMock();

        $repository1->expects($this->any())
            ->method('getOutputList')
            ->will($this->returnValue($this->_firstContent));

        $cliSort = new App\CliSort($repository1);
        $sortedList = $cliSort->getSortedList();

        $testResult = "today's ($this->_formattedDate) almost completely random daily list:\nDummy2\nDummy3\nDummy5\nDummy4\nDummy1\nDummy6";

        $this->assertEquals($testResult, $sortedList);

        $repository2 = $this->_getRepositoryMock();

        $repository2->expects($this->any())
            ->method('getOutputList')
            ->will($this->returnValue($this->_secondContent));

        $cliSort2 = new App\CliSort($repository2);
        $sortedList2 = $cliSort2->getSortedList();

        $testResult2 = "today's ($this->_formattedDate) almost completely random daily list:\nDummy2\nDummy1\nDummy6\nDummy4\nDummy3\nDummy5";

        $this->assertEquals($testResult2, $sortedList2);

        $this->assertNotEquals($sortedList, $sortedList2);
    }

    public function testSortCliWithCacheEnabledAndReset()
    {
        $repository = $this->_getRepositoryMock();

        $repository->expects($this->at(0))
            ->method('getOutputList')
            ->will($this->returnValue($this->_firstContent));

        $repository->expects($this->at(1))
            ->method('getOutputList')
            ->will($this->returnValue(false));

        $repository->expects($this->once())
            ->method('getOriginalList')
            ->will($this->returnValue(json_decode($this->_sourceContent)));

        $repository->expects($this->any())
            ->method('resetList');

        $cliSort = new App\CliSort($repository);
        $sortedList1 = $cliSort->getSortedList();

        $testResult = "today's ($this->_formattedDate) almost completely random daily list:\nDummy2\nDummy3\nDummy5\nDummy4\nDummy1\nDummy6";

        $this->assertEquals($testResult, $sortedList1);

        $cliSort->resetList();
        
        $sortedList2 = $cliSort->getSortedList();

        // @todo we need to inject the sortlib to test this:
        //$testResult2 = "today's ($this->_formattedDate) almost completely random daily list:\nDummy2\nDummy1\nDummy6\nDummy4\nDummy3\nDummy5";
        //$this->assertEquals($testResult2, $sortedList2);

        $this->assertNotEquals($sortedList1, $sortedList2);
    }

}
