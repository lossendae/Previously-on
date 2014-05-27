<?php

$base = 'Lossendae\PreviouslyOn\Controllers\\';

Route::get('/', $base . 'IndexPageController@index');

Route::group(array('before' => 'xsrf', 'prefix' => 'api'), function () use($base)
{
    Route::get('remote/search', $base . 'Api\SearchController@get');
    Route::put('remote/{id}', $base . 'Api\AssignController@put');

    Route::get('manage/list', $base . 'ManageController@query');
    Route::get('manage/{id}', $base . 'ManageController@listSeasons');
    Route::put('manage/{id}/{status}', $base . 'EpisodeStatusController@update');
    Route::delete('manage/{id}', $base . 'ManageController@removeTvShow');
});

Route::get('/{all}', $base . 'IndexPageController@index')
     ->where('all', '.*');
