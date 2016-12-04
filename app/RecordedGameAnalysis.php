<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordedGameAnalysis extends Model
{
    protected $guarded = [];

    public function recordedGame()
    {
        return $this->belongsTo(RecordedGame::class);
    }

    public function players()
    {
        return $this->hasMany(RecordedGamePlayer::class);
    }

    public function getMapNameAttribute()
    {
        return trans('recanalyst::ageofempires.map_names.' . $this->map_id);
    }
}
