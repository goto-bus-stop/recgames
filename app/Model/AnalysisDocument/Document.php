<?php

namespace App\Model\AnalysisDocument;

use Illuminate\Support\Collection;

/**
 *
 */
class Document extends ToObject
{
    private $players;

    public static function hydrate(array $doc)
    {
        return new Document($doc);
    }

    /**
     *
     */
    protected function __construct(array $doc)
    {
        parent::__construct($doc);

        $this->players = collect($doc['players'])->map(function ($player) {
            return Player::hydrate($this, $player);
        });
    }

    public function versionName(): string
    {
        return trans('recanalyst::game_versions.' . $this->version);
    }

    public function mapName(): string
    {
        $key = 'recanalyst::ageofempires.map_names.' . $this->map_id;
        return app('translator')->has($key) ? trans($key) : $this->map_name;
    }

    public function mapSizeName(): string
    {
        return trans('recanalyst::ageofempires.map_sizes.' . $this->map_size);
    }

    public function gameTypeName(): string
    {
        return trans('recanalyst::ageofempires.game_types.' . $this->game_type);
    }

    /**
     *
     */
    public function players(): Collection
    {
        return $this->players->reject(function (Player $player): bool {
            return $player->type === 'spectator';
        });
    }

    public function player(int $index)
    {
        return $this->players->first(function (Player $player) use ($index) {
            return $player->index === $index;
        });
    }

    public function pov(): Player
    {
        return $this->players->first(function (Player $player): bool {
            return $player->is_pov;
        });
    }

    /**
     *
     */
    public function spectators(): Collection
    {
        return $this->players->filter(function (Player $player): bool {
            return $player->type === 'spectator';
        });
    }

    /**
     *
     */
    public function teams(): Collection
    {
        return $this->players()->groupBy(function (Player $player) {
            return $player->team ?? uniqid();
        });
    }

    /**
     *
     */
    public function isOutdated(): bool
    {
        return $this->analyze_version < config('recgames.analysis_version');
    }

    public function pregameChat(): Collection
    {
        return collect($this->pregame_chat ?? [])->map(function ($message) {
            return ChatMessage::hydrate(
                $this->player($message['player']),
                $message
            );
        });
    }


    public function ingameChat(): Collection
    {
        return collect($this->ingame_chat ?? [])->map(function ($message) {
            return ChatMessage::hydrate(
                $message['player'] ? $this->player($message['player']) : null,
                $message
            );
        });
    }

    /**
     *
     */
    public function save()
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     *
     */
    public function toArray(): array
    {
        return $this->doc;
    }
}
