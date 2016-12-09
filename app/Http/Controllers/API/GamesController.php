<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

use App\RecordedGame;
use App\Jobs\RecAnalyzeJob;
use App\Http\Controllers\Controller;
use App\Exceptions\JsonApiException;
use App\Exceptions\NotFoundException;

class GamesController extends Controller
{
    private $fs;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs->disk('local');
    }

    /**
     * Serialize a game into a JSON-API compatible array.
     *
     * @param \App\RecordedGame  $rec  Recorded game model.
     * @return array
     */
    private function serializeGame(RecordedGame $rec): array
    {
        return [
            'type' => 'recorded-games',
            'id' => $rec->slug,
            'attributes' => [
                'filename' => $rec->filename ?? null,
                'status' => $rec->status,
            ],
            'relationships' => [],
            'links' => [
                'self' => action('API\GamesController@show', $rec->slug),
                'upload' => action('API\GamesController@upload', $rec->slug),
                'download' => action('API\GamesController@download', $rec->slug),
                'page' => action('GamesController@show', $rec->slug),
                'embed' => action('GamesController@embed', $rec->slug),
            ],
        ];
    }

    /**
     * List public recorded games.
     */
    public function list()
    {
        $games = RecordedGame::paginate(10);
        return response()->json([
            'meta' => [],
            'data' => $games->map(function (RecordedGame $game): array {
                return $this->serializeGame($game);
            })->all(),
        ], 200);
    }

    /**
     * Create a recorded game model.
     *
     * @param \Illuminate\Http\Request  $request
     */
    public function create(Request $request)
    {
        $recordedGame = (new RecordedGame([]))->generatedSlug();
        $recordedGame->save();

        return response()->json([
            'meta' => [],
            'data' => $this->serializeGame($recordedGame),
        ], 200);
    }

    /**
     * Retrieve a single recorded game.
     *
     * @param string  $slug  URL slug of the recorded game.
     */
    public function show(string $slug)
    {
        $recordedGame = RecordedGame::fromSlug($slug);

        return response()->json([
            'meta' => [],
            'data' => $this->serializeGame($recordedGame),
        ], 200);
    }

    /**
     * Upload a recorded game file to a game resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @param string  $slug  URL slug of the recorded game resource.
     */
    public function upload(Request $request, string $slug)
    {
        $this->validate($request, [
            'recorded_game' => 'required',
        ]);

        $recordedGame = RecordedGame::fromSlug($slug);
        $file = $request->file('recorded_game');

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new UploadException($file);
        }

        $hash = md5_file($file->path());
        $storageName = $hash . '.bin';
        // Redirect to the analysis page if this exact file was uploaded
        // before.
        if ($this->fs->exists('recordings/' . $storageName)) {
            $rec = RecordedGame::where('path', 'recordings/' . $storageName)->first();
            if ($rec) {
                $recordedGame->delete();
                throw (new JsonApiException('file-exists', 'That file was already uploaded.', 400))
                    ->links([
                        'download' => action('API\GamesController@download', $rec->slug),
                        'recorded-game' => action('API\GamesController@show', $rec->slug),
                        'page' => action('GamesController@show', $rec->slug),
                    ]);
            }
        }

        $filename = $file->getClientOriginalName();
        $path = $this->fs->putFile('recordings', $file);

        $recordedGame->path = $path;
        $recordedGame->filename = $filename;
        $recordedGame->hash = $hash;
        $recordedGame->save();

        dispatch(RecAnalyzeJob::uploaded($recordedGame));

        $id = $recordedGame->slug;
        return response()->json([
            'links' => [
                'download' => action('API\GamesController@download', $id),
                'recorded-game' => action('API\GamesController@show', $id),
                'page' => action('GamesController@show', $id),
            ],
            'data' => [],
        ], 200);
    }

    /**
     * Download a file associated with a recorded game resource.
     *
     * @param string  $slug  URL slug of the recorded game resource.
     */
    public function download(string $slug)
    {
        $recordedGame = RecordedGame::fromSlug($slug);
        if (!$recordedGame) {
            throw new NotFoundException('That recorded game does not exist.');
        }

        return response($this->fs->read($recordedGame->path))
            ->header('content-disposition', 'attachment; filename="' . urlencode($recordedGame->filename) . '"');
    }

    /**
     * Request a reanalysis of a recorded game.
     *
     * @param string  $slug  URL slug of the recorded game resource.
     */
    public function reanalyze(string $slug)
    {
        $recordedGame = RecordedGame::fromSlug($slug);
        if (!$recordedGame) {
            throw new NotFoundException('That recorded game does not exist.');
        }

        dispatch(RecAnalyzeJob::reanalyze($recordedGame));

        return [
            'data' => [],
        ];
    }
}
