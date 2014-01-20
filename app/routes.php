<?php

use Bogardo\Mailgun\Facades\Mailgun;

Route::get('/', ['as' => 'home', function() {
    return 'Red Hot Mayo Homepage';
}]);

Route::get('profile', function() {
    dd(Auth::user());
    return "Welcome ". Auth::user()->username;
})->before('auth');

Route::resource('accounts', 'AccountsController');
Route::post('/accounts/create', 'AccountsController@upload');

Route::get('login', array('as' => 'login', 'uses' => 'SessionsController@create'));
Route::get('logout', array('as' => 'logout', 'uses' => 'SessionsController@destroy'));
Route::resource('sessions', 'SessionsController', ['only' => ['store', 'create', 'destroy']]);

Route::get('password_resets/reset/{token}', 'PasswordResetsController@resetPasswordForm');
Route::post('password_resets/reset/{token}', 'PasswordResetsController@update');
Route::resource('password_resets', 'PasswordResetsController', ['only' => ['store', 'create', 'destroy']]);

