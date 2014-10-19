@extends('layouts.master')

@section('content')
    @include('partials.errors')
    <h2>Dashboard</h2>

    {{ link_to('subscribe', 'Update Regions', ['class' => 'btn btn-primary btn-lg']) }}
    {{ link_to('billing/cancel', 'Cancel Subscription', ['class' => 'btn btn-primary btn-lg']) }}

    <?php
		$username = Auth::user()->username;
		$usernames = array('amye', 'donv', 'dgiles', 'cgiles', 'jweyer');
	?>
    @if (in_array($username, $usernames))
		{{ link_to('administration', 'Upload Leads', ['class' => 'btn btn-primary btn-lg']) }}
	@endif

@endsection
@stop

@section('javascript')
{{ HTML::script('assets/javascripts/dashboard/dashboard.js') }}
@endsection
