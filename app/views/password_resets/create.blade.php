@extends('layouts.master')

@section('content')
    <h1>Reset Password</h1>

    {{ Form::open(['route' => 'password_resets.store' ]) }}
    <div>
        {{ Form::label('email', 'Email Address') }}
        {{ Form::text('email', null, ['required' => true]) }}
    </div>
    <div>
        {{ Form::submit('Reset') }}
    </div>
    {{ Form::close() }}
@stop

