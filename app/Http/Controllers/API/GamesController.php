<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

use App\RecordedGame;
use App\Jobs\RecAnalyzeJob;
use App\Http\Controllers\Controller;

class GamesController extends Controller
{
    private $fs;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs->disk('local');
    }

    /**
     * Create a recorded game model.
     */
    public function create(Request $request)
    {
        $recordedGame = (new RecordedGame([]))->generatedSlug();
        $recordedGame->save();

        return response()->json([
            'meta' => [],
            'data' => [
                'type' => 'recorded-games',
                'attributes' => [
                    'id' => $recordedGame->slug,
                ],
                'relationships' => [],
                'links' => [
                    'self' => action('API\GamesController@show', $recordedGame->slug),
                    'upload' => action('API\GamesController@upload', $recordedGame->slug),
                ],
            ],
        ], 200);
    }

    public function show($slug)
    {
        $recordedGame = RecordedGame::fromSlug($slug);
        $id = $recordedGame->slug;

        return response()->json([
            'data' => [
                'type' => 'recorded-games',
                'attributes' => [
                    'id' => $id,
                    'filename' => $recordedGame->filename,

                ],
                'links' => [
                    'self' => action('API\GamesController@show', $id),
                    'download' => action('API\GamesController@download', $id),
                ],
            ],
        ], 200);
    }

    public function upload(Request $request, $slug)
    {
        $recordedGame = RecordedGame::fromSlug($slug);
        $file = $request->file('recorded_game');
        if (!$file) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'missing-file',
                        'title' => 'Expected a file in the "recorded_game" field.',
                    ],
                ],
            ], 400);
        }

        $storageName = $file->hashName();
        // Redirect to the analysis page if this exact file was uploaded
        // before.
        if ($this->fs->exists('recordings/' . $storageName)) {
            $rec = RecordedGame::where('path', $storageName)->first();
            if ($rec) {
                return response()->json([
                    'links' => [
                        'recorded-game' => action('API\GamesController@show', $rec->slug),
                    ],
                    'errors' => [
                        [
                            'code' => 'file-exists',
                            'title' => 'That file was already uploaded.',
                        ]
                    ],
                ], 400);
            }
        }

        $tmpPath = $file->path();
        $path = $this->fs->putFile('recordings', $file);
        $filename = $file->getClientOriginalName();

        $recordedGame->path = $path;
        $recordedGame->filename = $filename;
        // $recordedGame->hash = sha1_file($tmpPath);
        $recordedGame->save();

        dispatch(new RecAnalyzeJob($recordedGame));

        $id = $recordedGame->slug;
        return response()->json([
            'links' => [
                'download' => action('API\GamesController@download', $id),
                'recorded-game' => action('API\GamesController@show', $id),
            ],
            'data' => [],
        ], 200);
    }

    public function download($slug)
    {
        $recordedGame = RecordedGame::fromSlug($slug);
        if ($recordedGame) {
            return response($this->fs->read($recordedGame->path))
                ->header('content-disposition', 'attachment; filename="' . urlencode($recordedGame->filename) . '"');
        }
        return response()->json([
            'errors' => [
                [
                    'code' => 'not-found',
                    'title' => 'That recorded game does not exist.',
                ],
            ],
        ], 404);
    }
}
