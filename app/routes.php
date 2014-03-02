<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use redhotmayo\api\controllers\ApiAccountController;
use redhotmayo\api\controllers\ApiNoteController;
use redhotmayo\api\controllers\ApiRegistrationController;
use redhotmayo\api\controllers\ApiSessionController;

Route::get('/', ['as' => 'home', function() {
    return 'Red Hot Mayo Homepage';
}]);
////TODO: REMOVE ===========================================
//
//Route::get('/api/token_test', ['before' => 'api.auth', 'uses' => ApiAccountController::SEARCH]);
//Route::post('/api/token_test', ['before' => 'api.auth', 'uses' => ApiAccountController::SEARCH]);
//
//// Mobile API
//// Authorization / Authentication
//Route::post('/api/login/', ApiSessionController::LOGIN);
//
//// Accounts
//Route::get('/api/accounts/search', ApiAccountController::SEARCH);
//Route::get('/api/accounts/distance', ApiAccountController::DISTANCE);
//Route::post('/api/accounts/delete', ['before' => 'api.auth', 'uses' => ApiAccountController::DELETE]);
//Route::post('/api/accounts/target', ['before' => 'api.auth', 'uses' => ApiAccountController::TARGET]);
//Route::post('/api/accounts/update', ApiAccountController::UPDATE);
//
//
//// Notes
//Route::post('/api/notes/add', ApiNoteController::ADD);
//
////TODO : ==================================================

Route::post('/api/login/', ApiSessionController::LOGIN);
Route::post('/api/users/new', ApiRegistrationController::STORE);

// Mobile API
Route::group(array('before' => 'api.auth'), function() {
    Route::get('/api/accounts/search', ApiAccountController::SEARCH);
    Route::get('/api/accounts/distance', ApiAccountController::DISTANCE);

    Route::post('/api/accounts/delete', ApiAccountController::DELETE);
    Route::post('/api/accounts/target', ApiAccountController::TARGET);
    Route::post('/api/accounts/update', ApiAccountController::UPDATE);
    Route::post('/api/notes/add', ApiNoteController::ADD);
});


// Web System
Route::get('profile', function() {
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

