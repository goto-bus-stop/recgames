<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BumpAnalysisVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recgames:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bump the analysis version number. '
        . 'Causes old games to be analysed again when requested.';

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
        $path = config_path('recgames.php');

        $source = file_get_contents($path);
        $result = preg_replace_callback('/\'analysis_version\' => (\d+),/', function ($input): string {
            return "'analysis_version' => " . ($input[1] + 1) . ',';
        }, $source);

        file_put_contents($path, $result);

        $newConfig = require $path;
        $this->info('Analysis version bumped to ' . $newConfig['analysis_version'] . '.');
    }
}
