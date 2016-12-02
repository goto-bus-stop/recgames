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
                    'status' => $recordedGame->status,
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
                    'status' => $recordedGame->status,
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

        dispatch(new RecAnalyzeJob($recordedGame));

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

    public function download($slug)
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
     */
    public function reanalyze($slug)
    {
        $recordedGame = RecordedGame::fromSlug($slug);
        if (!$recordedGame) {
            throw new NotFoundException('That recorded game does not exist.');
        }

        dispatch(new RecAnalyzeJob($recordedGame));
        return [
            'data' => [],
        ];
    }
}
