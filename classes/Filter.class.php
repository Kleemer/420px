<?php

require_once 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;
Image::configure(array('driver' => 'gd'));

class Filter
{
    public  static function apply_filter($filterId, $image)
    {
        switch ($filterId)
        {
            case(1):
                $image = $image->contrast(10)->save();
                break;
            case(2):
                $image = $image->contrast(-10)->save();
                break;
            case(3):
                $image = $image->brightness(10)->save();
                break;
            case(4):
                $image = $image->brightness(-10)->save();
                break;
            case(5):
                $image = $image->greyscale()->brightness(-10)
                    ->contrast(10)->colorize(38, 27, 12)
                    ->brightness(-10)->contrast(10)->save();
                break;
            case(6):
                $image = $image->greyscale()->save();
                break;
            case(7):
                $image = $image->blur()->save();
                break;
            case(8):
                $image = $image->edge()->save();
            default:
                break;
        }
    }
}