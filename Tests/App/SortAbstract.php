<?php

namespace sort\Tests\App;

use sort\App;

abstract class SortAbstract extends \PHPUnit_Framework_TestCase
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

    protected $_firstContent = array(
        "Dummy2",
        "Dummy3",
        "Dummy5",
        "Dummy4",
        "Dummy1",
        "Dummy6"
    );

    protected $_secondContent = array(
        "Dummy2",
        "Dummy1",
        "Dummy6",
        "Dummy4",
        "Dummy3",
        "Dummy5"
    );

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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getRepositoryMock()
    {
        $repository = $this->getMock(
            'sort\App\Repository',
            array('getOutputList', 'getOriginalList', 'setOutputFile', 'resetList'),
            array(\vfsStream::url($this->_sourceFile), '1day')
        );

        return $repository;
    }
}
