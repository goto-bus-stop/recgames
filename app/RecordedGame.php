<?php

namespace App;

use Storage;
use Illuminate\Database\Eloquent\Model;

class RecordedGame extends Model
{
    use SlugableTrait;

    protected $fillable = ['slug', 'path', 'filename', 'status'];

    public function getMinimapUrlAttribute()
    {
        return Storage::url('public/minimaps/' . $this->analysis->minimap_hash . '.png');
    }

    public function analysis()
    {
        return $this->hasOne(RecordedGameAnalysis::class)
            ->orderBy('created_at', 'desc');
    }

    public function scopeMatchesPlayer($query, $likePlayer)
    {
        return $query->whereHas('analysis.players', function ($query) use (&$likePlayer) {
            $query->where('name', 'like', $likePlayer);
        });
    }

    public function scopeHasPlayer($query, $playerName)
    {
        return $query->whereHas('analysis.players', function ($query) use (&$playerName) {
            $query->where('name', $playerName);
        });
    }
}
