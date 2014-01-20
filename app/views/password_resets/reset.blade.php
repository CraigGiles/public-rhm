@extends('layouts.master')

@section('content')

    {{ Form::open() }}

        <h1>Password Reset Form</h1>
        <p>Passwords must be AT LEAST 6 characters in length:</p>
        {{ Form::hidden('token', $token) }}

        <div>
            {{ Form::label('email', 'Email Address:') }}
            {{ Form::text('email', null, ['required' => true]) }}
        </div>

        <div>
            {{ Form::label('password', 'Password:') }}
            {{ Form::password('password') }}
        </div>

        <div>
            {{ Form::label('password_confirmation', 'Password Confirmation:') }}
            {{ Form::password('password_confirmation') }}
        </div>

        <div>
            {{ Form::submit('Reset Password') }}
        </div>
    {{ Form::close() }}




@stop