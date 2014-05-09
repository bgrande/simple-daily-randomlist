<?php

namespace sort\Tests\App;

use sort\App;

class RepositoryTest extends \PHPUnit_Framework_TestCase
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

    public function testRepositoryInitialization()
    {
        $repository = new App\Repository(\vfsStream::url($this->_sourceFile));

        $expected1 = array("Dummy1", "Dummy2", "Dummy3", "Dummy4", "Dummy5", "Dummy6");

        $this->assertEquals($expected1, $repository->getOriginalList());
    }

    public function testRepositoryWithCacheForeverEnabled()
    {
        $repository = new App\Repository(\vfsStream::url($this->_sourceFile));

        $this->assertFalse($repository->getOutputList());

        $sorted = array("Dummy2", "Dummy4", "Dummy1", "Dummy3", "Dummy5", "Dummy6");
        $repository->setOutputFile($sorted);

        $this->assertEquals($sorted, $repository->getOutputList());
    }

    public function testRepositoryWithCache1DayEnabled()
    {
        $repository = new App\Repository(\vfsStream::url($this->_sourceFile), '1day');

        $sorted = array("Dummy2", "Dummy4", "Dummy1", "Dummy3", "Dummy5", "Dummy6");
        $repository->setOutputFile($sorted);

        /** @todo change date of outputfile */

        $this->assertEquals($sorted, $repository->getOutputList());
    }

    public function testRepositoryWithCacheDisabled()
    {
        $repository = new App\Repository(\vfsStream::url($this->_sourceFile), false);

        $sorted = array("Dummy2", "Dummy4", "Dummy1", "Dummy3", "Dummy5", "Dummy6");
        $repository->setOutputFile($sorted);

        $this->assertFalse($repository->getOutputList());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage File  is not a valid json file or empty
     */
    public function testSetWrongOutputFile()
    {
        $repository = new App\Repository(\vfsStream::url($this->_sourceFile), false);

        $repository->setOutputFile(null);
    }

    public function testResetList()
    {
        $repository = new App\Repository(\vfsStream::url($this->_sourceFile), '1day');

        $sorted = array("Dummy2", "Dummy4", "Dummy1", "Dummy3", "Dummy5", "Dummy6");
        $repository->setOutputFile($sorted);

        $repository->resetList();

        $this->assertFalse($repository->getOutputList());
    }

    public function testGetNotSetOutputFile()
    {
        $repository = new App\Repository(\vfsStream::url($this->_sourceFile));

        $devFile = new \vfsStreamFile('devlist_forever.json');
        $devFile->setContent(
            $this->_sourceContent
        );
        $this->_root->addChild($devFile);

        $expected = array("Dummy1", "Dummy2", "Dummy3", "Dummy4", "Dummy5", "Dummy6");

        $this->assertEquals($expected, $repository->getOutputList());
    }

    public function testCantGetOriginalList()
    {
        $this->_root = \vfsStream::setup('src', '0000');

        $repository = new App\Repository(\vfsStream::url($this->_sourceFile), '1day');

        $this->assertFalse($repository->getOriginalList());
    }



    // @todo test missing methods and separate tests into method tests
}
