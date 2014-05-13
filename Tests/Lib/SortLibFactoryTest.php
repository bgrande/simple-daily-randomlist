<?php

namespace sort\Tests\Lib;

use sort\Lib\Sort;

class SortLibFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    protected $_list;

    /**
     * @param string $type
     * 
     * @param array $list
     * 
     * @return \sort\Lib\Sort\Random
     */
    protected function _setFactory($type, $list = null)
    {
        return new Sort\SortFactory(
            $type,
            (null === $list) ? $this->_list : $list
        );
    }

    public function garbageProvider()
    {
        return array(
            array(null),
            array('bar'),
        );
    }

    public function testRandomWithList()
    {
        $sortObject = Sort\SortFactory::factory(
            'random',
            array(
                'Dev1',
                'Dev2',
                'Dev3',
                'Dev4',
            )
        );
        $this->assertInstanceOf('sort\Lib\Sort\Random', $sortObject);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The list you provided ist empty!
     */
    public function testWithoutValidList()
    {
        Sort\SortFactory::factory('random', null);
    }

    public function testSetSortList()
    {
        $sortLib = Sort\SortFactory::factory('random');
        $sortLib->setSortList(
            array(
                'Dev1',
                'Dev2',
                'Dev3',
                'Dev4',
            )
        );

        $this->assertContains('Dev1', $sortLib->sort());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing sort class
     * @dataProvider garbageProvider
     */
    public function testWithoutValidType($type)
    {
        Sort\SortFactory::factory($type, array('dev1'));
    }
}
