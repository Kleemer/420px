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

    public static function downloadZip($login)
    {
        $zipname = 'zip/'.$login.'.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);
        $dir = 'images/'.$login.'/';
        if ($handle = opendir($dir))
        {
            while (($entry = readdir($handle)) !== false)
            if ($entry != "." && $entry != "..")
                $zip->addFile($dir.$entry, $entry);
            closedir($handle);
        }
        $zip->close();

        header('Content-Type: application/zip');
        header("Content-Disposition: attachment; filename=".$zipname);
        header('Content-Length: ' . filesize($zipname));
        header("Location:" . $zipname);
    }

    public static function downloadRss($login)
    {
        $xmlname = 'xml/'.$login.'.xml';
        $xml = new DOMDocument();
        $xml_images = $xml->createElement("Images");
        $dir = 'images/'.$login.'/';
        if ($handle = opendir($dir))
        {
            while (($entry = readdir($handle)) !== false)
                if ($entry != "." && $entry != "..")
                {
                    $xml_image = $xml->createElement("Name");
                    $xml_image->nodeValue = $entry;
                    $xml_images->appendChild( $xml_image );
                }
            closedir($handle);
        }
        $xml->appendChild( $xml_images );
        $xml->save($xmlname);

        header('Content-type: application/xml');
        header("Content-Disposition: attachment; filename=".$xmlname);
        header('Content-Length: ' . filesize($xmlname));
        header("Location:" . $xmlname);
    }
}