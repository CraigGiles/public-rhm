<?php

Route::resource('user', 'UserController');

Route::get('/', function() {
	return View::make('hello');
});

Route::get('users', function() {
	return 'Hello World!';
});