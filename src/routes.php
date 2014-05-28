<?php

$base = 'Lossendae\PreviouslyOn\Controllers\\';

Route::group(array('before' => 'xsrf', 'prefix' => 'api'), function () use($base)
{
    Route::get('remote/search', $base . 'Api\SearchController@get');
    Route::put('remote/{id}', $base . 'Api\AssignController@put');

    Route::get('manage/list', $base . 'ManageController@query');
    Route::get('manage/{id}', $base . 'ManageController@listSeasons');
    Route::put('manage/{id}/{status}', $base . 'EpisodeStatusController@update');
    Route::delete('manage/{id}', $base . 'ManageController@removeTvShow');
});

Route::group(array('before' => 'xsrf'), function () use($base)
{
    Route::get('auth/logout', $base . 'AuthController@logout');
});

Route::get('auth/token', function ()
{
    return array('_token' => csrf_token());
});

Route::get('auth/check', function ()
{
        return array('logged' => Auth::check());
});

Route::get('auth/session', function ()
{
    return ['user' => Auth::check() ? Auth::user()->toArray() : false];
});

Route::post('auth/login', $base . 'AuthController@login');

Route::group(array('prefix' => Config::get('previously-on::app.url_prefix')), function () use ($base)
{
    Route::get('/', $base . 'IndexPageController@index');

    // Lastly all the remaining url access will redirect to the index
    Route::get('/{all}', $base . 'IndexPageController@index')
         ->where('all', '.*');
});
