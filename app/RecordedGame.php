<?php

namespace App;

use Storage;
use Illuminate\Database\Eloquent\Model;

class RecordedGame extends Model
{
    protected $fillable = ['slug', 'path', 'filename', 'status'];

    public function generatedSlug()
    {
        while (!$this->slug || self::where('slug', $this->slug)->count() > 0) {
            $this->slug = str_random(6);
        }
        return $this;
    }

    public function getMinimapUrlAttribute()
    {
        return Storage::url('public/minimaps/' . $this->slug . '.png');
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

    /**
     *
     */
    public static function fromSlug($slug)
    {
        return self::where('slug', $slug)->first();
    }
}
