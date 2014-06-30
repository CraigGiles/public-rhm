@extends('layouts.master')

@section('content')
    @include('partials.errors')
    @include('partials.billing.total')

    {{ Form::open(['url' => 'billing']) }}
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="pk_test_o4o45Ofc5jC5zohixHx5J4f6"
            data-amount="{{ $price }}"
            data-name="{{ $name }}"
            data-description="{{ $description }}"
            data-image="/{{ $image }}">
        </script>
    {{ Form::close() }}
@stop
