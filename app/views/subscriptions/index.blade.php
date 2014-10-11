@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/string-helper.js'); }}
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section("content")
        
    <div id="bodywrap">
        <div id="nav">
            SELECT YOUR COVERAGE AREA FOR PRICING: 
        </div>

        <div id="right-bar">
                <img src="assets/tablets.png"><p>
                <img src="assets/continue_button.jpg">
        </div> 
    </div>


        <div class="page-header">
            <h1>
                Select your coverage area to get pricing.
                <div class="btn btn-lg btn-primary pull-right" id="subscription-submit" style="padding-right: 20px">Subscribe</div>
                <div class="pull-right" id="subscription-total" style="padding-right: 20px"></div>
            </h1>
        </div>

        <div class="row">

            <div class="col-sm-3 sidebar-module well">
                @include('subscriptions.sidebar', [$states, $counties])
            </div>

            <div class="col-sm-5">
                @include('subscriptions.available-regions', $activeSubscriptions)
            </div>

            <div class="col-sm-4">
                @include('subscriptions.selected-regions', $activeSubscriptions)
            </div>
        </div>

        <div style="display: none">
            <div id="region-item-template">
                <li style="padding: 2px 0px">
                    {{ Form::button('+', ['class' => "btn btn-success region-item-button-add"]) }}
                    {{ Form::label('region-city', "cityName", ['class' => 'region-city type']) }}
                    ({{ Form::label('region-county', "countyName", ['class' => 'region-county text-muted small']) }},
                    {{ Form::label('region-state', "STATENAME", ['class' => 'region-state text-muted small']) }})
                </li>
            </div>
        </div>

    @endsection
@stop
