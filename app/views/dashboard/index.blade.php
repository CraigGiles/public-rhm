@extends('layouts.master')

@section('content')
    @include('partials.errors')
    <h2>Dashboard</h2>

    {{ link_to('subscribe', 'Update Regions', ['class' => 'btn btn-primary btn-lg']) }}
    {{ link_to('billing/cancel', 'Cancel Subscription', ['class' => 'btn btn-primary btn-lg']) }}

<div class="col-md-4 pull-right app-image" style="margin-top: -80px">
    <img src="http://redhotmayo.com/wp-content/uploads/2014/03/Phone-Tablet-Launch-Page-image-portrait-v2.png" width="100%"/>
</div>

@endsection

@stop

@section('javascript')
{{ HTML::script('assets/javascripts/dashboard/dashboard.js') }}
@endsection
