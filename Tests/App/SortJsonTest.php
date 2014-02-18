<?php

namespace sort\Tests\App;

use sort\App;

require_once 'vfsStream/vfsStream.php';

class SortJsonTest extends \PHPUnit_Framework_TestCase 
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

    public function testSortJsonWithCacheEnabled()
    {
        $jsonSort = new App\JsonSort(\vfsStream::url($this->_sourceFile));
        $sortedList = $jsonSort->getSortedList();
        
        $this->assertCount(6, json_decode($sortedList));  

        $jsonSort2 = new App\JsonSort(\vfsStream::url($this->_sourceFile));
        $sortedList2 = $jsonSort2->getSortedList();
        
        $this->assertCount(6, json_decode($sortedList2));
        
        $this->assertEquals($sortedList, $sortedList2);
    }

    public function testSortJsonWithCacheDisabled()
    {
        $jsonSort = new App\JsonSort(\vfsStream::url($this->_sourceFile), false);
        $sortedList = $jsonSort->getSortedList();

        $this->assertCount(6, json_decode($sortedList));

        $jsonSort2 = new App\JsonSort(\vfsStream::url($this->_sourceFile), false);
        $sortedList2 = $jsonSort2->getSortedList();
        
        $this->assertCount(6, json_decode($sortedList2));

        $this->assertNotEquals($sortedList, $sortedList2); // well this might sometimes be equal though...
    }

    public function testSortJsonWithCacheEnabledAndReset()
    {
        $jsonSort = new App\JsonSort(\vfsStream::url($this->_sourceFile), true);
        $sortedList = $jsonSort->getSortedList();

        $this->assertCount(6, json_decode($sortedList));

        $jsonSort->resetList();
        
        $sortedList2 = $jsonSort->getSortedList();

        $this->assertCount(6, json_decode($sortedList2));

        $this->assertNotEquals($sortedList, $sortedList2); // well this might sometimes be equal though...
    }
}
