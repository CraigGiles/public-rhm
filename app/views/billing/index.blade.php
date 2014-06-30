@extends('layouts.master')

@include('partials.errors')

@section('content')
    {{ Form::open(['url' => 'billing']) }}
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="pk_test_o4o45Ofc5jC5zohixHx5J4f6"
            data-amount="2000"
            data-name="Demo Site"
            data-description="2 widgets ($20.00)"
            data-image="/128x128.png">
        </script>
    {{ Form::close() }}

    @include('partials.billing.total')

@stop
