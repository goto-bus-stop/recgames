<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\GameSet;

class SetsController extends Controller
{
    /**
     *
     */
    public function list()
    {
        $sets = GameSet::with([
            'thumbnailGames',
            'thumbnailGames.analysis'
        ])->withCount('recordedGames')->paginate(32);

        return view('sets.list', [
            'sets' => $sets,
        ]);
    }

    /**
     *
     */
    public function show(string $slug)
    {
        $set = GameSet::fromSlug($slug);

        return view('sets.show', [
            'set' => $set,
            'recordings' => $set->recordedGames()->paginate(32),
        ]);
    }
}
