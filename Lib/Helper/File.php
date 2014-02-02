<?php

namespace dailySort\Lib\Helper;

class File
{

    /**
     * @var string
     */
    const BASE_PATH = 'src';

    /**
     * @param string $listId
     * @param null|string $basePath
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public static function getFilePathById($listId, $basePath = null)
    {
        $listId = self::sanatizeInput($listId);
        $filePath = sprintf("%s/%s/list.json", self::_getBasePath($basePath), $listId);

        if (!file_exists($filePath)) {
            throw new \RuntimeException('no valid listid provided!');
        }

        return $filePath;
    }

    /**
     * @param string $listId
     * @param null|string $basePath
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public static function getUploadPath($listId, $basePath = null)
    {
        $listId = self::sanatizeInput($listId);

        $uploadDir = sprintf("%s/%s", self::_getBasePath($basePath), $listId);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }

        if (!file_exists($uploadDir)) {
            throw new \RuntimeException(sprintf('could not create upload dir "%s"!', $uploadDir));
        }

        return $uploadDir . "/list.json";
    }

    public static function uploadFile($targetPath, $file)
    {
        if ('application/octet-stream' == $file['type']) {
            return move_uploaded_file($file['tmp_name'], $targetPath);
        } else {
            throw new \RuntimeException('No valid file provided');
        }
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public static function sanatizeInput($input)
    {
        return trim(preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $input));
    }

    /**
     * @param string $basePath
     *
     * @return string
     */
    protected static function _getBasePath($basePath)
    {
        return $basePath != null ? $basePath : self::BASE_PATH;
    }

}
 