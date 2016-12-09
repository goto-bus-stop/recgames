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
    Route::get('recorded-games', 'GamesController@show');
    Route::post('recorded-games', 'GamesController@create');
    Route::get('recorded-games/{id}', 'GamesController@show');
    Route::post('recorded-games/{id}/file', 'GamesController@upload');
    Route::get('recorded-games/{id}/file', 'GamesController@download');
    Route::post('recorded-games/{id}/reanalyze', 'GamesController@reanalyze');

    Route::get('sets', 'SetsController@list');
    Route::post('sets', 'SetsController@create');
    Route::get('sets/{id}', 'SetsController@show');
    Route::get('sets/{id}/items', 'SetsController@showGames');
});
