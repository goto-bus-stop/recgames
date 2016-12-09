<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecordedGamePlayer extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function analysis()
    {
        return $this->belongsTo(
            RecordedGameAnalysis::class,
            'recorded_game_analysis_id'
        );
    }
}
