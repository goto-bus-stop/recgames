<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\SlugableTrait;

/**
 * Represents a set of zero or more recorded games.
 */
class GameSet extends Model
{
    use SlugableTrait;

    /**
     * The database table to use.
     *
     * @var string
     */
    protected $table = 'game_sets';

    /**
     * Attributes that are mass-assignable.
     *
     * @var string[]
     */
    protected $fillable = ['slug', 'title', 'description'];

    /**
     * Relationship to the recorded games in this set.
     */
    public function recordedGames()
    {
        return $this->belongsToMany(RecordedGame::class, 'game_sets_games', 'set_id', 'game_id');
    }
}
