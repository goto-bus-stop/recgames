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
    protected $signature = 'recgames:reanalyze {slug=all}';

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
        if ($this->argument('slug') !== 'all') {
            $game = RecordedGame::fromSlug($this->argument('slug'));
            if (!$game) {
                $this->error('Could not find game ' . $game->slug . '.');
                return;
            }
            $this->info('Queueing ' . $game->slug . ' for reanalysis.');
            return dispatch(RecAnalyzeJob::reanalyze($game));
        }

        $this->info('Queueing all games for reanalysis.');
        foreach (RecordedGame::cursor() as $game) {
            dispatch(RecAnalyzeJob::reanalyze($game));
        }
    }
}
