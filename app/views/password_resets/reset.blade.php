@extends('layouts.master')

@section('content')

    {{ Form::open() }}

        <h1>Password Reset Form</h1>
        <p>Passwords must be AT LEAST 6 characters in length:</p>
        {{ Form::hidden('token', $token) }}

    <div class="form-group row">
      <div class="col-lg-5">
            {{ Form::label('email', 'Email: ') }}
            {{ Form::input("email", "email", null, ["class" => "form-control", "required" => true]) }}
      </div>
    </div>

    <div class="form-group row">
      <div class="col-lg-5">
            {{ Form::label('password', 'Password: ') }}
            {{ Form::input("password", "password", null, ["class" => "form-control", "required" => true]) }}
      </div>
    </div>

    <div class="form-group row">
      <div class="col-lg-5">
            {{ Form::label('password_confirmation', 'Password Confirmation: ') }}
            {{ Form::input("password", "password_confirmation", null, ["class" => "form-control", "required" => true]) }}
      </div>
    </div>

        <div>
            {{ Form::submit('Reset Password') }}
        </div>
    {{ Form::close() }}




@stop