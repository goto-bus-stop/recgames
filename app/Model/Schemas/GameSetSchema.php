<?php

namespace App\Model\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

use App\Model\GameSet;

class GameSetSchema extends SchemaProvider
{
    protected $resourceType = 'sets';

    public function getId($set)
    {
        return $set->slug;
    }

    public function getAttributes($set)
    {
        return [
            'title' => $set->title,
            'description' => $set->description,
            'created-at' => $set->created_at->toW3cString(),
            'updated-at' => $set->updated_at->toW3cString(),
        ];
    }
}
