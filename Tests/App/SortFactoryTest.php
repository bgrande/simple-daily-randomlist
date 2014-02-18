<?php

namespace sort\Tests\App;

use sort\App;

class SortFactoryTest extends \PHPUnit_Framework_TestCase 
{
    protected $_sourceFile = '../_files/bar.json';  

    /**
     * @var \vfsStream
     */
    protected $_root;

    /**
     * @param string $type
     * 
     * @param null $file
     * 
     * @return \sort\App\SortFactory
     */
    protected function _setFactory($type, $file = null)
    {
        return new App\SortFactory(
            $type,
            (null === $file) ? $this->_sourceFile : $file
        );
    }

    public function provider()
    {
        return array(
            array('json'),
            array('cli'),
        );
    }

    public function garbageProvider()
    {
        return array(
            array(null),
            array('bar'),
        );
    }

    public function testJsonWithSourceFile()
    {
        $sortObject = App\SortFactory::factory('json', realpath(__DIR__) . DIRECTORY_SEPARATOR . $this->_sourceFile);
        $this->assertInstanceOf('sort\App\JsonSort', $sortObject);
    }

    public function testCliWithSourceFile()
    {
        $sortObject = App\SortFactory::factory('cli', realpath(__DIR__) . DIRECTORY_SEPARATOR . $this->_sourceFile);
        $this->assertInstanceOf('sort\App\CliSort', $sortObject);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Could not read list input file
     * @dataProvider provider
     */
    public function testWithoutSourceFile($type)
    {
        App\SortFactory::factory($type);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing sort class
     * @dataProvider garbageProvider
     */
    public function testWithoutType($type)
    {
        App\SortFactory::factory($type);
    }
}
