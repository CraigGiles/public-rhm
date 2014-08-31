<div class="region-item-template ">
    <div class="{{$class}}" style="display: list-item;">
        {{ Form::button($buttonText , ['class' => "btn btn-{$buttonColor} region-item-button {$regionItemAddOrRemove}"]) }}
        {{ Form::label('search-term', $searchTerm, ['class' => 'search-term']) }}
        {{ Form::label('region-type', "{$regionType}, {$state}", ['class' => 'region-type type text-muted']) }}
    </div>
</div>
