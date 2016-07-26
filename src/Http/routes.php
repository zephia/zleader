<?php
Route::group(['prefix' => 'zleader'], function(){
    Route::get('/', 'DashboardController@index');
    Route::get('/leads', 'LeadController@index');
    Route::get('/leads/datagrid/source', 'LeadController@datagrid');
    Route::get('/leads/{lead_id}', 'LeadController@show');
    Route::get('/forms', 'FormController@index');
    Route::get('/config', 'ConfigController@index');
    Route::get('/companies', 'CompanyController@index');
    Route::get('/areas', 'AreaController@index');
    Route::get('/fields', 'FieldController@index');
});

Route::group(['prefix' => 'zleader/api', 'middleware' => 'zleadercors'], function(){
    Route::post('/lead/form/{form_slug}', 'Api\LeadController@store');
    Route::post('/fbwebhook', 'Api\FbwebhookController@store');
    Route::get('/fbwebhook', 'Api\FbwebhookController@store');
    Route::get('/fb-leadgen-platform', 'Api\FbwebhookController@platform');
});