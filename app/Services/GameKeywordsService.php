<?php

namespace App\Services;

use RecAnalyst\RecordedGame;

/**
 * Service to find keywords for recorded games that might be useful for search.
 */
class GameKeywordsService
{
    /**
     * Game keyword for "diplomacy" games, where players can build and change
     * alliances over the course of the game.
     *
     * @param \RecAnalyst\RecordedGame  $rec  Recorded game instance.
     * @return string|null
     */
    private function keywordDiplomacy(RecordedGame $rec)
    {
        $settings = $rec->gameSettings();
        if (count($rec->players()) > 2 && !$settings->getLockDiplomacy()) {
            return 'diplomacy';
        }
    }

    /**
     * Game keyword for co-op games, where multiple players control the same
     * civilization.
     *
     * @param \RecAnalyst\RecordedGame  $rec  Recorded game instance.
     * @return string|null
     */
    private function keywordCoop(RecordedGame $rec)
    {
        foreach ($rec->players() as $player) {
            if ($player->isCooping()) {
                return 'coop';
            }
        }
    }

    /**
     * Get relevant search keywords for a recorded game.
     *
     * @param \RecAnalyst\RecordedGame  $rec  Recorded game instance.
     * @return string[] List of keywords as strings.
     */
    public function getKeywords(RecordedGame $rec)
    {
        $keywords = [];
        foreach (get_class_methods($this) as $method) {
            if (starts_with($method, 'keyword')) {
                $keyword = $this->{$method}($rec);
                if ($keyword) {
                    $keywords[] = $keyword;
                }
            }
        }
        return $keywords;
    }
}
