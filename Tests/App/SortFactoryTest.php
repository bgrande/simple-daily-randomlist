<?php

namespace dailySort\Tests\App;

use dailySort\App;

class SortFactoryTest extends \PHPUnit_Framework_TestCase 
{
    protected $_sourceFile = '';

    /**
     * @var App\SortFactory
     */
    protected $_sortFactory;

    /**
     * @var \vfsStream
     */
    protected $_root;

    protected function setUp()
    {
        //$this->_root = new \vfsStream();
        $this->_sortFactory = new App\SortFactory(
            $this->_sourceFile
        );
    }

    public function testJsonWithSourceFile()
    {
        
    }

    public function atestCliWithSourceFile()
    {

    }

    public function atestJsonWithoutSourceFile()
    {

    }

    public function atestCliWithoutSourceFile()
    {

    }
}
