@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/string-helper.js'); }}
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section('black-bar-text')
        SELECT YOUR COVERAGE AREA FOR PRICING:
    @endsection

    @section("content")
        <div class="row" style="padding-bottom: 10px; padding-right: 30px">
            <h1>
                <div class="btn btn-lg btn-primary pull-right" id="subscription-submit" style="padding-right: 20px">Continue</div>
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
