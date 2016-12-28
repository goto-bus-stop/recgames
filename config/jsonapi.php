<?php

use App\Model\{GameSet, RecordedGame};
use App\Model\Schemas\{GameSetSchema, RecordedGameSchema};

return [
    'schemas' => [
        GameSet::class => GameSetSchema::class,
        RecordedGame::class => RecordedGameSchema::class,
    ],
];
