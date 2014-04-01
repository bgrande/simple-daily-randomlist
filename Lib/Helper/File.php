<?php

namespace sort\Lib\Helper;

class File
{

    /**
     * @var string
     */
    const BASE_PATH = 'src';

    /**
     * @var array
     */
    protected $_file;

    /**
     * @var string
     */
    protected $_basePath;

    /**
     * @param string|null $basePath
     *
     * @throws \RuntimeException
     */
    public function __construct($basePath = null)
    {
        $this->_basePath = $basePath;
    }

    /**
     * @param array $file
     *
     * @throws \RuntimeException
     */
    public function setFile($file)
    {
        /** @todo make sure file is a real file  */

        $this->_file = $file;

        if (!$this->_checkUploadFile()) {
            throw new \RuntimeException('The given file is no uploaded file');
        }
    }

    /**
     * @param string $listId
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function getFilePathById($listId)
    {
        $listId = $this->sanatizeInput($listId);
        $filePath = sprintf("%s/%s/list.json", $this->_getBasePath(), $listId);

        if (!file_exists($filePath)) {
            throw new \RuntimeException('no valid listid provided!');
        }

        return $filePath;
    }

    /**
     * @param string $listId
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function getUploadPath($listId)
    {
        $listId = $this->sanatizeInput($listId);

        $uploadDir = sprintf("%s/%s", $this->_getBasePath(), $listId);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }

        if (!file_exists($uploadDir)) {
            throw new \RuntimeException(sprintf('could not create upload dir "%s"!', $uploadDir));
        }

        return $uploadDir . "/list.json";
    }

    /**
     * @param string $targetPath
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function uploadFile($targetPath)
    {
        if ('application/octet-stream' == $this->_file['type']) {
            return $this->_moveUploadFile($targetPath);
        } else {
            throw new \RuntimeException('No valid file provided');
        }
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function sanatizeInput($input)
    {
        return trim(preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $input));
    }

    /**
     * @return string
     */
    protected function _getBasePath()
    {
        return $this->_basePath != null ? $this->_basePath : self::BASE_PATH;
    }

    /**
     * @param string $targetPath
     *
     * @return bool
     */
    public function _moveUploadFile($targetPath)
    {
        return move_uploaded_file($this->_file['tmp_name'], $targetPath);
    }

    /**
     * @return bool
     */
    public function _checkUploadFile()
    {
        return is_uploaded_file($this->_file['tmp_name']);
    }

}
