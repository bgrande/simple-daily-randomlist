<?php

namespace sort\Tests\App;

use sort\App;

require_once 'vfsStream/vfsStream.php';

class SortJsonTest extends SortAbstract
{

    public function testSortJsonWithCacheEnabled()
    {
        $repository = $this->_getRepositoryMock();

        $repository->expects($this->any())
            ->method('getOutputList')
            ->will($this->returnValue($this->_firstContent));

        $jsonSort = new App\JsonSort($repository);
        $sortedList = $jsonSort->getSortedList();

        $testResult = json_encode($this->_firstContent);

        $this->assertEquals($testResult, $sortedList);

        $jsonSort2 = new App\JsonSort($repository);
        $sortedList2 = $jsonSort2->getSortedList();

        $this->assertEquals($testResult, $sortedList2);

        $this->assertEquals($sortedList, $sortedList2);
    }

    public function testSortJsonWithCacheDisabled()
    {
        $repository1 = $this->_getRepositoryMock();

        $repository1->expects($this->any())
            ->method('getOutputList')
            ->will($this->returnValue($this->_firstContent));

        $jsonSort = new App\JsonSort($repository1);
        $sortedList = $jsonSort->getSortedList();

        $testResult = json_encode($this->_firstContent);

        $this->assertEquals($testResult, $sortedList);

        $repository2 = $this->_getRepositoryMock();

        $repository2->expects($this->any())
            ->method('getOutputList')
            ->will($this->returnValue($this->_secondContent));

        $jsonSort2 = new App\JsonSort($repository2);
        $sortedList2 = $jsonSort2->getSortedList();

        $testResult2 = json_encode($this->_secondContent);

        $this->assertEquals($testResult2, $sortedList2);

        $this->assertNotEquals($sortedList, $sortedList2);
    }

    public function testSortJsonWithCacheEnabledAndReset()
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

        $jsonSort = new App\JsonSort($repository);
        $sortedList1 = $jsonSort->getSortedList();

        $testResult = json_encode($this->_firstContent);

        $this->assertEquals($testResult, $sortedList1);

        $jsonSort->resetList();

        $sortedList2 = $jsonSort->getSortedList();

        // @todo we need to inject the sortlib to test this:
        //$testResult2 = json_encode($this->_secondContent);
        //$this->assertEquals($testResult2, $sortedList2);

        $this->assertNotEquals($sortedList1, $sortedList2);
    }
}
