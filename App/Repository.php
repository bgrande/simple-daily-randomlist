<?php

namespace sort\App;

class Repository 
{
    /**
     * @var string
     */
    protected $_outputFilePath;

    /**
     * @var string
     */
    protected $_originFilePath;

    /**
     * @var array
     */
    protected $_outputFileContent;

    /**
     * @var array
     */
    protected $_cacheTypes = array(
        '1day',
        'forever',
    );

    /**
     * @param string $filePath
     * @param string $useCache
     *
     * @throws \RuntimeException
     */
    public function __construct($filePath, $useCache = 'forever')
    {
        $this->_originFilePath = $filePath;

        $this->_setOutputFilePath($useCache);
    }

    /**
     * @return bool|array
     */
    public function getOutputList()
    {
        if (file_exists($this->_outputFilePath)) {
            $output = $this->_getFileContentByPath($this->_outputFilePath);

            if (!is_array($this->_outputFileContent)) {
                $this->_setOutputFileContent($output);
            }

            return $output;
        }

        return false;
    }

    /**
     * @return bool|array
     */
    public function getOriginalList()
    {
        if (is_readable($this->_originFilePath)) {
            return json_decode(file_get_contents($this->_originFilePath));
        }

        return false;
    }

    /**
     * @param array $fileContent
     *
     * @return void
     */
    public function setOutputFile($fileContent)
    {
        $this->_setOutputFileContent($fileContent);

        if (null !== $this->_outputFilePath && !file_exists($this->_outputFilePath)) {
            file_put_contents($this->_outputFilePath, json_encode($this->_outputFileContent));
        }
    }

    /**
     * empty cached list
     *
     * @return void
     */
    public function resetList()
    {
        $this->_deleteOutputFile($this->_outputFilePath);
    }

    /**
     * @param array $fileContent
     *
     * @throws \RuntimeException
     */
    protected function _setOutputFileContent($fileContent)
    {
        if ($fileContent) {
            $this->_outputFileContent = $fileContent;
        } else {
            throw new \RuntimeException(
                sprintf(
                    'File %s is not a valid json file or empty',
                    basename($this->_outputFilePath)
                )
            );
        }
    }

    /**
     * @param string $fileToDelete
     *
     * @return void
     */
    protected function _deleteOutputFile($fileToDelete)
    {
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
    }

    /**
     * @param string $path
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function _getFileContentByPath($path)
    {
        if (file_exists($path)) {
            $fileContent = json_decode(file_get_contents($path));
        } else {
            throw new \RuntimeException(
                sprintf(
                    'Could not read list input file %s',
                    basename($path)
                )
            );
        }

        return $fileContent;
    }

    /**
     * @param string $useCache
     *
     * @return void
     */
    protected function _setOutputFilePath($useCache)
    {
        $date = new \DateTime();

        $basePath = dirname($this->_originFilePath) . DIRECTORY_SEPARATOR .
            basename($this->_originFilePath, '.json') . '_';

        if ($useCache && in_array($useCache, $this->_cacheTypes)) {
            switch ($useCache) {
                case '1day':
                    $yesterday = new \DateTime("-1 day");
                    $fileToDelete = $basePath . $yesterday->format("Y-m-d") . ".json";
                    $this->_deleteOutputFile($fileToDelete);

                    $this->_outputFilePath = $basePath . $date->format("Y-m-d") . ".json";
                    break;

                case 'forever':
                default:
                    $this->_outputFilePath = $basePath . 'forever' . ".json";
            }
        } else {
            $this->_outputFilePath = null;
        }
    }
}
 