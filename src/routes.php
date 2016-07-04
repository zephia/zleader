<?php
Route::group(['prefix' => 'zleader'], function( ){
	Route::get('/', 'Zephia\ZLeader\ZLeaderController@index');
});
	