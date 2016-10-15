<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'GamesController@list');

Route::get('/upload', 'GamesController@uploadForm');
Route::post('/upload', 'GamesController@upload');

Route::get('/game/{slug}', 'GamesController@show');
