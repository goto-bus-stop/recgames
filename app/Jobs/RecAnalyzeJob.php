<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Queue\{SerializesModels, InteractsWithQueue};
use Elasticsearch\ClientBuilder;

use RecAnalyst\Model\{
    ChatMessage,
    Player,
    Research
};

use App\{
    RecordedGame,
    RecordedGamePlayer,
    RecordedGameAnalysis,
    Services\RecAnalystManager
};

class RecAnalyzeJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    const ANALYZE_VERSION = 1;
    const MAP_IMAGE_WIDTH = 400;
    const MAP_IMAGE_HEIGHT = 200;

    protected $model;
    protected $analyzer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RecordedGame $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RecAnalystManager $recAnalyst, Filesystem $fs)
    {
        $disk = $fs->disk('local');

        $this->model->status = 'processing';
        $this->model->save();

        $fd = $disk->readStream($this->model->path);
        $this->analyzer = $recAnalyst->make($fd);
        $rec = $this->analyzer;

        $mapImage = $rec->mapImage([
            'showElevation' => true,
        ])->resize(
            self::MAP_IMAGE_WIDTH,
            self::MAP_IMAGE_HEIGHT
        );
        $mapPath = 'minimaps/' . $this->model->slug . '.png';

        $pov = null;
        foreach ($rec->players() as $player) {
            if ($player->owner) {
                $pov = $player;
                break;
            }
        }

        $disk->put('public/' . $mapPath, $mapImage->encode('png') . '');

        // Extract Voobly-style ratings
        foreach ($rec->body()->chatMessages as $message) {
            if ($message->group === 'Rating') {
                $message->player->rating = (int) trim($message->msg);
            }
        }

        $analysis = $this->model->analysis()->create([
            'version' => $rec->version()->version,
            'duration' => $rec->body()->duration,
            'game_type' => $rec->gameSettings()->gameType,
            'multiplayer' => true,
                // $rec->gameSettings()->gameMode === \RecAnalyst\GameInfo::MODE_MULTIPLAYER,
            'map_size' => $rec->gameSettings()->mapSize,
            'map_id' => $rec->gameSettings()->mapId,
            'pop_limit' => $rec->gameSettings()->popLimit,
            'lock_diplomacy' => $rec->gameSettings()->lockDiplomacy,
        ]);

        $players = [];
        foreach ($rec->players() as $player) {
            $players[] = new RecordedGamePlayer([
                'name' => $player->name,
                'player_index' => $player->index,
                'type' => $player->isSpectator() ? 'spectator' :
                         ($player->isHuman() ? 'human' : 'ai'),
                'team' => $player->team,
                'color' => $player->colorId,
                'civilization' => $player->civId,
                'rating' => isset($player->rating) ? $player->rating : null,
            ]);
        }

        $analysis->players()->saveMany($players);

        $html = view('components.full_analysis', [
            'model' => $this->model,
            'analysis' => $analysis,
            'rec' => $rec,
            'pov' => $pov,
            'mapPath' => $this->model->minimap_url,
        ])->render();

        $disk->put('analyses/' . $this->model->slug . '.html', $html);

        $this->model->status = 'completed';
        $this->model->save();
    }

    public function failed(Exception $e)
    {
        $this->model->status = 'errored';
        $this->model->save();
    }

    /**
     * Create a recanalyze job for a game that was just uploaded.
     */
    public static function uploaded(RecordedGame $recordedGame): ShouldQueue
    {
        $job = new self($recordedGame);
        return $job->onQueue('upload');
    }

    /**
     * Create a recanalyze job for a game that should be analyzed again.
     * "reanalyze" jobs have a lower priority than new uploads.
     */
    public static function reanalyze(RecordedGame $recordedGame): ShouldQueue
    {
        $job = new self($recordedGame);
        return $job->onQueue('reanalyze');
    }
}
