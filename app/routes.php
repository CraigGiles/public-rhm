<?php

use Bogardo\Mailgun\Facades\Mailgun;
//
//Route::get('/test', function() {
//    $data = array(
//        'customer' => 'John Smith',
//        'url' => 'http://laravel.com'
//    );
//
//    Mail::queue('emails.welcome', $data, function($message)
//        {
//            $message->to('craig@gilesc.com', 'John Smith')->subject('Welcome!');
//        }
//    );
////    Mailgun::send('emails.welcome', $data, function($message)
////    {
////        $message->to('craig@gilesc.com', 'John Smith')->subject('Welcome!');
////    });
//
//    return 'email sent?';
//});

Route::get('/', ['as' => 'home', function() {
    return 'Home page';
}]);

Route::get('profile', function() {
    dd(Auth::user());
    return "Welcome ". Auth::user()->username;
})->before('auth');

Route::resource('accounts', 'AccountsController');
Route::post('/accounts/create', 'AccountsController@upload');

Route::get('login', 'SessionsController@create');
Route::get('logout', 'SessionsController@destroy');
Route::resource('sessions', 'SessionsController', ['only' => ['store', 'create', 'destroy']]);

Route::get('password_resets/reset/{token}', 'PasswordResetsController@resetPasswordForm');
Route::post('password_resets/reset/{token}', 'PasswordResetsController@reset');
Route::resource('password_resets', 'PasswordResetsController');

