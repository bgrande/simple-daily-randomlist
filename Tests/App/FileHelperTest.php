<?php

namespace dailySort\Tests\App;

use dailySort\App\Helper;

class FileHelperTest extends \PHPUnit_Framework_TestCase
{

    public function testSanatizeInput()
    {
        $input = "asdasdasd\\asdasdasd/asdasda0/aasda-s.d/.././";
        $expected = "asdasdasdasdasdasdasdasda0aasda-sd";

        $sanatized = Helper\File::sanatizeInput($input);

        $this->assertEquals($expected, $sanatized);
    }

}
