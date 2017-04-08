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

    /**
     * Relationship to the game to use as the thumbnail for the set.
     */
    public function thumbnailGames()
    {
        return $this->recordedGames()->limit(1);
    }

    /**
     * Relationship to the user who created this set.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the Recorded Game to use as the thumbnail.
     */
    public function getThumbnailGameAttribute()
    {
        return $this->thumbnailGames->first();
    }
}
