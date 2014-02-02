<?php

namespace dailySort\Tests\Lib;

use dailySort\Lib\Helper;

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
        $filepath = Helper\File::getFilePathById('foo', \vfsStream::url('src'));
        $this->assertEquals($expected, $filepath);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage no valid listid provided!
     */
    public function testGetFilePathByInvalidId()
    {
        $filepath = Helper\File::getFilePathById('bar', \vfsStream::url('src'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage no valid listid provided!
     */
    public function testGetFilePathByIdWithInvalidBasePath()
    {
        $filepath = Helper\File::getFilePathById('foo');
    }

    public function testGetUploadPath()
    {
        $expected = \vfsStream::url('src/foo/list.json');
        $uploadPath = Helper\File::getUploadPath('foo', \vfsStream::url('src'));
        $this->assertEquals($expected, $uploadPath);
    }

    public function testGetUploadPathDirNotExisting()
    {
        $expected = \vfsStream::url('src/bar/list.json');
        $uploadPath = Helper\File::getUploadPath('bar', \vfsStream::url('src'));
        $this->assertEquals($expected, $uploadPath);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage could not create upload dir "vfs://src/bar"!
     */
    public function testGetUploadPathDirNotExistingAndCannotBeCreated()
    {
        $newRoot = \vfsStream::setup('src', 0000);
        $uploadPath = Helper\File::getUploadPath('bar', \vfsStream::url('src'));
    }

    /**
     * @todo complete this!
     */
    public function testUploadFile()
    {
        $file['type'] = 'application/octet-stream';
        $file['tmp_name'] = 'list.json';
        $uploadPath = Helper\File::getUploadPath('foo', \vfsStream::url('src'));

        $result = Helper\File::uploadFile($uploadPath, $file);

        $this->assertTrue($result);
    }

    public function testUploadNotExistingFile()
    {
        $file['type'] = 'application/octet-stream';
        $file['tmp_name'] = 'bar.json';
        $uploadPath = Helper\File::getUploadPath('foo', \vfsStream::url('src'));

        $result = Helper\File::uploadFile($uploadPath, $file);

        $this->assertFalse($result);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No valid file provided
     */
    public function testUploadInvalidFile()
    {
        $file['type'] = 'image/jpeg';
        $file['tmp_name'] = 'bar.jpg';
        $uploadPath = Helper\File::getUploadPath('foo', \vfsStream::url('src'));

        $result = Helper\File::uploadFile($uploadPath, $file);
    }

    public function testSanatizeInput()
    {
        $input = "foo\\bar/foo/bar-foo.bar/.././";
        $expected = "foobarfoobar-foobar";

        $sanatized = Helper\File::sanatizeInput($input);

        $this->assertEquals($expected, $sanatized);
    }

}
