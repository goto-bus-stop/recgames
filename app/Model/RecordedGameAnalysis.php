<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecordedGameAnalysis extends Model
{
    protected $guarded = [];

    public function isOutdated()
    {
        return $this->analysis_version < config('recgames.analysis_version');
    }

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
