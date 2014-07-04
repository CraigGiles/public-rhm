@extends('layouts.master')

@section('content')
    @include('partials.errors')

    <div class="billing-summary" >
        <h2>Billing Summary</h2>
        Price is calculated based on the population of territories:<br /><br />

        <div class="billing-current">
        Current subscription payment: ${{ $currentPrice }}<br /><br />
        </div>

        <div class="billing-total">
        New subscription total:
            <li>Population Total: {{ $population }} </li>
            <li>Price per month: ${{ $price }}</li>
        </div>
    </div>

    <br />

    {{ Form::open(['url' => 'billing']) }}
        <script
            src="https://checkout.stripe.com/checkout.js"
            class="stripe-button"
            data-key="{{ $billingToken }}"
            data-amount="{{ $price }}"
            data-name="{{ $name }}"
            data-description="{{ $description }}"
            data-image="/{{ $image }}">
        </script>
    {{ Form::close() }}
@stop
