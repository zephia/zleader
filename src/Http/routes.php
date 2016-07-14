<?php
Route::group(['prefix' => 'zleader'], function( ){
    Route::get('/', 'DashboardController@index');
    Route::get('/leads', 'LeadController@index');
    Route::get('/datagrid/source', 'DataGridController@source');
    Route::get('/forms', 'FormController@index');
    Route::get('/config', 'ConfigController@index');
    Route::get('/companies', 'CompanyController@index');
    Route::get('/areas', 'AreaController@index');
});

Route::group(['prefix' => 'zleader/api'], function( ){
    Route::post('/lead/form/{form_slug}', 'Api\LeadController@store');
});