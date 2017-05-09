<?php

class Tools
{
    public static function isAnImage($path)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);
        return strpos($mimeType, 'image');
    }

    public static function saveImage($dir, $absolutePath, $path)
    {
        $name = explode(".", $path);
        $i = 1;
        while (file_exists($dir.$path))
            $path = $name[0]."(".$i++.").".$name[1];
        return $path;
    }
}