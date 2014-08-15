@extends('layouts.master')

@section('content')
    @include('partials.errors')

    <h2>Dashboard</h2>

    {{ link_to('subscription/update', 'Update Regions', ['class' => 'btn btn-primary btn-lg']) }}
    {{ link_to('billing/cancel', 'Cancel Subscription', ['class' => 'btn btn-primary btn-lg']) }}
@endsection
@stop
