<?php

namespace sort\Tests\Lib;

use sort\Lib\Helper;

class FileHelperTest extends \PHPUnit_Framework_TestCase
{

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
        $devFile = new \vfsStreamFile('list.json');
        $devFile->setContent(
            $this->_sourceContent
        );
        $subdir = new \vfsStreamDirectory('foo');
        $subdir->addChild($devFile);

        $this->_root->addChild($subdir);
    }

    public function testGetFilePathById()
    {
        $expected = \vfsStream::url('src/foo/list.json');
        $fileHelper = new Helper\File(\vfsStream::url('src'));

        $filepath = $fileHelper->getFilePathById('foo');

        $this->assertEquals($expected, $filepath);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage no valid listid provided!
     */
    public function testGetFilePathByInvalidId()
    {
        $fileHelper = new Helper\File(\vfsStream::url('src'));

        $filepath = $fileHelper->getFilePathById('bar');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage no valid listid provided!
     */
    public function testGetFilePathByIdWithInvalidBasePath()
    {
        $fileHelper = new Helper\File(\vfsStream::url('src/bar'));

        $filepath = $fileHelper->getFilePathById('foo');
    }

    public function testGetUploadPath()
    {
        $expected = \vfsStream::url('src/foo/list.json');

        $fileHelper = new Helper\File(\vfsStream::url('src'));

        $uploadPath = $fileHelper->getUploadPath('foo');

        $this->assertEquals($expected, $uploadPath);
    }

    public function testGetUploadPathDirNotExisting()
    {
        $expected = \vfsStream::url('src/bar/list.json');

        $fileHelper = new Helper\File(\vfsStream::url('src'));

        $uploadPath = $fileHelper->getUploadPath('bar');

        $this->assertEquals($expected, $uploadPath);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage could not create upload dir "vfs://src/bar"!
     */
    public function testGetUploadPathDirNotExistingAndCannotBeCreated()
    {
        $newRoot = \vfsStream::setup('src', 0000);
        $fileHelper = new Helper\File(\vfsStream::url('src'));

        $uploadPath = $fileHelper->getUploadPath('bar');
    }

    public function testUploadFile()
    {
        $fileHelper = $this->_getFileHelperMock();

        $fileHelper->expects($this->once())
            ->method('_checkUploadFile')
            ->withAnyParameters()
            ->will($this->returnValue(true));

        $fileHelper->expects($this->once())
            ->method('_moveUploadFile')
            ->withAnyParameters()
            ->will($this->returnValue(true));

        $file['type'] = 'application/octet-stream';
        $file['tmp_name'] = 'list.json';

        $fileHelper->setFile($file);

        $uploadPath = $fileHelper->getUploadPath('foo');

        $result = $fileHelper->uploadFile($uploadPath);

        $this->assertTrue($result);
    }

    public function testUploadNotExistingFile()
    {
        $fileHelper = $this->_getFileHelperMock();

        $fileHelper->expects($this->once())
            ->method('_checkUploadFile')
            ->withAnyParameters()
            ->will($this->returnValue(true));

        $fileHelper->expects($this->once())
            ->method('_moveUploadFile')
            ->withAnyParameters()
            ->will($this->returnValue(false));

        $file['type'] = 'application/octet-stream';
        $file['tmp_name'] = 'bar.json';
        $fileHelper->setFile($file);

        $uploadPath = $fileHelper->getUploadPath('foo');

        $result = $fileHelper->uploadFile($uploadPath);

        $this->assertFalse($result);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The given file is no uploaded file
     */
    public function testUploadNoUploadedFile()
    {
        $fileHelper = new Helper\File(\vfsStream::url('src'));

        $file['type'] = 'image/jpeg';
        $file['tmp_name'] = 'bar.jpg';
        $fileHelper->setFile($file);
        $uploadPath = $fileHelper->getUploadPath('foo');

        $fileHelper->uploadFile($uploadPath);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No valid file provided
     */
    public function testUploadInvalidFile()
    {
        $fileHelper = $this->_getFileHelperMock();

        $fileHelper->expects($this->once())
            ->method('_checkUploadFile')
            ->withAnyParameters()
            ->will($this->returnValue(true));

        $file['type'] = 'image/jpeg';
        $file['tmp_name'] = 'bar.jpg';
        $fileHelper->setFile($file);
        $uploadPath = $fileHelper->getUploadPath('foo');

        $fileHelper->uploadFile($uploadPath);
    }

    public function testSanatizeInput()
    {
        $input = "foo\\bar/foo/bar-foo.bar/.././";
        $expected = "foobarfoobar-foobar";

        $fileHelper = $fileHelper = new Helper\File(\vfsStream::url('src'));;

        $sanatized = $fileHelper->sanatizeInput($input);

        $this->assertEquals($expected, $sanatized);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getFileHelperMock()
    {
        return $this->getMock(
            'sort\Lib\Helper\File',
            array('_checkUploadFile', '_moveUploadFile'),
            array(\vfsStream::url('src'))
        );
    }

}
