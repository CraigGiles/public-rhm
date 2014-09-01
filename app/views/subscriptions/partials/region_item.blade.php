<li class="region-item">
    {{ Form::button('+', ['class' => "btn btn-{$buttonColor} region-item-button-{$addOrRemove}"]) }}
    {{ Form::label('region-city', $city, ['class' => 'region-city type']) }}
    {{ Form::label('region-state', $state, ['class' => 'region-state type text-muted']) }}
</li>
