<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\GameSet;
use App\RecordedGame;

class SetsController extends Controller
{
    private function serializeSet(GameSet $set)
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
                    'data' => $set->recordedGames->map(function ($rec) {
                        return ['type' => 'recorded-games', 'id' => $rec->slug];
                    })->all(),
                ],
            ],
            'links' => [
                'self' => action('API\SetsController@show', $set->slug),
            ],
        ];
    }

    public function list()
    {
        $sets = GameSet::paginate(10);

        return response()->json([
            'meta' => [],
            'data' => $sets->getCollection()->map(function (GameSet $set) {
                return $this->serializeSet($set);
            })->all(),
        ], 200);
    }

    public function show(string $slug)
    {
        $set = GameSet::fromSlug($slug);
        return response()->json([
            'meta' => [],
            'data' => $this->serializeSet($set),
        ], 200);
    }

    /**
     *
     */
    public function create(Request $request)
    {
        $data = collect($request->input('data'));
        $set = (new GameSet(
            collect($data['attributes'])
                ->only('title', 'description')
                ->all()
        ))->generatedSlug();
        $set->save();

        if ($data->has('relationships.games')) {
            $set->recordedGames()->saveMany($data['relationships']['games']);
        }

        return response()->json($this->serializeSet($set), 200);

        return response()->json([
            'meta' => [],
            'data' => [
                'type' => 'sets',
                'id' => $set->slug,
                'attributes' => [
                    'title' => $set->title,
                    'description' => $set->description,
                ],
                'relationships' => [
                    'items' => [
                        'links' => [
                            'related' => action('API\SetsController@showGames', $set->slug),
                        ],
                        'data' => $set->recordedGames->map(function ($rec) {
                            return [
                                'type' => 'recorded-games',
                                'id' => $rec->slug,
                            ];
                        })->all(),
                    ],
                ],
                'links' => [
                    'self' => action('API\SetsController@show', $set->slug),
                ],
            ],
        ], 200);
    }
}
