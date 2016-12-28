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

Route::get('/game/{slug}', 'GamesController@show')->name('games.show');
Route::get('/game/{slug}/embed', 'GamesController@embed')->name('games.embed');
Route::get('/game/{slug}/download', 'GamesController@download')->name('games.download');

Route::get('/sets', 'SetsController@list')->name('sets.list');
Route::get('/set/{slug}', 'SetsController@show')->name('sets.show');

Route::get('/recanalyst', 'RecAnalystController@index')->name('recanalyst');

Route::get('/profile/{user}', 'ProfileController@show');
Route::get('/profile', 'ProfileController@showSelf')->name('profile');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout')->name('logout');

    Route::get('/register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'RegisterController@register');

    Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
    Route::post('/password/reset', 'ResetPasswordController@reset');

    // Social logins
    Route::get('/auth/steam', 'SocialiteController@steamRedirect');
    Route::get('/auth/steam/callback', 'SocialiteController@steamCallback');
});
