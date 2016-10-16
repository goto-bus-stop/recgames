<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordedGame extends Model
{
    protected $fillable = ['slug', 'path', 'filename', 'status'];

    public function getMinimapUrlAttribute()
    {
        return Storage::url('public/minimaps/' . $this->slug . '.png');
    }

    public function analysis()
    {
        return $this->hasOne(RecordedGameAnalysis::class);
    }
}
