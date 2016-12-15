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
    Model\RecordedGame,
    Model\RecordedGamePlayer,
    Model\RecordedGameAnalysis,
    Services\RecAnalystManager,
    Contracts\AnalysisStorageService
};

class RecAnalyzeJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle(RecAnalystManager $recAnalyst, Filesystem $fs, AnalysisStorageService $analyses)
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

        $pov = null;
        foreach ($rec->players() as $player) {
            if ($player->owner) {
                $pov = $player;
                break;
            }
        }

        $imageData = $mapImage->encode('png') . '';
        $imageHash = md5($imageData);
        $mapPath = 'minimaps/' . $imageHash . '.png';
        $disk->put('public/' . $mapPath, $imageData);

        // Extract Voobly-style ratings
        foreach ($rec->body()->chatMessages as $message) {
            if ($message->group === 'Rating') {
                $message->player->rating = (int) trim($message->msg);
            }
        }

        $analysis = $this->model->analysis()->create([
            'analysis_version' => config('recgames.analysis_version'),
            'game_version' => $rec->version()->version,
            'game_subversion' => $rec->version()->subVersion,
            'minimap_hash' => $imageHash,
            'duration' => $rec->body()->duration,
            'game_type' => $rec->gameSettings()->gameType,
            'multiplayer' => true,
            'map_size' => $rec->gameSettings()->mapSize,
            'map_id' => $rec->gameSettings()->mapId,
            'map_name' => $rec->gameSettings()->isCustomMap() ? $rec->gameSettings()->mapName() : null,
            'pop_limit' => $rec->gameSettings()->popLimit,
            'lock_diplomacy' => $rec->gameSettings()->lockDiplomacy,
        ]);

        $players = [];
        foreach ($rec->players() as $player) {
            $players[] = new RecordedGamePlayer([
                'name' => $player->name,
                'player_index' => $player->index,
                'is_pov' => $player->owner,
                'type' => $player->isSpectator() ? 'spectator' :
                         ($player->isHuman() ? 'human' : 'ai'),
                'team' => $player->team,
                'color' => $player->colorId,
                'civilization' => $player->civId,
                'rating' => isset($player->rating) ? $player->rating : null,
            ]);
        }

        $analysis->players()->saveMany($players);

        $analysisDocument = $this->makeDocument($rec);
        $analyses->store($analysis->id, $analysisDocument);

        $this->model->status = 'completed';
        $this->model->save();
    }

    public function failed(Exception $e)
    {
        $this->model->status = 'errored';
        $this->model->save();
    }

    private function makeDocument(): array
    {
        $rec = $this->analyzer;

        $toArray = function ($obj) use (&$toArray) {
            if (!is_object($obj) && !is_array($obj)) {
                return $obj;
            }

            return array_map($toArray, (array) $obj);
        };

        $players = array_map(function (Player $player) use (&$toArray): array {
            return [
                'is_pov' => $player->owner ? true : false,
                'achievements' => $toArray($player->achievements()),
                'index' => $player->index,
                'name' => $player->name,
                'team' => $player->team,
                'color' => $player->colorId,
                'civilization' => $player->civId,
                'type' => $player->isSpectator() ? 'spectator' :
                    ($player->isHuman() ? 'human' : 'ai'),
                'researches' => array_map(function (Research $research): array {
                    return ['id' => $research->id, 'time' => $research->time];
                }, $player->researches()),
                'rating' => $player->rating ?? null,
            ];
        }, $rec->players());

        return [
            'analyze_version' => config('recgames.analysis_version'),
            'version' => $rec->version()->version,
            'sub_version' => $rec->version()->subVersion,
            'duration' => $rec->body()->duration,
            'game_type' => $rec->gameSettings()->gameType,
            'multiplayer' => true, // $rec->gameSettings()->gameMode === 1,
            'map_size' => $rec->gameSettings()->mapSize,
            'map' => $rec->gameSettings()->mapId,
            'map_name' => $rec->gameSettings()->mapName(),
            'scenario_filename' => $rec->header()->scenarioFilename ?? '',
            'pop_limit' => $rec->gameSettings()->popLimit,
            'lock_diplomacy' => $rec->gameSettings()->lockDiplomacy,
            'players' => $players,
            'pregame_chat' => array_map(function (ChatMessage $chat): array {
                return [
                    'group' => $chat->group,
                    'player' => $chat->player ? $chat->player->index : null,
                    'message' => $chat->msg,
                ];
            }, $rec->header()->pregameChat),
            'ingame_chat' => array_map(function (ChatMessage $chat): array {
                return [
                    'time' => $chat->time,
                    'group' => $chat->group,
                    'player' => $chat->player ? $chat->player->index : null,
                    'message' => $chat->msg,
                ];
            }, $rec->body()->chatMessages),
        ];
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
