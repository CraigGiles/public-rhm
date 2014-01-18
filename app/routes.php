<?php

//Route::any('/', array( 'as' => 'home', 'uses' => 'AccountsController@index' ));
Route::get('/', function() {
    echo "THIS IS MASTER";
    dd(App::environment());
});

Route::resource('accounts', 'AccountsController');
Route::post('/accounts/create', 'AccountsController@upload');


