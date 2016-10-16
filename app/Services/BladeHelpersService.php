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

    public function getCivImage($playerColorId, $civId)
    {
        $resourcePack = new AgeOfEmpires();
        $civName = strtolower($resourcePack->getCivName($civId));
        return asset('images/civilizations/' . $playerColorId . '/' . $civName . '.png');
    }

    public function getResearchImage($research)
    {
        $data = RecAnalystConst::$RESEARCHES[$research->id];
        $path = base_path('../recanalyst/resources/researches/' . $data[1] . '.png');
        if (is_file($path)) {
            return $this->images->make($path)->encode('data-url') . '';
        }
        return '';
    }

    public function getResearchName($research)
    {
        $data = RecAnalystConst::$RESEARCHES[$research->id];
        return $data[0];
    }
}
