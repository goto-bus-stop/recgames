<?php

namespace App\Services;

use RecAnalyst\Utils as RecAnalystUtils;
use RecAnalyst\RecAnalystConst;
use Intervention\Image\ImageManager;
use RecAnalyst\ResourcePacks\AgeOfEmpires;

class BladeHelpersService
{
    public function __construct(ImageManager $images)
    {
        $this->images = $images;
    }

    public function formatGameTime($time)
    {
        return RecAnalystUtils::formatGameTime($time);
    }
}
