<?php

Route::group(['prefix' => 'zleader/api', 'middleware' => 'zleadercors'], function() {
    Route::post('/lead/form/{form_slug}', 'Api\LeadController@store');
    Route::post('/fbwebhook', 'Api\FbwebhookController@store');
    Route::get('/fbwebhook', 'Api\FbwebhookController@store');
    Route::get('/fb-leadgen-platform', 'Api\FbwebhookController@platform');
});
