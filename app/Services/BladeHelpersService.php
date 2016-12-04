<?php

namespace App\Services;

use RecAnalyst\Utils as RecAnalystUtils;
use Intervention\Image\ImageManager;

class BladeHelpersService
{
    public function __construct(ImageManager $images)
    {
        $this->images = $images;
    }

    public function formatGameTime(int $time): string
    {
        return RecAnalystUtils::formatGameTime($time);
    }
}
