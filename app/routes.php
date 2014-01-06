<?php

//Route::resource('user', 'UserController');
Route::resource('accounts', 'AccountsController');

Route::get('/', function() {
	return View::make('hello');
});

Route::get('users', function() {
	return 'Hello World!';
});
