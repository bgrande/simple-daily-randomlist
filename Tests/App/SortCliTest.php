<?php

namespace dailySort\Tests\App;

use dailySort\App;

class SortCliTest extends \PHPUnit_Framework_TestCase 
{
    protected $_sourceFile = 'src/devlist.json';

    protected $_sourceContent = '[
        "Dummy1",
        "Dummy2",
        "Dummy3",
        "Dummy4",
        "Dummy5",
        "Dummy6"
    ]';

    /**
     * @var \vfsStream
     */
    protected $_root;

    protected function setUp()
    {
        $this->_root = \vfsStream::setup('src');
        $devFile = new \vfsStreamFile('devlist.json');
        $devFile->setContent(
            $this->_sourceContent
        );
        $this->_root->addChild($devFile);
    }

    public function testSortCliWithCacheEnabled()
    {
        $jsonSort = new App\CliSort(\vfsStream::url($this->_sourceFile));
        $sortedList = $jsonSort->getSortedList();
        
        $this->assertContains("almost completely random daily list", $sortedList);

        $jsonSort2 = new App\CliSort(\vfsStream::url($this->_sourceFile));
        $sortedList2 = $jsonSort2->getSortedList();

        $this->assertContains("Dummy1", $sortedList);

        $this->assertEquals($sortedList, $sortedList2);
    }

    public function testSortCliWithCacheDisabled()
    {
        $jsonSort = new App\CliSort(\vfsStream::url($this->_sourceFile), false);
        $sortedList = $jsonSort->getSortedList();

        $this->assertContains("Dummy2", $sortedList);

        $jsonSort2 = new App\CliSort(\vfsStream::url($this->_sourceFile), false);
        $sortedList2 = $jsonSort2->getSortedList();

        $this->assertContains("Dummy3", $sortedList);
        
        $this->assertNotEquals($sortedList, $sortedList2); // well this might sometimes be equal though...
    }

    public function testSortCliWithCacheEnabledAndReset()
    {
        $jsonSort = new App\CliSort(\vfsStream::url($this->_sourceFile), true);
        $sortedList = $jsonSort->getSortedList();

        $this->assertContains("Dummy4", $sortedList);

        $jsonSort->resetList();
        
        $sortedList2 = $jsonSort->getSortedList();
        
        $this->assertContains("Dummy5", $sortedList2);

        $this->assertNotEquals($sortedList, $sortedList2); // well this might sometimes be equal though...
    }
}
