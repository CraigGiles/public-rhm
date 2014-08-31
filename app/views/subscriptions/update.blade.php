@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/string-helper.js'); }}
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section("content")
        <div class="page-header">
          <h1>
            Select your coverage area to get pricing.
            <div class="price-list"></div>
          </h1>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4><span class="badge alert-success">1</span> Select a state to reveal its regions</h4>
                    @include('subscriptions.partials.dropdown', [
                        'items' => $states,
                        'dropdown_id' => 'dropdown-states',
                        'label' => 'Select a State',
                        'item_id' => "state-item"
                    ])
                </div>
                <div class="col-md-6">
                    <h4><span class="badge alert-success">2</span> Further filter regional options by county</h4>
                    @include('subscriptions.partials.dropdown', [
                        'items' => $counties,
                        'dropdown_id' => 'dropdown-counties',
                        'label' => 'Filter by County',
                        'item_id' => "county-item"
                    ])
                </div>
            </div>
            <br/>

            <div class="row">
                <div class="col-md-6">
                    <h4><span class="badge alert-success">3</span> Select your desired regions</h4>
                    <div class="selected-regions" id="available-regions">
                        @include('subscriptions.partials.region_item', [
                            'class' => 'region-item',
                            'searchTerm' => 'Search Term Here',
                            'buttonText' => '+',
                            'buttonColor' => 'success',
                            'regionType' => 'city',
                            'regionItemAddOrRemove' => 'region-item-add',
                            'state' => 'CA'
                        ])
                    </div>
                    <br>
                </div>
                <div class="col-md-6">
                    <h4><span class="badge alert-success">4</span> Your new subscription list</h4>
                    {{ Form::open() }}
                    <div class="selected-regions">

                        {{ Form::open() }}
                        @foreach($subscriptions as $sub)
                            <div class="region">
                                {{ Form::button('x', ['class' => 'btn btn-primary region-item-remove']) }} {{ Str::title($sub->getCity()) }}
                            </div>
                        @endforeach
                        {{ Form::submit('Unsubscribe', ['class' => 'btn btn-lg btn-primary pull-right']) }}
                        {{ Form::close() }}
                    </div>
                    <br />

                    {{ Form::submit('Subscribe', ['class' => 'btn btn-lg btn-primary pull-right']) }}
                    {{ Form::close() }}
                    <br>
                </div>
            </div>
        </div>

        <br>


    @endsection
    @include('subscriptions.partials.region_item_template')
@stop
