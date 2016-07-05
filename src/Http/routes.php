<?php
Route::group(['prefix' => 'zleader'], function( ){
	Route::get('/', 'Zephia\ZLeader\Http\Controllers\ZLeaderController@index');
	Route::get('/datagrid/source', 'Zephia\ZLeader\Http\Controllers\DataGridController@source');
});
	