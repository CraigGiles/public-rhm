<?php

Route::any('/', array( 'as' => 'home', 'uses' => 'AccountsController@index' ));
Route::resource('accounts', 'AccountsController');
Route::post('/accounts/create', 'AccountsController@upload');


