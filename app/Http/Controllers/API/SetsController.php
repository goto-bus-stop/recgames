<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\GameSet;
use App\Model\RecordedGame;

class SetsController extends Controller
{
    /**
     * List public sets.
     */
    public function list()
    {
        $sets = GameSet::paginate(10);

        return response()->jsonapi()->list($sets);
    }

    /**
     * Show a single set.
     *
     * @param string  $slug  URL Slug of the set.
     */
    public function show(string $slug)
    {
        $set = GameSet::fromSlug($slug);
        if (!$set) {
            throw new NotFoundException('That set does not exist.');
        }

        return response()->jsonapi()->single($set);
    }

    /**
     * Create a new set.
     *
     * @param \Illuminate\Http\Request  $request
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'data.attributes' => 'required',
            'data.attributes.title' => 'string',
            'data.attributes.description' => 'string',
            'data.relationships.games.*.id' => 'string',
            'data.relationships.games.*.type' => 'in:recorded-games',
        ]);

        $data = $request->input('data');

        $attrs = array_only($data['attributes'], [
            'title',
            'description',
        ]);

        $set = (new GameSet($attrs))->generatedSlug();
        $set->save();

        if (array_has($data, 'relationships.games')) {
            $gameSlugs = array_pluck($data['relationships']['games'], 'id');
            $set->recordedGames()->saveMany(
                RecordedGame::whereIn('slug', $gameSlugs)->get()
            );
        }

        return response()->jsonapi()->single($set);
    }
}
