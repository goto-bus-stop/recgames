<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\RecordedGame;
use App\Jobs\RecAnalyzeJob;

class ReanalyzeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recgames:reanalyze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue reanalysis of all games.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (RecordedGame::cursor() as $game) {
            dispatch(RecAnalyzeJob::reanalyze($game));
        }
    }
}
