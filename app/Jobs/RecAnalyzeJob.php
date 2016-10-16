<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

use App\RecordedGame;
use App\RecordedGamePlayer;
use App\RecordedGameAnalysis;
use App\Services\RecAnalystManager;

class RecAnalyzeJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    const MAP_IMAGE_WIDTH = 400;
    const MAP_IMAGE_HEIGHT = 200;

    protected $model;

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
        $rec = $recAnalyst->make($fd);

        $mapImage = $rec->mapImage()->resize(
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
            'mapPath' => $disk->url('public/' . $mapPath),
        ])->render();

        $disk->put('analyses/' . $this->model->slug . '.html', $html);

        $this->model->status = 'completed';
        $this->model->save();
    }
}
