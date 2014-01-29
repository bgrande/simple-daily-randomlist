<?php

namespace dailySort\App\Helper;

class File
{

     /**
     * @param string $listId
     *
     * @throws \RuntimeException
     *
     * @returns string
     */
    public static function getFilePathById($listId)
    {
        $listId = self::sanatizeInput($listId);
        $filePath = sprintf("src/%s/list.json", $listId);

        if (!file_exists($filePath)) {
            throw new \RuntimeException('no valid listid provided!');
        }

        return $filePath;
    }

    /**
     * @param string $listId
     *
     * @return string
     */
    public static function getUploadPath($listId)
    {
        $listId = self::sanatizeInput($listId);

        $uploadDir = sprintf("src/%s", $listId);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
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

}
 