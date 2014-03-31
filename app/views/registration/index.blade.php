@extends('layouts.master')

@section('content')
<h1>Registration</h1>

@if($errors->has())
@foreach ($errors->all() as $error)
<div>{{ $error }}</div>
@endforeach
@endif

{{ Form::open(["url" => "registration", "autocomplete" => "off"]) }}

<ul>
    <li>
        {{ Form::label('username', 'User Name:') }}
        {{ Form::text('username') }}
    </li>

    <li>
        {{ Form::label('password', 'Password:') }}
        {{ Form::password('password') }}
    </li>

    <li>
        {{ Form::label('password_confirmation', 'Password Confirmation:') }}
        {{ Form::password('password_confirmation') }}
    </li>

    <li>
        {{ Form::label('email', 'Email:') }}
        {{ Form::text('email') }}
    </li>

    <li>
        {{ Form::submit() }}
    </li>
</ul>

{{ Form::close() }}
@stop