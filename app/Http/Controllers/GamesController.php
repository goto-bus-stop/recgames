<?php

namespace App\Http\Controllers;

use Emgag\Flysystem\Tempdir;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

use App\Http\Requests;
use App\Jobs\RecAnalyzeJob;
use App\Model\{GameSet, RecordedGame};
use App\Contracts\{AnalysisStorageService, AnalysisSearchService};

class GamesController extends Controller
{
    use UploadsRecordedGames;

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

        $title = 'Uploaded files';
        if (count($files) === 1) {
            if ($files[0]->getMimeType() === 'application/zip' || $files[0]->getMimeType() === 'application/x-rar') {
                $title = pathinfo($files[0]->getClientOriginalName(), PATHINFO_FILENAME);
            }
        }

        $set = (new GameSet([
            'title' => $title,
            'description' => 'Auto-generated set for multiple upload.',
        ]))->generatedSlug();
        $set->save();

        $temp = new Tempdir();
        $models = collect($files)
            ->map(function ($file) use (&$temp) {
                return $this->extract($file, $temp);
            })
            ->flatten()
            ->map(function ($file) {
                return $this->storeRecordedGame($file, $this->fs);
            });

        $set->recordedGames()->saveMany($models);

        $models->each(function ($rec) {
            dispatch(RecAnalyzeJob::uploaded($rec));
        });

        if (count($models) === 1) {
            return redirect()->action('GamesController@show', $models->first()->slug);
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

        if (!empty($filter['generic'])) {
            $coll = app(AnalysisSearchService::class)->search($filter['generic'])->all();

            $recs = RecordedGame::whereHas('analysis', function ($query) use (&$coll) {
                $query->whereIn('id', $coll);
            });
        }

        $recs->withAnalysis();

        return view('games.list', [
            'filter' => $filter ?? [],
            'recordings' => $recs->paginate(32),
        ]);
    }

    /**
     * Show data about a recorded game file.
     */
    public function show(Request $request, AnalysisStorageService $analyses, string $slug)
    {
        $rec = RecordedGame::where('slug', $slug)->first();

        if (!$rec) {
            abort(404, 'Could not find the requested game.');
        }

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

        $doc = $analyses->get($rec->analysis->id);
        $html = view('analysis.index', [
            'achievements' => !!($doc->players()->first()->achievements ?? false),
            'rec' => $rec,
            'analysis' => $doc,
        ])->render();

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
