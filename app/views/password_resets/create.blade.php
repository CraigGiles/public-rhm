@extends('layouts.master')

@section('content')
    <h1>Reset Password</h1>

    <p>
    Please enter your email address and we will send you instructions on how to reset your password.
    </p>

    {{ Form::open(['route' => 'password_resets.store' ]) }}

    <div class="form-group row">
      <div class="col-lg-5">
            {{ Form::label('email', 'Email Address') }}
            {{ Form::input("email", "email", null, ["class" => "form-control", "required" => true]) }}
      </div>
    </div>

    <div>
        {{ Form::submit('Reset') }}
    </div>
    {{ Form::close() }}

@if (Session::has('error'))
<p>{{ Session::get('reason') }}</p>
@endif

@stop

