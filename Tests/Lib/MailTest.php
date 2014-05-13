<?php

namespace sort\Tests\Lib;

use sort\Lib;

class MailTest extends \PHPUnit_Framework_TestCase
{

    public function testSendMail()
    {
        $root = \vfsStream::setup('test');

        $file = new \vfsStreamFile('test.txt');
        $file->setContent(
            'test'
        );
        $root->addChild($file);

        /** @var \sort\Lib\Mail|\PHPUnit_Framework_MockObject_MockObject $mailLib */
        $mailLib = $this->getMock(
            'sort\Lib\Mail',
            array('_sendMail'),
            array('foobar')
        );

        $expectedMessage = "This is a multi-part message in MIME format.\r\n\r\n--8843d7f92416211de9ebb963ff4ce28125932878\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\nContent-Disposition: inline\r\n\r\nbar\r\n\r\n--8843d7f92416211de9ebb963ff4ce28125932878\r\nContent-Disposition: attachment;\r\n\tfilename=\"test.txt\";\r\nContent-Type: text/plain;\r\n	name=\"test.txt\"\r\nContent-Transfer-Encoding: base64\r\n\r\ndGVzdA==";
        $expectedHeader = "Mime-Version: 1.0\r\nContent-Type: multipart/mixed;\r\n	boundary=8843d7f92416211de9ebb963ff4ce28125932878\r\nBcc: foo@test.de\r\nFrom: bar@test.de";

        $mailLib->expects($this->once())
            ->method('_sendMail')
            ->with('test@test.de', 'foo', $expectedMessage, $expectedHeader);

        $mailLib->setTo('test@test.de');
        $mailLib->setSubject('foo');
        $mailLib->setMessage('bar');
        $mailLib->setFrom('bar@test.de');
        $mailLib->setBcc('foo@test.de');
        $mailLib->setFile(\vfsStream::url('test/test.txt'));

        $mailLib->sendMail();
    }

}
