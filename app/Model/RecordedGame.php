<?php

namespace App\Model;

use Storage;
use Illuminate\Database\Eloquent\Model;

use App\SlugableTrait;

class RecordedGame extends Model
{
    use SlugableTrait;

    /**
     * Attributes that are mass-assignable.
     *
     * @var string[]
     */
    protected $fillable = ['slug', 'path', 'filename', 'status'];

    /**
     * Get a URL to a rendered minimap image of this game.
     *
     * @return string
     */
    public function getMinimapUrlAttribute(): string
    {
        return Storage::url('public/minimaps/' . $this->analysis->minimap_hash . '.png');
    }

    /**
     * Relationship to the most recent analysis.
     */
    public function analysis()
    {
        return $this->hasOne(RecordedGameAnalysis::class)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Query scope that filters games based on whether the players in them match
     * a given regex.
     *
     * @param string  $likePlayer  Regular expression to match to the player name.
     */
    public function scopeMatchesPlayer($query, string $likePlayer)
    {
        return $query->whereHas('analysis.players', function ($query) use (&$likePlayer) {
            $query->where('name', 'like', $likePlayer);
        });
    }

    /**
     * Query scope that filters games based on whether the named player is in
     * it.
     *
     * @param string  $playerName  Exact player name to match.
     */
    public function scopeHasPlayer($query, string $playerName)
    {
        return $query->whereHas('analysis.players', function ($query) use (&$playerName) {
            $query->where('name', $playerName);
        });
    }

    /**
     *
     */
    public function scopeWithAnalysis($query)
    {
        return $query->with([
            'analysis',
            'analysis.players' => function ($query) {
                return $query
                    ->orderBy('team', 'asc')
                    ->where('type', '!=', 'spectator');
            },
        ]);
    }
}
