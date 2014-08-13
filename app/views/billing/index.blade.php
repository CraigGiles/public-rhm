@extends('layouts.master')

@section('content')
    @include('partials.errors')

    <div class="billing-summary" >
        <h2>Subscription Order Summary</h2>
        Your monthly charge is calculated based on the regions you have selected:<br /><br />

        @if(isset($showCurrentSubscription) && $showCurrentSubscription)
            <div class="billing-current">
                <li>Current subscription monthly total: ${{ $currentPrice }}</li>
            </div>
        @endif

        <div class="billing-total">
            <li>New subscription monthly total: ${{ $priceInDollars }}</li>
        </div>
    </div>

    <br />

    {{ Form::open(['url' => 'billing']) }}
        <script
            src="https://checkout.stripe.com/checkout.js"
            class="stripe-button"
            data-key="{{ $billingToken }}"
            data-amount="{{ $priceInPennies }}"
            data-name="{{ $name }}"
            data-description="{{ $description }}"
            data-image="/{{ $image }}">
        </script>
    {{ Form::close() }}
@stop
