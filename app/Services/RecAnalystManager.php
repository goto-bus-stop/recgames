<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use RecAnalyst\RecordedGame;

/**
 *
 */
class RecAnalystManager
{
    public function __construct()
    {
    }

    /**
     *
     */
    public function make($filename, string $diskName = 'local')
    {
        return new RecordedGame($filename);
    }
}
