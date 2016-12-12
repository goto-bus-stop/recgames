<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'API'], function () {
    Route::get('recorded-games', 'GamesController@list')->name('api.recorded-games.list');
    Route::post('recorded-games', 'GamesController@create')->name('api.recorded-games.create');
    Route::get('recorded-games/{id}', 'GamesController@show')->name('api.recorded-games.show');
    Route::post('recorded-games/{id}/file', 'GamesController@upload')->name('api.recorded-games.upload');
    Route::get('recorded-games/{id}/file', 'GamesController@download')->name('api.recorded-games.download');
    Route::post('recorded-games/{id}/reanalyze', 'GamesController@reanalyze')->name('api.recorded-games.reanalyze');

    Route::get('sets', 'SetsController@list')->name('api.sets.list');
    Route::post('sets', 'SetsController@create')->name('api.sets.create');
    Route::get('sets/{id}', 'SetsController@show')->name('api.sets.show');
    Route::get('sets/{id}/items', 'SetsController@showGames')->name('api.sets.items');
});
