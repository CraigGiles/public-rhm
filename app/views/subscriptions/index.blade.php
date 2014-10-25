@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/string-helper.js'); }}
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section('black-bar-text')
        SELECT YOUR COVERAGE AREA FOR PRICING:
    @endsection

    @section("content")
    <div id="bodywrap">
        <div id="right-bar" style="background:transparent; pointer-events:none;">
            <img src="assets/tablets.png"><p>
            <div class="btn btn-lg btn-primary" id="subscription-submit" style="padding-right: 20px; margin-top: -7px; pointer-events:auto">Continue</div>
        </div> 

        <div id="main-left">
            <img src="assets/dropdown-subscribe.jpg" style="position:absolute;">
            <div class="styleselect" id="state-dropdown" style="padding-top: 10px; padding-left: 15px;">
            {{ Form::select('states', $states, null, [
                    'class' => 'styleselect', 'style' => 'position:relative'
                ]) }}
            </div>


            <div id="filterbox" class="col-sm-5" style="margin-top:45px;">
                <div class="styleselect" id="county-dropdown" style="margin-top:15px;">
                    {{ Form::select('counties', $counties, null, [
                            'class' => 'styleselect', 'style' => 'position:relative'
                        ]) }}

                    {{ Form::button('Go', [
                    'class' => 'buttontiny', 'id' => 'submit-region-info', 'style' => 'position:relative'
                        ]) }}
                </div>

                <ul id="available-regions-container" style="width: 100%; overflow-y: scroll; height: 100%; list-style-type: none"></ul>

            </div>

            <!--  <ul id="available-regions-container" style="width: 100%; min-height: 450px; overflow-y: scroll; height: 100%; list-style-type: none"></ul> -->
        </div>

        <div id="main-right" style="padding-top: 1px; white-space:nowrap;">
                <h1>
                    <font color="#fe504f" style="font-size:24px; font-family: 'roboto'; font-weight: 700;">TOTAL:&nbsp;</font><strike><span id="subscription-total"></span></strike> <font color="#8ec83e">FREE</font>
                </h1>
            <div id="filterbox">
                <p><h2>YOUR COVERAGE AREA(S)</h2><p>

                <ul id="selected-regions-container" style="padding-left:0; width: 100%; min-height: 450px; max-height: 450px; overflow-y: scroll; height: 100%; list-style-type: none">
                    @foreach($activeSubscriptions as $reg)
                         <li style="padding: 2px 0px;">
                             {{ Form::button('Unsubscribe', ['class' => "btn btn-success region-item-button-remove buttonunsubscribe"]) }}
                             {{ Form::label('region-city', Str::title($reg->getCity()), ['class' => 'region-city type']) }}
                             ({{ Form::label('region-county', Str::title($reg->getCounty()), ['class' => 'region-county text-muted small']) }},
                             {{ Form::label('region-state', strtoupper($reg->getState()), ['class' => 'region-state text-muted small']) }})
                         </li>
                    @endforeach
                </ul>

            </div>
        </div>

    </div>


        <div style="display: none">
            <div id="region-item-template">
                <li style="padding: 2px 0px">
                    {{ Form::button('+', ['class' => "btn btn-danger region-item-button-add buttonsubscribe"]) }}
                    {{ Form::label('region-city', "cityName", ['class' => 'region-city type']) }}
                    ({{ Form::label('region-county', "countyName", ['class' => 'region-county text-muted small']) }},
                    {{ Form::label('region-state', "STATENAME", ['class' => 'region-state text-muted small']) }})
                </li>
            </div>
        </div>
    </div>

    @endsection
@stop
