<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordedGame extends Model
{
    public $fillable = ['slug', 'path', 'filename'];
}
