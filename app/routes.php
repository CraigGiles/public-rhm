<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use redhotmayo\api\controllers\ApiAccountController;

Route::get('/', ['as' => 'home', function() {
    return 'Red Hot Mayo Homepage';
}]);

// Mobile API
// Authorization / Authentication
Route::post('/api/authorize/', 'redhotmayo\api\controllers\ApiController@authorize');

// Accounts
Route::get('/api/accounts/search', 'redhotmayo\api\controllers\ApiAccountController@search');
Route::get('/api/accounts/distance', 'redhotmayo\api\controllers\ApiAccountController@distance');
Route::get('/api/accounts/delete', 'redhotmayo\api\controllers\ApiAccountController@delete');
Route::get('/api/accounts/target', 'redhotmayo\api\controllers\ApiAccountController@target');
Route::post('/api/accounts/update', ApiAccountController::UPDATE);


// Notes
Route::post('/api/notes/add', 'redhotmayo\api\controllers\ApiNoteController@add');

// Web System
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

