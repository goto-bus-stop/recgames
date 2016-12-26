<?php

namespace App\Model\AnalysisDocument;

use RecAnalyst\Utils;
use Illuminate\Support\Collection;

class Research extends ToObject
{
    public static function hydrate(Player $parent, array $raw): Research
    {
        return new Research($parent, $raw);
    }

    private $player;

    protected function __construct(Player $player, array $raw)
    {
        parent::__construct($raw);

        $this->player = $player;
    }

    public function player()
    {
        return $this->player;
    }

    public function name()
    {
        return trans('recanalyst::ageofempires.researches.' . $this->id);
    }

    public function formattedTime()
    {
        return Utils::formatGameTime($this->time);
    }
}
