<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\GameSet;
use App\RecordedGame;

class SetsController extends Controller
{
    /**
     * Serialize a set into a JSON-API compatible array.
     *
     * @param \App\GameSet  $set  Set.
     * @return array
     */
    private function serializeSet(GameSet $set): array
    {
        return [
            'type' => 'sets',
            'id' => $set->slug,
            'attributes' => [
                'title' => $set->title,
                'description' => $set->description,
            ],
            'relationships' => [
                'games' => [
                    'links' => [
                        'related' => action('API\SetsController@showGames', $set->slug),
                    ],
                    'data' => $set->recordedGames->map(function (RecordedGame $rec): array {
                        return ['type' => 'recorded-games', 'id' => $rec->slug];
                    })->all(),
                ],
            ],
            'links' => [
                'self' => action('API\SetsController@show', $set->slug),
            ],
        ];
    }

    /**
     * List public sets.
     */
    public function list()
    {
        $sets = GameSet::paginate(10);

        return response()->json([
            'links' => [
                'first' => $sets->url(0),
                'last' => $sets->url($sets->lastPage()),
                'prev' => $sets->previousPageUrl(),
                'next' => $sets->nextPageUrl(),
            ],
            'meta' => [],
            'data' => $sets->getCollection()->map(function (GameSet $set): array {
                return $this->serializeSet($set);
            })->all(),
        ], 200);
    }

    /**
     * Show a single set.
     *
     * @param string  $slug  URL Slug of the set.
     */
    public function show(string $slug)
    {
        $set = GameSet::fromSlug($slug);
        return response()->json([
            'meta' => [],
            'data' => $this->serializeSet($set),
        ], 200);
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

        return response()->json($this->serializeSet($set), 200);
    }
}
