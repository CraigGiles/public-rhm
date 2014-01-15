@extends('layouts/master')

@section ('container')
    <h1>Upload new accounts file</h1>

{{ Form::open(['route' => 'accounts.create', 'files' => true]) }}
    <div class="form-group">
        {{ Form::file('accounts') }}
    </div>
    <div class="form-group">
        {{ Form::submit('Submit') }}
    </div>

{{ Form::close() }}

@stop