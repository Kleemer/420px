<?php

namespace Intervention\Image\Gd\Commands;

class EdgeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Applies edge effect on image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $amount = $this->argument(0)->between(0, 100)->value(1);

        for ($i=0; $i < intval($amount); $i++) {
            imagefilter($image->getCore(), IMG_FILTER_EDGEDETECT);
        }

        return true;
    }
}
