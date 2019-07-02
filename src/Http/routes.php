<?php

Route::group(['prefix' => 'zleader'], function() {
    Route::get('/', 'DashboardController@index');
    Route::get('/leads', 'LeadController@index');
    Route::get('/leads/datagrid/source', 'LeadController@datagrid');
    Route::get('/leads/{lead_id}', 'LeadController@show');
    Route::patch('/leads/{lead_id}', 'LeadController@patch');
    Route::get('/forms', 'FormController@index');
    Route::get('/forms/json', 'FormController@jsonIndex');
    Route::get('/config', 'ConfigController@index');
    Route::get('/companies', 'CompanyController@index');
    Route::get('/areas', 'AreaController@index');
    Route::get('/fields', 'FieldController@index');
});
