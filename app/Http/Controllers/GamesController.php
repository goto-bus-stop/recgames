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

        dispatch(RecAnalyzeJob::uploaded($model));

        return redirect()->action('GamesController@show', $model->slug);
    }

    public function list(Request $request)
    {
        $recs = RecordedGame::where('status', 'completed')->orderBy('created_at', 'desc');

        $filter = $request->input('filter');

        if (isset($filter['player'])) {
            collect($filter['player'])->each(function ($name) use (&$recs) {
                $recs->hasPlayer($name);
            });
        }

        $recs->with([
            'analysis',
            'analysis.players' => function ($query) {
                return $query
                    ->orderBy('team', 'asc')
                    ->where('type', '!=', 'spectator');
            }
        ]);

        return view('recorded_games_list', [
            'filter' => $filter ?? [],
            'recordings' => $recs->paginate(32),
        ]);
    }

    /**
     * Show data about a recorded game file.
     */
    public function show(Request $request, string $slug)
    {
        $rec = RecordedGame::where('slug', $slug)->first();

        if ($rec->status === 'errored') {
            return view('recorded_game_error', [
                'rec' => $rec,
            ]);
        } else if ($rec->status !== 'completed') {
            return view('recorded_game_status', [
                'rec' => $rec,
            ]);
        }

        if ($rec->analysis->isOutdated()) {
            dispatch(RecAnalyzeJob::reanalyze($rec));
        }

        $html = $this->fs->get('analyses/' . $rec->slug . '.html');

        $title = $rec->analysis->players
            ->reject(function ($player) { return $player->type === 'spectator'; })
            ->groupBy(function ($player) {
                return $player->team ?: uniqid();
            })
            ->map(function ($team, $key) {
                return $team->pluck('name')->implode(', ');
            })
            ->implode(' VS ');

        return view('recorded_game_result', [
            'title' => $title,
            'rec' => $rec,
            'html' => $html,
        ]);
    }

    public function download(string $slug)
    {
        $rec = RecordedGame::where('slug', $slug)->first();

        $localPath = storage_path('app/' . $rec->path);

        return response()->download($localPath, $rec->filename);
    }
}
