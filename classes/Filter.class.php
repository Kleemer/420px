<?php

require_once 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;
Image::configure(array('driver' => 'gd'));

class Filter
{
    public static function apply_filters($list, $image)
    {
        foreach($list as $filter)
            Filter::apply_filter($filter, $image);
    }

    public static function apply_filter($filterId, $image)
    {
        switch ($filterId)
        {
            case(1):
                $image = $image->contrast(15);
                break;
            case(2):
                $image = $image->contrast(-15);
                break;
            case(3):
                $image = $image->brightness(15);
                break;
            case(4):
                $image = $image->brightness(-15);
                break;
            case(5):
                $image = $image->greyscale()->brightness(-10)
                    ->contrast(10)->colorize(38, 27, 12)
                    ->brightness(-10)->contrast(10);
                break;
            case(6):
                $image = $image->greyscale();
                break;
            case(7):
                $image = $image->blur();
                break;
            case(8):
                $image = $image->edge();
                break;
            case(9):
                $image = $image->sharpen();
                break;
            case(10):
                $image = $image->pixelate(4);
                break;
            case(11):
                $image = $image->invert();
                break;
            case(12):
                $image = $image->flip('h')->flip('v');
                break;
            default:
                break;
        }
    }
}