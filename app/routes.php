<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use redhotmayo\api\controllers\ApiAccountController;
use redhotmayo\api\controllers\ApiNoteController;
use redhotmayo\api\controllers\ApiRegistrationController;
use redhotmayo\api\controllers\ApiSessionController;
use redhotmayo\api\controllers\MobileDeviceController;

Route::get('/', 'SubscriptionController@index');

Route::post('/api/login/', ApiSessionController::LOGIN);
Route::post('/api/users/new', ApiRegistrationController::STORE);

Route::post('/api/mobiledevice/create', MobileDeviceController::CREATE);

//--------------------------------------------------
// Mobile API
//--------------------------------------------------
Route::group(array('before' => 'api.auth'), function() {

    Route::get('/api/accounts/search', ApiAccountController::SEARCH);
    Route::get('/api/accounts/distance', ApiAccountController::DISTANCE);

    Route::post('/api/accounts/delete', ApiAccountController::DELETE);
    Route::post('/api/accounts/target', ApiAccountController::TARGET);
    Route::post('/api/accounts/update', ApiAccountController::UPDATE);
    Route::post('/api/notes/add', ApiNoteController::ADD);
});

//--------------------------------------------------
// Web System
//--------------------------------------------------

// Subscription
Route::get('geography/search', 'GeographyController@search');
Route::get('subscribe', 'SubscriptionController@index');
Route::resource('subscribe', 'SubscriptionController', ['only' => ['index', 'store']]);

// Registration
Route::get('registration', 'RegistrationController@index');
Route::get('registration/confirmation', 'RegistrationController@confirmation');

Route::group(['before' => 'csrf'], function(){
    Route::post('registration', 'RegistrationController@store');
});

Route::get('login', ['as' => 'login', 'uses' => 'SessionsController@create']);
Route::get('login/confirmation', 'SessionsController@confirmation');
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionsController@destroy']);
Route::resource('sessions', 'SessionsController', ['only' => ['store', 'create', 'destroy']]);

Route::get('password_resets/reset/{token}', 'PasswordResetsController@resetPasswordForm');
Route::post('password_resets/reset/{token}', 'PasswordResetsController@update');
Route::resource('password_resets', 'PasswordResetsController', ['only' => ['store', 'create', 'destroy']]);

Route::get('cancel', 'BillingController@cancel');
Route::resource('billing', 'BillingController');


/**
 * Remove the folowing once done testing
 */


View::composer('layouts.master', function($view)
{
  $username = null;
  if (Auth::user()) {
    $username = Auth::user()->username;
  }
  $view->with('username',$username);
});
