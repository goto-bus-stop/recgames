<?php

namespace App\Model\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

use App\Model\RecordedGame;

class RecordedGameSchema extends SchemaProvider
{
    protected $resourceType = 'recorded-games';

    public function getId($game)
    {
        return $game->slug;
    }

    public function getAttributes($game)
    {
        return [
            'filename' => $game->filename,
            'status' => $game->status,
            'created-at' => $game->created_at->toW3cString(),
        ];
    }
}
