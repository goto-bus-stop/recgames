<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameSet extends Model
{
    use SlugableTrait;

    protected $table = 'game_sets';

    protected $fillable = ['slug', 'title', 'description'];

    public function recordedGames()
    {
        return $this->belongsToMany(RecordedGame::class, 'game_sets_games', 'set_id', 'game_id');
    }
}
