@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/string-helper.js'); }}
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section("content")
        <div class="page-header">
            <h1>
                Select your coverage area to get pricing.
                <div class="price-list pull-right">Total: $49.99</div>
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
            @include('subscriptions.partials.region_item', [
                 'buttonColor' => 'success',
                 'addOrRemove' => 'add',
                 'city' => 'cityName',
                 'county' => 'countyName',
                 'state' => 'stateName',
             ])
        </div>
        </div>

    @endsection
@stop
