<div class="well well-sm">

<div class="panel-title" style="text-align: center">
    <h4>Selected Regions</h4>
</div>
<hr>

<ul id="selected-regions-container" style="width: 100%; min-height: 450px; max-height: 450px; overflow-y: scroll; height: 100%; list-style-type: none">
    @foreach($activeSubscriptions as $reg)
         <li style="padding: 2px 0px">
             {{ Form::button('Unsubscribe', ['class' => "btn btn-success region-item-button-remove buttonunsubscribe"]) }}
             {{ Form::label('region-city', Str::title($reg->getCity()), ['class' => 'region-city type']) }}
             ({{ Form::label('region-county', Str::title($reg->getCounty()), ['class' => 'region-county text-muted small']) }},
             {{ Form::label('region-state', strtoupper($reg->getState()), ['class' => 'region-state text-muted small']) }})
         </li>
    @endforeach
</ul>
</div>
