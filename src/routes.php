<?php

$base = 'Lossendae\Admin\Controllers\\';

Route::get('/', 'IndexPageController@index');

Route::group(array('before' => 'xsrf', 'prefix' => 'api'), function ()
{
    Route::get('remote/search', 'ApiController@search');
    Route::put('remote/{id}', 'ApiController@put');

    Route::get('manage/list', 'ManageController@query');
    Route::get('manage/{id}', 'ManageController@listSeasons');
    Route::put('manage/{id}/{status}', 'EpisodeStatusController@update');
    Route::delete('manage/{id}', 'ManageController@removeTvShow');
});

Route::get('/{all}', 'IndexPageController@index')
     ->where('all', '.*');
