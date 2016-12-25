<?php

namespace App\Model\AnalysisDocument;

use Illuminate\Support\Collection;

class Player extends ToObject
{
    public static function hydrate(Document $parent, array $raw): Player
    {
        return new Player($parent, $raw);
    }

    /**
     * @var App\AnalysisDocument\Research[]
     */
    private $researches;

    protected function __construct(Document $document, array $raw)
    {
        parent::__construct($raw);

        $this->doc = $document;
        $this->researches = collect($raw['researches'] ?? [])->map(function ($research) {
            return Research::hydrate($this, $research);
        });
    }

    public function civName()
    {
        return trans('recanalyst::ageofempires.civilizations.' . $this->civilization);
    }

    public function researches()
    {
        return $this->researches;
    }

    private function getResearchTime($id)
    {
        $research = $this->researches->first(function (Research $research) use ($id) {
            return $research->id === $id;
        });

        return $research ? $research->time : 0;
    }

    public function feudalTime()
    {
        return $this->getResearchTime(101);
    }

    public function castleTime()
    {
        return $this->getResearchTime(102);
    }

    public function imperialTime()
    {
        return $this->getResearchTime(103);
    }
}
