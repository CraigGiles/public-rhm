@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section("content")

        <div class="page-header">
          <h1>
            Select your coverage area to get pricing.
            <div class="price-list"></div>
          </h1>
        </div>

        <h4>Select a state to reveal its cities / counties</h4>
        @include('subscriptions.partials.dropdown', [
            'items' => $states,
            'dropdown_id' => 'dropdown-states',
            'label' => 'Select a State',
            'item_id' => "state-item"
        ])
        <br />

        <h4>Filter city selection by county</h4>
        @include('subscriptions.partials.dropdown', [
            'items' => $counties,
            'dropdown_id' => 'dropdown-counties',
            'label' => 'Filter by County',
            'item_id' => "county-item"
        ])

    @endsection
@stop
