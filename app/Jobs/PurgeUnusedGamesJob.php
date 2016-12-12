<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\{SerializesModels, InteractsWithQueue};

use App\Model\RecordedGame;

/**
 * Delete recorded game models that have not had a game file uploaded within
 * five minutes after creation.
 */
class PurgeUnusedGamesJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $before = strtotime('5 minutes ago');
        RecordedGame::where('status', 'new')
                    ->where('updated_at', '<', date('Y-m-d H:i:s', $before))
                    ->delete();
    }
}
