<?php

//Route::any('/', array( 'as' => 'home', 'uses' => 'AccountsController@index' ));
Route::get('/', function() {
    dd(App::environment());
});

Route::resource('accounts', 'AccountsController');
Route::post('/accounts/create', 'AccountsController@upload');

Route::get('logon', 'SessionsController@create');
Route::get('logout', 'SessionsController@destroy');
Route::resource('sessions', 'SessionsController');


