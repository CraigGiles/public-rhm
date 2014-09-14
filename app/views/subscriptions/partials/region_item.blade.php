<li style="padding: 2px 0px">
    {{ Form::button('+', ['class' => "btn btn-{$buttonColor} region-item-button-{$addOrRemove}"]) }}
    {{ Form::label('region-city', Str::title($city), ['class' => 'region-city type']) }}
    ({{ Form::label('region-county', Str::title($county), ['class' => 'region-county text-muted small']) }},
    {{ Form::label('region-state', strtoupper($state), ['class' => 'region-state text-muted small']) }})
</li>
