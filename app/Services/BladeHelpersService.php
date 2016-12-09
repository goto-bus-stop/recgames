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

    /**
     * Build an array for a research table.
     *
     * @return \RecAnalyst\Model\Research[][][] An array of the format:
     *
     *     [$player => [
     *         $minute => [...$researches]
     *     ]]
     */
    public function buildResearchesTable(array $players): array {
        $researches = [];
        foreach ($players as $player) {
            $researches[$player->index] = [];
        }

        $researchesByMinute = [];
        foreach ($players as $player) {
            foreach ($player->researches() as $research) {
                $minute = floor($research->time / 1000 / 60);
                $researchesByMinute[$minute][$player->index][] = $research;
            }
        }

        foreach ($researchesByMinute as $minute => $researchesByPlayer) {
            foreach ($players as $player) {
                $researches[$player->index][$minute] =
                    $researchesByPlayer[$player->index] ?? [];
            }
        }

        foreach ($researches as &$timeline) {
            ksort($timeline, SORT_NUMERIC);
        }

        return $researches;
    }
}
