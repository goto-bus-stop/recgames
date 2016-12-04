<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

use App\RecordedGame;
use App\Http\Requests;
use App\Jobs\RecAnalyzeJob;

class GamesController extends Controller
{
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs->disk('local');
    }

    /**
     * Show the recorded game upload form.
     */
    public function uploadForm()
    {
        return view('upload_form');
    }

    /**
     * Upload and save a recorded game file.
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'recorded_game' => 'required',
        ]);

        $file = $request->file('recorded_game');

        $storageName = $file->hashName();
        // Redirect to the analysis page if this exact file was uploaded
        // before.
        if ($this->fs->exists('recordings/' . $storageName)) {
            $rec = RecordedGame::where('path', $storageName)->first();
            if ($rec) {
                return redirect()->action('GamesController@show', $rec->slug);
            }
        }

        $tmpPath = $file->path();
        $path = $this->fs->putFile('recordings', $file);

        $filename = $file->getClientOriginalName();

        // Save the recorded game file metadata.
        $model = (new RecordedGame([
            'path' => $path,
            'filename' => $filename,
        ]))->generatedSlug();

        $model->save();

        dispatch(new RecAnalyzeJob($model));

        return redirect()->action('GamesController@show', $model->slug);
    }

    public function list(Request $request)
    {
        $recs = RecordedGame::where('status', 'completed')->orderBy('created_at', 'desc');

        $recs->with([
            'analysis',
            'analysis.players' => function ($query) {
                return $query
                    ->orderBy('team', 'asc')
                    ->where('type', '!=', 'spectator');
            }
        ]);

        $filter = $request->input('filter');

        if (isset($filter['player'])) {
            collect($filter['player'])->each(function ($name) use (&$recs) {
                $recs->hasPlayer($name);
            });
        }

        return view('recorded_games_list', [
            'recordings' => $recs->paginate(32),
        ]);
    }

    /**
     * Show data about a recorded game file.
     */
    public function show(Request $request, string $slug)
    {
        $rec = RecordedGame::where('slug', $slug)->first();

        $html = $this->fs->get('analyses/' . $rec->slug . '.html');

        return view('recorded_game_result', [
            'html' => $html,
        ]);
    }

    public function download($slug)
    {
        $rec = RecordedGame::where('slug', $slug)->first();

        $localPath = storage_path('app/' . $rec->path);

        return response()->download($localPath, $rec->filename);
    }
}
