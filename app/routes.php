<?php

Route::any('/', array( 'as' => 'home', 'uses' => 'AccountsController@index' ));
Route::resource('accounts', 'AccountsController');


