<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

use App\Http\Requests;
use App\Model\GameSet;
use App\Model\RecordedGame;
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

        $files = $request->file('recorded_game');

        $set = (new GameSet([
            'title' => 'Uploaded files',
            'description' => 'Auto-generated set for multiple upload.',
        ]))->generatedSlug();
        $set->save();

        collect($files)->each(function ($file) use (&$set) {
            $storageName = $file->hashName();

            // Reuse a previous recorded game resource if this same game was
            // uploaded before.
            if ($this->fs->exists('recordings/' . $storageName)) {
                $rec = RecordedGame::where('path', $storageName)->first();
                $set->recordedGames()->save($rec);
                return;
            }

            $tmpPath = $file->path();
            $path = $this->fs->putFile('recordings', $file);

            $filename = $file->getClientOriginalName();

            // Save the recorded game file metadata.
            $model = (new RecordedGame([
                'path' => $path,
                'filename' => $filename,
            ]))->generatedSlug();

            $set->recordedGames()->save($model);

            dispatch(RecAnalyzeJob::uploaded($model));
        });

        if (count($files) === 1) {
            return redirect()->action('GamesController@show', $set->recordedGames()->first()->slug);
        }

        return redirect()->action('SetsController@show', $set->slug);
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

        return view('games.list', [
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
            return view('games.show_error', [
                'rec' => $rec,
            ]);
        } else if ($rec->status !== 'completed') {
            return view('games.show_status', [
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

        return view('games.show', [
            'title' => $title,
            'rec' => $rec,
            'html' => $html,
        ]);
    }

    public function embed(Request $request, string $slug)
    {
        $rec = RecordedGame::where('slug', $slug)->first();

        $html = $this->fs->get('analyses/' . $rec->slug . '.html');

        return view('games.embed', [
            'rec' => $rec,
            'title' => $rec->title,
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
