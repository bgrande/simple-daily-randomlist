<?php

namespace dailySort\Tests\Lib;

use dailySort\Lib\Helper;

class HashHelperTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateListHash()
    {
        $salt = "bar";
        $hash = Helper\Hash::createListHash($salt);

        $this->assertNotEmpty($hash);
        $this->assertEquals(64, strlen($hash));
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage A salt is required!
     */
    public function testCreateListHashMissingSalt()
    {
        $hash = Helper\Hash::createListHash();
    }

}
