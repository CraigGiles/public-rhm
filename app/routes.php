<?php

//Route::resource('user', 'UserController');
Route::resource('accounts', 'AccountsController');

Route::get('/', function() {
	return View::make('hello');
});

Route::get('users', function() {
	return 'Hello World!';
});

//Route::get('env', function() {
//    dd(App::environment());
//});
//
//Route::get('api/accounts?&zip={zip}', function() {
//    return "All accounts in zipcode {$zip}";
//});
//
//Route::get('api/user/{user}?zipcode={zipcode}&date={date}', function($user, $zipcode, $date) {
//    return "yep, {$user}, {$zipcode}";
//});
